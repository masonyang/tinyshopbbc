<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2015 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * description...
 * 
 * @author Tiny
 * @class ClassConfig
 */
class ClassConfig
{
    protected $config = NULL;
    protected $status = false;

    public function __construct()
    {
        $modle = new Model('class_config');
        $obj = $modle->where("class_name='".get_class($this)."'")->find();
        if($obj){
            $this->config = unserialize($obj['config']);
            if($obj['status']==1) $this->status = true;
            else
                $this->status = false;
        }
    }
    
    public function getStatus()
    {
        return $this->status;
    }
}