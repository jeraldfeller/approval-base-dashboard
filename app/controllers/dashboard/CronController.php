<?php
/**
 * Created by PhpStorm.
 * User: Grabe Grabe
 * Date: 11/21/2018
 * Time: 9:44 AM
 */

namespace Aiden\Controllers;
use Aiden\Models\Das;
use Aiden\Models\DasPhrases;
use Aiden\Models\DasUsers;
use Aiden\Models\Users;
use Aiden\Models\Billing;
use Aiden\Models\UsersPhrases;

class CronController extends _BaseController
{
    public function checkSubscriptionAction(){
        $dateNow = date('Y-m-d H:i:s');
        //check trial users
        $user = new Users();
        $sql = 'SELECT id, created, subscription_status FROM users WHERE subscription_status = "trial"';
        $results = new \Phalcon\Mvc\Model\Resultset\Simple(
            null
            , $user
            , $user->getReadConnection()->query($sql, [], [])
        );

        foreach ($results as $row){
            $createdPlus14 = date('Y-m-d', strtotime($row->getCreated()->format('Y-m-d'). '+14 days'));
            if($createdPlus14 <= $dateNow){
                // trial expired
                $this->updateSubscriptionStatus($row->getId());
            }
        }

        // check billing

        $billing = new Billing();
        $sql = 'SELECT id, users_id FROM billing WHERE subscription_end_date <= "'.$dateNow.'"';
        $results = new \Phalcon\Mvc\Model\Resultset\Simple(
            null
            , $billing
            , $billing->getReadConnection()->query($sql, [], [])
        );

        foreach ($results as $row){
            $this->updateBillingStatus($row->getId(), $row->getUsersId());
        }



    }

    public function updateBillingStatus($id, $userId){
        $billing = Billing::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                "id" => $id
            ]
        ]);
        if($billing){
            $billing->setStatus('expired');
            $billing->save();
            $this->updateSubscriptionStatus($userId);
        }

        return true;
    }

    public function updateSubscriptionStatus($userId){
        $userEnt = Users::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                "id" => $userId
            ]
        ]);
        if($userEnt){
            $userEnt->setSubscriptionStatus('expired');
            $userEnt->save();
        }

        return true;
    }


    // send alerts email for new leads

    public function alertNotificationAction(){
        $das = new Das();
        // fetch all users first
        $users = Users::find();
        $di = \Phalcon\DI::getDefault();
        $daIds = [];
        foreach ($users as $user){
            $councils = array();
            echo $user->getEmail(). '<br>';
            $sql = "SELECT 
                        d.id as dasId, d.council_reference, d.council_url, d.description,
                        c.id as councilId, c.name
                    FROM das d, das_users du, councils c
                    WHERE 
                        d.id = du.das_id
                        AND d.council_id = c.id
                        AND du.email_sent = 0
                        AND du.users_id = ".$user->getId()."
                        ";
            $result = new \Phalcon\Mvc\Model\Resultset\Simple(
                null
                , $das
                , $das->getReadConnection()->query($sql, [], [])
            );
            foreach ($result as $row){
                $daIds[] = $row->dasId;
                $councils[$row->name]['das'][] =  array(
                    'dasId' => $row->dasId,
                    'description' => $row->getHighlightedDescription($user->Phrases, false, [], true),
                    'reference' => $row->council_reference
                );
            }

            if(count($result) > 0){
                $view = $di->getView();
                $view->start();
                $view->setVars([
                    'BASE_URI' => BASE_URI,
                    'totalMatches' => count($result),
                    'councils' => $councils,
                    'totalCouncils' => count($councils),
                ]);
                $view->setTemplateAfter('email'); // template name
                $view->render('controller', 'action');
                $view->finish();

                $emailHtml = $view->getContent();


                $config = $di->getConfig();
                $postFields = [
                    'from' => sprintf('%s <%s>', $config->mailgun->mailFromName, $config->mailgun->mailFromEmail),
                    'subject' => $config->mailgun->mailDigestSubject,
                    'html' => $emailHtml,
                    'text' => strip_tags(\Aiden\Classes\SwissKnife::br2nl($emailHtml)),
                    'to' => $user->getEmail()
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $config->mailgun->mailgunApiKey);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/' . $config->mailgun->mailgunDomain . '/messages');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !$config->dev);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, !$config->dev);

                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);


                // Error
                if ($info['http_code'] != 200) {

                    $message = sprintf('Could not send email, HTTP STATUS [%s]', $info['http_code']);
                    continue;
                }

                // Attempt to parse JSON
                $json = json_decode($output);
                if ($json === null) {
                    $message = 'Mailgun returned a non-Json response';
                    continue;
                }

                // After we've received confirmation from Mailgun, set matches as
                // processed so we only get fresh matches next time.
                if ($json->message == 'Queued. Thank you.') {
                    // set DA processed to true

                    for($x = 0; $x < count($daIds); $x++){
                        $daEntity = DasUsers::findFirst([
                            'conditions' => 'das_id = :das_id: AND users_id = :users_id:',
                            'bind' => [
                                'das_id' => $daIds[$x],
                                'users_id' => $user->getId()
                            ]
                        ]);
                        if($daEntity){
                            $daEntity->setEmailSent(1);
                            $daEntity->save();
                        }else{
                            $daIds->getMessage();
                        }
                    }
                }
                else {
                    $message = print_r($json);
                }

            }

        }

    }


    // fetch users matching phrases

    public function execScanPhraseAction(){
        $file = fopen("CRONLOCK", "r");
        $stat = fread($file, filesize("CRONLOCK"));
        if ($stat == 0) {
            $file = fopen("CRONLOCK", "w");
            fwrite($file, '1');
            fclose($file);
            $up = UsersPhrases::find();
            foreach ($up as $row) {
                $userId = $row->getUserId();
                $phraseId = $row->getId();
                $phrase = $row->getPhrase();
                $caseSensitive = $row->getCaseSensitive();
                $literalSearch = $row->getLiteralSearch();
                $excludePhrase = $row->getExcludePhrase();
                $metadata = $row->getMetadata();
                $filterBy = ($row->getFilterBy() == 'all' ? 'all' : json_decode($row->getFilterBy()));
                $filterBy = ($filterBy != '' ? $filterBy : 'all');
                $councils = ($row->getCouncils() == 'all' ? 'all' : json_decode($row->getCouncils()));
                $costFrom = $row->getCostFrom();
                $costFrom = ($costFrom != '' ? $costFrom : 971763760);
                $costTo = $row->getCostTo();
                $costTo = ($costTo != '' ? $costTo : 0);

                // include null if $costTo = 0;
                $includeNull = ($costFrom == 0 ? " OR d.estimated_cost IS NULL " : "");
                $costQuery = " AND ((d.estimated_cost >= " . $costFrom . " AND d.estimated_cost <= " . $costTo . ")" . $includeNull . ")";
                $councilsQry = '';
                if ($councils != 'all' AND $councils != '') {
                    $councilsQry = " AND (d.council_id = " . implode(' OR d.council_id = ', $councils) . ") ";
                }


                // if case sensitive make Like query in BINARY
                $caseSensitiveQuery = ($caseSensitive == true ? ' BINARY ' : '');
                $excludeQuery = ($excludePhrase == true ? ' NOT LIKE ' : ' LIKE ');
                if ($literalSearch == true) {
                    $excludeQuery = ($excludePhrase == true ? ' NOT RLIKE ' : ' RLIKE ');
                }


                // metadata
                $metadataQuery = '';
                if ($metadata == false) {
                    $metadataQuery = ' AND (SELECT COUNT(id) FROM das_documents WHERE das_id = d.id) > 1 ';
                }


                $filter = ($literalSearch == 'true' ? "[[:<:]]" . $phrase . "[[:>:]]" : "%" . $phrase . "%");
                // filter query by applicant
                $orAnd = ($excludePhrase == true ? " AND " : " OR ");
                $searchQuery = '';
                $searchFilterAll = true;
                if ($filterBy != 'all') {
                    if (!in_array('applicant', $filterBy) || !in_array('description', $filterBy)) {
                        if (in_array('applicant', $filterBy)) {
                            $filterByApplicant = ' AND p.role = "Applicant" ';
                            $searchFilterAll = false;
                            $searchQuery .= ' AND (p.name ' . $excludeQuery . $caseSensitiveQuery . '"' . $filter . '")';
                        }
                        if (in_array('description', $filterBy)) {
                            $searchQuery .= ' AND (d.description ' . $excludeQuery . $caseSensitiveQuery . '"' . $filter . '")';
                        }
                    } else {
                        $searchQuery .= ' AND (d.description ' . $excludeQuery . $caseSensitiveQuery . '"' . $filter . '")';
                    }
                } else {
                    $searchQuery .= ' AND (d.description ' . $excludeQuery . $caseSensitiveQuery . '"' . $filter . '")';
                }


                $das = new Das();
                if ($searchFilterAll == false) {
                    $sql = 'SELECT
                       d.id,
                       d.description,
                       p.name as applicantName
                FROM das d, councils c, das_parties p
                WHERE d.council_id = c.id
                AND d.id = p.das_id
                ' . $filterByApplicant . '
                ' . $searchQuery . '
                ' . $costQuery . '
                ' . $councilsQry . '
                ' . $metadataQuery;
                } else {
                    $sql = 'SELECT
                       d.id,
                       d.description
                FROM das d, councils c
                WHERE d.council_id = c.id
                ' . $searchQuery . '
                ' . $costQuery . '
                ' . $councilsQry . '
                ' . $metadataQuery;
                }


                $result = new \Phalcon\Mvc\Model\Resultset\Simple(
                    null
                    , $das
                    , $das->getReadConnection()->query($sql, [], [])
                );

                foreach ($result as $val) {

                    // check if phrase and da already exists

                    if (DasUsers::find([
                            'conditions' => 'das_id = :das_id: AND 	users_phrase_id = :users_phrase_id:',
                            'bind' => [
                                'das_id' => $val->getId(),
                                'users_phrase_id' => $phraseId
                            ]
                        ])->count() > 0
                    ) {
                        continue;
                    }

                    $dasPhrases = new DasPhrases();
                    $dasPhrases->setDasId($val->getId());
                    $dasPhrases->setPhraseId($phraseId);
                    $dasPhrases->setCreated(new \DateTime());

                    if (!$dasPhrases->save()) {
                        echo $dasPhrases->getMessages();
                    }

                    // Create a relation between the development application and the user

                    $dasUsers = new DasUsers();
                    $dasUsers->setDasId($val->getId());
                    $dasUsers->setUserId($userId);
                    $dasUsers->setUsersPhraseId($phraseId);
                    $dasUsers->setStatus(DasUsers::STATUS_LEAD);
                    $dasUsers->setCreated(new \DateTime());
                    $dasUsers->setSeen(false);
                    $dasUsers->setEmailSent(false);

                    if (!$dasUsers->save()) {
                        // DEBUG
                        echo $dasUsers->getMessages();
                    }
                }

            }
            $file = fopen("CRONLOCK", "w");
            fwrite($file, '0');
            fclose($file);
        }else {
            echo "Cron file locked, the crawler is already processing a phrase search";
        }

    }
}