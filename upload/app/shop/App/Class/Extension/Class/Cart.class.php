<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城购物车类文件($)*/

!defined('DYHB_PATH') && exit;

class Cart{

	protected $_arrCarts=array();
	protected $_nCartsCount=0;
	protected $_nExpires=86400;
	protected $_sCookiename='nfbcart_cookie';
	
	public function __construct($nGoodid=0,$sGoodsname='',$arrGoodsprice=array(0,0,0,0,0,0,0,0),$nGoodscount=0,$nExpires=null,$sCookiename=null){
		if(!is_null($nExpires)){
			$this->_nExpires=$nExpires;
		}

		if(!is_null($sCookiename)){
			$this->_sCookiename=$sCookiename;
		}

		if(is_numeric($nGoodid) && $nGoodid>0){
			$this->addCart($nGoodid,$sGoodsname,$arrGoodsprice,$nGoodscount);
		}
	}

	public function addCart($nGoodid,$sGoodsname,$arrGoodsprice,$nGoodscount){
		$this->_arrCarts=$this->view();
		
		if($this->checkItem($nGoodid)){
			$this->edit($nGoodid,$nGoodscount,0);
			return false;
		}

		$this->_arrCarts['goods_id'][$nGoodid]=$nGoodid;
		$this->_arrCarts['goods_name'][$nGoodid]=$sGoodsname;
		$this->_arrCarts['goods_price'][$nGoodid]=$arrGoodsprice;
		$this->_arrCarts['goods_count'][$nGoodid]=$nGoodscount;

		$this->save();
	}

	public function editPrice($nGoodid,$arrGoodsprice){
		if($this->checkItem($nGoodid)){
			$this->_arrCarts=$this->view();
			$arrCarts=&$this->_arrCarts;
			$arrCarts['goods_price'][$nGoodid]=$arrGoodsprice;

			$this->save();
		}
	}

	public function edit($nGoodsid,$nGoodscount=1,$nFlag=0){
		$this->_arrCarts=$this->view();
		$arrCarts=&$this->_arrCarts;

		if(!isset($arrCarts['goods_id']) || !is_array($arrCarts['goods_id'])){
			return false;
		}

		if($nGoodsid<1){
			return false;
		}

		if(is_array($arrCarts['goods_id'])){
			foreach($arrCarts['goods_id'] as $nItem){
				if($nItem===$nGoodsid){
					switch($nFlag){
						case 0: // 添加商品数量
							$arrCarts['goods_count'][$nGoodsid]+=$nGoodscount;
							break;
						case 1: // 减少商品数量
							$arrCarts['goods_count'][$nGoodsid]-=$nGoodscount;
							break;
						case 2: // 修改数量
							if($nGoodscount==0){
								unset($arrCarts['goods_id'][$nGoodsid]);
								unset($arrCarts['goods_name'][$nGoodsid]);
								unset($arrCarts['goods_price'][$nGoodsid]);
								unset($arrCarts['goods_count'][$nGoodsid]);
								break;
							}else{
								$arrCarts['goods_count'][$nGoodsid]=$nGoodscount;
								break;
							}
						case 3: // 删除商品
							unset($arrCarts['goods_id'][$nGoodsid]);
							unset($arrCarts['goods_name'][$nGoodsid]);
							unset($arrCarts['goods_price'][$nGoodsid]);
							unset($arrCarts['goods_count'][$nGoodsid]);
							break;
						default:
							break;
					}
				}
			}
		}

		$this->save();
	}

	public function clear(){
		$this->_arrCarts=array();
		$this->save();
	}

	public function view(){
		$arrCarts=Dyhb::cookie($this->_sCookiename);

		if(!$arrCarts){
			return false;
		}

		return $arrCarts;
	}

	public function check(){
		$arrCarts=$this->_arrCarts=$this->view();
		
		if (count($arrCarts['goods_id'])<1){
			return false;
		}

		return true;
	}

	public function countPrice(){
		$arrCarts=$this->_arrCarts=$this->view();

		$arrData=array(
			'goods_price0'=>0,
			'goods_price1'=>0,
			'goods_price2'=>0,
			'goods_price3'=>0,
			'goods_price4'=>0,
			'goods_price5'=>0,
			'goods_price6'=>0,
			'goods_price7'=>0,
			'goods_count'=>0
		);

		$nI=0;
		if(is_array($arrCarts['goods_id'])){
			foreach($arrCarts['goods_id'] as $nKey=>$nVal){
				for($nM=0;$nM++;$nM<8){
					if(isset($arrCarts['goods_price'][$nKey]) && is_array($arrCarts['goods_price'][$nKey]) && array_key_exists($nM,$arrCarts['goods_price'][$nKey])){
						$arrData['goods_count'.$nM]+=$arrCarts['goods_price'][$nKey][$nM]*$arrCarts['goods_count'][$nKey];
					}else{
						$arrData['goods_count'.$nM]+=$arrCarts['goods_price'][$nKey][$nM]*$arrCarts['goods_count'][$nKey];
					}
				}

				$arrData['goods_count']+=$arrCarts['goods_count'][$nKey];
			}
		}

		return $arrData;
	}

	public function count(){
		$arrCarts=$this->view();
		$nCartsCount=count($arrCarts['goods_id']);
		$this->_nCartsCount=$nCartsCount;

		return $nCartsCount;
	}

	public function save(){
		$arrCarts=$this->_arrCarts;
		Dyhb::cookie($this->_sCookiename,$arrCarts,$this->_nExpires);
	}

	public function checkItem($nGoodsid){
		$arrCarts=$this->view();

		if(!isset($arrCarts['goods_id']) || !is_array($arrCarts['goods_id'])){
			return false;
		}

		if(is_array($arrCarts)){
			foreach($arrCarts['goods_id'] as $nItem){
				if($nItem==$nGoodsid){
					return true;
				}
			}
		}

		return false;
	}

	public function setExpires($nExpires=86400){
		$nOldValue=$this->_nExpires;
		$this->_nExpires=$nExpires;

		return $nOldValue;
	}	
	
	public function setCookiename($sCookiename='nfbcart_cookie'){
		$nOldValue=$this->_sCookiename;
		$this->_sCookiename=$sCookiename;

		return $nOldValue;
	}	
	
	public function getExpires(){
		return $this->_nExpires;
	}	
	
	public function getCookiename(){
		return $this->_sCookiename;
	}

	public function getCarts(){
		return $this->_arrCarts;
	}

	public function getCartsCount(){
		return $this->_nCartsCount;
	}

}
