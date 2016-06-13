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
 */
class apilist extends baseapi
{
    protected $apilist = array(
        'advert',
    );

    private $apiname = '';

    private $detail_html = array();

    private $requestDefaultParams = array(
        array(
            'colum'=>'con',
            'required'=>'必选',
            'type'=>'string',
            'content'=>'控制器名称,api',
        ),
        array(
            'colum'=>'act',
            'required'=>'必选',
            'type'=>'string',
            'content'=>'方法名称,index',
        ),
        array(
            'colum'=>'method',
            'required'=>'必选',
            'type'=>'string',
            'content'=>'调用接口名称,根据实际请求而定',
        ),
    );

    public function index()
    {
        switch($this->params['source']){
            case 'detail':
                $this->detail($this->params['apiname']);
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

        foreach($this->apilist as $api){

            $html .= "<tr><td>".$api::$title.'&nbsp;&nbsp; ----&nbsp;&nbsp; <a href="/index.php?con=api&act=index&method=apilist&source=detail&apiname='.$api.'">查看'."</td></tr>";
        }

        $html .= '</table>';

        echo $html;
    }

    private function detail($apiname)
    {
        if(!in_array($this->params['apiname'],$this->apilist)){
            echo 'apiname 不存在';
            exit;
        }

        $this->apiname = $apiname;


        $this->header();

        $this->buildSignRule();

        $this->body();

        $this->footer();

        echo implode('',$this->detail_html);
    }

    private function header()
    {

        $appname = $this->apiname;

        $html = '<a href="/index.php?con=api&act=index&method=apilist">返回</a><table align="center" border="0" width="100%">';

        $html .= "<tr><td align='center'>".$appname::$title." <br><h5>最后更新时间:".$appname::$lastmodify."</h5></td></tr>";

        $this->detail_html[] =  $html;

    }


    private function buildSignRule()
    {
        $html = "<tr><td style='font-size: 16px;'>请求时， 通用的生成sign签名规则</td></tr>";

        $html .= "<tr><td><table border=1 width='100%'>
        <tr><td>请求的参数key value拼接 后 md5<br/>
            例如：请求参数为 con=api&act=index&method=apilist&source=detail&apiname=advert
            那么 先拼接数据 conapiactindexmethodapilistsourcedetailapinameapiname
            最后将拼接完的数据 md5加密。</td></tr>
        </table></td></tr>";

        $this->detail_html[] =  $html;
    }

    private function body()
    {

        $html = "<tr><td style='font-size: 16px;'>请求参数</td></tr>";

        $html .= "<tr><td><table border=1 width='100%'>";

        $html .= "<tr><td align='center'>字段</td><td align='center'>必选</td><td align='center'>类型</td><td align='center'>说明</td></tr>";

        $appname = $this->apiname;

        $this->requestDefaultParams[2]['content'] = str_replace('根据实际请求而定',$appname,$this->requestDefaultParams[2]['content']);

        $request = array_merge($this->requestDefaultParams,$appname::$requestParams);
        foreach($request as $req){
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

        foreach($appname::$responsetParams as $req){
            $html .= "<tr><td align='center'>".$req['colum']."</td><td align='center'>".$req['content']."</td></tr>";
        }

        $classname = new $appname();


        $html .= "</table></td></tr>";

        $html .= "<tr><td><pre>".var_export(json_decode($classname->demo(),1),1)."</td></tr>";
        $html .= '</table>';



        $this->detail_html[] =  $html;
    }


}