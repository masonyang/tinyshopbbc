#!/usr/bin/env php
<?php

define("APP_ROOT",dirname(__file__).'/../');

include(APP_ROOT."framework/tiny.php");

if(isset($config['classes']))Tiny::setClasses($config['classes']);
else Tiny::setClasses('classes.*');


$productsModel = new Model('products','zd','master');

$goodsModel = new Model('goods','zd','master');

$inventorysModel = new Model('inventorys','zd','master');

$goodsData = array();

$gData = $goodsModel->fields('id,store_nums')->findAll();

foreach($gData as $goods){
    $goodsData[$goods['id']] = $goods['store_nums'];
}

$productsData = $productsModel->fields('id,goods_id,store_nums')->findAll();

foreach($productsData as $pro){

    $data = array();

    $data['goods_id'] = $pro['goods_id'];
    $data['product_id'] = $pro['id'];
    $data['p_store_nums'] = $pro['store_nums'];
    $data['p_freeze_nums'] = 0;
    //$data['p_sales_nums'] = $pro['store_nums'];

    $data['g_store_nums'] = $goodsData[$pro['goods_id']];
    $data['g_freeze_nums'] = 0;
    //$data['g_sales_nums'] = $goodsData[$pro['goods_id']];
    $inventorysModel->data($data)->insert();
}

