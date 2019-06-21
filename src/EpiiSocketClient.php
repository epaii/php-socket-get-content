<?php
/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2019/5/16
 * Time: 3:00 PM
 */


namespace epii\socket\client;


class EpiiSocketClient
{
    private static $time_out = 30;

    public static function init($timeout)
    {
        self::$time_out = $timeout;
    }

    public static function socket_get_content($host_port, $content, callable $onerror = null)
    {

        list($host, $port) = explode(":", $host_port);

        $fp = @fsockopen($host, $port, $errno, $errstr, self::$time_out);


        if (!$fp) {

            if ($onerror) $onerror($errstr, $errno);
            return false;
        } else {
            $result = fwrite($fp, $content);


            if ($result === false) {

                fclose($fp);
                $errorMsg = '处理失败';
                if ($onerror) $onerror($errorMsg, -1);
                return false;
            } else {

                $out = '';
                while (!feof($fp)) {
                    $out = $out . fgets($fp);
                }


                fclose($fp);
                return $out;
            }

        }
    }
}