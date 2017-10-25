<?php

namespace guolei\php\util\common;
/**
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class CommonUtil
{
    public static function getIP()
    {
        $ip = "unknow";
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = "unknow";
        }
        $ips = explode(",", $ip);
        return $ips[0];
    }

    public static function ip()
    {
        $ip = "unknow";
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = "unknow";
        }
        $ips = explode(",", $ip);
        return $ips[0];
    }

    public static function getSuffixName($fileName)
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    public static function randomString($length, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    public static function randomNumber($min = 0, $max)
    {
        return mt_rand($min, $max);
    }

    public static function generateUUID($option = 0)
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);
            //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = chr(123) . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125);
            switch ($option) {
                case "0":
                    $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
                    break;
                case "1":
                    $uuid = chr(123) . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125);
                    break;
                case "2":
                    $uuid = "(" . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . ")";
                    break;
                case "3":
                    $uuid = "[" . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . "]";
                    break;
                case "4":
                    $uuid = substr($charid, 0, 8) . substr($charid, 8, 4) . substr($charid, 12, 4) . substr($charid, 16, 4) . substr($charid, 20, 12);
            }
            return $uuid;
        }
    }

    public static function safeReplace($string)
    {
        $string = str_replace('%20', '', $string);
        $string = str_replace('%27', '', $string);
        $string = str_replace('%2527', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace("{", '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace('\\', '', $string);
        return $string;
    }

    public static function getUrl()
    {
        $sysProtocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $phpSelf = $_SERVER['PHP_SELF'] ? self::SafeReplace($_SERVER['PHP_SELF']) : self::SafeReplace($_SERVER['SCRIPT_NAME']);
        $pathInfo = isset($_SERVER['PATH_INFO']) ? self::SafeReplace($_SERVER['PATH_INFO']) : '';
        $relateUrl = isset($_SERVER['REQUEST_URI']) ? self::SafeReplace($_SERVER['REQUEST_URI']) : $phpSelf . (isset($_SERVER['QUERY_STRING']) ? '?' . self::SafeReplace($_SERVER['QUERY_STRING']) : $pathInfo);
        return $sysProtocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relateUrl;
    }

    public static function isWeChatBrower($userAgent = null)
    {
        if ($userAgent == null) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        return preg_match("/MicroMessenger/i", $userAgent);
    }

    public static function isIPadBrower($userAgent = null)
    {
        if ($userAgent == null) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        return preg_match("/(iPad).*OS\s([\d_]+)/", $userAgent);
    }

    public static function isAndroidBrower($userAgent = null)
    {
        if ($userAgent == null) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        return preg_match("/(Android)\s+([\d.]+)/", $userAgent);
    }

    public static function isIPhoneBrower($userAgent = null)
    {
        if ($userAgent == null) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        return !self::isIPadBrower() && preg_match("/(iPhone\sOS)\s([\d_]+)/", $userAgent);
    }

    public static function isEmail($email)
    {
        return preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }

    public static function isMobile($mobile)
    {
        return preg_match("/^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/", $mobile);
    }

}
