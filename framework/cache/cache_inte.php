<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
 //缓存接口
interface ICache
{
    public function set($key, $content);
    public function get($key);
    public function delete($key);
}
