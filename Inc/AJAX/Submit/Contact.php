<?php

namespace Inc\AJAX\Submit;

use Inc\Common\Functions;
use Inc\System\AJAXResponder;
use Inc\System\Renderer;
use Inc\System\Utilities\Perform;
use Inc\System\Includes\MailingLib\Mailer;

final class Contact
{
    public static function respond()
    {
        global $MASTER;

        Renderer::init();

        $name = \trim($_POST['name']);
        $email = \trim($_POST['email']);
        $message = \trim($_POST['message']);
        $captcha = \trim($_POST['captcha']);

        if (empty($name) || \strlen($name) > 30)
        {
            AJAXResponder::setWarning($MASTER['_contact_invalid_name']);
        }
        elseif (empty($message) || \strlen($message) > 500)
        {
            AJAXResponder::setWarning($MASTER['_contact_invalid_message']);
        }
        elseif (\strlen($email) > 30 || ! (\filter_var($email, FILTER_VALIDATE_EMAIL) !== false))
        {
            AJAXResponder::setWarning($MASTER['_contact_invalid_email']);
        }
        elseif ($captcha != $_SESSION['captcha']['code'])
        {
            require_once __DIR__ . '/../../../lib/simple-php-captcha/loader.php';

            $_SESSION['captcha'] = \simple_php_captcha();

            $js = '
            <script>
                $(\'#contact_form .js-captcha-image img\').attr(\'src\', \'' . $_SESSION['captcha']['image_src'] . '\');
                $(\'#contact_form .js-captcha\').val(\'\').focus();
            </script>';

            AJAXResponder::setError($js . $MASTER['_contact_invalid_captcha']);
        }
        else {

            $message = nl2br(Perform::sanitize($message));

            Renderer::insert('name', Perform::sanitize($name));

            Renderer::insert('email', Perform::sanitize($email));

            Renderer::insert('message', $message);

            Renderer::insert('message_ack', ($MASTER['profile_json']['contact']['contact_acknowledgement_message']));
            Renderer::insert('support_email', ($MASTER['profile_json']['contact']['contact_acknowledgement_support_email']));
            Renderer::insert('support_contact', ($MASTER['profile_json']['contact']['contact_acknowledgement_support_contact']));

            $email_subject_to_owner = \sprintf($MASTER['_contact_to_owner_subject'], Perform::sanitize($name));

            $email_message_to_owner = Renderer::openParse('partials/emails/contact_to_owner');

            $email_subject_to_user = \sprintf($MASTER['_contact_to_acknowledgement_user_subject'], Perform::sanitize($name));

            $email_message_to_user = Renderer::openParse('partials/emails/contact_acknowledgement_to_user');

            if (Functions::isSpammed($_SERVER['REMOTE_ADDR'], $email))
            {
                AJAXResponder::setError($MASTER['_contact_service_unavailable']);
            }
            elseif (true)
            {
                Functions::saveMessage($_SERVER['REMOTE_ADDR'], $email, array(
                    'name'    => $name,
                    'email'   => $email,
                    'message' => $message,
                ));

                Mailer::init($MASTER['profile_json']['mailer']);

                // send email to owner
                Mailer::send($MASTER['profile_json']['mailer']['recieve_acknowledgements'], $email_subject_to_owner, $email_message_to_owner);

                // send acknowledgement email to user
                Mailer::send($email, $email_subject_to_user, $email_message_to_user);

                Renderer::insert('page_title', $MASTER['_contact_done_title']);

                Renderer::insert('page_description', $MASTER['_contact_done_description']);

                Renderer::insert('hide_desktop', '');

                $js = '<script>$("#contact_form .js-response").siblings().remove();$(".page-navigation").first().remove()</script>';

                AJAXResponder::setData($js . Renderer::openParse('partials/page_title'));
            }
        }

        AJAXResponder::respond();
    }
}