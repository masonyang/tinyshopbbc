<?php
/**
 * description...
 *
 * @author Tinyhu
 * @package IndexController
 */
class WeixinController extends Controller{

    public $layout='admin';
    private $weChat;

    public function init(){
        $menu = new Menu();
        $this->assign('mainMenu',$menu->getMenu());
        $menu_index = $menu->current_menu();
        $this->assign('menu_index',$menu_index);
        $this->assign('subMenu',$menu->getSubMenu($menu_index['menu']));
        $this->assign('menu',$menu);
        $nav_act = Req::get('act')==null?$this->defaultAction:Req::get('act');
        $nav_act = preg_replace("/(_edit)$/", "_list", $nav_act);
        $this->assign('nav_link','/'.Req::get('con').'/'.$nav_act);
        $this->assign('node_index',$menu->currentNode());
        $this->safebox = Safebox::getInstance();
        $this->manager = $this->safebox->get('manager');
        $this->assign('manager',$this->safebox->get('manager'));

        $currentNode = $menu->currentNode();
        if(isset($currentNode['name']))$this->assign('admin_title',$currentNode['name']);
        $this->weChat = new WeChat();
    }
    public function index()
    {
        $wx = $this->weChat;
        $echostr = Req::args('echostr');
        if($echostr) $wx->checkSign();
        $msg = $wx->getMessage();
        $response = new stdclass();
        $response->msgType = 'text';
        $openId = $msg->fromUserName;
		$userinfo = $wx->getUserInfo($openId);
        $response->content = $msg->content."--".$userinfo->city.'--'.$openId;
        $wx->response($response);
    }
    public function send(){
        $wx = $this->weChat;
        $data = array(
            'touser'=>'ovlRTuK9jkpAe4jB66nYlBlkW0Nk',
            'template_id'=>'pVheCxUioSuuoBxv6CYPeycyA5PdNScYTULX-19-M-0',
            'topcolor'=>'#0067A6',
            'data'=>array(
                'first'=>array('value'=>urlencode('贺喜！有人下单了！'),
                    'color'=>'#00ABD8'),
                'keyword1'=>array('value'=>urlencode('测试商品'),
                    'color'=>'#008972'),
                'keyword2'=>array('value'=>urlencode('张三'),
                    'color'=>'#008972'),
                'keyword3'=>array('value'=>urlencode('20元'),
                    'color'=>'#EFC028'),
                'keyword4'=>array('value'=>urlencode('15元'),
                    'color'=>'#EFC028'),
                'keyword5'=>array('value'=>date('Y-m-d H:i:s'),
                    'color'=>'#EFC028'),
                'remark'=>array('value'=>urlencode('泰创软件科技有限公司-祝你生意兴隆！'),
                    'color'=>'#F2572D'),
                )
            );
        $str = $wx->sendTemplateMessage($data);
        var_dump($str);
    }

    public function showSend(){
        header("Content-type: text/html; charset=utf8");
        $NoticeService = new NoticeService();
        $NoticeService->send('create_order',array());
    }


    public function event($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "欢迎关注方倍工作室";
            case "unsubscribe":
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "company":
                        $contentStr[] = array("Title" =>"公司简介",
                        "Description" =>"方倍工作室提供移动互联网相关的产品及服务",
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                    default:
                        $contentStr[] = array("Title" =>"默认菜单回复",
                        "Description" =>"您正在使用的是方倍工作室的自定义菜单测试接口",
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                }
                break;
            default:
                break;

        }
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

}

?>
