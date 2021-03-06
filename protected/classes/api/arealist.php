<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午1:53
 */
class arealist extends baseapi
{

    public static $title = array(
        'arealist'=>'地区列表'
    );

    public static $lastmodify = array(
        'arealist'=>'2016-6-28',
    );

    public static $notice = array(
        'arealist'=>'',
    );

    public static $requestParams = array(
        'arealist'=>array(
        ),
    );

    public static $responsetParams = array(
        'arealist'=>array(
        ),
    );

    public static $requestUrl = array(
        'arealist'=>'     /index.php?con=api&act=index&method=arealist'
    );

    public function __construct($params = array())
    {
        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');

        $this->params = $params;

    }

    public function index()
    {

        $result = $this->areas();

        $result = json_decode($result,1);

        if($result){
            $this->output['status'] = 'succ';
            $this->output['msg'] = '地区列表获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '地区列表获取失败';
            $this->output();
        }

    }

    private function areas()
    {

        if(isset($this->params['v'])){
            $areas_json = APP_ROOT.'data'.DIRECTORY_SEPARATOR.'/areas.json';

            $items = file_get_contents($areas_json);

            if($items == null)
            {
                $items = JSON::encode($this->_AreaInit(0));
                file_put_contents($areas_json,$items);
            }
        }else{
            $items = JSON::encode($this->_AreaInitOld(0));
        }


        return $items;
    }

    private function _AreaInitOld($id, $level = '0') {
        $model = new Model('area','zd','salve');
        $result = $model->where("parent_id=".$id)->order('sort')->findAll();
        $list = array();

        if($result) {

            foreach($result as$key => $value) {

                $id = "o_".$value['id'];
                $list[$key]['id'] = $value['id'];
                $list[$key]['name'] = $value['name'];

                if($value['parent_id'] ==  0)
                {
                    $model2 = new Model('area','zd','salve');
                    $rr = $model2->where("parent_id=".$value['id'])->order('sort')->findAll();
                    $list[$key]['city'] = $rr;
                    //---
                    $area = array();
                    foreach($rr as $kk => $vv)
                    {
                        $model3 = new Model('area','zd','salve');
                        $area = $model3->where("parent_id=".$vv['id'])->order('sort')->findAll();
                        $rr[$kk]['area'] = $area;
                    }
                    $list[$key]['city'] = $rr;
                    //$list[$key]['city']['area'] =$area;
                    //---
                }
            }
        }

        return $list;
    }

    private function _AreaInit($id, $level = '0') {
        $model = new Model('area','zd','salve');
        $result = $model->where("parent_id=".$id)->order('sort')->findAll();
        $list = array();

        if($result) {

            foreach($result as$key => $value) {

                $id = "o_".$value['id'];
                $list[$key]['id'] = $value['id'];
                $list[$key]['name'] = $value['name'];

                if($value['parent_id'] ==  0)
                {
                    $model2 = new Model('area','zd','salve');
                    $rr = $model2->where("parent_id=".$value['id'])->order('sort')->findAll();
                    $list[$key]['sub'] = $rr;
                    //---
                    $area = array();
                    foreach($rr as $kk => $vv)
                    {
                        $model3 = new Model('area','zd','salve');
                        $area = $model3->where("parent_id=".$vv['id'])->order('sort')->findAll();
                        $rr[$kk]['sub'] = $area;
                    }
                    $list[$key]['sub'] = $rr;
                    //$list[$key]['city']['area'] =$area;
                    //---
                }
            }
        }

        return $list;
    }


    public function arealist_demo()
    {

        $return = array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'地区列表获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'地区列表获取成功',
                'data'=>array(
                ),
            )
        );

        $return['succ']['data'] = $this->_AreaInit(0);

        return $return;

    }

}