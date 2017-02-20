<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 13/3/16
 * Time: 上午11:27
 */
class SelectBranch
{

    private static $instance = null;

    protected $ctlObj = null;

    public static function getInstance($obj)
    {
        if(null === self::$instance){
            self::$instance = new self($obj);
        }

        return self::$instance;
    }

    public function __construct($obj)
    {
        $this->ctlObj = $obj;
    }

    public function showSelect()
    {
        $manager = Safebox::getInstance()->get('manager');

        $con = Req::get('con');
        $act = Req::get('act');

        $config = Config::getInstance('selectbranch')->get($con.'-'.$act);

        if(!$manager['distributor_id'] && $config){

            $disObj = new Model('distributor');
            $distrs = $disObj->where('disabled=0')->findAll();
            $selectbranch = '<li class="submenu" style="color:black;">选择分店:
                <select name="site" id="selectSite"><option value="all">--选择分店--</option>';

            foreach($distrs as $distr){
                $selectbranch .= '<option value="'.$distr['site_url'].'">'.$distr['distributor_name'].'</option>';
            }

            $selectbranch .= '</select>
            </li>';

            $selectbranch .= '<script>

                var url = window.location.href;
                $("#selectSite").change(function(){
                     var selectSite = $("#selectSite option:selected").val();
                     $.cookie("branchStore",selectSite);
                     window.location.href = url;
                });

                if($.cookie("branchStore")){
                    $("#selectSite option[value=\'"+$.cookie("branchStore")+"\']").attr("selected",true);
                }

            </script>';

            $data['selectbranch'] = $selectbranch;

            $this->ctlObj->setDatas($data);
        }

    }



}