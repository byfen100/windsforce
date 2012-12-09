<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城个人中心控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopucenterController extends InitController{

	public function index(){
		$this->display('shopucenter+index');
	}

	public function shopgoodsbuy(){
		$this->display('shopucenter+shopgoodsbuy');
	}

	public function shopgoodsorder(){
		$this->display('shopucenter+shopgoodsorder');
	}

	public function myorder(){
		$this->display('shopucenter+myorder');
	}

	public function favorite(){
		$this->display('shopucenter+favorite');
	}

	public function friend(){
		$this->display('shopucenter+friend');
	}

	public function avatar(){
		$this->display('shopucenter+avatar');
	}

	public function profile(){
		$this->display('shopucenter+profile');
	}

	public function password(){
		$this->display('shopucenter+password');
	}

	public function shopaddress(){
		$this->display('shopucenter+shopaddress');
	}

	public function message(){
		$this->display('shopucenter+message');
	}

	public function announcement(){
		$this->display('shopucenter+announcement');
	}

	public function shopcart(){
		$this->display('shopucenter+shopcart');
	}

	public function album(){
		$this->display('shopucenter+album');
	}

}
