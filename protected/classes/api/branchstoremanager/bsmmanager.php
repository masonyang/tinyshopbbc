<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/12/16
 * Time: 上午11:06
 * 店掌柜端api登陆、退出处理类
 *
 * 管理员相关操作
 *
 */
class bsmmanager extends baseapi
{

    protected $captchaKey = 'bsmVcode';

    public static $title = array(
        'login'=>'登录 ok',
        'loginout'=>'退出 【暂不实现】',
        'uck'=>'账户检测/是否登录 ok',
    );

    public static $lastmodify = array(
        'login'=>'2016-12-17',
        'loginout'=>'2016-12-17',
        'uck'=>'2016-12-17',
    );

    public static $notice = array(
        'login'=>'',
        'loginout'=>'',
        'uck'=>'',
    );

    public static $requestParams = array(
        'login'=>array(
            array(
                'colum'=>'name',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'用户名',
            ),
            array(
                'colum'=>'password',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'密码',
            ),
            array(
                'colum'=>'vaildcode',
                'required'=>'是',
                'type'=>'string',
                'content'=>'图片验证码',
            ),
            array(
                'colum'=>'rand',
                'required'=>'是',
                'type'=>'string',
                'content'=>'随机串为 手机序列号',
            ),
        ),
        'loginout'=>array(
            array(
                'colum'=>'mid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'管理员id',
            ),
        ),
        'uck'=>array(
            array(
                'colum'=>'mid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'管理员id',
            ),
        ),
    );

    public static $responsetParams = array(
        'login'=>array(
            array(
                'colum'=>'name',
                'content'=>'姓名',
            ),
        ),
        'loginout'=>array(
            array(
                'colum'=>'无',
                'content'=>'无',
            ),
        ),
        'uck'=>array(
            array(
                'colum'=>'name',
                'content'=>'姓名',
            ),
            array(
                'colum'=>'mid',
                'content'=>'管理员id',
            ),
        ),
    );

    public static $requestUrl = array(
        'login'=>'     /index.php?con=api&act=index&method=bsmmanager&source=login',
        'loginout'=>'     /index.php?con=api&act=index&method=bsmmanager&source=loginout',
        'uck'=>'     /index.php?con=api&act=index&method=bsmmanager&source=uck'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        switch($this->params['source']){
            case 'login':
                $this->login();
            break;
            case 'loginout':
                $this->loginout();
            break;
            case 'uck':
                $this->uck();
            break;
        }

    }

    private function login()
    {

        $_vaildcode = $this->params['vaildcode'];

        $cacheModel = new Model('cache');

        $md5 = $this->captchaKey.$this->params['rand'];

        $code = $cacheModel->where('`key`="'.$md5.'"')->find();

        $_code = $code['content'];

        if($_vaildcode != $_code){
            $this->output['msg'] = '验证码不正确';
            $this->output();
            exit;
        }

        $cacheModel->where('`key`="'.$md5.'"')->delete();

        $name = $this->params['name'];

        $model = new Model('manager');
        $name = Filter::sql($name);
        $user = $model->where("name='".$name."'")->find();

        if($user){

            $password = $this->params['password'];

            $key = md5($user['validcode']);
            $password = substr($key,0,16).$password.substr($key,16,16);
            if($user['password'] == md5($password)){
                $data = array();
                $data['name'] = $user['name'];
                $data['mid'] = $user['id'];
                $this->output['status'] = 'succ';
                $this->output['msg'] = '登录成功';
                $this->output($data);
            }else{
                $this->output['msg'] = '密码不正确';
                $this->output();
                exit;
            }
        }else{
            $this->output['msg'] = '用户不存在';
            $this->output();
            exit;
        }

    }

    private function loginout()
    {

    }

    private function uck()
    {
        if(!isset($this->params['mid']) || empty($this->params['mid'])){
            $this->output['msg'] = '参数错误';
            $this->output();
            exit;
        }

        $model = new Model('manager');
        $id = Filter::int($this->params['mid']);
        $user = $model->where("id='".$id."'")->find();

        if($user){
            $data = array();
            $data['name'] = $user['name'];
            $data['mid'] = $user['id'];
            $this->output['status'] = 'succ';
            $this->output['msg'] = '登录成功';
            $this->output($data);
        }else{
            $this->output['msg'] = '用户不存在';
            $this->output();
            exit;
        }

    }

    public function login_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'name'=>'测试分销商',
                    ),
                ),
            )
        );

    }

    public function loginout_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'安全退出',
                'data'=>array(
                ),
            )
        );

    }

    public function uck_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'name'=>'测试分销商',
                        'mid'=>'1',
                    ),
                ),
            )
        );

    }

}