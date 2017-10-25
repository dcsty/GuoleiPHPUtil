<?php

namespace guolei\php\util\common;
/**
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class HttpUtil
{
    public static function get($url, $headers = [])
    {
        return self::request([
            "url" => $url,
            "headers" => $headers,
        ]);
    }

    public static function post($url, $parameters = [], $headers = [])
    {
        return self::request([
            "url" => $url,
            "parameters" => $parameters,
            "headers" => $headers,
        ]);
    }

    public static function request($options = [])
    {
        try {
            $ch = curl_init();
            //请求url
            curl_setopt($ch, CURLOPT_URL, $options["url"]);

            $options["connectTimeout"] = isset($options["connectTimeout"]) && intval($options["connectTimeout"]) > 0 ? $options["connectTimeout"] : 60;
            //连接超时
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $options["connectTimeout"]);

            $options["timeout"] = isset($options["timeout"]) && intval($options["timeout"]) > 0 ? $options["timeout"] : 60;
            //请求超时
            curl_setopt($ch, CURLOPT_TIMEOUT, $options["timeout"]);

            $options["sslVaild"] = isset($options["sslVaild"]) && is_bool($options["sslVaild"]) ? $options["sslVaild"] : false;
            //ssl 验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $options["sslVaild"]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $options["sslVaild"]);

            if (isset($options["certificate"]) && is_array($options["certificate"]) && count($options["certificate"]) > 0) {
                //请求证书
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, $options["certificate"]["type"]);
                curl_setopt($ch, CURLOPT_SSLCERT, @$options["certificate"]["publicKeyPath"]);
                curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $options["certificate"]["password"]);
                curl_setopt($ch, CURLOPT_SSLKEYTYPE, $options["certificate"]["type"]);
                curl_setopt($ch, CURLOPT_SSLKEY, @$options["certificate"]["privateKeyPath"]);
            }

            if (isset($options["userAgent"]) && is_string($options["userAgent"]) && strlen($options["userAgent"]) > 0) {
                //设置浏览器类型
                curl_setopt($ch, CURLOPT_USERAGENT, $options["userAgent"]);
            }

            $options["method"] = isset($options["method"]) && in_array(strtolower($options["method"]), ["get", "post", "put", "delete"]) ? $options["method"] : "GET";


            //设置请求方法
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($options["method"]));

            if (strtolower($options["method"]) == "post") {
                curl_setopt($ch, CURLOPT_POST, 1);
            }

            if (isset($options["parameters"]) && (is_array($options["parameters"]) || is_object($options["parameters"]))) {
                $options["parameters"] = http_build_query($options["parameters"]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options["parameters"]));
            }

            if (isset($options["headers"]) && is_array($options["headers"]) && count($options["headers"]) > 0) {
                //设置请求头信息
                curl_setopt($ch, CURLOPT_HTTPHEADER, $options["headers"]);
            }


            curl_setopt($ch, CURLOPT_HEADER, 1);

            //是否输出内容
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            if (isset($options["referer"]) && is_string($options["referer"]) && strlen($options["referer"]) > 0) {
                curl_setopt($ch, CURLOPT_REFERER, $options["referer"]);
            }

            //cookie
            if (isset($options["cookie"]) && is_string($options["cookie"]) && strlen($options["cookie"]) > 0) {
                curl_setopt($ch, CURLOPT_COOKIE, $options["cookie"]);
            }

            if (isset($options["cookieFile"]) && is_string($options["cookieFile"]) && strlen($options["cookieFile"]) > 0) {
                curl_setopt($ch, CURLOPT_COOKIEFILE, $options["cookieFile"]);
            }

            if (isset($options["cookieJar"]) && is_string($options["cookieJar"]) && strlen($options["cookieJar"]) > 0) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, $options["cookieJar"]);
            }

            $options["noBody"] = isset($options["noBody"]) && is_bool($options["noBody"]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $options["noBody"]);

            if (isset($options["upload"]) && is_array($options["upload"]) && count($options["upload"]) > 0) {
                curl_setopt($ch, CURLOPT_UPLOAD, 1);
                curl_setopt($ch, CURLOPT_PUT, 1);
                curl_setopt($ch, CURLOPT_INFILE, $options["upload"]["filePath"]);
                curl_setopt($ch, CURLOPT_INFILESIZE, $options["upload"]["pageSize"]);
            }
            if (isset($options["proxy"]) && is_string($options["proxy"]) && strlen($options["proxy"]) > 0) {
                curl_setopt($ch, CURLOPT_PROXY, $options["proxy"]);
            }

            if (isset($options["proxyUserPwd"]) && is_string($options["proxyUserPwd"]) && strlen($options["proxyUserPwd"]) > 0) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $options["proxyUserPwd"]);
            }

            ob_start();
            $responseContent = curl_exec($ch);
            ob_end_clean();

            $responseObject = curl_getinfo($ch);
            $responseHeaderSize = $responseObject["header_size"];
            $responseHeaders = substr($responseContent, 0, $responseHeaderSize);
            $httpCode = $responseObject["http_code"];

            return [
                "responseStatus" => $httpCode,
                "responseHeaders" => $responseHeaders,
                "responseObject" => $responseObject,
                "responseContent" => substr($responseContent, $responseHeaderSize),
            ];


        } catch (\Exception $e) {
            return false;
        }

    }


}