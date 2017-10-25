<?php

namespace guolei\php\util\common;
/**
 * upload file util
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
use \guolei\php\util\common\CommonUtil;
class UploadFileUtil
{

    private static $uploadPath = "";

    private static $uploadFileName = "";

    private static $uploadFileType = "";

    private static $uploadFileTmpName = "";

    private static $uploadFileSize = "0";

    private static $uploadFileError = "";

    /**
     * @return string
     */
    public static function getUploadPath()
    {
        return self::$uploadPath;
    }

    /**
     * @param string $uploadPath
     */
    public static function setUploadPath($uploadPath)
    {
        self::$uploadPath = $uploadPath;
    }

    /**
     * @return string
     */
    public static function getUploadFileName()
    {
        return self::$uploadFileName;
    }

    /**
     * @param string $uploadFileName
     */
    public static function setUploadFileName($uploadFileName)
    {
        self::$uploadFileName = $uploadFileName;
    }

    /**
     * @return string
     */
    public static function getUploadFileType()
    {
        return self::$uploadFileType;
    }

    /**
     * @param string $uploadFileType
     */
    public static function setUploadFileType($uploadFileType)
    {
        self::$uploadFileType = $uploadFileType;
    }

    /**
     * @return string
     */
    public static function getUploadFileTmpName()
    {
        return self::$uploadFileTmpName;
    }

    /**
     * @param string $uploadFileTmpName
     */
    public static function setUploadFileTmpName($uploadFileTmpName)
    {
        self::$uploadFileTmpName = $uploadFileTmpName;
    }

    /**
     * @return string
     */
    public static function getUploadFileSize()
    {
        return self::$uploadFileSize;
    }

    /**
     * @param string $uploadFileSize
     */
    public static function setUploadFileSize($uploadFileSize)
    {
        self::$uploadFileSize = $uploadFileSize;
    }

    /**
     * @return string
     */
    public static function getUploadFileError()
    {
        return self::$uploadFileError;
    }

    /**
     * @param string $uploadFileError
     */
    public static function setUploadFileError($uploadFileError)
    {
        self::$uploadFileError = $uploadFileError;
    }

    public static function GetSuffixName()
    {
        return pathinfo(self::$uploadFileName, PATHINFO_EXTENSION);
    }

    public function isLegalSize($min = 0, $max = 0)
    {
        if (doubleval(self::$uploadFileSize) >= doubleval($min) && doubleval(self::$uploadFileSize) <= $max) {
            return true;
        }
        return false;
    }

    public function isImage()
    {
        switch (self::$uploadFileType) {
            case "image/jpg":
                return true;
                break;
            case "image/jpeg":
                return true;
                break;
            case "image/gif":
                return true;
                break;
            case "image/png":
                return true;
                break;
            default:
                return false;
                break;
        }
    }


    public static function uploadFile($dir = "")
    {
        $uploadFileFullPath=self::$uploadPath;

        if (!is_dir($uploadFileFullPath)) {
            @mkdir($uploadFileFullPath);
        }

        $uploadFileFullPath=$uploadFileFullPath.DIRECTORY_SEPARATOR.date("Ymd",time());
        if (!is_dir($uploadFileFullPath)) {
            @mkdir($uploadFileFullPath);
        }

        $uploadFileFullPath=$uploadFileFullPath.DIRECTORY_SEPARATOR.date("H",time());
        if (!is_dir($uploadFileFullPath)) {
            @mkdir($uploadFileFullPath);
        }

        if (strlen($dir) > 0) {
            if (!is_dir(self::$uploadPath . DIRECTORY_SEPARATOR . $dir)) {
                @mkdir(self::$uploadPath . DIRECTORY_SEPARATOR . $dir);
                self::$uploadPath = self::$uploadPath . DIRECTORY_SEPARATOR . $dir;
            }
        }
        $fileNewName = md5(CommonUtil::randomString(32) . time()) . "." . self::getSuffixName();
        if (@move_uploaded_file(self::$uploadFileTmpName, $uploadFileFullPath . DIRECTORY_SEPARATOR . $fileNewName)) {
            return [
                "uploadFileSuccessTime" => time(),
                "uploadFileName" => $fileNewName,
                "uploadFileFullPath" => $uploadFileFullPath . DIRECTORY_SEPARATOR . $fileNewName,
            ];
        } else {
            return false;
        }

    }


    public static function setUploadFile($name)
    {
        if (is_array($_FILES)&&count($_FILES[$name]) > 0) {
            self::$fileName = $_FILES[$name]["name"];
            self::$uploadFileType = $_FILES[$name]["type"];
            self::$uploadFileTmpName = $_FILES[$name]["tmp_name"];
            self::$uploadFileError = $_FILES[$name]["error"];
            self::$uploadFileSize = $_FILES[$name]["size"];
            return true;
        }
        return false;
    }


    public static function clear()
    {
        self::$uploadPath = "";
        self::$uploadFileName = "";
        self::$uploadFileType = "";
        self::$uploadFileTmpName = "";
        self::$uploadFileSize = 0;
        self::$uploadFileError = "";
    }


}