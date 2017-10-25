<?php

namespace guolei\php\util\database;
/**
 * redis util
 * guolei php util
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class RedisUtil
{

    private static $host = null;

    private static $clusterHosts=[];

    private static $port = null;

    private static $timeout = null;

    private static $readTimeout=null;

    private static $auth = null;

    private static $redis = null;

    private static $redisCluster=null;

    /**
     * @return null
     */
    public static function getHost()
    {
        return self::$host;
    }

    /**
     * @return array
     */
    public static function getClusterHosts(): array
    {
        return self::$clusterHosts;
    }


    /**
     * @return null
     */
    public static function getPort()
    {
        return self::$port;
    }

    /**
     * @return null
     */
    public static function getTimeout()
    {
        return self::$timeout;
    }

    /**
     * @return null
     */
    public static function getReadTimeout()
    {
        return self::$readTimeout;
    }

    /**
     * @return null
     */
    public static function getAuth()
    {
        return self::$auth;
    }

    public static function openCluster($hosts=[],$auth=null,$timeout=3600,$readTimeout=3600){
        self::$timeout = $timeout;
        self::$auth = $auth;
        self::$clusterHosts=$hosts;
        self::$readTimeout=$readTimeout;
        self::$redisCluster=new \RedisCluster(null,self::$clusterHosts,$timeout,$readTimeout);
        #self::$redisCluster->command()
        if (self::$auth != null) {
            self::$redisCluster->auth(self::$auth);
        }
        return self::$redisCluster;
    }


    public static function open($host, $port = 6379, $timeout = 3600, $auth = null)
    {
        try {

            self::$host = $host;
            self::$port = $port;
            self::$timeout = $timeout;
            self::$auth = $auth;
            self::$redis = new \Redis();
            self::$redis->connect(self::$host, self::$port, self::$timeout);
            if (self::$auth != null) {
                self::$redis->auth(self::$auth);
            }
            return self::$redis;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function openByConfig($dbType = "redis", $name = "master", $key = "0")
    {
        $config = include(GUOLEI_PHP_UTIL_ROOT_DIR . "/config/config.php");
        $runModel = GUOLEI_PHP_UTIL_RUN_MODEL;
        $databseConfig = $config["database"][$runModel][$dbType][$name][$key];
        $host = $databseConfig["host"];
        $port = $databseConfig["port"];
        $timeout = $databseConfig["timeout"];
        $auth = $databseConfig["auth"];
        return self::open($host, $port, $timeout, $auth);
    }

    public static function openClusterByConfig($dbType = "redis", $name = "master", $key = "0")
    {
        $config = include(GUOLEI_PHP_UTIL_ROOT_DIR . "/config/config.php");
        $runModel = GUOLEI_PHP_UTIL_RUN_MODEL;
        $databseConfig = $config["database"][$runModel][$dbType][$name][$key];
        $hosts = $databseConfig["hosts"];
        $port = $databseConfig["port"];
        $timeout = $databseConfig["timeout"];
        $readTimeout = $databseConfig["timeout"];
        $auth = $databseConfig["auth"];
        return self::openCluster($hosts,$auth, $timeout,$readTimeout);
    }

    public static function close()
    {
        if (self::$redis != null) {
            self::$redis->close();
            self::$redis = null;
        }
        return true;
    }

    public static function clear()
    {
        self::$host = null;
        self::$port = null;
        self::$timeout = null;
        self::$auth = null;
        return self::close();
    }
}