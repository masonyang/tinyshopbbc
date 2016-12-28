<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 27/12/16
 * Time: 下午9:43
 *
 * 分销商
 *
 */
class bsmdistributor extends basmbase
{

    public static $title = array(
        'commission'=>'佣金列表 ok',
    );

    public static $lastmodify = array(
        'commission'=>'2016-12-27',
    );

    public static $notice = array(
        'commission'=>'',
    );

    public static $requestParams = array(
        'commission'=>array(
            array(
                'colum'=>'bmsmd5',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'加密字符串=店铺id+分店管理员id',
            ),
        ),
    );

    public static $responsetParams = array(
        'commission'=>array(
            array(
                'colum'=>'memo',
                'content'=>'备注',
            ),
            array(
                'colum'=>'op_time',
                'content'=>'时间',
            ),
            array(
                'colum'=>'op_timestamp',
                'content'=>'时间',
            ),
        ),
    );

    public static $requestUrl = array(
        'commission'=>'     /index.php?con=api&act=index&method=bsmdistributor&source=commission'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        switch($this->params['source']){
            case 'commission':
                $this->commission();
                break;
        }

    }

    private function commission()
    {

        $distrModel = new Model('distributor_depost',$this->domain,'salve');

        $op_time = time() - 604800;

        $distrData = $distrModel->fields('memo,op_time')->where('op_time >='.$op_time)->findAll();

        if($distrData){

            $data = array(
                'count'=>count($distrData)
            );

            $i = 0;
            foreach($distrData as $dist){
                $data[$i]['op_time'] = date('Y-m-d H:i:s',$dist['op_time']);
                $data[$i]['op_timestamp'] = $dist['op_time'];
                $data[$i]['memo'] = $dist['memo'];
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['code'] = self::CODE_SUCC;
            $this->output['msg'] = '获取成功';
            $this->output($data);

        }else{
            $this->output['status'] = 'succ';
            $this->output['code'] = self::CODE_SUCC;
            $this->output['msg'] = '暂无数据';
            $this->output(array('count'=>0));
            exit;
        }

    }

    public function commission_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'暂无数据',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'memo'=>'备注',
                        'op_time'=>'时间',
                        'op_timestamp'=>'时间戳',
                    ),
                ),
            )
        );

    }


}