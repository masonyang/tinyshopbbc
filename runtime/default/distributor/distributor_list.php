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
		<?php echo JS::import('dialog?skin=brief');?>
<?php echo JS::import('dialogtools');?>
<form action="" method="post">
<div class="tools_bar clearfix">
    <a class="icon-checkbox-checked icon-checkbox-unchecked" href="javascript:;" onclick="tools_select('id[]',this)" title="全选" data="true"> 全选 </a>

	<a class="icon-plus" href="<?php echo urldecode(Url::urlFormat("/distributor/distributor_edit"));?>"> 添加分销商</a>

    <span class="fr"><a href='javascript:;' id="condition" class="icon-search" style="" > 筛选条件</a><input id="condition_input" type="hidden" name="condition" value="<?php echo isset($condition)?$condition:"";?>"></span>
</div>
<table class="default" >
    <tr>
        <th style="width:30px">选择</th>
        <th style="width:70px">操作</th>
        <th style="width:100px">姓名</th>
        <th style="width:70px">联系方式 </th>
		<th style="width:70px">网店地址 </th>
        <th style="width:70px">预存款</th>
		<th style="width:70px">收益</th>
        <th style="width:70px">会员总数</th>
        <th style="width:80px">订单总数</th>
        <th style="width:80px">已授权分类</th>
    </tr>
		<?php $query = new Query("goods_category");$query->order = "path";$items = $query->find();?>
		<?php $goods_category=Common::treeArray($items);?>
		<?php foreach($goods_category as $key => $item){?>
		<?php if(!isset($path) || strpos($item['path'],$path)===false){?>
		<?php $num = count(explode(',',$item['path']))-3;?>
			<?php $categorys[$item['id']] = $item['name'];?>
		<?php }?>
		<?php }?>

    <?php $item=null; $obj = new Query("distributor");$obj->fields = "distributor_id,distributor_name,mobile,site_name,site_url,deposit,income,member_count,order_count,catids";$obj->where = "$where";$obj->page = "1";$obj->order = "distributor_id desc";$items = $obj->find(); foreach($items as $key => $item){?>
        <tr><td style="width:30px"><input type="checkbox" name="id[]" value="<?php echo isset($item['distributor_id'])?$item['distributor_id']:"";?>"></td>
            <td style="width:70px" class="btn_min"><div class="operat hidden"><a  class="icon-cog action" href="javascript:;"> 处理</a><div class="menu_select"><ul>
                <li><a class="icon-eye" href="javascript:;" onclick="view(<?php echo isset($item['distributor_id'])?$item['distributor_id']:"";?>)"> 查看</a></li>
                <li><a class="icon-pencil" href="<?php echo urldecode(Url::urlFormat("/distributor/distributor_edit/id/"));?><?php echo isset($item['distributor_id'])?$item['distributor_id']:"";?>"> 编辑</a></li>
				</ul></div></div>
			</td>
            <td style="width:100px"><?php echo isset($item['distributor_name'])?$item['distributor_name']:"";?></td>
			<td style="width:70px"><?php echo isset($item['mobile'])?$item['mobile']:"";?></td>
			<td style="width:70px"><?php echo isset($item['site_url'])?$item['site_url']:"";?><?php echo isset($domain)?$domain:"";?></td>
			<td style="width:70px"><?php echo isset($item['deposit'])?$item['deposit']:"";?></td>
			<td style="width:80px"><?php echo isset($item['income'])?$item['income']:"";?></td>
			<td style="width:80px"><?php echo isset($item['member_count'])?$item['member_count']:"";?></td>
			<td style="width:50px"><?php echo isset($item['order_count'])?$item['order_count']:"";?></td>
			<td style="width:50px">
				<?php $_catids = explode(',',$item['catids']);?>
				<?php foreach($_catids as $key => $item){?>
					<?php echo isset($categorys[$_catids[$key]])?$categorys[$_catids[$key]]:"";?>,
				<?php }?>
			</td>
		</tr>
    <?php }?>
</table>
</form>
<div class="page_nav">
<?php echo $obj->pageBar();?>
</div>
<script type="text/javascript">

    function view(id){
        art.dialog.open("<?php echo urldecode(Url::urlFormat("/distributor/distributor_view/id/"));?>"+id,{id:'view_dialog',title:'查看分销商',resize:false,width:900,height:450});
    }

$("#condition").on("click",function(){
  $("body").Condition({input:"#condition_input",okVal:'高级搜索',callback:function(data){tools_submit({action:'<?php echo urldecode(Url::urlFormat("/distributor/distributor_list"));?>',method:'get'});},data:{distributor_name:{name:'姓名'},site_name:{name:'站点名称'},mobile:{name:'手机号'}}});
})
</script>

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