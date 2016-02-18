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
		<?php echo JS::import('validator');?>
<?php echo JS::import('form');?>
<?php echo JS::import('dialog?skin=brief');?>
<?php echo JS::import('dialogtools');?>
<?php $id = Req::args('id');?>
<h1 class="page_title">类型编辑</h1>
<form action="<?php echo urldecode(Url::urlFormat("/goods/goods_type_save"));?>" method="post" callback="check_invalid">
 <?php if(isset($id)){?><input type="hidden" name="id" value="<?php echo isset($id)?$id:"";?>"><?php }?>
<div id="obj_form" class="form2 tab">
  <ul class="tab-head"><li>基本信息</li><li class="current">规格</li><li class="current">品牌</li></ul>
    <div class="tab-body">
    <!--属性 start-->
      <div>
        <dl class="lineD">
          <dt>名称：</dt>
          <dd><input name="name" type="text" pattern="required" value="<?php echo isset($name)?$name:"";?>" alt="名称不能为空"><label>类型名称</label></dd>
        </dl>
        <dl class="lineD">
          <dt></dt><dd><button class="button" id="addAttrButton" >扩展属性</button></dd>
        </dl>
        <div>
        <table class="default" id="attr">

          <tr><th>属性名称</th>
          <th style="width:120px;">显示方式</th>
          <th style="width:260px;">选择项可选值</th>
          <th style="width:160px;">操作</th></tr>
          <?php if(isset($id)){?>

          <?php $item=null; $query = new Query("goods_attr");$query->where = "type_id  = $id";$query->order = "sort";$items = $query->find(); foreach($items as $key => $item){?>
          <tr> <td>
          <input type="hidden" name="attr_id[]" value="<?php echo isset($item['id'])?$item['id']:"";?>" />
          <input type="text" name="attr_name[]" value="<?php echo isset($item['name'])?$item['name']:"";?>" pattern="required" /></td> <td>
          <select class="middle attr_type" name="attr_type[]">
                    <option value="1" <?php if($item['show_type']==1){?>selected="selected"<?php }?>>下拉可筛选</option>
                    <option value="2" <?php if($item['show_type']==2){?>selected="selected"<?php }?>>下拉不可筛选</option>
                    <option value="3" <?php if($item['show_type']==3){?>selected="selected"<?php }?>>输入不可搜索</option>
                  </select>
          </td><td>
          <?php $attr_value_str='';$value_str=''; $attr_id = $item['id'];?>
          <?php $item=null; $attr_value = new Query("attr_value");$attr_value->where = "attr_id = $attr_id";$attr_value->order = "sort";$attr = $attr_value->find(); foreach($attr as $key => $item){?>
          <?php $attr_value_str .= $item['id'].":=:".$item['name'].",";$value_str .= $item['name'].",";?>
          <?php }?>
          <?php $attr_value_str = rtrim($attr_value_str,',');$value_str = rtrim($value_str,',');?>
          <input type="hidden" name="attr_value[]" value="<?php echo isset($attr_value_str)?$attr_value_str:"";?>" pattern="required" /> <a href="javascript:;" class="edit_button button">编辑</a> <span class="list_value"><?php echo isset($value_str)?$value_str:"";?></span></td> <td class="btn_min"><a href="javascript:;" class="icon-arrow-up-2">上升</a><a href="javascript:;" class="icon-arrow-down-2">下降</a><a href="javascript:;" class="icon-remove-2" >删除</a></td>
          </tr>
          <?php }?>
          <?php }?>
        </table>
        </div>
        
     
      </div>
      <!--属性 end-->
      <!--规格 start-->
        <div>
          <dl class="lineD">
            <dt></dt><dd><button class="button" id="addSpecButton" >添加规格</button></dd>
          </dl>
          <div>
            <table class="default" id="spec">
            <colgroup>
              <col/>
              <col width="260">
              <col width="200">
            </colgroup>
              <tr><th>规格</th><th>显示方式</th><th>操作</th></tr>
              <?php if(isset($spec)){?>
              <?php $specs = unserialize($spec);?>
              <?php foreach($specs as $key => $item){?>
              <tr> <td><?php echo isset($item['name'])?$item['name']:"";?><input type="hidden" id="spec_id_<?php echo isset($item['id'])?$item['id']:"";?>" name="spec[id][]" value="<?php echo isset($item['id'])?$item['id']:"";?>"/></td> <td><select class="middle" name="spec[show_type][]">
              <option value="1" <?php if($item['show_type']==1){?> selected="selected" <?php }?>>平铺显示</option>
              <option value="2" <?php if($item['show_type']==2){?> selected="selected" <?php }?>>下拉显示</option>
              </select></td><td class="btn_min"><a href="javascript:;" class="icon-arrow-up-2">上升</a><a href="javascript:;" class="icon-arrow-down-2">下降</a><a href="javascript:;"  class="icon-remove-2">删除</a></td></tr>
              <?php }?>
              <?php }?>
            </table>
          </div>
        </div>
      <!--规格 end-->
      <!--S 品牌-->
        <div>
          <ul class="clearfix brand-list">
            <?php $brand = isset($brand)?$brand:''; $brand_str = ','.$brand.',';?>
            <?php $item=null; $query = new Query("brand");$items = $query->find(); foreach($items as $key => $item){?>
            <?php $bid = ','.$item['id'].',';?>
            <li><input type="checkbox" <?php if(strpos($brand_str,$bid)!==false){?>checked="checked"<?php }?> name="brand[]" value="<?php echo isset($item['id'])?$item['id']:"";?>"><label><?php echo isset($item['name'])?$item['name']:"";?></label></li>
            <?php }?>
          </ul>
        </div>
      <!--E 品牌-->
    </div>
</div>
<div style="text-align:center">
    <input type="submit" value="提交" class="button">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="重置" class="button">
    </div>
</form>

<script type="text/javascript">
var form =  new Form();
form.setValue('parent_id','<?php echo isset($parent_id)?$parent_id:"";?>');
form.setValue('type','<?php echo isset($type)?$type:"";?>');

$("#addAttrButton").on("click",function(){
    $("#attr").append('<tr> <td><input type="hidden" name="attr_id[]" value="0" /><input type="text" name="attr_name[]" pattern="required" /></td> <td><select class="middle attr_type"  name="attr_type[]"> <option value="1" selected="">下拉可筛选</option> <option value="2">下拉不可筛选</option> <option value="3">输入不可搜索</option> </select></td><td><input type="hidden" name="attr_value[]" pattern="required" /> <a href="javascript:;" class="edit_button button">编辑</a> <span class="list_value"></span> </td><td class="btn_min"><a href="javascript:;" class="icon-arrow-up-2">上升</a><a href="javascript:;" class="icon-arrow-down-2">下降</a><a href="javascript:;"  class="icon-remove-2">删除</a></td></tr>');
  bindEvent();
  return false;
})

//绑定规格事件
$("#addSpecButton").on("click",function(){
  art.dialog.open('<?php echo urldecode(Url::urlFormat("/goods/goods_spec_show"));?>',{id:'spec_dialog',title:'规格选择',width:600,height:360});
  return false;
})

function addSpec(id,name){
  if($("#spec_id_"+id).get(0)){
    art.dialog.tips('<p class="warning">此规格已经添加！</p>');
  }else{
    $("#spec").append('<tr> <td>'+name+'<input type="hidden" id="spec_id_'+id+'" name="spec[id][]" value="'+id+'"/></td> <td><select class="middle"  name="spec[show_type][]"><option value="1">平铺显示</option><option value="2">下拉显示</option></select></td><td class="btn_min"><a href="javascript:;" class="icon-arrow-up-2">上升</a><a href="javascript:;" class="icon-arrow-down-2">下降</a><a href="javascript:;"  class="icon-remove-2">删除</a></td></tr>');
      bindEvent();
      art.dialog({id:'spec_dialog'}).close();
  }
}

//改变规格的类型

bindEvent();
//操作按钮事件绑定
function bindEvent(){
  $(".icon-arrow-down-2").off();
  $(".icon-arrow-up-2").off();
  $(".icon-remove-2").off();
  $(".edit_button").off();
  $(".attr_type").off();
  $(".icon-arrow-down-2").on("click",function(){
    var current_tr = $(this).parent().parent();
    current_tr.insertAfter(current_tr.next());
  });
    $(".icon-arrow-up-2").on("click",function(){
    var current_tr = $(this).parent().parent();
    if(current_tr.prev().prev().html()!=null)current_tr.insertBefore(current_tr.prev());
  });
    $(".icon-remove-2").on("click",function(){
      $(this).parent().parent().remove();
      //else art.dialog.tips('必须至少保留一个规格值');
    });
    $(".attr_type").on("click",function(){
      if($(this).val()>2)$(this).parent().next().find(".edit_button").css({display:'none'});
      else $(this).parent().next().find(".edit_button").css({display:''});
    });
    $(".edit_button").each(function(i){
      var num = i;
      $(this).on("click",function(){
      addAttrValue(num);
      return false;
    });
    });
}

function check_invalid(e){
  var index = $('.tab-body > *').has(e).index();
  if(index!=-1){
    tabs_select(0,index);
    return false;
  }
  else return true;
}

function addAttrValue(num){
  art.dialog.data('attr_num',num);
  var current_value = $("input[name='attr_value[]']").eq(num).val();
  art.dialog.data('current_value',current_value);
  art.dialog.open("<?php echo urldecode(Url::urlFormat("/goods/attr_values"));?>",{id:'attr_dialog',resize:false,width:700,height:400});
}
function updAttrValue(values){
  var num = art.dialog.data('attr_num');
  $("input[name='attr_value[]']").eq(num).val(values.join(','));
  var values_str = "";
  for(i in values){
    var id_name = values[i].split(":=:");
    values_str += id_name[1]+",";
  }
  if(values_str.length>0)values_str = values_str.slice(0,-1);
  $("span.list_value").eq(num).text(values_str);
  art.dialog({id:'attr_dialog'}).close();
}
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