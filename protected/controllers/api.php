<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 22/4/16
 * Time: 下午10:54
 */
class ApiController extends Controller
{
    public function index()
    {
        $args = Req::args();

        $obj = isset($args['method']) ? $args['method'] : 'apilist';

        unset($args['method']);

//        if($args['data']){
//
//            $json = json_decode($args['data'],1);
//
//            $args = array_merge($args,$json);
//        }

        $class = new $obj($args);

        return $class->index();
    }

}