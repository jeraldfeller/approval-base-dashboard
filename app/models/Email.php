<?php
/**
 * Created by PhpStorm.
 * User: Grabe Grabe
 * Date: 12/5/2018
 * Time: 3:06 PM
 */

namespace Aiden\Models;


class Email extends _BaseModel
{

    public static function contactFormEmail($subject, $message, $email, $name){

        $di = \Phalcon\DI::getDefault();
        $view = $di->getView();
        $view->start();
        $view->setVars([
            'userEmail' => $email,
            'message' => $message,
            'name' => $name
        ]);
        $view->setTemplateAfter('contact_email'); // template name
        $view->render('controller', 'action');
        $view->finish();

        $emailHtml = $view->getContent();


        $config = $di->getConfig();
        $postFields = [
            'from' => sprintf('%s <%s>', $config->mailgun->mailFromName, $config->mailgun->mailFromEmail),
            'subject' => 'Support - ' . $subject,
            'html' => $emailHtml,
            'text' => strip_tags(\Aiden\Classes\SwissKnife::br2nl($emailHtml)),
            'to' => $config->adminEmail
        ];

        return self::sendEmail($postFields, $config);

    }
    public static function signupNotification($email){
        $di = \Phalcon\DI::getDefault();
        $view = $di->getView();
        $view->start();
        $view->setVars([
            'userEmail' => $email
        ]);
        $view->setTemplateAfter('signup_email'); // template name
        $view->render('controller', 'action');
        $view->finish();

        $emailHtml = $view->getContent();


        $config = $di->getConfig();
        $postFields = [
            'from' => sprintf('%s <%s>', $config->mailgun->mailFromName, $config->mailgun->mailFromEmail),
            'subject' => $config->mailgun->mailDigestSubject,
            'html' => $emailHtml,
            'text' => strip_tags(\Aiden\Classes\SwissKnife::br2nl($emailHtml)),
            'to' => $config->adminEmail
        ];

        return self::sendEmail($postFields, $config);
    }

    public static function sendEmail($postFields, $config){
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
            return false;
        }

        // Attempt to parse JSON
        $json = json_decode($output);
        if ($json === null) {
            $message = 'Mailgun returned a non-Json response';
            return false;
        }

        // After we've received confirmation from Mailgun, set matches as
        // processed so we only get fresh matches next time.

        if ($json->message == 'Queued. Thank you.') {
            // set DA processed to true
            return true;
        }
        else {
            return false;
        }

    }
}