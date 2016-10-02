<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 1/10/16
 * Time: 上午10:38
 * 图片裁剪类
 */
define('IMAGE_CLIPPER_DIR',APP_ROOT.'data'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'imageclipper'.DIRECTORY_SEPARATOR);
define('IMAGE_DIR',APP_ROOT.'data'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR);

class ImageClipper
{

    private static $instance = null;

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {

    }

    public function getImage($filename,$width,$height,$path = '')
    {

        $UPLOAD_URL = Tiny::getServerName(true).'/data/uploads/';
        $UPLOAD_CLIPPER_URL = Tiny::getServerName(true).'/data/uploads/imageclipper/';
        $UPLOAD_PATH = IMAGE_DIR;
        $UPLOAD_CLIPPER_PATH = $path ? $path : IMAGE_CLIPPER_DIR;

        $filename = str_ireplace($UPLOAD_URL, '', $filename); //将URL转化为本地地址
        $info = pathinfo($filename);
        $oldFile = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . $info['extension'];
        $thumbFile = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '_' . $width . '_' . $height . '.' . $info['extension'];

//        echo $UPLOAD_PATH."<br>";
//        echo $oldFile."<br>";
//        echo $thumbFile;exit;
        $oldFile = str_replace('\\', '/', $oldFile);
        $thumbFile = str_replace('\\', '/', $thumbFile);

        $filename = ltrim($filename, '/');
        $oldFile = ltrim($oldFile, '/');
        $thumbFile = ltrim($thumbFile, '/');

        $thumbFile = str_replace('/','_',$thumbFile);

        if (!file_exists($UPLOAD_PATH . $oldFile)) { //todo
            //原图不存在直接返回
            @unlink($UPLOAD_CLIPPER_PATH . $thumbFile);
            $info['src'] = $UPLOAD_URL.$oldFile;
            $info['width'] = intval($width);
            $info['height'] = intval($height);
            return $info;
        } elseif (file_exists($UPLOAD_CLIPPER_PATH . $thumbFile)) {
            //缩图已存在并且  replace替换为false
            $imageinfo = getimagesize($UPLOAD_CLIPPER_PATH . $thumbFile);
            $info['src'] = $UPLOAD_CLIPPER_URL.$thumbFile;
            $info['width'] = intval($imageinfo[0]);
            $info['height'] = intval($imageinfo[1]);
            return $info;
        } else {
            //执行缩图操作
            $oldimageinfo = getimagesize($UPLOAD_PATH . $oldFile);
            $old_image_width = intval($oldimageinfo[0]);
            $old_image_height = intval($oldimageinfo[1]);

            if ($old_image_width <= $width && $old_image_height <= $height) {
                @unlink($UPLOAD_CLIPPER_PATH . $thumbFile);
                @copy($UPLOAD_PATH . $oldFile, $UPLOAD_CLIPPER_PATH . $thumbFile);
                $info['src'] = $UPLOAD_CLIPPER_URL.$thumbFile;
                $info['width'] = $old_image_width;
                $info['height'] = $old_image_height;
                return $info;
            } else {
                if ($height == "auto") $height = $old_image_height * $width / $old_image_width;
                if ($width == "auto") $width = $old_image_width * $width / $old_image_height;
                if (intval($height) == 0 || intval($width) == 0) {
                    return 0;
                }

                $thumb = PhpThumbFactory::create($UPLOAD_PATH . $filename);

                $thumb->resize($width, $height);

                $res = $thumb->save($UPLOAD_CLIPPER_PATH . $thumbFile);

                $info['src'] = $UPLOAD_CLIPPER_URL . $thumbFile;
                $info['width'] = $old_image_width;
                $info['height'] = $old_image_height;
                return $info;

            }
        }

    }

}