<?php

namespace guolei\php\util\database;
/**
 * pdo util
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class PdoUtil
{

    private static $dsn = null;

    private static $username = null;

    private static $password = null;

    private static $options = null;

    private static $pdo = null;

    private static $pdoStatement = null;

    /**
     * @return null
     */
    public static function getDsn()
    {
        return self::$dsn;
    }

    /**
     * @return null
     */
    public static function getUsername()
    {
        return self::$username;
    }

    /**
     * @return null
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * @return null
     */
    public static function getOptions()
    {
        return self::$options;
    }

    /**
     * @return null
     */
    public static function getPdo()
    {
        return self::$pdo;
    }

    /**
     * @return null
     */
    public static function getPdoStatement()
    {
        return self::$pdoStatement;
    }


    public static function open($dsn, $username, $password, $options = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    ])
    {
        try {
            self::$dsn = $dsn;
            self::$username = $username;
            self::$password = $password;
            self::$options = $options;
            self::$pdo = new \PDO(self::$dsn, self::$username, self::$password, self::$options);
            return true;
        } catch (\Exception $e) {
            die($e);
        }
    }

    public static function openByConfig($dbType = "mysql", $name = "master", $key = "0")
    {
        try {
            $config = include(GUOLEI_PHP_UTIL_ROOT_DIR . "/config/config.php");
            $runModel = GUOLEI_PHP_UTIL_RUN_MODEL;
            $databseConfig = $config["database"][$runModel][$dbType][$name][$key];
            $dsn = $databseConfig["dsn"];
            $username = $databseConfig["username"];
            $password = $databseConfig["password"];
            $options = $databseConfig["options"];
            return self::open($dsn, $username, $password, $options);
        } catch (\Exception $e) {
            die($e);
        }

    }

    public static function execute($sql, $bindValues = [], $identity = false)
    {
        try {
            self::$pdoStatement = self::$pdo->prepare($sql);
            if ($bindValues != null && is_array($bindValues) && count($bindValues) > 0) {
                self::$pdoStatement->execute($bindValues);
            } else {
                self::$pdoStatement->execute();
            }
            return $identity ? self::$pdo->lastInsertId() : true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function findFirst($sql, $bindValues = []){
        return self::find($sql,$bindValues,"one");
    }

    public static function find($sql, $bindValues = [], $fetchNums = "all")
    {
        try {
            self::$pdoStatement = self::$pdo->prepare($sql);
            if ($bindValues != null && is_array($bindValues) && count($bindValues) > 0) {
                self::$pdoStatement->execute($bindValues);
            } else {
                self::$pdoStatement->execute();
            }
            if ($fetchNums == 'one') {
                return self::$pdoStatement->fetch(\PDO::FETCH_BOTH);
            }
            if ($fetchNums == 'one_columns') {
                return self::$pdoStatement->fetch(\PDO::FETCH_ASSOC);
            }
            if ($fetchNums == 'all') {
                return self::$pdoStatement->fetchAll(\PDO::FETCH_BOTH);
            }
            if ($fetchNums == 'all_columns') {
                return self::$pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function batchExecute($sqls = [])
    {
        try {
            if (self::$pdo->beginTransaction()) {
                foreach ($sqls as $sql) {
                    if (is_string($sql)) {
                        self::$pdoStatement = self::$pdo->prepare($sql);
                        self::$pdoStatement->execute();
                    }
                    if (is_array($sql)) {
                        $prepareSql = isset($sql["sql"]) ? $sql["sql"] : $sql[0];
                        self::$pdoStatement = self::$pdo->prepare($prepareSql);
                        $bindValues = is_set($sql["bindValues"]) ? $sql["bindValues"] : $sql[1];
                        if ($bindValues != null && is_array($bindValues) && count($bindValues) > 0) {
                            self::$pdoStatement->execute($bindValues);
                        } else {
                            self::$pdoStatement->execute();
                        }
                    }

                }
                self::$pdo->commit();
                return true;

            }
            return false;
        } catch (\Exception $e) {
            self::$pdo->rollBack();
            return false;
        }
    }

    public static function close()
    {
        if (self::$pdo != null) {
            self::$pdo = null;
        }
        if (self::$pdoStatement != null) {
            self::$pdoStatement = null;
        }
        return true;
    }

    public static function clear()
    {
        self::$dsn = null;
        self::$username = null;
        self::$password = null;
        self::$options = null;
        return self::close();
    }
}