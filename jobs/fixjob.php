#!/usr/bin/env php
<?php

define("APP_ROOT",dirname(__file__).'/../');

include(APP_ROOT."framework/tiny.php");

if(isset($config['classes']))Tiny::setClasses($config['classes']);
else Tiny::setClasses('classes.*');

$distributorModel = new Model('distributor','zd','master');

$distrDatas = $distributorModel->findAll();

foreach($distrDatas as $val){

//    if($val['site_url'] != 'g'){
//
//        continue;
//    }

    echo $val['site_url'];

    _addcategory($val['site_url'],$val['catids']);//商品分类

    $goods = _addgoods($val['site_url'],$val['catids']);//根据授权商品分类 对应的商品

    _addproducts($val['site_url'],$goods['goodsids']);//同步货品

    _addGoodsSpec($val['site_url'],$goods['goodsids'],$goods['specsids'],$goods['specvaluesids']);//同步商品规格

    _addGoodsType($val['site_url'],$goods['typeids']);//对应商品类型

    _addGoodsBrand($val['site_url'],$goods['brandids']);//品牌 信息

    _addTags($val['site_url'],$goods['tagids']);//商品标签

    _addpayment($val['site_url']);//支付方式

    sleep(1);
}

function _addGoodsBrand($domain,$brandids)
{
    $brandModel = new Model('brand','zd','master');
    $brands = $brandModel->where('id in ('.implode(',',$brandids).')')->findAll();

    $syncBrandModel = new Model('brand',$domain,'master');
    if($brands){
        foreach($brands as $brand){
            $syncBrandModel->data($brand)->add();
        }
    }
}

function _addGoodsType($domain,$typeids)
{
    $goodsAttrModel = new Model('goods_attr','zd','master');
    $goodsAttrs = $goodsAttrModel->where('type_id in ('.implode(',',$typeids).')')->findAll();

    $syncGoodsAttrModel = new Model('goods_attr',$domain,'master');

    $attrvalues = array();

    if($goodsAttrs){
        foreach($goodsAttrs as $goodsAttr){
            $attrvalues[$goodsAttr['id']] = $goodsAttr['id'];
            $syncGoodsAttrModel->data($goodsAttr)->add();
        }
    }

    if($attrvalues){
        $attrValueModel = new Model('attr_value','zd','master');
        $attrValues = $attrValueModel->where('attr_id in ('.implode(',',$attrvalues).')')->findAll();

        $syncattrValueModel = new Model('attr_value',$domain,'master');
        if($attrValues){
            foreach($attrValues as $attrValue){
                $syncattrValueModel->data($attrValue)->add();
            }
        }
    }


    $goodsTypeModel = new Model('goods_type','zd','master');
    $goodsTypes = $goodsTypeModel->where('id in ('.implode(',',$typeids).')')->findAll();

    $syncGoodsTypeModel = new Model('goods_type',$domain,'master');
    if($goodsTypes){
        foreach($goodsTypes as $goodsType){
            $syncGoodsTypeModel->data($goodsType)->add();
        }
    }
}

function _addGoodsSpec($domain,$goodsids,$specsids,$specvaluesids)
{
    $goodsSpecAttrModel = new Model('spec_attr','zd','master');
    $goodsSpecAttrs = $goodsSpecAttrModel->where('goods_id in ('.implode(',',$goodsids).')')->findAll();

    $syncGoodsSpecAttrModel = new Model('spec_attr',$domain,'master');
    if($goodsSpecAttrs){
        foreach($goodsSpecAttrs as $goodsSpecAttr){
            $syncGoodsSpecAttrModel->data($goodsSpecAttr)->add();
        }
    }

    $goodsSpecModel = new Model('goods_spec','zd','master');
    $goodsSpecs = $goodsSpecModel->where('id in ('.implode(',',$specsids).')')->findAll();

    $syncGoodsSpecModel = new Model('goods_spec',$domain,'master');
    if($goodsSpecs){
        foreach($goodsSpecs as $goodsSpec){
            $syncGoodsSpecModel->data($goodsSpec)->add();
        }
    }

    $SpecValueModel = new Model('spec_value','zd','master');
    $specValues = $SpecValueModel->where('id in ('.implode(',',$specvaluesids).')')->findAll();

    $syncSpecValueModel = new Model('spec_value',$domain,'master');
    if($specValues){
        foreach($specValues as $specValue){
            $syncSpecValueModel->data($specValue)->add();
        }
    }
}

function _addproducts($domain,$goodsids)
{
    $productsModel = new Model('products','zd','master');
    $products = $productsModel->where('goods_id in ('.implode(',',$goodsids).')')->findAll();

    $syncProductModel = new Model('products',$domain,'master');

    if($products){
        foreach($products as $product){
            $syncProductModel->data($product)->add();
        }
    }
}

function _addgoods($domain,$catids)
{

    $typeids = $brandids = $goodsids = $tagids = $specsids = $specvaluesids = array();

    $goodsModel = new Model('goods','zd','master');
    $goods = $goodsModel->where('category_id in ('.$catids.')')->findAll();

    $syncGoodsModel = new Model('goods',$domain,'master');
    if($goods){
        foreach($goods as $good){
            $typeids[$good['type_id']] = $good['type_id'];
            $brandids[$good['brand_id']] = $good['brand_id'];
            $goodsids[$good['id']] = $good['id'];
            $tagids[$good['tag_ids']] = $good['tag_ids'];
            $specs = unserialize($good['specs']);
            foreach($specs as $k=>$item){
                $specsids[$k] = $k;
                $specvalues = implode(',',array_keys($item['value']));
                $md5 = md5($specvalues);
                $specvaluesids[$md5] = $specvalues;
            }

            $syncGoodsModel->data($good)->add();
        }
    }

    return array(
        'typeids'=>$typeids,
        'brandids'=>$brandids,
        'goodsids'=>$goodsids,
        'tagids'=>$tagids,
        'specsids'=>$specsids,
        'specvaluesids'=>$specvaluesids,
    );
}

function _addcategory($domain,$catids)
{
    $categoryModel = new Model('goods_category','zd','master');
    $categorys = $categoryModel->where('id in ('.$catids.')')->findAll();

    $syncCategoryModel = new Model('goods_category',$domain,'master');
    if($categorys){
        foreach($categorys as $category){
            $syncCategoryModel->data($category)->add();
        }
    }
}

function _addpayment($domain)
{
    $paymentModel = new Model('payment','zd','master');
    $payments = $paymentModel->findAll();

    $syncPaymentModel = new Model('payment',$domain,'master');
    if($payments){
        foreach($payments as $payment){
            $syncPaymentModel->data($payment)->add();
        }
    }
}

function _addTags($domain,$tagids)
{
    $tagsModel = new Model('tags','zd','master');
    $tags = $tagsModel->where('id in ('.implode(',',$tagids).')')->findAll();

    $syncTagModel = new Model('tags',$domain,'master');
    if($tags){
        foreach($tags as $tag){
            $syncTagModel->data($tag)->add();
        }
    }
}
