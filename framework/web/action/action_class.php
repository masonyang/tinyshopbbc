<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 常用的action封装处理类
 * 
 * @author Tiny
 * @class Action
 */
class Action extends BaseAction
{
    /**
     * action 运行入口
     * 
     * @access public
     * @return mixed
     */
	public function run()
	{
		$controller = $this->getController();
		$methodName = preg_split("/_(?=(save|del|edit)$)/i",$this->getId());
        if(count($methodName)==2)
        {
            $op = $methodName[1];
            $modelName = $methodName[0];
        }
        else
        {
            $op = $methodName[0];
            $modelName = $controller->getId();
        }
		$operator = array('save'=>'save','del'=>'delete','edit'=>'find');
		//如果配制文件存在curd函数自动进行处理
		
		if($controller->getAutoActionRight() && array_key_exists($op,$operator))
		{
			if(($op=='save'))
			{
                $pre_validator = $modelName.'_validator';
                if(method_exists($controller,$pre_validator)){ 
                    $validator = $controller->$pre_validator();
                    if(is_array($validator))
                    {
                        $data = Req::args()+array('validator'=>$validator);
                        $controller->redirect($modelName.'_edit',false,$data);
                        exit;
                    }
                }
				
			}

            $model = new Model($modelName);
            $data=$model->data(Req::args())->$operator[$op]();
            switch($op)
            {
                case 'save':
                {
                    if($data!==false)
                    {
                        $p = Req::args();
                        if(isset($p['id'])){
                            $action = 'update';
                        }else{
                            $action = 'add';
                        }

                        DataSync::service($modelName,Req::args(),$action);

                        $p = Req::args('p');
                        if(isset($p)){

                            $c = get_class($controller);

                            $ctl = str_replace('Controller','',$c);

                            $controller->redirect($ctl.'/'.$modelName.'_list/p/'.$p);
                        }else{
                            $controller->redirect($modelName.'_list');
                        }

                    }
                    else
                    {
                        $controller->redirect($modelName.'_edit',null,false,array('form'=>$model->find()));
                    }
                    break;
                }
                case 'del':
                {
                    DataSync::service($modelName,Req::args(),'del');
                    $controller->redirect($modelName.'_list');
                    break;
                }
                case 'edit':
                {
                	$data = isset($data)?$data:array();
                    if(isset($data)){
                        DataSync::service($modelName,Req::args(),'update');
                    }else{
                        $data = array();
                    }
					$controller->redirect($modelName.'_edit',false,$data);
                    break;
                }
            }
		}
		else
		{
			$action = new ViewAction($controller, $this->getId());
			$action->run();
			//exit;
		}
	}
}
