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
<?php echo JS::import('form');?>
<?php echo JS::import('mutiselect');?>
<?php echo JS::import('validator');?>
<h2 class="page_title"><?php echo isset($node_index['name'])?$node_index['name']:"";?></h2>
<div class="form2">
	<form name="config_form" method="post" action="<?php echo urldecode(Url::urlFormat("/admin/config/group/globals"));?>">
        <?php if(isset($manager['id'])){?><input type="hidden" name="id" value="<?php echo isset($manager['id'])?$manager['id']:"";?>"><?php }?>

	 <dl class="lineD">
      <dt>授权分类：</dt>
      <dd>
		<?php $query = new Query("goods_category");$query->order = "path";$items = $query->find();?>
		<?php $goods_category=Common::treeArray($items);?>
		<?php foreach($goods_category as $key => $item){?>
		<?php if(!isset($path) || strpos($item['path'],$path)===false){?>
		<?php $num = count(explode(',',$item['path']))-3;?>
			<?php $categorys[$item['id']] = $item['name'];?>
		<?php }?>
		<?php }?>
		<?php $_catids = explode(',',$manager['catids']);?>
				<?php foreach($_catids as $key => $item){?>
					<?php echo isset($categorys[$_catids[$key]])?$categorys[$_catids[$key]]:"";?>,
				<?php }?>
      </dd>
      </dl>
    <dl class="lineD">
      <dt>分销商名称：</dt>
      <dd>
        <label><?php echo isset($manager['name'])?$manager['name']:"";?></label>
      </dd>
      </dl>
      <dl class="lineD">
      <dt>邮箱：</dt>
      <dd>
        <label><?php echo isset($manager['email'])?$manager['email']:"";?></label>
      </dd>
      </dl>
      <dl class="lineD">
      <dt>注册时间：</dt>
      <dd>
        <label><b>注册：</b><?php echo isset($manager['register_time'])?$manager['register_time']:"";?></label>
      </dd>
      </dl>
	  <dl class="lineD">
      <dt>地区：</dt>
      <dd >
        <div id="area">
        <select id="province"  name="province" province="<?php echo isset($manager['province'])?$manager['province']:0;?>" disabled><option value="0">==省份/直辖市==</option></select>
        <select id="city" name="city" city="<?php echo isset($manager['city'])?$manager['city']:0;?>" disabled><option value="0">==市==</option></select>
        <select id="county" name="county" county="<?php echo isset($manager['county'])?$manager['county']:0;?>" disabled><option value="0">==县/区==</option></select><input pattern="^\d+,\d+,\d+$" id="test" type="text" style="visibility:hidden;width:0;" value="<?php echo isset($manager['province'])?$manager['province']:"";?>,<?php echo isset($manager['city'])?$manager['city']:"";?>,<?php echo isset($manager['county'])?$manager['county']:"";?>" alt="请选择完整地区信息！"><label></label></div>
      </dd>
      </dl>
      <dl class="lineD">
      <dt>详细地址：</dt>
      <dd>
        <input name="addr" type="text" empty  pattern="required" value="<?php echo isset($manager['addr'])?$manager['addr']:"";?>">
        <label></label>
      </dd>
      </dl>
      <dl class="lineD">
      <dt>固定电话：</dt>
      <dd>
        <input name="phone" type="text" empty pattern="phone" value="<?php echo isset($manager['phone'])?$manager['phone']:"";?>">
        <label></label>
      </dd>
      </dl><dl class="lineD">
      <dt>手机：</dt>
      <dd>
        <input name="mobile" type="text" empty pattern="mobi" value="<?php echo isset($manager['mobile'])?$manager['mobile']:"";?>">
        <label></label>
      </dd>
      </dl>
      <dl class="lineD">
      <dt>站点名称：</dt>
      <dd>
        <input name="site_name" type="text" empty pattern="required" value="<?php echo isset($manager['site_name'])?$manager['site_name']:"";?>">
        <label></label>
      </dd>
      </dl>
	  <dl class="lineD">
        <dt>站点Logo：</dt>
        <dd>
          <div id="img-show" >
            <?php if(isset($manager['site_logo'])){?>
              <img height='50' src="<?php echo isset($manager['site_logo'])?$manager['site_logo']:"";?>">
            <?php }?>
          </div>
          <div>
          <?php $path = Tiny::getPath('uploads_url');?>
          <input name="site_logo" type="hidden" id="logo" value="<?php echo isset($manager['site_logo'])?$manager['site_logo']:"";?>" /><label></label><button class="button select_button">选择图片</button>
          </div>
        </dd>
      </dl>
	  <dl class="lineD">
      <dt>公司/备案号：</dt>
      <dd>
        <?php echo isset($manager['site_icp'])?$manager['site_icp']:"";?>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>网址：</dt>
      <dd>
        <?php echo isset($manager['site_url'])?$manager['site_url']:"";?><?php echo isset($domain)?$domain:"";?>
        <span>  </span>
      </dd>
    </dl>
	<dl class="lineD">
      <dt>ios下载地址：</dt>
      <dd>
        <input name="site_ios_url" type="text"  size=40 value="<?php echo isset($manager['site_ios_url'])?$manager['site_ios_url']:"";?>"/>
        <span>  </span>
      </dd>
    </dl>
	<dl class="lineD">
      <dt>android下载地址：</dt>
      <dd>
        <input name="site_android_url" type="text"  size=40 value="<?php echo isset($manager['site_android_url'])?$manager['site_android_url']:"";?>"/>
        <span>  </span>
      </dd>
    </dl>
	<dl class="lineD">
      <dt>邮政编码：</dt>
      <dd>
        <input name="zip" type="text" size=40 value="<?php echo isset($manager['zip'])?$manager['zip']:"";?>"/>
        <span>  </span>
      </dd>
    </dl>
	    <dl class="lineD">
      <dt>关键字：</dt>
      <dd>
        <input name="site_keyword" type="text" size=60 value="<?php echo isset($manager['site_keyword'])?$manager['site_keyword']:"";?>">
        <label>多个使用英文的“|”分割</label>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>描述信息：</dt>
      <dd>
        <input name="site_description" type="text" size=60 value="<?php echo isset($manager['site_description'])?$manager['site_description']:"";?>">
        <label>多个使用英文的“|”分割 </label>
      </dd>
    </dl>
	  <dl class="lineD">
      <dt>预存款：</dt>
      <dd>
        <input name="deposit" type="text" size=10 disabled value="<?php echo isset($manager['deposit'])?$manager['deposit']:"";?>">&nbsp;&nbsp;&nbsp;<a href="#">充值</a>
      </dd>
    </dl>

	   <div class="center">
      <input type="submit" name="submit" class="button action fn" value="确 定">
    </div>
    </form>
</div>

<script>
	<?php if(isset($message)){?>
	art.dialog.tips('<p class="success"><?php echo isset($message)?$message:"";?></p>');
	<?php }?>

  var province = $('#province').attr('province');
  var city = $('#city').attr('city');
  var county = $('#county').attr('county');
  $("#area").Linkage({ url:"<?php echo urldecode(Url::urlFormat("/ajax/area_data"));?>",selected:[province,city,county],callback:function(data){
    var text = new Array();
    var value = new Array();
    for(i in data[0]){
      if(data[0][i]!=0){
        text.push(data[1][i]);
        value.push(data[0][i]);
      }
    }
    $("#test").val(value.join(','));
    FireEvent(document.getElementById("test"),"change");
    }});


  $(".select_button").on("click",function(){
      uploadFile();
      return false;
    });
function uploadFile(){
  art.dialog.open('<?php echo urldecode(Url::urlFormat("/admin/photoshop?type=2"));?>',{id:'upimg_dialog',title:'选择图片',width:613,height:380});
}
function setImg(value){
  $("#logo").val(value);
  var img = "<?php echo urldecode(Url::urlFormat("@"));?>"+value;
  $("#img-show").html("<img height='50' src='"+img+"'>");
  art.dialog({id:'upimg_dialog'}).close();
}

$(function(){
    $("#category").multiselect({
        noneSelectedText: "==请选择==",
        checkAllText: "全选",
        uncheckAllText: '全不选',
        selectedList:8
    });
});



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