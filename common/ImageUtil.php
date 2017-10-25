<?php
namespace guolei\php\util\common;
/**
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class ImageUtil{

    public static function getImageSize($imageFile)
    {
        if (file_exists($imageFile) == false) {
            return false;
        }
        return getimagesize($imageFile);
    }

    public static function toBase64Data($imageFile)
    {
        if (file_exists($imageFile) == false) {
            return false;
        }
        $imageSizeObject = getimagesize($imageFile);
        $base64ImageContent = "data:{" . $imageSizeObject['mime'] . "};base64," . chunk_split(base64_encode(file_get_contents($imageFile)));
        return $base64ImageContent;
    }

    public static function base64ToImage($base64, $imageFile)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
            $type = $result[2];
            $imageFile = $imageFile . ".{$type}";
            if (file_put_contents($imageFile, base64_decode(str_replace($result[1], '', $base64)))) {
                return true;
            }
        }
        return false;
    }
}