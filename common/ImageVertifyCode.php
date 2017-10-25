<?php
namespace guolei\php\util\common;
/**
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class ImageVertifyCode
{
    private $chars = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789'; //随机因子
    private $code; //验证码
    private $codelen = 4; //验证码长度
    private $width = 130; //宽度
    private $height = 50; //高度
    private $img; //图形资源句柄
    private $font; //指定的字体
    private $fontsize = 20; //指定字体大小
    private $fontcolor; //指定字体颜色

    /**
     * @return string
     */
    public function getChars()
    {
        return $this->chars;
    }

    /**
     * @param string $chars
     */
    public function setChars($chars)
    {
        $this->chars = $chars;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCodelen()
    {
        return $this->codelen;
    }

    /**
     * @param int $codelen
     */
    public function setCodelen($codelen)
    {
        $this->codelen = $codelen;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * @param string $font
     */
    public function setFont($font)
    {
        $this->font = $font;
    }

    /**
     * @return int
     */
    public function getFontsize()
    {
        return $this->fontsize;
    }

    /**
     * @param int $fontsize
     */
    public function setFontsize($fontsize)
    {
        $this->fontsize = $fontsize;
    }

    /**
     * @return mixed
     */
    public function getFontcolor()
    {
        return $this->fontcolor;
    }

    /**
     * @param mixed $fontcolor
     */
    public function setFontcolor($fontcolor)
    {
        $this->fontcolor = $fontcolor;
    }

    //构造方法初始化
    public function __construct()
    {
        $this->font = dirname(__FILE__) . '/font/elephant.ttf'; //注意字体路径要写对，否则显示不了图片

    }

    //生成随机码
    private function createCode()
    {
        $_len = strlen($this->chars) - 1;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->code .= $this->chars[mt_rand(0, $_len)];
        }
    }

    //生成背景
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);

        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont()
    {
        $_x = $this->width / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
        }
    }

    //生成线条、雪花
    private function createLine()
    {
        //线条
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    //输出
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        //imagedestroy($this->img);
    }

    //对外生成
    public function generateImage()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    //获取验证码
    public function getVertifyCode()
    {
        return strtolower($this->code);
    }
}