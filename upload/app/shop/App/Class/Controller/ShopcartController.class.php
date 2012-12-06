<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城购物车控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopcartController extends InitController{
	
	public function index(){
		$arrData=Shoporder_Extend::getShopcartdata();
		$arrShopcartsData=$arrData[0];
		$arrShopcartsTotal=$arrData[1];
		
		$this->assign('arrShopcartsData',$arrShopcartsData);
		$this->assign('arrShopcartsTotal',$arrShopcartsTotal);

		$this->display('shopcart+index');
	}
	
	public function add(){
		$nShopgoodsnumber=intval(G::getGpc('number','P'));
		$nShopgoodsid=intval(G::getGpc('shopgoods_id','P'));
		$sShopgoodsprice=trim(G::getGpc('shopgoods_shopprice','P'));
		$sShopgoodname=trim(G::getGpc('shopgoods_name','P'));

		// 检查商品ID
		if($nShopgoodsnumber<1){
			$this->E('购买的商品数量不能为空');
		}

		if(empty($sShopgoodname)){
			$this->E('购买的商品名字不能为空');
		}

		// 检查商品
		if(empty($nShopgoodsid)){
			$this->E('你没有指定购买商品的ID');
		}

		$oShopgoods=ShopgoodsModel::F('shopgoods_id=? AND shopgoods_isonsale=1',$nShopgoodsid)->getOne();
		if(empty($oShopgoods['shopgoods_id'])){
			$this->E('你购买的商品不存在或者已经下架');
		}

		// 商品进入购物车
		$oCart=Dyhb::instance('Cart');
		$oCart->addCart($nShopgoodsid,$sShopgoodname,$sShopgoodsprice,$nShopgoodsnumber);

		$this->U('shop://shopcart/index');
	}

	public function clear(){
		$oCart=Dyhb::instance('Cart');
		$oCart->clear();

		$this->S('购物车清理成功');
	}

	public function update(){
		$arrShopcartcount=G::getGpc('shopcartcount','P');

		if(empty($arrShopcartcount)){
			$this->E('购物车为空');
		}

		$oCart=Dyhb::instance('Cart');
		foreach($arrShopcartcount as $nKey=>$nValue){
			$nValue=intval($nValue);
			if($nValue<1){
				$this->E('购物车中商品数量不能少于1');
			}

			$oCart->edit($nKey,$nValue,2);
		}

		$this->S('更新购物车成功');
	}

	public function delete(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E('你没有指定待购物车待删除的商品');
		}

		$oCart=Dyhb::instance('Cart');
		if(!$oCart->checkItem($nId)){
			$this->E('购物车中不存在该商品');
		}

		$oCart->edit($nId,0,3);

		$this->S('删除购物车中的商品成功');
	}

	public function notlogin(){
		Dyhb::cookie('___notlogin___',1);

		$this->U('shop://shopcart/checkout');
	}

	public function checkout(){
		$nNotlogin=Dyhb::cookie('___notlogin___');

		if($nNotlogin!=1 && $GLOBALS['___login___']===FALSE){
			$this->U('shop://shopcart/login');
		}else{
			$arrData=Shoporder_Extend::getShopcartdata();
			$arrShopcartsData=$arrData[0];
			$arrShopcartsTotal=$arrData[1];

			if(empty($arrShopcartsData)){
				$this->assign('__JumpUrl__',Dyhb::U('shop://public/index'));
				$this->E('购物车为空，无法结算');
			}

			// 获取支付方式
			$arrShoppayments=ShoppaymentModel::F('shoppayment_status=?',1)->getAll();
			
			// 获取配送方式
			$arrShopshippings=Shoppayment_Extend::getShippinglistData();

			// 用户已有配送地址
			$arrShopaddressDatas=array();

			if($GLOBALS['___login___']!==false){
				// 从数据库中读取用户的配送地址
			}else{
				// 如果为不直接登录则，使用cookie临时保存的数据
				$arrShopaddressCookiedata=Dyhb::cookie('___shopaddress___');
				if(!empty($arrShopaddressCookiedata)){
					$sShopaddressto=Shoporder_Extend::getShopaddressto($arrShopaddressCookiedata);
					$arrShopaddressCookiedata['shopaddressto']=$sShopaddressto;
					$arrShopaddressCookiedata['selected']=1;
					$arrShopaddressCookiedata['default']=1;

					$arrShopaddressDatas[]=$arrShopaddressCookiedata;
				}
			}

			$this->assign('arrShopcartsData',$arrShopcartsData);
			$this->assign('arrShopcartsTotal',$arrShopcartsTotal);
			$this->assign('arrShoppayments',$arrShoppayments);
			$this->assign('arrShopshippings',$arrShopshippings);
			$this->assign('nShopshippingid',4);
			$this->assign('nNotlogin',$nNotlogin);
			$this->assign('arrShopaddressDatas',$arrShopaddressDatas);

			$this->display('shopcart+checkout');
		}
	}


	public function save_consignee(){
		$arrShopaddressData=array();

		$arrShopaddressCookiedata=Dyhb::cookie('___shopaddress___');

		// 配送地址处理
		$arrShopaddressData['shopaddress_province']=Shoporder_Extend::getShopaddressdistrict('province',$arrShopaddressCookiedata);
		$arrShopaddressData['shopaddress_city']=Shoporder_Extend::getShopaddressdistrict('city',$arrShopaddressCookiedata);
		$arrShopaddressData['shopaddress_district']=Shoporder_Extend::getShopaddressdistrict('district',$arrShopaddressCookiedata);
		$arrShopaddressData['shopaddress_community']=Shoporder_Extend::getShopaddressdistrict('community',$arrShopaddressCookiedata);

		// 读取表单数据
		$arrShopaddressData['shopaddress_handaddress']=trim(G::getGpc('shopaddress_handaddress','P'));
		$arrShopaddressData['shopaddress_consignee']=trim(G::getGpc('shopaddress_consignee','P'));
		$arrShopaddressData['shopaddress_email']=trim(G::getGpc('shopaddress_email','P'));
		$arrShopaddressData['shopaddress_address']=trim(G::getGpc('shopaddress_address','P'));
		$arrShopaddressData['shopaddress_zipcode']=intval(G::getGpc('shopaddress_zipcode','P'));
		$arrShopaddressData['shopaddress_tel']=trim(G::getGpc('shopaddress_tel','P'));
		$arrShopaddressData['shopaddress_mobile']=trim(G::getGpc('shopaddress_mobile','P'));
		$arrShopaddressData['shopaddress_signbuilding']=trim(G::getGpc('shopaddress_signbuilding','P'));
		$arrShopaddressData['shopaddress_besttime']=trim(G::getGpc('shopaddress_besttime','P'));

		// 配送地址ID
		if(isset($_POST['shopaddress_id']) && !empty($_POST['shopaddress_id'])){
			$arrShopaddressData['shopaddress_id']=intval(G::getGpc('shopaddress_id','P'));
		}else{
			$arrShopaddressData['shopaddress_id']='default';
		}

		// 使用cookie临时保存数据
		Dyhb::cookie('___shopaddress___',$arrShopaddressData);
		
		// 处理配送地址
		$sShopaddressto=Shoporder_Extend::getShopaddressto($arrShopaddressData);
		$arrShopaddressData['shopaddressto']=$sShopaddressto;

		$this->A($arrShopaddressData,'地址保存成功',1);
	}

	public function login(){
		$nNotlogin=Dyhb::cookie('___notlogin___');
		
		if($nNotlogin==1 || $GLOBALS['___login___']!==FALSE){
			$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
			$this->S('你已经登录了或者启用不登陆直接购买模式');
		}else{
			// 登陆相关
			Core_Extend::loadCache('sociatype');

			$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_login_status']);
			$this->assign('nRememberTime',$GLOBALS['_option_']['remember_time']);
			$this->assign('arrBindeds',$GLOBALS['_cache_']['sociatype']);

			$this->display('shopcart+login');
		}
	}

	public function indb(){
		if($GLOBALS['___login___']===false){
			$this->E('你没有登录无法保存购物车');
		}
	}

	public function done(){
		// 读取并且检查购物车中的商品
		$arrShopcarTempdata=Shoporder_Extend::getShopcartdata();
		$arrShopcartsData=$arrShopcarTempdata[0];
		$arrShopcartsTotal=$arrShopcarTempdata[1];
		
		if(empty($arrShopcartsData)){
			$this->assign('__JumpUrl__',Dyhb::U('shop://public/index'));
			$this->E('购物车中的商品为空');
		}

		// 用户登陆,如果不是不直接登录购买，则跳转到登录地址
		$nNotlogin=Dyhb::cookie('___notlogin___');
		if($nNotlogin!=1 && $GLOBALS['___login___']===FALSE){
			$this->U('home://public/login');
		}

		// 收货人信息
		$arrShopaddressData=Dyhb::cookie('___shopaddress___');
		if(empty($arrShopaddressData['shopaddress_consignee'])){
			$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
			$this->E('收货人信息不完整');
		}

		// 处理订单信息表单
		$_POST['shoporderinfo_postscript']=isset($_POST['shoporderinfo_postscript'])?htmlspecialchars($_POST['shoporderinfo_postscript']):'';
		$_POST['shoporderinfo_howoos']=isset($_POST['shoporderinfo_howoos'])?intval($_POST['shoporderinfo_howoos']):0;
		
		// 生成商品订单入库信息
		$arrShoporderinfo=array();
		$arrShoporderinfo['shoporderinfo_sn']=Shoporder_Extend::getShoporderinfosn();
		$arrShoporderinfo['shoporderinfo_consignee']=$arrShopaddressData['shopaddress_consignee'];
		$arrShoporderinfo['shoporderinfo_province']=$arrShopaddressData['shopaddress_province'];
		$arrShoporderinfo['shoporderinfo_city']=$arrShopaddressData['shopaddress_city'];
		$arrShoporderinfo['shoporderinfo_district']=$arrShopaddressData['shopaddress_district'];
		$arrShoporderinfo['shoporderinfo_community']=$arrShopaddressData['shopaddress_community'];
		$arrShoporderinfo['shoporderinfo_address']=$arrShopaddressData['shopaddress_address'];
		$arrShoporderinfo['shoporderinfo_zipcode']=$arrShopaddressData['shopaddress_zipcode'];
		$arrShoporderinfo['shoporderinfo_tel']=$arrShopaddressData['shopaddress_tel'];
		$arrShoporderinfo['shoporderinfo_mobile']=$arrShopaddressData['shopaddress_mobile'];
		$arrShoporderinfo['shoporderinfo_email']=$arrShopaddressData['shopaddress_email'];
		$arrShoporderinfo['shoporderinfo_besttime']=$arrShopaddressData['shopaddress_besttime'];
		$arrShoporderinfo['shoporderinfo_signbuilding']=$arrShopaddressData['shopaddress_signbuilding'];

		// 根据配送方式取得配送方式名字
		$nShopshippingid=intval(G::getGpc('shopshipping_id','P'));
		if(empty($nShopshippingid)){
			$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
			$this->E('你没有选择任何配送方式');
		}

		$oShopshipping=ShopshippingModel::F('shopshipping_id=? AND shopshipping_status=1',$nShopshippingid)->getOne();
		if(empty($oShopshipping['shopshipping_id'])){
			$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
			$this->E('你选择的配送方式不存在或者尚未启用');
		}
		$arrShoporderinfo['shoporderinfo_shippingname']=$oShopshipping['shopshipping_name'];

		// 取得支付方式
		$nShoppaymentid=intval(G::getGpc('shoppayment_id','P'));
		if(empty($nShoppaymentid)){
			$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
			$this->E('你没有选择任何支付方式');
		}

		$oShoppayment=ShoppaymentModel::F('shoppayment_id=? AND shoppayment_status=1',$nShoppaymentid)->getOne();
		if(empty($oShoppayment['shoppayment_id'])){
			$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
			$this->E('你选择的支付方式不存在或者尚未启用');
		}
		$arrShoporderinfo['shoporderinfo_paymentname']=$oShoppayment['shoppayment_name'];

		// 商品价格
		//($arrShoporderinfo,$cart_goods);
		
		G::dump($arrShopcartsData);

		$arrTotalprice=Shoporder_Extend::orderinfoFee($arrShoporderinfo);
		//$order['bonus']        = $total['bonus'];
		//$order['goods_amount'] = $total['goods_price'];
		//$order['discount']     = $total['discount'];
		//$order['surplus']      = $total['surplus'];
		//$order['tax']          = $total['tax'];

		//$arrShoporderinfo['shoporderinfo_goodsamount']=$arrShopcartsTotal['goods_price'];

		exit();

		// 配送费用
		$arrShoporderinfo['shoporderinfo_shippingfee']=0;
		$arrShoporderinfo['shoporderinfo_insurefee']=0;

		// 支付费用
		$arrShoporderinfo['shoporderinfo_payfee']=0;

		// 商品订单入库
		$oShoporderinfo=Dyhb::instance('ShoporderinfoModel');
		$oShoporderinfo->insertShoporderinfo($arrShoporderinfo);
		if($oShoporderinfo->isError()){
			$this->E($oShoporderinfo->getErrorMessage());
		}

		// 订单商品入库
		if(!empty($arrShopcartsData)){
			foreach($arrShopcartsData as $arrShopcart){
				$oShopgoods=ShopgoodsModel::F('shopgoods_id=?',$arrShopcart['goods_id'])->getOne();
				if(empty($oShopgoods['shopgoods_id'])){
					$this->assign('__JumpUrl__',Dyhb::U('shop://shopcart/checkout'));
					$this->E('购物车中的商品不存在');
				}

				$arrShopordergoods=array(
					'shoporderinfo_id'=>$oShoporderinfo->shoporderinfo_id,
					'shopgoods_id'=>$arrShopcart['goods_id'],
					'shopordergoods_goodsname'=>$oShopgoods->shopgoods_name,
					'shopordergoods_goodssn'=>$oShopgoods->shopgoods_sn,
					'shopordergoods_goodsnumber'=>$oShopgoods->shopgoods_number,
					'shopordergoods_price'=>$oShopgoods->shopgoods_price,
					'shopordergoods_shopprice'=>$oShopgoods->shopgoods_shopprice,
					'shopordergoods_isreal'=>$oShopgoods->shopgoods_isreal,
				);

				$oShopordergoods=Dyhb::instance('ShopordergoodsModel');
				$oShopordergoods->insertShopordergoods($arrShopordergoods);

				if($oShopordergoods->isError()){
					$this->E($oShopordergoods->getErrorMessage());
				}
			}
		}

		// 支付日志
		$oShoppaylog=Dyhb::instance('ShoppaylogModel');
		$oShoppaylog->insertShoppaylog($oShoporderinfo['shoporderinfo_id'],$oShoporderinfo['shoporderinfo_orderamount']);
		if($oShoppaylog->isError()){
			$this->E($oShoppaylog->getErrorMessage());
		}

		// 支付按钮
		$sShoppaymentReturnhtml=Shoporder_Extend::getShoppaymentreturnhtml($oShoppayment);

		$this->assign('oShoporderinfo',$oShoporderinfo);
		$this->assign('oShopshipping',$oShopshipping);
		$this->assign('oShoppayment',$oShoppayment);
		$this->assign('sShoppaymentReturnhtml',$sShoppaymentReturnhtml);

		$this->display('shopcart+done');
	}

	public function clearnotlogin(){
		Dyhb::cookie('___notlogin___',null,-1);

		$this->S('清除不登录直接购买状态');
	}

	public function shopaddress(){
		$arrShopaddressData=Dyhb::cookie('___shopaddress___');
		$sShopaddressdistrict=Shoporder_Extend::getShopaddressdistrictTwo($arrShopaddressData);

		$this->assign('sShopaddressdistrict',$sShopaddressdistrict);
		$this->assign('arrShopaddressData',$arrShopaddressData);

		$this->display('shopcart+address');
	}

}
