<?php

namespace Inc\Common;

final class Functions
{
    public static function getSpamLog($identity, $type)
    {
        $local_full_log_path = self::getTodaysPath('/attempts');

        $identity = \preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $identity);

        $identity = \preg_replace("([\.]{2,})", '', $identity);

        $log_file = $local_full_log_path . $type . '_' . $identity . '.json';

        if ( ! \file_exists($log_file))
        {
            $log = array('attempts' => 0);

            self::saveSpamLog($identity, $type, $log);

            return $log;
        }

        return \json_decode(\file_get_contents($log_file), true);
    }

    public static function getTodaysPath($sub_folder_in_logs = '/')
    {
        $local_day = \date('jS D');
        $local_month = \date('mS M');
        $local_year = \date('Y');

        $local_log_path = DIR_PATH_ROOT . '/logs/' . $sub_folder_in_logs;

        $local_full_log_path = $local_log_path . "/{$local_year}/{$local_month}/{$local_day}/";

        if ( ! \file_exists($local_full_log_path))
        {
            \mkdir($local_full_log_path, 777, true);
        }

        return $local_full_log_path;
    }

    public static function isSpammed($ip, $email)
    {
        $ip_log = self::getSpamLog($ip, 'ip');

        $email_log = self::getSpamLog($email, 'email');

        return ($ip_log['attempts'] >= MAX_CONTACT_IN_DAY)

            || ($email_log['attempts'] >= MAX_CONTACT_IN_DAY);
    }

    public static function saveMessage($ip, $email, $message)
    {
        $email = \preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $email);

        $email = \preg_replace("([\.]{2,})", '', $email);

        $local_log_path = DIR_PATH_ROOT . '/logs/messages/';

        $messages_file = $local_log_path . $email . '.json';

        if ( ! \file_exists($messages_file))
        {
            $messages = array('messages' => array());

            if ( ! \is_dir(\dirname($messages_file)))
            {
                \mkdir(\dirname($messages_file), 0777, true);
            }

            \file_put_contents($messages_file, \json_encode($messages, JSON_PRETTY_PRINT));
        }
        else 
        {
            $messages = \json_decode(\file_get_contents($messages_file), true);
        }

        $messages['messages'][md5(microtime())] = $message;

        \file_put_contents($messages_file, \json_encode($messages, JSON_PRETTY_PRINT));

        $email_spam_log = self::getSpamLog($email, 'email');
        $email_spam_log['attempts']++;

        $ip_spam_log = self::getSpamLog($ip, 'ip');
        $ip_spam_log['attempts']++;

        self::saveSpamLog($email, 'email', $email_spam_log);

        self::saveSpamLog($ip, 'ip', $email_spam_log);
    }

    public static function saveSpamLog($identity, $type, $log)
    {
        $local_full_log_path = self::getTodaysPath('/attempts');

        $identity = \preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $identity);

        $identity = \preg_replace("([\.]{2,})", '', $identity);

        $local_unique_name = $type . '_' . $identity . '.json';

        $log_file = $local_full_log_path . $local_unique_name;

        \file_put_contents($log_file, \json_encode($log));
    }

    public static function secret($length = 15, $strong = true)
    {
        if ($strong && \function_exists('openssl_random_pseudo_bytes'))
        {
            return \substr(\str_shuffle(\bin2hex(\openssl_random_pseudo_bytes(16))), 0, $length);
        }

        if ($strong && ! \function_exists('openssl_random_pseudo_bytes'))
        {
            return \substr(\str_shuffle(\random_int(11001, 99999) . \str_shuffle('CeNVAsa3EpYR2') . \random_int(11001, 99999) . \str_shuffle('85mvKqOgcZfy') . \str_shuffle('9n_Pli4BSUtD6X') . \str_shuffle('GFJ1kIhxM0HoTzubrQd7Lj')), 0, $length);
        }

        return \substr(\str_shuffle('CeNVAsa3EpYR285mvKqOgcZfy9n_Pli4BSUtD6XGFJ1kIhxM0HoTzubrQd7Lj'), 0, $length);
    }

    public static function simpleHash($from)
    {
        return \md5($from);
    }
}
