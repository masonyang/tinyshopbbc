<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 系统封装的一些JS框架支持
 *
 * @author Tiny
 * @class JS
 */
class JS
{
    private static $JSPackages = array(
        'autoajax'=>'auto_ajax.js',
        'dialog'=>'artdialog/artDialog.js',
        'dialogtools'=>'artdialog/plugins/iframeTools.js',
        'dialogjquery'=>'artdialog/jquery.artDialog.js',
        'jquery'=>array('js'=>array('jquery.min.js','jquery.cookie.js')),
        'date'=>'My97DatePicker/WdatePicker.js',
        'editor'=>array('js'=>array('editor/kindeditor-min.js','editor/lang/zh_CN.js')),
		'highcharts'=>array('js'=>array('highcharts/highcharts.js','highcharts/highcharts-more.js','highcharts/modules/exporting.js')),
        'layoutui'=>'jquery.layout.min-1.2.0.js',
        'jqgrid'=>array(
            'js'=>array('jqgrid/grid.locale-cn.js','jqgrid/jquery.jqGrid.min.js','jqgrid/grid.common.js','jqgrid/grid.formedit.js'),
            'css'=>array('jqgrid/ui.jqgrid.css','jqgrid/jquery-ui-1.8.7.custom.css')
        ),
        'easyui'=>array(
            'js'=>'easyui/jquery.easyui.min.js',
            'css'=>array('easyui/themes/default/easyui.css',
            'easyui/themes/icon.css')
        ),
        'form'=>array(
            'js'=>'form/form.js',
            'css'=>'form/style.css'
        ),
        'validation'=>array(
            'js'=>'Validation/jquery.validationEngine.js',
            'css'=>'Validation/validationEngine.jquery.css'
        ),
        'flexigrid'=>array(
            'css'=>'flexigrid/flexigrid.css',
            'js'=>'flexigrid/flexigrid.js'
        ),
        'swfupload'=>array(
            'js'=>array('swfupload/swfupload.js','swfupload/handlers.js')
        ),
        'lazyload'=>'lazyload.js',
		'mutiselect'=>array(
			'js'=>array('mutiselect/jquery.ui.core.js','mutiselect/jquery.ui.widget.js','mutiselect/prettify.js','mutiselect/jquery.multiselect.js'),
			'css'=>array('mutiselect/jquery.multiselect.css','mutiselect/prettify.css','mutiselect/jquery-ui.css'),
		),
        'autocomplete'=>array(
            'js'=>array('autocomplete/jquery-1.8.2.min.js','autocomplete/jquery.mockjax.js','autocomplete/autocomplete.js'),
            'css'=>array('autocomplete/autocomplete.css'),
        ),
    );
    private static $createfiles = array();
    /**
     * 执行路径
     *
     * @access public
     * @param mixed $name
     * @return mixed
     */
    public static function path($name)
    {
        return Tiny::app()->getRuntimePath().'/systemjs/'.$name.'/';
    }
    /**
     * 引用方法
     *
     * @access public
     * @param mixed $name
     * @param string $charset
     * @return String
     */
    public static function import($name,$charset='UTF-8')
    {
        $parames = explode('?',$name);
        $name = $parames[0];
        if(isset($parames[1])) $parames = '?'.$parames[1];
        else $parames = '';
        if(isset(self::$JSPackages[$name]))
        {
            if(!isset(self::$createfiles[$name]))
            {
                $is_file = false;
                $file = null;
                if(is_string(self::$JSPackages[$name]))
                {
                    if(stripos(self::$JSPackages[$name],'/')===false)
                    {
                        $is_file = true;
                        $file = self::$JSPackages[$name];
                    }
                    else $file = dirname(self::$JSPackages[$name]);
                }
                else
                {
                    if(is_array(self::$JSPackages[$name]['js'])) $file = dirname(self::$JSPackages[$name]['js'][0]);
                    else $file = dirname(self::$JSPackages[$name]['js']);
                }
                if(!file_exists(APP_ROOT.'runtime/systemjs/'.$file))
                {
                    self::$createfiles[$name] = true;
                    File::xcopy(TINY_ROOT.'/web/js/source/'.$file,APP_ROOT.'runtime/systemjs/'.$file);
                }
            }
            $webjspath = Tiny::app()->getRuntimeUrl().'/systemjs/';
            if(is_string(self::$JSPackages[$name])) return '<script type="text/javascript" charset="'.$charset.'" src="'.$webjspath.self::$JSPackages[$name].$parames.'"></script>';
            else if(is_array(self::$JSPackages[$name]))
            {
                $str='';
                if(isset(self::$JSPackages[$name]['css']))
                {
                    if(is_string(self::$JSPackages[$name]['css'])) $str .= '<link rel="stylesheet" type="text/css" href="'.$webjspath.self::$JSPackages[$name]['css'].'"/>';
                    else if(is_array(self::$JSPackages[$name]['css']))
                    {
                        foreach(self::$JSPackages[$name]['css'] as $css)
                        {
                            $str .= '<link rel="stylesheet" type="text/css" href="'.$webjspath.$css.'"/>';
                        }
                    }
                }
                if(isset(self::$JSPackages[$name]['js']))
                {
                    if(is_array(self::$JSPackages[$name]['js']))
                    {
                        foreach(self::$JSPackages[$name]['js'] as $js)
                        {
                            $str .= '<script type="text/javascript" charset="'.$charset.'" src="'.$webjspath.$js.$parames.'"></script>';
                        }
                    }
                    else
                    {
                        $str .= '<script type="text/javascript" charset="'.$charset.'" src="'.$webjspath.self::$JSPackages[$name]['js'].$parames.'"></script>';
                    }
                }

                return $str;
            }
        }
        else return '';
    }

}
