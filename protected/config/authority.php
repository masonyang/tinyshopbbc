<?php

// nodisplay: 不在页面显示
// justshow: 只是显示，不能编辑
// modify:新增或修改页面内容
return array(
	'branchstore'=>array(
		'customer_list'=>array(
			'nodisplay'=>array(
			    '<a  class="icon-remove-2" href="javascript:;" onclick="tools_submit({action:\'<?php echo urldecode(Url::urlFormat("/customer/customer_del"));?>\',msg:\'删除后无法恢复，你真的删除吗？\'})" title="删除"> 删除</a>',
    '<a href=\'<?php echo urldecode(Url::urlFormat("/customer/customer_edit"));?>\' class="icon-plus" > 添加</a>',
    '<a class="icon-delicious" href="<?php echo urldecode(Url::urlFormat("/customer/customer_list"));?>"> 全部用户</a>',

			),
		),
		'goods_list'=>array(
			'nodisplay'=>array(
				'<a  class="icon-remove-2" href="javascript:;" onclick="tools_submit({action:\'<?php echo urldecode(Url::urlFormat("/goods/goods_del"));?>\',msg:\'删除后无法恢复，你真的删除吗？\'})" title="删除"> 删除</a>',
    '<a href=\'<?php echo urldecode(Url::urlFormat("/goods/goods_edit"));?>\'  class="icon-plus" > 添加</a>',
    '<a href=\'javascript:;\' onclick="tools_submit({action:\'<?php echo urldecode(Url::urlFormat("/goods/set_online/status/0"));?>\'});" class="icon-point-up" > 上架</a>',
    '<a href=\'javascript:;\' onclick="tools_submit({action:\'<?php echo urldecode(Url::urlFormat("/goods/set_online/status/1"));?>\'});" class="icon-point-down" > 下架</a>',
    '<li><a class="icon-point-up" href="<?php echo urldecode(Url::urlFormat("/goods/set_online/status/0/id/$item[id]"));?>"> 上架</a></li>',
                '<li><a class="icon-point-down" href="<?php echo urldecode(Url::urlFormat("/goods/set_online/status/1/id/$item[id]"));?>"> 下架</a></li>',
                '<li><a class="icon-remove-2" href="javascript:;" onclick="confirm_action(\'<?php echo urldecode(Url::urlFormat("/goods/goods_del/id/$item[id]"));?>\')"> 删除</a></li>',

			),
		),
		'order_list'=>array(
			'nodisplay'=>array(
                '<a class="icon-print" href="<?php echo urldecode(Url::urlFormat("/orderscanner/scannerorder"));?>"> 单据扫描</a>',
				'<a  class="icon-remove-2" href="javascript:;" onclick="tools_submit({action:\'<?php echo urldecode(Url::urlFormat("/order/order_del"));?>\',msg:\'删除后无法恢复，你真的删除吗？\'})" title="删除"> 删除</a>',
				'<a class="icon-eye-blocked" href="<?php echo urldecode(Url::urlFormat("/order/order_list/status/2"));?>"> 未审核</a>',
	    '<a class="icon-cogs" href="<?php echo urldecode(Url::urlFormat("/order/order_list/status/3"));?>"> 执行中</a>',
	    		'<li><a class="icon-drawer-3" href="javascript:;" onclick="change_status(<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>,3,null)"> 审核</a></li>',
	    		'<li><a class="icon-truck" href="javascript:;" onclick="send(<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>)"> 发货</a></li>',
                '<li><a class="icon-switch"  href="javascript:;" onclick="change_status(<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>,4,null)"> 完成</a></li>',
                '<li><a class="icon-remove" href="javascript:;" onclick="change_status(<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>,6,null)"> 作废</a></li>',
                '<li><a class="icon-close" href="javascript:confirm_action(\'<?php echo urldecode(Url::urlFormat("/order/order_del/id/$item[id]"));?>\')"> 删除</a></li>',
                '<li><a class="icon-attachment"  href="javascript:;" onclick="change_status(<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>,null,\'note\')"> 备注</a></li>',
                '<a class="icon-print action bottom" href="javascript:;"> 打印</a>',
                '<li><a class="icon-print" href="<?php echo urldecode(Url::urlFormat("/order/print_order/id/$item[id]"));?>" target="order"> 订单</a></li>
                        <li><a class="icon-print" href="<?php echo urldecode(Url::urlFormat("/order/print_product/id/$item[id]"));?>" target="product" > 购物单</a></li>
                        <li><a class="icon-print" href="<?php echo urldecode(Url::urlFormat("/order/print_picking/id/$item[id]"));?>" target="picking"> 配送单</a></li>
                        <li><a class="icon-print" href="<?php echo urldecode(Url::urlFormat("/order/print_express/id/$item[id]"));?>" target="express"> 快递单</a></li>',
			),
		),
		'goods_category_list'=>array(
			'nodisplay'=>array(
				'<a href=\'<?php echo urldecode(Url::urlFormat("/goods/goods_category_edit"));?>\' class="icon-plus" > 添加</a>',
				'<a  class="icon-cog action" href="javascript:;"> 处理</a>',
			),
		),
		'goods_edit'=>array(
			'justshow'=>array(
				'origin'=>array(
					'<select name="category_id" id="category_id" pattern="[1-9]\d*" alt="选择分类，若无分类请先创建。">',
					'<select name="type_id" id="type_id">',
					'<select name="brand_id">',
					'<input name="name" type="text" pattern="required" value="<?php echo isset($name)?$name:"";?>" style="width:400px;" alt="不能为空">',
        			'<input name="tag_ids" type="text"  value="<?php echo isset($tag_ids)?$tag_ids:"";?>" style="width:400px;">',
        			'<input name="goods_no" id="goods_no" type="text" pattern="required" alt="请输入3个以上的字符(不能为中文)" value="<?php echo isset($goods_no)?$goods_no:"";?>" >',
        			'<input  type="text" pattern="required" name="pro_no" alt="请输入3个以上的字符(不能为中文)" value="<?php echo isset($goods_no)?$goods_no:"";?>"/>',
        			'<input class="small" pattern="int" type="text" name="store_nums" value="<?php echo isset($store_nums)?$store_nums:"";?>" alt="必需为整数" />',
        			'<input class="small" type="text" pattern="int" name="warning_line" value="<?php echo isset($warning_line)?$warning_line:2;?>" alt="必需为整数"/>',
        			'<input class="small" type="text" pattern="int" name="weight" value="<?php echo isset($weight)?$weight:0;?>" alt="必需为整数"/>',
        			'<input name="seo_title" type="text" value="<?php echo isset($seo_title)?$seo_title:"";?>">',
        			'<input name="seo_keywords" type="text" value="<?php echo isset($seo_keywords)?$seo_keywords:"";?>">',
        			'<input name="seo_description" type="text" value="<?php echo isset($seo_description)?$seo_description:"";?>">',
        			'<textarea id="sale_protection"  name="sale_protection" style="width:700px;height:360px;visibility:hidden;"><?php echo isset($sale_protection)?$sale_protection:"";?></textarea>',
        			'<textarea id="content" pattern="required" name="content" style="width:700px;height:360px;visibility:hidden;"><?php echo isset($content)?$content:"";?></textarea>',
        			'<select name="attr[<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>]">',
        			'<input name="attr[<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>]" type="text" value="<?php echo isset($attrs[$item[\'id\']])?$attrs[$item[\'id\']]:"";?>" />',
        			'<a class="icon-arrow-left-2" href="javascript:;"></a>',
        			'<a class="icon-arrow-right-2" href="javascript:;"></a>',
        			'<a class="icon-link" href="javascript:;" onclick="linkImg(this)"></a>',
        			'<a class="icon-close" href="javascript:;" onclick="delImg(this)"></a>',
                	'<b class="red">(注：点选图片，使其成为默认图片)</b>',
                	'<button class="button  select_button" type="button" >',
                	'<b class="icon-plus green"></b>',
                	'添加图片',
          			'<input class="small" name="point" id="point" type="text" empty="" pattern="int" value="<?php echo isset($point)?$point:0;?>"></td>',
          			'<input class="small" name="sort" id="sort" type="text" pattern="int" empty="" value="<?php echo isset($sort)?$sort:1;?>"></td>',
          			'<input class="small" name="unit" pattern="required" type="text" value="<?php echo isset($unit)?$unit:\'件\';?>"></td>',
          			'<input type="checkbox" checked="checked" value=',
                    '<input  type="text" pattern="required" name="pro_no" alt="请输入3个以上的字符(不能为中文)" value="<?php echo isset($pro_no)?$pro_no:"";?>"/>',
                    '<input class="small" type="text" pattern="float" name="sell_price" value="<?php echo isset($sell_price)?$sell_price:"";?>" alt="整数或保留小数点后两位精确度的数" />',
                    '<input class="small" pattern="float" type="text" name="trade_price" value="<?php echo isset($trade_price)?$trade_price:"";?>" alt="整数或保留小数点后两位精确度的数"/>',
				),

				'replace'=>array(
					'<select name="category_id" disabled id="category_id" pattern="[1-9]\d*" alt="选择分类，若无分类请先创建。">',
					'<select name="type_id" disabled id="type_id">',
					'<select name="brand_id" disabled>',
					'<label><?php echo isset($name)?$name:"";?></label><input name="name" type="hidden" value="<?php echo isset($name)?$name:"";?>" />
            <label></label>
          </dd>
        </dl><dl class="lineD">
          <dt>
            <b class="red">*</b>
            自定义商品名称：
          </dt>
          <dd>
            <input name="branchstore_goods_name" type="text" value="<?php echo isset($branchstore_goods_name)?$branchstore_goods_name:"";?>" style="width:400px;">
            <label></label>
          </dd>
        </dl>',
        			'<label><?php echo isset($tag_ids)?$tag_ids:"暂无";?><input type="hidden" name="tag_ids" value="<?php echo isset($tag_ids)?$tag_ids:"";?>"></label>',
        			'<label><?php echo isset($goods_no)?$goods_no:"";?></label>',
        			'<label><?php echo isset($goods_no)?$goods_no:"";?></label>',
        			'<label><?php echo isset($store_nums)?$store_nums:"";?><input type="hidden" name="store_nums" value="<?php echo isset($store_nums)?$store_nums:"";?>"></label>',
        			'<label><?php echo isset($warning_line)?$warning_line:2;?><input type="hidden" name="warning_line" value="<?php echo isset($warning_line)?$warning_line:"";?>"></label>',
        			'<label><?php echo isset($weight)?$weight:0;?><input type="hidden" name="weight" value="<?php echo isset($weight)?$weight:"";?>"></label>',
        			'<label><?php echo isset($seo_title)?$seo_title:"";?><input type="hidden" name="store_nums" value="<?php echo isset($store_nums)?$store_nums:"";?>"></label>',
        			'<label><?php echo isset($seo_keywords)?$seo_keywords:"";?><input type="hidden" name="store_nums" value="<?php echo isset($store_nums)?$store_nums:"";?>"></label>',
        			'<label><?php echo isset($seo_description)?$seo_description:"";?><input type="hidden" name="seo_description" value="<?php echo isset($seo_description)?$seo_description:"";?>"></label>',
        			'<label><?php echo isset($sale_protection)?$sale_protection:"";?></label>',
        			'<label><?php echo isset($content)?$content:"";?></label>',
        			'<select disabled name="attr[<?php echo isset($item[\'id\'])?$item[\'id\']:"";?>]">',
        			'<label><?php echo isset($attrs[$item[\'id\']])?$attrs[$item[\'id\']]:"";?></label>',
        			'',
        			'',
        			'',
        			'',
        			'',
        			'',
        			'',
        			'',
        			'<?php echo isset($point)?$point:0;?>',
        			'<?php echo isset($sort)?$sort:1;?>',
        			'<?php echo isset($unit)?$unit:\'件\';?>',
        			'<input type="checkbox" disabled checked="checked" value=',
                    '<?php echo isset($pro_no)?$pro_no:"";?><input type="hidden" name="pro_no" value="<?php echo isset($pro_no)?$pro_no:"";?>">',
                    '<?php echo isset($sell_price)?$sell_price:"";?><input type="hidden" name="sell_price" value="<?php echo isset($sell_price)?$sell_price:"";?>">',
                    '<?php echo isset($trade_price)?$trade_price:"";?><input type="hidden" name="trade_price" value="<?php echo isset($trade_price)?$trade_price:"";?>">',
				),
			),
		),
	),
	'headstore'=>array(
		'layout'=>array(
			'file'=>'layouts/admin.html',
			'nodisplay'=>array(
				'<a href="<?php echo urldecode(Url::urlFormat("/index/index"));?>" target="_blank">返回前台</a> |',
			),
		),
        'order_list'=>array(
            'nodisplay'=>array(
                '<a class="icon-delicious" href="javascript:;" onclick="batch_orders()"> 批量下单</a>',

            ),
        ),
		'customer_list'=>array(
			'nodisplay'=>array(
			    '<a  class="icon-remove-2" href="javascript:;" onclick="tools_submit({action:\'<?php echo urldecode(Url::urlFormat("/customer/customer_del"));?>\',msg:\'删除后无法恢复，你真的删除吗？\'})" title="删除"> 删除</a>',
    '<a href=\'<?php echo urldecode(Url::urlFormat("/customer/customer_edit"));?>\' class="icon-plus" > 添加</a>',
    '<a class="icon-delicious" href="<?php echo urldecode(Url::urlFormat("/customer/customer_list"));?>"> 全部用户</a>',
    '<li><a class="icon-coin" href="javascript:balance_op(<?php echo isset($item[\'user_id\'])?$item[\'user_id\']:"";?>,2);"> 充值</a></li>',
                '<li><a class="icon-credit" href="javascript:balance_op(<?php echo isset($item[\'user_id\'])?$item[\'user_id\']:"";?>,4);"> 退款</a></li>',
                '<li><a class="icon-pencil" href="<?php echo urldecode(Url::urlFormat("/customer/customer_edit/id/$item[id]"));?>"> 编辑</a></li>',
                '<li><a class="icon-remove-2" href="javascript:confirm_action(\'<?php echo urldecode(Url::urlFormat("/customer/customer_del/id/$item[id]"));?>\')"> 删除</a></li>',


			),
		),
	),
);