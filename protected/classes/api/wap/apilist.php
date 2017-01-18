<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 18/1/17
 * Time: 下午11:20
 *
 * 分店h5 api列表
 *
 * http://a.test.com/index.php?con=api&act=index&method=wapapilist
 *
 */
class wapapilist extends qqclistapi
{

    protected $apiTitle = '分店h5 API 文档';

    protected $default_method = 'wapapilist';

    protected $apilist = array(
        'wptest'=>'wptest',
    );

}