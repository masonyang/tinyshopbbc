<?php
/**
 * @copyright Copyright(c) 2011 http://www.Tinyhu.com
 * @file
 * @brief
 * @author Tinyhu
 * @date 2010-03-25
 * @version 0.6
 * @note
 */
class Safebox
{
    private static $box;
    private static $obj;

    private function __construct(){}
    private function __clone(){}

    public static function getInstance($type='session')
    {
        if(!self::$obj instanceof self)
        {
            $type = strtolower($type);
            if($type == 'session')
            {
                self::$box = new Session();
            }
            else
            {
                self::$box = new Cookie();
                self::$box->setSafeCode(Tiny::app()->getSafeCode());
            }
            self::$obj = new self();
        }
        return self::$box;
    }
}
?>