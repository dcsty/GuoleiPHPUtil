<?php
namespace guolei\php\util\common;
/**
 * xml util
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class XmlUtil{
    /**
     * 数组转换为xml
     * @param $data 转换数组
    */
    public static function arrayToXml($data){
        $xmlStr = "<xml>";
        $xmlStr.=self::arrayToXmlMethod($data);
        $xmlStr .= "</xml>";
        return $xmlStr;
    }

    /**
     * 数组转xml方法
     * @param $data
    */
    private static function arrayToXmlMethod($data)
    {
        $xmlStr = "";
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $xmlStr.="<" . $key . ">" . self::arrayToXmlMethod($value) . "</" . $key . ">";
            }else{
                if (is_numeric($value)) {
                    $xmlStr .= "<" . $key . ">" . $value . "</" . $key . ">";
                } else {
                    $xmlStr .= "<" . $key . "><![CDATA[" . $value . "]]</" . $key . ">";
                }
            }

        }
        return $xmlStr;
    }

    /**
     * xml转数组
    */
    public static function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * xml转json
     */
    public static function xmlToJson($xml)
    {
        libxml_disable_entity_loader(true);
        json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
    }
}