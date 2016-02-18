<?php
class Cart{

	private static $ins = null;
	private $items = array();
	
	final protected function __construct() {
	}
	final protected function __clone() {	
	}

	protected static function getIns() {
		if (!(self::$ins instanceof self)) {
			self::$ins = new self();
		}
		return self::$ins;
	}

	public static function getCart() {
		if(Session::get("tiny_cart")==null || !(Session::get("tiny_cart") instanceof self)) {
			Session::set("tiny_cart",self::getIns());
		}
		return Session::get("tiny_cart");
	} 

	public  function addItem($id,$num=1) {
		if ($this->hasItem($id)) {
			$this->incNum($id,$num);
			return;
		}
		$this->items[$id] = $num;
	}

	public function hasItem($id) {
		return isset($this->items[$id]);
	}

	public function delItem($id) {
		unset($this->items[$id]);
	}

	public function modNum($id,$num=1) {
		if (!$this->hasItem($id)) {
			return false;
		}
		$this->items[$id] = $num;
	}

	public function incNum($id,$num=1) {
		if ($this->hasItem($id)) {
			$this->items[$id] += $num;
		}
	}

	public function decNum($id,$num=1) {
		if ($this->hasItem($id)) {
			$this->items[$id] -= $num;
		}
		if ($this->items[$id] <1) {
			$this->delItem($id);
		}
	}

	public function getCnt() {
		return count($this->items);
	}

	public function getNum(){
		if ($this->getCnt() == 0) {
			return 0;
		}

		$sum = 0;
		foreach ($this->items as $item) {
			$sum += $item;
		}
		return $sum;
	}

	public function all() {
		$products = array();
		if($this->getCnt()>0){
			$model = new Model("products as pr");
			$ids = array_keys($this->items);
			$ids = trim(implode(",", $ids),',');
			if($ids!=''){
				$prom = new Prom();
				$items= $model->fields("pr.*,go.img,go.name,go.prom_id,go.point")->join("left join goods as go on pr.goods_id = go.id ")->where("pr.id in($ids)")->findAll();
				foreach ($items as $item) {
					$num = $this->items[$item['id']];
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
						$products[$item['id']] = array('id'=>$item['id'],'goods_id'=>$item['goods_id'],'name'=>$item['name'],'img'=>$item['img'],'num'=>$num,'store_nums'=>$item['store_nums'],'price'=>$item['sell_price'],'prom_id'=>$item['prom_id'],'real_price'=>$prom_goods['real_price'],'sell_price'=>$item['sell_price'],'spec'=>unserialize($item['spec']),'amount'=>$amount,'prom'=>$prom_goods['note'],'weight'=>$item['weight'],'point'=>$item['point'],'sell_total'=>$sell_total,"prom_goods"=>$prom_goods);
					}
				}
			}
			
		}
		return $products;
	}


	public function clear() {
		$this->items = array();
	}
}