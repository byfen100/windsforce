<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商品支付方式控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShoppaymentController extends InitController{

	public function filter_(&$arrMap){
	}

	public function index($sModel=null,$bDisplay=true){
		// 数据库中记录数量
		$arrPaymentlist=array();

		$arrPayments=ShoppaymentModel::F()->order('shoppayment_sort DESC')->getAll();
		if(is_array($arrPayments)){
			foreach($arrPayments as $oPayment){
				$arrPaymentlist[$oPayment['shoppayment_code']]=$oPayment;
			}
		}

		$arrWarningmessage=array();

		// 读取支付方式中的配置数据
		$arrPaymentlistData=array();

		$sParmentpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Payment';
		
		$arrPaymentdir=G::listDir($sParmentpath);
		if(is_array($arrPaymentdir)){
			foreach($arrPaymentdir as $sPaymentdir){
				$sConfigfile=$sParmentpath.'/'.$sPaymentdir.'/Config.php';

				if(!is_file($sConfigfile)){
					$arrWarningmessage[]=sprintf('支付方式 %s 配置文件不存在',$sConfigfile);
					continue;
				}else{
					$sPaymentcode=$sPaymentdir;

					$arrPaymentlistData[$sPaymentcode]=(array)(include $sConfigfile);

					if(isset($arrPaymentlist[$sPaymentcode])){
						$arrPaymentlistData[$sPaymentcode]=array_merge($arrPaymentlistData[$sPaymentcode],$arrPaymentlist[$sPaymentcode]->toArray());
						$arrPaymentlistData[$sPaymentcode]['install']='1';
					}else{
						if(!isset($arrPaymentlistData[$sPaymentcode]['shoppayment_fee'])){
							$arrPaymentlistData[$sPaymentcode]['shoppayment_fee']=0;
						}
						$arrPaymentlistData[$sPaymentcode]['install']='0';
					}
				}
			}
		}

		$this->assign('arrPaymentlistData',$arrPaymentlistData);
		$this->assign('arrWarningmessage',$arrWarningmessage);

		$this->display(Admin_Extend::template('shop','shoppayment/index'));
	}

	public function install(){
		$sCode=trim(G::getGpc('code','G'));

		if(empty($sCode)){
			$this->E('你没有指定要安装支付方式');
		}

		// 查询是否已经安装了支付方式
		$oTryshoppayment=ShoppaymentModel::F('shoppayment_code=?',$sCode)->getOne();
		if(!empty($oTryshoppayment['shoppayment_id'])){
			$this->E(sprintf('你安装的支付方式 %s 已经存在或者支付方式代码和已经有的重复',$sCode));
		}

		// 保存支付方式数据
		$sParmentpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Payment';

		$sConfigfile=$sParmentpath.'/'.$sCode.'/Config.php';
		if(!is_file($sConfigfile)){
			$this->E(sprintf('支付方式 %s 配置文件不存在',$sConfigfile));
		}else{
			$arrPaymentData=(array)(include $sConfigfile);
			if(!isset($arrPaymentData['shoppayment_fee'])){
				$arrPaymentData['shoppayment_fee']=0;
			}

			$this->assign('arrPaymentData',$arrPaymentData);

			$this->display(Admin_Extend::template('shop','shoppayment/add'));
		}
	}
	
	public function insert($sModel=null,$nId=null){
		$oShoppayment=new ShoppaymentModel();
		$oShoppayment->shoppayment_config=serialize(G::getGpc('shoppayment_option','P'));
		$oShoppayment->save(0);
		
		if($oShoppayment->isError()){
			$this->E($oShoppayment->getErrorMessage());
		}
		
		$this->assign('__JumpUrl__',Admin_Extend::base(array('controller'=>'shoppayment')));
		
		$this->S('安装支付方式成功');
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');

		parent::update('shoppayment',$nId);
	}
	
	public function AUpdateObject_($oModel){
		$oModel->shoppayment_config=serialize(G::getGpc('shoppayment_option','P'));
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');
		
		
		parent::foreverdelete('shoppayment',$sId);
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::forbid('shoppayment',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::resume('shoppayment',$nId,true);
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		if(empty($nId)){
			$this->E('你没有指定要编辑支付方式');
		}

		// 查询是否已经安装了支付方式
		$oShoppayment=ShoppaymentModel::F('shoppayment_id=?',$nId)->getOne();
		if(empty($oShoppayment['shoppayment_id'])){
			$this->E('待编辑的支付方式不存在');
		}
		
		$sParmentpath=NEEDFORBUG_PATH.'/app/shop/App/Class/Extension/Payment';

		$sConfigfile=$sParmentpath.'/'.$oShoppayment['shoppayment_code'].'/Config.php';
		if(!is_file($sConfigfile)){
			$this->E(sprintf('支付方式 %s 配置文件不存在',$sConfigfile));
		}else{
			$arrPaymentData=(array)(include $sConfigfile);
			
			$arrShoppayment=$oShoppayment->toArray();
			$arrShoppaymentValue=unserialize($arrShoppayment['shoppayment_config']);
			unset($arrShoppayment['shoppayment_config']);
			
			$arrPaymentData=array_merge($arrPaymentData,$arrShoppayment);

			$this->assign('arrPaymentData',$arrPaymentData);
			$this->assign('arrShoppaymentValue',$arrShoppaymentValue);
		
			$this->display(Admin_Extend::template('shop','shoppayment/add'));
		}
	}
	
	public function get_shoppaymentvalue($arrPaymentoption,$arrShoppaymentValue){
		$sPaymentoptionvalue='';
		
		if(G::getGpc('value','G')){
			return isset($arrShoppaymentValue[$arrPaymentoption['name']])?$arrShoppaymentValue[$arrPaymentoption['name']]:'';
		}else{
			$sPaymentoptionvalue=$arrPaymentoption['value'];

			if($arrPaymentoption['type']=='number'){
				$sPaymentoptionvalue=intval($sPaymentoptionvalue);
			}

			if(in_array($arrPaymentoption['type'],array('select','selects'))){
				$sPaymentoptionvalue=$arrPaymentoption['inputoption']?$arrPaymentoption['inputoption']:array();
			}

			return $sPaymentoptionvalue;
		}
	}

}
