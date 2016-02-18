<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo isset($admin_title)?$admin_title:"";?>-109商城</title>
<meta name="author" content="designer:webzhu, date:2012-03-23" />
<link type="image/x-icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>" rel="icon">
<link rel="stylesheet" type="text/css" href="<?php echo urldecode(Url::urlFormat("@static/css/base.css"));?>" />
<link rel="stylesheet" type="text/css" href="<?php echo urldecode(Url::urlFormat("@static/css/admin.css"));?>" />
<link rel="stylesheet" type="text/css" href="<?php echo urldecode(Url::urlFormat("@static/css/font_icon.css"));?>" />
<?php echo JS::import('jquery');?>
<script type="text/javascript" src="<?php echo urldecode(Url::urlFormat("@static/js/common.js"));?>"></script>
<!--[if lte IE 7]><script src="<?php echo urldecode(Url::urlFormat("@static/css/fonts/lte-ie7.js"));?>"></script><![endif]-->
</head>
<body >
<div id="header">
	<div class="nav_sub">
			    	您好[<?php echo isset($manager['name'])?$manager['name']:"";?>]&nbsp; | <?php if($showFront){?><a href="<?php echo urldecode(Url::urlFormat("/index/index"));?>" target="_blank">返回前台</a> | <?php }?><a href="<?php echo urldecode(Url::urlFormat("/admin/logout"));?>">退出</a>
	</div>
    <div id="Logo"><a href=""><img src="<?php echo urldecode(Url::urlFormat("@static/images/logo_min.png"));?>" width=192 height=46></a></div>
	<ul id="main_nav" class="clearfix">
	<?php foreach($mainMenu as $key => $item){?>
		<li <?php if($key==$menu_index['menu']){?>class="active"<?php }?>><a href="<?php echo urldecode(Url::urlFormat("$item[link]"));?>"  ><?php echo isset($item['name'])?$item['name']:"";?></a></li>
	<?php }?>
	</ul>
</div>
<div id="mainContent">
	<div id="sidebar" >
		<ul class="menu" style="margin-top:15px;">
		<?php foreach($subMenu as $key => $item){?>
			<li class="submenu">
			<ul><li class="sub-index"><b><a href="javascript:;"><?php echo isset($item['name'])?$item['name']:"";?></a></b></li>
			<?php foreach($menu->getNodes($item['link']) as $key => $item){?>
			<?php if(substr($item['link'],-5)!='_edit'){?>
				<li><a href='<?php echo urldecode(Url::urlFormat("$item[link]"));?>' <?php if($item['link']==$nav_link){?>class="current"<?php }?> ><?php echo isset($item['name'])?$item['name']:"";?></a></li>
				<?php }?>
			<?php }?>
			</ul>
			</li>
		<?php }?>
		</ul>
	</div>
	<div id="content" >

		<?php if(!isset($msg)){?><?php $msg=Req::post('msg');?><?php }?>
		<?php if(!isset($validator)){?><?php $validator=Req::post('validator');?><?php }?>
		<?php if(isset($msg[0])){?>
		<div id="message-bar" class="message_<?php echo isset($msg[0])?$msg[0]:"";?>"><?php echo isset($msg[1])?$msg[1]:"";?></div>
		<?php }elseif(isset($validator)){?>
		<div class="message_warning"><?php echo isset($validator['msg'])?$validator['msg']:"";?></div>
		<?php }?>
		<div class="clearfix">
<div style="" >
    <h1><b>系统信息:</b></h1>
    <table class="mt10 default">
        
        <tr><th class="caption">服务器系统及PHP版本：</th> <td><?php echo PHP_OS;?> / PHP V<?php echo PHP_VERSION;?></td></tr>
        <tr><th class="caption">服务器软件：</th> <td><?php echo isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:"";?></td></tr>
        <?php $model = new Model();$version = $model->query('select version() as ver');?>
        <tr><th class="caption">MySQL版本：</th> <td><?php echo $version[0]['ver'];?></td></tr>
        <tr><th class="caption">最大上传许可：</th> <td><?php echo ini_get('upload_max_filesize');?></td></tr>
    </table>
</div>
<div class="mt10">
    <h1><b>重要信息:</b></h1>
    <table class="mt10 default">
        <tr><th class="caption">待发货订单：</th> <td><a href="<?php echo urldecode(Url::urlFormat("/order/order_list?condition=and--pay_status--eq--1__and--delivery_status--eq--0__and--od.status--lt--4"));?>" class="red"><?php echo isset($shipped_num)?$shipped_num:"";?>条</a></td><th class="caption">待回复商品咨询：</th> <td><a href="<?php echo urldecode(Url::urlFormat("/customer/ask_list?condition=and--a.status--eq--0"));?>" class="red"><?php echo isset($ask_num)?$ask_num:"";?>条</a></td></tr>
        <tr><th class="caption">商品库存报警：</th> <td><a href="<?php echo urldecode(Url::urlFormat("/goods/goods_list"));?>" class="red"><?php echo isset($warning_num)?$warning_num:"";?>条</a></td><th class="caption">待处理的提现申请：</th> <td><a href="<?php echo urldecode(Url::urlFormat("/customer/withdraw_list?condition=and--wd.status--eq--0"));?>" class="red"><?php echo isset($withdraw_num)?$withdraw_num:"";?>条</a></td></tr>
        <tr><th class="caption">待处理的退款单：</th> <td><a href="<?php echo urldecode(Url::urlFormat("/order/doc_refund_list?condition=and--dr.pay_status--eq--0"));?>" class="red"><?php echo isset($refund_num)?$refund_num:"";?>条</a></td><th class="caption">商品差评：</th> <td><a href="<?php echo urldecode(Url::urlFormat("/customer/review_list?condition=and--a.point--lt--3"));?>" class="red"><?php echo isset($review_num)?$review_num:"";?>条</a></td></tr>
    </table>
</div>
<div class="mt10">
    <h1><b>信息统计:</b></h1>
    <table class="mt10 default">
        <tr><th class="caption">会员总数：</th> <td><?php echo isset($user_num)?$user_num:"";?></td><th class="caption">商品总数：</th> <td><?php echo isset($goods_num)?$goods_num:"";?></td></tr>
        <tr><th class="caption">订单总数：</th> <td><?php echo isset($orders_num)?$orders_num:"";?></td><th class="caption">订单汇总：</th> <td><?php foreach($orders as $key => $item){?><span><i class="icon-order-<?php echo isset($key)?$key:"";?>"></i><?php echo isset($item)?$item:0;?></span><?php }?> </td></tr>
    </table>
</div>
</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		if('<?php echo Req::args("con");?>'=='admin'){
			$(".submenu .current").parent().parent().parent().addClass('current');
		}else{
			$(".submenu").addClass('current');
		}
		$(".submenu .sub-index").on("click",function(){
			$(this).parent().parent().toggleClass('current');
		})
	})
	
</script>
</body>
</html>
<!--Powered by TinyRise-->