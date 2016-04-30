<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 30/4/16
 * Time: 下午12:02
 */
class carts extends baseapi
{

    protected $cartIndexTemplate = '<li class="swipeout">
            <div class="card ks-facebook-card item-content swipeout-content">
                <div class="card-header">
                    <div class="ks-facebook-avatar"><img src="http://lorempixel.com/68/68/people/1/" width="60" height="60"/></div>
                    <div style="float:right;">
                        <div class="col-33 spinner" style="margin-bottom:0px;">
                            <button class="spinner_decr decrease">-</button><input type="text" size="1" value="0" class="spinnerExample spinner_text"><button class="increase spinner_incr">+</button>
                        </div>
                    </div>
                    <div class="ks-facebook-name" style="margin-right:44px;">John Doe</div>
                    <div class="ks-facebook-date" style="margin-right:44px;">Monday at 3:47 PM</div>
                    <div class="ks-facebook-date" style="margin-right:44px;">￥12.00</div>
                </div>
                <div class="swipeout-actions-right"><a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete">Delete</a></div>
            </div>
        </li>';

    protected $checkoutTemplate = '<li>
            <div class="item-content">
                <div class="item-inner">
                    <div class="item-title label">合计</div>
                    <div class="item-input right">
                        ￥12.00
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="item-content">
                <div class="item-inner">
                    <div class="item-title label">&nbsp;</div>
                    <div class="item-input right">
                        &nbsp;
                    </div>
                    <div class="item-after">
                        <a href="checkout.html" class="item-link"><span class="button">去结算(3)</span></a>
                    </div>
                </div>
            </div>
        </li>';

    protected $noCartTemplate = '<li>
            <div class="item-content">
                <div class="item-inner">
                    <div class="item-title">您的购物车还是空荡荡的<br/><a href="index.html" class="item-link">赶紧去逛逛</a></div>
                    <div class="item-input right">
                        &nbsp;
                    </div>
                </div>
            </div>
        </li>';

    public function index()
    {
        switch($this->params['source']){
            case 'cindex':
                $this->cartIndex();
                break;

        }
    }

    //购物车页面
    protected function cartIndex()
    {
        $html = $this->noCartTemplate;

        echo $html;
    }

}