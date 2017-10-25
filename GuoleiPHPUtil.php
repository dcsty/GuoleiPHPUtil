<?php
/**
 * 项目主文件 使用时需要 include 此文件
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
*/

//设置当前时区
ini_set("date.timezone", "Asia/Shanghai");

//设置错误消息显示级别
ini_set("display_errors", "1");

//设置内存大小
ini_set('memory_limit', -1);

//设置超时时间
set_time_limit(0);

//设置 guolei php util 根目录
defined("GUOLEI_PHP_UTIL_ROOT_DIR") or define("GUOLEI_PHP_UTIL_ROOT_DIR", __DIR__);

//设置 guolei php util 运行模式
defined("GUOLEI_PHP_UTIL_RUN_MODEL") or define("GUOLEI_PHP_UTIL_RUN_MODEL", "developer");

//加载util文件 可根据使用情况 适当选择需要加载的文件
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/CommonUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/HttpUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/MailUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/PageUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/XmlUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/ImageUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/ImageVertifyCode.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/common/UploadFileUtil.php");
include_once(GUOLEI_PHP_UTIL_ROOT_DIR . "/common/FileLogUtil.php");
include_once(GUOLEI_PHP_UTIL_ROOT_DIR . "/common/FileCacheUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/database/PdoUtil.php");
include_once (GUOLEI_PHP_UTIL_ROOT_DIR."/database/RedisUtil.php");

include_once(GUOLEI_PHP_UTIL_ROOT_DIR . "/log/FileLogUtil.php");

