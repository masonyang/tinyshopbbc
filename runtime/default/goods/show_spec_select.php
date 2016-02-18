<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>图片管理</title>
	<link rel="stylesheet" href="<?php echo urldecode(Url::urlFormat("@static/css/base.css"));?>" />
	<link rel="stylesheet" href="<?php echo urldecode(Url::urlFormat("@static/css/admin.css"));?>" />
	<link rel="stylesheet" href="<?php echo urldecode(Url::urlFormat("@static/css/font_icon.css"));?>" />
	<!--[if lte IE 7]>
	<script src="<?php echo urldecode(Url::urlFormat("@static/css/fonts/lte-ie7.js"));?>"></script>
	<![endif]-->
	<?php echo JS::import('jquery');?>
	<?php echo JS::import('form');?>
	<?php echo JS::import('dialog?skin=brief');?>
	<?php echo JS::import('dialogtools');?>
	<script src="<?php echo urldecode(Url::urlFormat("@static/js/common.js"));?>" charset="UTF-8" type="text/javascript"></script>
</head>
<body style="background:#fff;">
	<div class="alone_box">
		<div class="head">商品规格（点选当前产品所需要规格）</div>
		<div>
			<div class="left">
				<ul class="spec_names">
					<?php $query = new Query("goods_spec");$query->order = "id";$spec = $query->find();?>
			<?php foreach($spec as $key => $item){?>
					<li>
						<a href="javascript:;" value="<?php echo isset($item['id'])?$item['id']:"";?>:<?php echo isset($item['name'])?$item['name']:"";?>">
							<?php echo isset($item['name'])?$item['name']:"";?>[<?php echo isset($item['note'])?$item['note']:"";?>]
							<span></span>
						</a>
					</li>
					<?php }?>
				</ul>
			</div>
			<div class="right" style="padding-right:5px">
				<div class="box" style="margin:0 0 10px 0">
					<div class="head">
						<input type="checkbox" id="check_all" class="r5">
						<label for="all">全选</label>
					</div>
					<div class="spec_values">
						<?php foreach($spec as $key => $item){?>
				<?php $values = unserialize($item['value']);?>
						<ul>
							<?php foreach($values as $key => $ite){?>
					<?php if($item['type']==2){?>
							<li>
								<input type="checkbox" name="" onchange="spec_select()" id="" value="<?php echo isset($ite['id'])?$ite['id']:"";?>" />
								<label><?php echo isset($ite['name'])?$ite['name']:"";?></label>
								<div class="img">
									<img src="<?php echo urldecode(Url::urlFormat("@$ite[img]"));?>" ></div>
							</li>
							<?php }else{?>
							<li>
								<input type="checkbox" onchange="spec_select()" id="" name="" value="<?php echo isset($ite['id'])?$ite['id']:"";?>" />
								<label><?php echo isset($ite['name'])?$ite['name']:"";?></label>
							</li>
							<?php }?>
				<?php }?>
						</ul>
						<?php }?>
					</div>
				</div>
				<table class="default">
					<tr>
						<th width="100">系统规格</th>
						<th width="120">自定义规格</th>
						<th width="256">自定义规格图片</th>
						<th width="54">操作</th>
					</tr>
				</table>
				<div id="spec_show_list">
					<?php foreach($spec as $key => $item){?>
			<?php $values = unserialize($item['value']);?>
					<table id="<?php echo isset($item['id'])?$item['id']:"";?>:<?php echo isset($item['name'])?$item['name']:"";?>" class="default" style="border-top:0;">
						<?php foreach($values as $key => $ite){?>
						<tr>
							<td width="100">
								<input type="hidden" value="<?php echo isset($ite['id'])?$ite['id']:"";?>">
								<input type="hidden" value="<?php echo isset($ite['name'])?$ite['name']:"";?>"><?php echo isset($ite['name'])?$ite['name']:"";?></td>
							<td width="120">
								<input type='text' class="small spec_key" value='<?php echo isset($ite['name'])?$ite['name']:"";?>' />
							</td>
							<td width="256">
								<input class="spec_value"  name=''/>
								<button class='button' onclick='uploadFile(this)'>添加图片</button>
							</td>
							<td width="54">
								<a class='icon-close' href='javascript:;' onclick='spec_del(this)'></a>
							</td>
						</tr>
						<?php }?>
					</table>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	<div style="text-align: center; position: fixed; bottom: 0px; background: #F0F0F0; left: 0; right: 0; border-top: 1px solid #C4C4C4; height: 40px; line-height: 40px; ">
		<button class="focus_button" onclick="saveSpec()">生成所有货品</button>
	</div>
	<script type="text/javascript">
	$(".spec_values >ul").css({display:'none'});
	$(".spec_names li").each(function(i){
		var num = i;
		$(this).on("click",function(){
			$(".spec_names li").removeClass("current");
			$(this).addClass("current");
			$(".spec_values >ul").css({display:'none'});
			$(".spec_values >ul:eq("+num+")").css({display:''});
			spec_select();
		})
	})
	$("#check_all").on("click",function(){
		$(".spec_values input:visible").attr("checked",!!$(this).attr("checked"));
		spec_select();
	})

	function spec_select(){
		var current_spec = $(".spec_values ul:visible");
		var num = 0;
		$("#spec_show_list table").removeClass('current');
		var current_table = $("#spec_show_list table").eq(current_spec.index());
			current_table.addClass("current");
		$("input",current_spec).each(function(){
			
			var val = $(this).val();
			if(!!$(this).attr("checked")){
				num++;
				$("tr",current_table).has("input[value='"+val+"']").addClass("select");
			}
			else{
				$("tr",current_table).has("input[value='"+val+"']").removeClass("select");
			}
			
		});
		if(current_spec.find("input").length==num && num>0)$("#check_all").attr("checked",true);
		else $("#check_all").attr("checked",false);
		$(".spec_names span").eq(current_spec.index()).text(num==0?"":"("+num+")");
		if(current_spec.index()>=0) $(".spec_names li").removeClass("current").eq(current_spec.index()).addClass("current");
	}
	function spec_del(id){
		var tr = $(id).parent().parent();
		var spec_val = tr.find("input:eq(0)").val();
		$(".spec_values ul:visible").find("input[value='"+spec_val+"']").attr("checked",false).change();
	}
	function saveSpec (){
		var tem = "";
		$('#spec_show_list table').has(".select").each(function(){
			var table = $(this);
			var spec_id = table.attr("id");
			tem += spec_id+'=';
			$("tr.select",table).each(function(){
				var tr = $(this);
				$("input",tr).each(function(){
					tem += $(this).val()+':';
				});
				tem = tem.slice(0,-1);
				tem +=",";
			})
			tem = tem.slice(0,-1);
			tem += ";";
		})
		tem = tem.slice(0,-1);
		if(tem!='')parent.addSpec(tem);
		else art.dialog.alert("没有选择任何规格！");
	}

	 Array.prototype.uniq = function() {  
        var temp = {}, len = this.length;

        for(var i=0; i < len; i++)  {  
            if(typeof temp[this[i]] == "undefined") {
                temp[this[i]] = 1;
            }  
        }  
        this.length = 0;
        len = 0;
        for(var i in temp) {  
            this[len++] = i;
        }  
        return this;  
    } 
	//初始化spec
	spec_init();
	function spec_init(){
		var spec_dcr = art.dialog.data("spec_init_data");
		spec_dcr = spec_dcr.split(",");
		spec_dcr = spec_dcr.uniq();
		var len = spec_dcr.length;
		for(var i=0;i<len; i++){
			var value = spec_dcr[i].split(":");
			$(".spec_values ul").hide();
			$(":checkbox[value='"+value[0]+"']").parent().parent().show();
			$(":checkbox[value='"+value[0]+"']").click();
			var spec_tr = $("#spec_show_list input[value='"+value[0]+"']").parent().parent();
			$("input",spec_tr).eq(2).val(value[2]);
			$("input",spec_tr).eq(3).val(value[3]);
			spec_select();
		}
	}
	
	function uploadFile(id){
		art.dialog.data('current_item',id);
		art.dialog.open('<?php echo urldecode(Url::urlFormat("/admin/photoshop/type/1"));?>',{id:'upimg_indialog',title:'选择图片',width:613,height:380});
	}
	//回写选择图片
	function setImg(value){
		var id = art.dialog.data('current_item');
		$(id).prev().val(value);
		art.dialog({id:'upimg_indialog'}).close(); 
	}
</script>
</body>
</html>
<!--Powered by TinyRise-->