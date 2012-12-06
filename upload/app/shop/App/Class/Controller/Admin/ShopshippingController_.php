<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商品配送方式控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShopshippingController extends InitController{

	public function filter_(&$arrMap){
	}

	public function index($sModel=null,$bDisplay=true){
		// 数据库中记录数量
		$arrShippinglist=array();

		$arrShippings=ShopshippingModel::F()->order('shopshipping_sort DESC')->getAll();
		if(is_array($arrShippings)){
			foreach($arrShippings as $oShipping){
				$arrShippinglist[$oShipping['shopshipping_code']]=$oShipping;
			}
		}

		$arrWarningmessage=array();

		// 读取支付方式中的配置数据
		$arrShippinglistData=array();

		$sShippingpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Shipping';
		
		$arrShippingdir=G::listDir($sShippingpath);
		if(is_array($arrShippingdir)){
			foreach($arrShippingdir as $sShippingdir){
				$sConfigfile=$sShippingpath.'/'.$sShippingdir.'/Config.php';

				if(!is_file($sConfigfile)){
					$arrWarningmessage[]=sprintf('支付方式 %s 配置文件不存在',$sShippingdir);
					continue;
				}else{
					$sShippingcode=$sShippingdir;

					$arrShippinglistData[$sShippingcode]=(array)(include $sConfigfile);

					if(isset($arrShippinglist[$sShippingcode])){
						$arrShippinglistData[$sShippingcode]=array_merge($arrShippinglistData[$sShippingcode],$arrShippinglist[$sShippingcode]->toArray());
						$arrShippinglistData[$sShippingcode]['install']='1';
					}else{
						$arrShippinglistData[$sShippingcode]['install']='0';
					}
				}
			}
		}

		$this->assign('arrShippinglistData',$arrShippinglistData);
		$this->assign('arrWarningmessage',$arrWarningmessage);

		$this->display(Admin_Extend::template('shop','shopshipping/index'));
	}
	
	public function install(){
		$sCode=trim(G::getGpc('code','G'));

		if(empty($sCode)){
			$this->E('你没有指定要安装配送方式');
		}

		// 查询是否已经安装了配送方式
		$oTryshopshipping=ShopshippingModel::F('shopshipping_code=?',$sCode)->getOne();
		if(!empty($oTryShopshipping['shopshipping_id'])){
			$this->E(sprintf('你安装的配送方式 %s 已经存在或者配送方式代码和已经有的重复',$sCode));
		}

		// 保存配送方式数据
		$sShippingpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Shipping';

		$sConfigfile=$sShippingpath.'/'.$sCode.'/Config.php';
		if(!is_file($sConfigfile)){
			$this->E(sprintf('配送方式 %s 配置文件不存在',$sConfigfile));
		}else{
			$arrShippingData=(array)(include $sConfigfile);

			$this->assign('arrShippingData',$arrShippingData);

			//G::dump($arrShippingData);
			
			$this->display(Admin_Extend::template('shop','shopshipping/add'));
		}
	}
	
	public function insert($sModel=null,$nId=null){
		$oShopshipping=new ShopshippingModel();
		$oShopshipping->save(0);
		
		if($oShopshipping->isError()){
			$this->E($oShopshipping->getErrorMessage());
		}
		
		$this->assign('__JumpUrl__',Admin_Extend::base(array('controller'=>'shopshipping')));
		
		$this->S('安装配送方式成功');
	}
	
	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::forbid('shopshipping',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::resume('shopshipping',$nId,true);
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		if(empty($nId)){
			$this->E('你没有指定要编辑配送方式');
		}

		// 查询是否已经安装了配送方式
		$oShopshipping=ShopshippingModel::F('shopshipping_id=?',$nId)->getOne();
		if(empty($oShopshipping['shopshipping_id'])){
			$this->E('待编辑的配送方式不存在');
		}
		
		$sShippingpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Shipping';

		$sConfigfile=$sShippingpath.'/'.$oShopshipping['shopshipping_code'].'/Config.php';
		if(!is_file($sConfigfile)){
			$this->E(sprintf('配送方式 %s 配置文件不存在',$sConfigfile));
		}else{
			$arrShippingData=(array)(include $sConfigfile);
			
			$arrShipping=$oShopshipping->toArray();
			
			$arrShippingData=array_merge($arrShippingData,$arrShipping);

			$this->assign('arrShippingData',$arrShippingData);

			$this->display(Admin_Extend::template('shop','shopshipping/add'));
		}
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');

		parent::update('shopshipping',$nId);
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');
		
		parent::foreverdelete('shopshipping',$sId);
	}

}
