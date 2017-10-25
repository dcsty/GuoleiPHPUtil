<?php

namespace guolei\php\util\common;
/**
 * file log util
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class FileLogUtil
{
    public static function log($tag, $content, $dir = null)
    {
        try {
            $logFullPath = GUOLEI_PHP_UTIL_ROOT_DIR . "/runtime/logs/";
            $logFullPath .= date("Ymd", time()) . "/";
            if (!is_dir($logFullPath)) {
                @mkdir($logFullPath);
            }
            $logFullPath .= date("H", time()) . "/";
            if (!is_dir($logFullPath)) {
                @mkdir($logFullPath);
            }
            if (strlen($dir) > 0) {
                $logFullPath .= $dir . "/";
            }

            if (!is_dir($logFullPath)) {
                @mkdir($logFullPath);
            }
            $logFullPath .= date("Y_m_d_H_i_s", time()) . ".log";
            $logPutContent = [
                "logTag" => $tag,
                "logTime" => date("Y-m-d H:i:s", time()),
                "logIP" => \guolei\php\util\common\CommonUtil::getIP(),
                "logContent" => $content
            ];
            @file_put_contents($logFullPath, json_encode($logPutContent, SON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}