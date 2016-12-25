<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/12/16
 * Time: 上午11:49
 * 会员相关操作
 */
class bsmcustomer extends basmbase
{

    public static $title = array(
        'usrlist'=>'会员列表 ok',
    );

    public static $lastmodify = array(
        'usrlist'=>'2016-12-17',
    );

    public static $notice = array(
        'usrlist'=>'',
    );

    public static $requestParams = array(
        'usrlist'=>array(
            array(
                'colum'=>'type',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'搜索类型:name - 按用户名搜索',
            ),
            array(
                'colum'=>'filter',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'搜索内容',
            ),
            array(
                'colum'=>'limit',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'每次请求加载的数据条数',
            ),
            array(
                'colum'=>'offset',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'上一页最后一条数据的索引',
            ),
            array(
                'colum'=>'iscount',
                'required'=>'可选',
                'type'=>'boolean',
                'content'=>'是否返回总数',
            ),
            array(
                'colum'=>'bmsmd5',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'加密字符串=店铺id+分店管理员id',
            ),
        ),
    );

    public static $responsetParams = array(
        'usrlist'=>array(
            array(
                'colum'=>'cid',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'register_time',
                'content'=>'注册时间',
            ),
            array(
                'colum'=>'name',
                'content'=>'会员姓名',
            ),
            array(
                'colum'=>'count',
                'content'=>'当传入iscount时，返回count。',
            ),
        ),
    );

    public static $requestUrl = array(
        'usrlist'=>'     /index.php?con=api&act=index&method=bsmcustomer&source=usrlist',
    );

    public function __construct($params = array())
    {

        parent::__construct($params);

    }

    public function index()
    {

          switch($this->params['source']){
              case 'usrlist':
                  $this->usrlist();
              break;
          }

    }

    private function usrlist()
    {

        $customerModel = new Model('customer',$this->domain,'salve');

        $filter = $this->__filter();

        $customerModel->fields('user_id,reg_time,mobile')->where($filter['where']);

        $customerModel->limit($filter['limit']);

        $customerLists = $customerModel->findAll();

        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $customerModel->where($filter['where'])->count();
        }

        if($customerLists){
            $_data = array();

            if($count){
                $_data['count'] = $count;
            }

            $i = 0;
            foreach($customerLists as $val){
                $_data['customers'][$i]['cid'] = $val['user_id'];
                $_data['customers'][$i]['register_time'] = $val['reg_time'];
                $_data['customers'][$i]['name'] = $val['mobile'];
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员列表获取成功';
            $this->output($_data);
        }else{
            $this->output['status'] = 'succ';
            $this->output['msg'] = '暂无会员';
            $this->output();
        }
    }

    private function __filter()
    {
        $return = array(
            'type'=>'',
            'order'=>'',
            'where'=>'',
            'limit'=>'',
        );

        $type = $this->params['type'];

        if($type == 'name'){ // 根据 关键字

            $return['type'] = 'search';

            $return['where'] = $this->params['filter'] ? 'mobile like "'.Filter::sql($this->params['filter']).'%"' : '';

        }

        $this->params['offset'] = ($this->params['offset'] == 0) ? 1 : $this->params['offset'];

        $offset = ($this->params['offset'] - 1) * $this->params['limit'];

        $return['limit'] = $offset.','.$this->params['limit'];

        return $return;
    }


    public function usrlist_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'会员列表获取成功',
                'data'=>array(
                    'count'=>10,
                    'customers'=>array(
                        array(
                            'cid'=>'会员id',
                            'register_time'=>'注册时间',
                            'name'=>'手机号'
                        ),
                    ),
                ),
            )
        );

    }


}