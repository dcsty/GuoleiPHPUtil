<?php
namespace guolei\php\util\plugins\tencent\wechat;
use \guolei\php\util\common\CommonUtil;
use \guolei\php\util\common\HttpUtil;
use \guolei\php\util\common\FileCacheUtil;
/**
 * wechat util
 * guolei php util
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class WeChatUtil
{
    private $appId = "";
    private $appSecret = "";
    private $payKey = "";
    private $mchId = "";
    private $payNotifyUrl = "";
    private $timeOut = 7200;

    public function __construct()
    {
    }

    public function choose($key)
    {
        $config = require(GUOLEI_PHP_UTIL_ROOT_DIR . "config.php");
        $wechatConfig = $config["wechat"][$key];
        $this->appId = $wechatConfig["appId"];
        $this->appSecret = $wechatConfig["appSecret"];
        $this->payKey = $wechatConfig["payKey"];
        $this->mchId = $wechatConfig["mchId"];
        $this->payNotifyUrl = $wechatConfig["payNotifyUrl"];
        $this->timeOut = $wechatConfig["timeOut"];

    }

    public function getAccessToken()
    {
        $fileCacheKey = "wechatAccess_" . $this->appId;
        if (FileCacheUtil::exists($fileCacheKey) == false) {
            return FileCacheUtil::get($fileCacheKey);
        }
        $requestUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret;

        $httpResult = HttpUtil::get($requestUrl);
        $httpContent = json_decode($httpResult["content"], true);
        $accessToken = $httpContent["access_token"];
        //此处需要缓存
        FileCacheUtil::set($fileCacheKey, $accessToken, $this->timeOut);
        return FileCacheUtil::get($fileCacheKey);
    }

    public function getJsApiTicket()
    {
        $fileCacheKey = "wechatJsApiTicket_" . $this->appId;
        if (FileCacheUtil::exists($fileCacheKey) == false) {
            return FileCacheUtil::get($fileCacheKey);
        }
        $accessToken = $this->GetAccessToken();
        $requestUrl = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $accessToken . "&type=jsapi";
        $httpResult = HttpUtil::get($requestUrl);
        $httpContent = json_decode($httpResult["content"], true);
        $ticket = $httpContent["ticket"];
        //此处需要缓存
        FileCacheUtil::set($fileCacheKey, $ticket, $this->timeOut);
        return FileCacheUtil::get($fileCacheKey);
    }

    public static function getSignature($url = '', $signType = 'shar1')
    {
        $jsApiTicket = self::getJsApiTicket();
        $nonceStr = CommonUtil::randomString(16) . time();
        $timestamp = time();
        $string1 = 'jsapi_ticket=' . $jsApiTicket . '&noncestr=' . $nonceStr . '&timestamp=' . $timestamp . '&url=' . $url;
        //exit($string1);
        $signature = sha1($string1);
        if ($signType == 'md5') {
            $signature = md5($string1);
        }
        return [
            'nonceStr' => $nonceStr,
            'signature' => $signature,
            'timestamp' => $timestamp,
        ];
    }

    public function getCodeUrl($url)
    {
        $submitUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        return $submitUrl;
    }

    public function getOpenId($code)
    {
        $requestUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appId . "&secret=" . $this->appSecret . "&code=" . $code . "&grant_type=authorization_code";
        $httpResult = HttpUtil::get($requestUrl);
        $httpContent = json_decode($httpResult["content"], true);
        $openId = $httpContent["openid"];
        return $openId;

    }


}
