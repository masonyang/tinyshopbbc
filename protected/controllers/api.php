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

        $obj = $args['method'];

        unset($args['method']);

        $class = new $obj($args);

        return $class->index();
    }

}