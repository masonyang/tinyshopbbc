<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 13/6/16
 * Time: 下午9:55
 * api文档
 *
 * http://a.tinyshop.com/index.php?con=api&act=index&method=carts&source=scount
 *
 * http://a.tinyshop.com/index.php?con=api&act=index&method=apilist
 *
 */
class apilist extends baseapi
{
    protected $apilist = array(
        'advert'=>'advert',
        'captchacode'=>'captchacode',
        'login'=>'customer',
        'register'=>'customer',
        'scategory'=>'gcat',
        'category'=>'gcat',
        'goods'=>'goods',
    );

    private $apiname = '';

    private $ailas = '';

    private $detail_html = array();


    public function index()
    {
        switch($this->params['source']){
            case 'detail':
                $this->detail($this->params['apiname'],$this->params['ailas']);
            break;
            default:
                $this->main();
            break;
        }
    }

    private function main()
    {
        $html = '<table align="center" border="1" width="20%">';

        $html .= "<tr><td align='center'>API 文档</td></tr>";

        foreach($this->apilist as $ailas=>$api){

            if($api::$notice[$ailas]){
                $notice = '<span style="color:red;">('.$api::$notice[$ailas].')</span>';
            }else{
                $notice = '';
            }


            $html .= "<tr><td>".$api::$title[$ailas].'&nbsp;&nbsp; ----&nbsp;&nbsp; <a href="/index.php?con=api&act=index&method=apilist&source=detail&apiname='.$api.'&ailas='.$ailas.'">查看 </a>'.$notice."</td></tr>";
        }

        $html .= '</table>';

        echo $html;
    }

    private function detail($apiname,$ailas)
    {
        if(!in_array($this->params['apiname'],array_values($this->apilist))){
            echo 'apiname 不存在';
            exit;
        }

        $this->apiname = $apiname;

        $this->ailas = $ailas;

        $this->header();

        $this->buildSignRule();

        $this->body();

        $this->footer();

        echo implode('',$this->detail_html);
    }

    private function header()
    {

        $appname = $this->apiname;

        $ailas = $this->ailas;

        $html = '<a href="/index.php?con=api&act=index&method=apilist">返回</a><table align="center" border="0" width="100%">';

        $html .= "<tr><td align='center'>".$appname::$title[$ailas]." <br><h5>最后更新时间:".$appname::$lastmodify[$ailas]."</h5></td></tr>";

        if($appname::$notice[$ailas]){
            $html .= "<tr><td align='center' style='color:red;'><h5>".$appname::$notice[$ailas]."</h5></td></tr>";
        }

        $this->detail_html[] =  $html;

    }


    private function buildSignRule()
    {
        $appname = $this->apiname;

        $ailas = $this->ailas;

        $html = "<tr><td style='font-size: 16px;'>请求地址:".$appname::$requestUrl[$ailas]." &nbsp;&nbsp;&nbsp;&nbsp; 【所有请求参数POST提交】</td></tr>";

        $html .= "<tr><td style='font-size: 16px;'>生成sign签名规则 后续完善</td></tr>";

//        $html .= "<tr><td><table border=1 width='100%'>
//        <tr><td>请求的参数key value拼接 后 md5<br/>
//            例如：请求参数为 con=api&act=index&method=apilist&source=detail&apiname=advert
//            那么 先拼接数据 conapiactindexmethodapilistsourcedetailapinameapiname
//            最后将拼接完的数据 md5加密。</td></tr>
//        </table></td></tr>";

        $this->detail_html[] =  $html;
    }

    private function body()
    {

        $html = "<tr><td style='font-size: 16px;'>请求参数</td></tr>";

        $html .= "<tr><td><table border=1 width='100%'>";

        $html .= "<tr><td align='center'>字段</td><td align='center'>必选</td><td align='center'>类型</td><td align='center'>说明</td></tr>";

        $appname = $this->apiname;

        $ailas = $this->ailas;

        foreach($appname::$requestParams[$ailas] as $req){

            $html .= "<tr><td align='center'>".$req['colum']."</td><td align='center'>".$req['required']."</td><td align='center'>".$req['type']."</td><td align='center'>".$req['content']."</td></tr>";
        }

        $html .= "</table></td></tr>";

        $this->detail_html[] =  $html;
    }

    private function footer()
    {
        $html = "<tr><td style='font-size: 16px;'>返回字段说明</td></tr>";

        $html .= "<tr><td><table border=1 width='100%'>";

        $html .= "<tr><td align='center'>字段</td><td align='center'>说明</td></tr>";

        $appname = $this->apiname;

        $ailas = $this->ailas;

        foreach($appname::$responsetParams[$ailas] as $req){
            $html .= "<tr><td align='center'>".$req['colum']."</td><td align='center'>".$req['content']."</td></tr>";
        }

        $classname = new $appname();


        $method = $this->ailas.'_demo';

        $html .= "</table></td></tr>";

        $json = $classname->$method();

        if(is_array($json)){
            $html .= "<tr><td> 成功 结果:<br/><pre>".var_export($json['succ'],1)."</td></tr>";
            $html .= "<tr><td>错误 结果:<br/><pre>".var_export($json['fail'],1)."</td></tr>";
        }else{
            $html .= "<tr><td><pre>结果:<br/>".$classname->$method()."</td></tr>";
        }

        $html .= '</table>';



        $this->detail_html[] =  $html;
    }


}