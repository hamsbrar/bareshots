<?php

namespace Inc\System\Includes\MailingLib;

use Inc\System\Includes\MailingLib\PHPMailer\PHPMailer;

final class Mailer
{
    public static $mailer_settings;

    public static function init($settings)
    {
        self::$mailer_settings['mailing_log'] = $settings['mailing_log'];
        self::$mailer_settings['mailer_use_smtp'] = $settings['mailer_use_smtp'];

        self::$mailer_settings['php_from'] = $settings['php_from'];
        self::$mailer_settings['php_reply_to'] = $settings['php_reply_to'];

        self::$mailer_settings['smtp_server'] = $settings['smtp_server'];
        self::$mailer_settings['smtp_username'] = $settings['smtp_username'];
        self::$mailer_settings['smtp_password'] = $settings['smtp_password'];
        self::$mailer_settings['smtp_from'] = $settings['smtp_from'];
    }

    public static function saveMailLog($to_email, $subject, $content, $return_message)
    {
        $data = array(
            'to'             => $to_email,
            'subject'        => $subject,
            'content'        => $content,
            'return_message' => $return_message,
        );

        $logs_path = DIR_PATH_ROOT . '/logs/emails-sent/';

        if ( ! \file_exists($logs_path))
        {
            \mkdir($logs_path, 0777, true);
        }

        \file_put_contents($logs_path . \time() . "-{$to_email}.json", \json_encode($data));
    }

    public static function send($to_email, $subject, $content)
    {
        global $MASTER;

        if ( ! self::$mailer_settings['mailer_use_smtp'])
        {
            $mail = array();

            // Set MIME version
            $set = 'MIME-Version: 1.0' . "\r\n";

            // Set content type
            $set .= 'Content-type: text/html; charset=utf-8' . "\r\n";

            $set .= 'From: '.$MASTER['profile_json']['website']['name'].' <'.self::$mailer_settings['php_from'].'>' . PHP_EOL .
                        'Reply-To: Jack Sparrow <'.self::$mailer_settings['php_reply_to'].'>' . PHP_EOL .
                        'X-Mailer: PHP/' . phpversion();

            // Send MAIL
            $sent = @\mail($to_email, $subject, $content, $set);
        }
        else {
            $mail = new PHPMailer();

            $mail->IsSMTP();

            $mail->SMTPDebug = false;

            $mail->SMTPAuth = true;

            $mail->SMTPSecure = 'ssl';

            $mail->Host = self::$mailer_settings['smtp_server'];

            $mail->Port = 465;

            $mail->IsHTML(true);

            $mail->Username = self::$mailer_settings['smtp_username'];

            $mail->Password = self::$mailer_settings['smtp_password'];

            $mail->SetFrom(self::$mailer_settings['smtp_from'], $MASTER['profile_json']['website']['name']);

            $mail->Subject = $subject;

            $mail->Body = $content;

            $mail->AddAddress($to_email);

            $mail->Send();
        }

        if (self::$mailer_settings['mailing_log'])
        {
            self::saveMailLog($to_email, $subject, $content, (isset($mail->ErrorInfo) && $mail->ErrorInfo ? $mail->ErrorInfo : ''));
        }
    }
}
