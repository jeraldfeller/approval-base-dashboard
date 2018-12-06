<?php

namespace Aiden\Controllers;

use Aiden\Controllers\_BaseController;
use Aiden\Models\Users;
use Aiden\Models\Admin;
use Aiden\Models\Billing;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Token;
use Mailgun\Mailgun;


class SettingsController extends _BaseController
{

    public function indexAction()
    {
        $this->view->setVars([
            'page_title' => 'Profile settings'
        ]);

    }

    public function supportAction()
    {
        $this->view->setVars([
            'page_title' => 'Contact form'
        ]);
        $this->view->pick('settings/index');
    }

    public function billingAction()
    {

        // get billing
        $billing = $this->getUsersBilling();
        $this->view->setVars([
            'page_title' => 'Billing',
            'current' => $billing['current'],
            'invoices' => $billing['invoices']
        ]);
        $this->view->pick('settings/index');
    }

    public function notificationsAction()
    {
        $this->view->setVars([
            'page_title' => 'Notifications'
        ]);
        $this->view->pick('settings/index');
    }

    public function saveAction()
    {
        $action = $this->request->getQuery("ajax");
        if ($action == 1) {
            return $this->dispatcher->forward(["action" => "updateProfile"]);
        } else if ($action == 2) {
            return $this->dispatcher->forward(["action" => "contact"]);
        } else {
            return false;
        }

    }

    public function updateProfileAction()
    {
        $userId = $this->getUser()->getId();
        $filePath = '';
        $basePath = '';
        if (isset($_FILES['avatar'])) {
            if ($_FILES['avatar']['name'] != '') {
                $source = $_FILES['avatar']['tmp_name'];
                $fileName = $_FILES['avatar']['name'];
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                //allowed extensions
                $imageExt = array('png', 'jpg', 'jpeg', 'gif');
                if (in_array($ext, $imageExt)) {
                    $filePath = BASE_URI . "dashboard_assets/images/avatars/" . $userId . "." . $ext;
                    $basePath = "dashboard_assets/images/avatars/" . $userId . "." . $ext;
                    $targetPath = __DIR__ . '/../../../public/dashboard_assets/images/avatars/' . $userId . '.' . $ext;
                    if (move_uploaded_file($source, $targetPath)) {

                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

            }
        }
        $data = array(
            'firstName' => trim($this->request->getPost('firstName')),
            'lastName' => trim($this->request->getPost('lastName')),
            'websiteUrl' => trim($this->request->getPost('websiteUrl')),
            'companyName' => trim($this->request->getPost('companyName')),
            'companyCity' => trim($this->request->getPost('companyCity')),
            'companyCountry' => trim($this->request->getPost('companyCountry')),
            'avatar' => $filePath,
            'avatarBasePath' => $basePath
        );
        return json_encode(Users::updateUserInfo($data, $userId));
    }

    public function notificationsUpdateAction(){
        if($this->request->getPost('action') == 'show_alerts'){
            $this->getUser()->setShowAlerts($this->request->getPost('value'));
        }else{
            $this->getUser()->setSendNotificationsOnLeads($this->request->getPost('value'));
        }

        $result = $this->getUser()->save();

        return json_encode($result);

    }

    public function contactAction()
    {
        $userId = $this->getUser()->getId();
        $email = $userId = $this->getUser()->getEmail();
        $subject = trim($this->request->getPost('subject'));
        $message = trim($this->request->getPost('message'));
        $name = $userId = $this->getUser()->getName() . ' ' . $userId = $this->getUser()->getLastName();
        $response = \Aiden\Models\Email::contactFormEmail($subject, $message, $email, $name);
        return json_encode($response);
    }


    public function stripeApiAction()
    {
        $token = $this->request->getPost('token');
        $amount = 1900;
        $stripe = new Stripe();
        $stripe::setApiKey(Admin::getApiKeyBySource('stripe')['secretKey']);


        // create customer
        if ($this->getUser()->getStripeCustomerId() == '') {
            $customer = Customer::create([
                'email' => $this->getUser()->getEmail(),
                'source' => $token
            ]);
            $customerId = $customer->id;
            $this->getUser()->setStripeCustomerId($customerId);
            $this->getUser()->save();
        } else {
            $customerId = $this->getUser()->getStripeCustomerId();
        }
        // create charge;
        $charge = Charge::create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => 'Monthly subsription',
            'customer' => $customerId,
            'metadata' => ['userId' => $this->getUser()->getId()]
        ]);


        if ($charge->status == 'succeeded') {
            $date = date('Y-m-d H:i:s');
            $endDate = date('Y-m-d H:i:s', strtotime('+30 days'));
            $billing = new Billing();
            $billing->setUsersId($this->getUser()->getId());
            $billing->setChargeId($charge->id);
            $billing->setDateCreated(new \DateTime($date));
            $billing->setAmount($amount / 100);
            $billing->setSubscriptionStartDate(new \DateTime($date));
            $billing->setSubscriptionEndDate(new \DateTime($endDate));
            $billing->setStatus('active');
            if ($billing->save()) {
                $this->getUser()->setSubscriptionStatus('active');
                $this->getUser()->save(0);
            }
            return json_encode(true);
        } else {
            return json_encode(false);
        }
    }

    public function getUsersBilling()
    {
        $billing = new Billing();
        $sql = 'SELECT * FROM `billing` WHERE `users_id` = ' . $this->getUser()->getId() . ' ORDER BY `id` DESC';
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(
            null
            , $billing
            , $billing->getReadConnection()->query($sql, [], [])
        );

        $invoices = array();
        $current = array();
        $x = 0;
        foreach ($result as $row) {
            if ($x == 0) {
                $current = array(
                    'id' => $row->getId(),
                    'chargeId' => $row->getChargeId(),
                    'startDate' => $row->getSubscriptionStartDate()->format('M d, Y'),
                    'endDate' => $row->getSubscriptionEndDate()->format('M d, Y'),
                    'status' => ucfirst($row->getStatus()),
                    'amount' => $row->getAmount()
                );
            }
            $invoices[] = array(
                'id' => $row->getId(),
                'chargeId' => $row->getChargeId(),
                'startDate' => $row->getSubscriptionStartDate()->format('M d, Y'),
                'endDate' => $row->getSubscriptionEndDate()->format('M d, Y'),
                'status' => ucfirst($row->getStatus()),
                'amount' => $row->getAmount()
            );
            $x++;
        }

        return array(
            'invoices' => $invoices,
            'current' => $current
        );

    }


    public function updateSeenAction(){
        $this->getUser()->setSeenModal(1);
        $this->getUser()->save();
        return true;
    }


}
