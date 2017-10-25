<?php

namespace guolei\php\util\cache;
/**
 * file cache util
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class FileCacheUtil
{
    public static function set($key, $value, $dir = null, $expire = 0)
    {
        try {
            $fileFullPath = GUOLEI_PHP_UTIL_ROOT_DIR . "/runtime/cache/";
            if (is_null($dir) == false) {
                $fileFullPath = $fileFullPath . $dir . "/";
            }
            if (!is_dir($fileFullPath)) {
                @mkdir($fileFullPath);
            }

            $fileFullPath .= $key . ".cache";
            $fileExpireTime = $expire;
            if ($expire > 0) {
                $fileExpireTime = time() + $expire;
            }
            $filePutContent = ["fileCacheKey" => $key, "fileCacheValue" => $value, "fileCacheExpire" => $fileExpireTime];
            @file_put_contents($fileFullPath, json_encode($filePutContent));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function get($key, $dir = null)
    {
        try {
            $fileFullPath = GUOLEI_PHP_UTIL_ROOT_DIR . "/runtime/cache/";
            if (is_null($dir) == false) {
                $fileFullPath = $fileFullPath . $dir . "/";
            }
            $fileFullPath .= $key . ".cache";
            $fileCache = json_decode(file_get_contents($fileFullPath), true);
            if ($fileCache["fileCacheKey"] == $key) {
                if (intval($fileCache["fileCacheExpire"]) > 0) {
                    if (intval($fileCache["fileCacheExpire"]) > time()) {
                        return $fileCache["fileCacheValue"];
                    }
                    return false;
                }
                return $fileCache["fileCacheValue"];
            }
            return false;

        } catch (\Exception $e) {
            return false;
        }
    }

    public static function remove($key, $dir = null)
    {
        try {
            $fileFullPath = GUOLEI_PHP_UTIL_ROOT_DIR . "/runtime/cache/";
            if (is_null($dir) == false) {
                $fileFullPath = $fileFullPath . $dir . "/";
            }
            $fileFullPath .= $key . ".cache";
            if (file_exists($fileFullPath)) {
                unlink($fileFullPath);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function exists($key, $dir = null)
    {
        try {
            $fileFullPath = GUOLEI_PHP_UTIL_ROOT_DIR . "/runtime/cache/";
            if (is_null($dir) == false) {
                $fileFullPath = $fileFullPath . $dir . "/";
            }
            $fileFullPath .= $key . ".cache";
            if (file_exists($fileFullPath)) {
                $fileCache = json_decode(file_get_contents($fileFullPath), true);
                if ($fileCache["fileCacheKey"] == $key) {
                    if (intval($fileCache["fileCacheExpire"]) > 0) {
                        if (intval($fileCache["fileCacheExpire"]) > time()) {
                            return true;
                        }
                        return false;
                    }
                    return true;
                }
                return false;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}