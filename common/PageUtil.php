<?php

namespace guolei\php\util\common;
/**
 * page util
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class PageUtil
{
    private static $firstPageIndex = 1;
    private static $nextPageIndex = 1;
    private static $previousPageIndex = 1;
    private static $lastPageIndex = 1;
    private static $pageIndex = 1;
    private static $pageOffset = 0;
    private static $pageSize = 10;
    private static $pageTotal = 1;
    private static $recordTotal = 0;

    /**
     * @return int
     */
    public static function getFirstPageIndex()
    {
        self::$firstPageIndex = 1;
        return self::$firstPageIndex;
    }

    /**
     * @return int
     */
    public static function getNextPageIndex()
    {
        self::$pageIndex = self::getPageIndex();
        self::$lastPageIndex = self::getLastPageIndex();
        self::$nextPageIndex = self::$pageIndex;
        if (self::$nextPageIndex + 1 > self::$lastPageIndex) {
            self::$nextPageIndex = self::$lastPageIndex;
        } else {
            self::$nextPageIndex = self::$pageIndex + 1;
        }
        return intval(self::$nextPageIndex);
    }

    /**
     * @return int
     */
    public static function getPreviousPageIndex()
    {
        self::$pageIndex = self::getPageIndex();
        if (self::$pageIndex - 1 < 1) {
            self::$previousPageIndex = 1;
        } else {
            self::$previousPageIndex = self::$pageIndex - 1;
        }
        return intval(self::$previousPageIndex);
    }

    /**
     * @return int
     */
    public static function getLastPageIndex()
    {
        self::$pageTotal = self::getPageTotal();
        self::$lastPageIndex = self::$pageTotal;
        return intval(self::$lastPageIndex);
    }

    /**
     * @return int
     */
    public static function getPageOffset()
    {
        self::$pageSize = self::getPageSize();
        self::$pageIndex = self::getPageIndex();
        self::$pageOffset = (self::$pageIndex - 1) * self::$pageSize;
        return intval(self::$pageOffset);
    }

    /**
     * @return int
     */
    public static function getPageTotal()
    {
        self::$pageSize = self::getPageSize();
        self::$recordTotal = self::getRecordTotal();
        if (self::$recordTotal % self::$pageSize == 0) {
            self::$pageTotal = self::$recordTotal / self::$pageSize;
        } else {
            self::$pageTotal = (self::$recordTotal / self::$pageSize) + 1;
        }
        return intval(self::$pageTotal);
    }

    /**
     * @return int
     */
    public static function getPageIndex()
    {
        if (self::$pageIndex == null || intval(self::$pageIndex) < 1) {
            self::$pageIndex = 1;
        }
        return intval(self::$pageIndex);
    }

    /**
     * @param int $pageIndex
     */
    public static function setPageIndex($pageIndex)
    {
        if ($pageIndex == null || $pageIndex < 1) {
            $pageIndex = 1;
        }
        self::$pageIndex = $pageIndex;
    }

    /**
     * @return int
     */
    public static function getPageSize()
    {
        if (self::$pageSize == null || intval(self::$pageSize) < 1) {
            self::$pageSize = 10;
        }
        return intval(self::$pageSize);
    }

    /**
     * @param int $pageSize
     */
    public static function setPageSize($pageSize)
    {
        if ($pageSize == null || intval($pageSize) < 1) {
            $pageSize = 10;
        }
        self::$pageSize = $pageSize;
    }

    /**
     * @return int
     */
    public static function getRecordTotal()
    {
        if (self::$recordTotal == null || intval(self::$recordTotal) < 0) {
            self::$recordTotal = 0;
        }

        return intval(self::$recordTotal);
    }

    /**
     * @param int $recordTotal
     */
    public static function setRecordTotal($recordTotal)
    {
        if ($recordTotal == null || intval($recordTotal) < 0) {
            $recordTotal = 0;
        }
        self::$recordTotal = $recordTotal;
    }


}