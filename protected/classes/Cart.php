<?php
class Cart{

	private static $ins = null;
	private $items = array();

    private $uid = null;

    private $cartModel = null;

	protected function __construct($uid) {
        $this->uid = $uid;

        $this->cartModel = new Model('cart_session');
	}

	final protected function __clone() {	
	}

//	protected static function getIns() {
//		if (!(self::$ins instanceof self)) {
//			self::$ins = new self();
//		}
//		return self::$ins;
//	}

	public static function getCart($uid = 0) {
        if (!(self::$ins instanceof self)) {
            self::$ins = new self($uid);
        }
        return self::$ins;
//		if(Session::get("tiny_cart")==null || !(Session::get("tiny_cart") instanceof self)) {
//			Session::set("tiny_cart",self::getIns());
//		}
//		return Session::get("tiny_cart");
	} 

	public  function addItem($id,$num=1) {
		if ($this->hasItem($id)) {
			$this->incNum($id,$num);
			return;
		}
        $this->cartModel->data(array('num'=>$num,'product_id'=>$id,'uid'=>$this->uid))->insert();
	}

	public function hasItem($id) {
        $items = $this->cartModel->fields('id')->where('uid='.$this->uid.' and product_id='.$id)->find();
		return $items['id'] ? true : false;
	}

	public function delItem($id) {
        $this->cartModel->fields('id')->where('uid='.$this->uid.' and product_id='.$id)->delete();
//		unset($this->items[$id]);
	}

	public function modNum($id,$num=1) {
		if (!$this->hasItem($id)) {
			return false;
		}
        $this->cartModel->data(array('num'=>$num))->where('uid='.$this->uid.' and product_id='.$id)->update();
//		$this->items[$id] = $num;
	}

	public function incNum($id,$num=1) {
		if ($this->hasItem($id)) {
            $test = $this->cartModel->fields('id,num')->where('uid='.$this->uid.' and product_id='.$id)->find();

            $this->cartModel->data(array('num'=>$test['num']+$num))->where('id='.$test['id'])->update();
//			$this->items[$id] += $num;
		}
	}

	public function decNum($id,$num=1) {
		if ($this->hasItem($id)) {

            $test = $this->cartModel->fields('id,num')->where('uid='.$this->uid.' and product_id='.$id)->find();

            $nums = $test['num']-$num;

            if($nums < 1){
                $this->delItem($id);
            }else{
                $this->cartModel->data(array('num'=>$nums))->where('id='.$test['id'])->update();
            }


//			$this->items[$id] -= $num;
		}

	}

	public function getCnt() {

        return $this->cartModel->fields('id')->where('uid='.$this->uid)->count();
//		return count($this->items);
	}

	public function getNum(){
		if ($this->getCnt() == 0) {
			return 0;
		}

        $items = $this->cartModel->fields('id,product_id,num')->where('uid='.$this->uid)->findAll();

		$sum = 0;
		foreach ($items as $item) {
			$sum += $item['num'];
		}
		return $sum;
	}

    public function itemids()
    {
        $return = array();

        $items = $this->cartModel->fields('product_id,num')->where('uid='.$this->uid)->findAll();

        foreach($items as $item){
            $return[$item['product_id']] = $item['num'];
        }
        return $return;
    }

	public function all() {
		$products = array();
		if($this->getCnt()>0){
			$model = new Model("products as pr");
			$itemids = $this->itemids();
            $ids = array_keys($itemids);
			$ids = trim(implode(",", $ids),',');
			if($ids!=''){
				$prom = new Prom();
				$items= $model->fields("pr.*,go.img,go.name,go.prom_id,go.point,go.goods_no")->join("left join goods as go on pr.goods_id = go.id ")->where("pr.id in($ids)")->findAll();

                $storenums = $this->getStoresByProIds($ids);

				foreach ($items as $item) {

                    if(($item['branchstore_sell_price'] == '0.00') || ($item['branchstore_sell_price'] == '0') || ($item['branchstore_sell_price'] == '')){

                    }else{
                        $item['sell_price'] = $item['branchstore_sell_price'];
                    }

                    $item['store_nums'] = $storenums[$item['id']]['store_nums'];

					$num = $itemids[$item['id']];
					if($num > $item['store_nums']){
						$num = $item['store_nums'];
						$this->modNum($item['id'],$num);
					}

					if($num<=0){
						$this->delItem($item['id']);
					}else{
						$item['goods_nums']=$num;
						$prom_goods = $prom->prom_goods($item);
						$amount = sprintf("%01.2f",$prom_goods['real_price']*$num);

						$sell_total = $item['sell_price']*$num;
						$products[$item['id']] = array('id'=>$item['id'],'goods_no'=>$item['goods_no'],'product_no'=>$item['pro_no'],'goods_id'=>$item['goods_id'],'name'=>$item['name'],'img'=>$item['img'],'num'=>$num,'store_nums'=>$item['store_nums'],'price'=>$item['sell_price'],'cost_price'=>$item['cost_price'],'prom_id'=>$item['prom_id'],'real_price'=>$prom_goods['real_price'],'trade_price'=>$item['trade_price'],'sell_price'=>$item['sell_price'],'spec'=>unserialize($item['spec']),'amount'=>$amount,'prom'=>$prom_goods['note'],'weight'=>$item['weight'],'point'=>$item['point'],'sell_total'=>$sell_total,"prom_goods"=>$prom_goods);
					}
				}
			}
			
		}
		return $products;
	}

    private function getStoresByProIds($pid = array())
    {
        $inventorysModel = new Model('inventorys','zd','salve');

        $indata = $inventorysModel->where('product_id in ('.$pid.')')->findAll();

        $return = array();

        foreach($indata as $val){
            $return[$val['product_id']]['store_nums'] = $val['p_store_nums'];
            $return[$val['product_id']]['freeze_nums'] = $val['p_freeze_nums'];
        }

        return $return;
    }

	public function clear() {
        $this->cartModel->fields('id')->where('uid='.$this->uid)->delete();
//		$this->items = array();
	}
}


