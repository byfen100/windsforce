<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   更新赞数量($)*/

!defined('DYHB_PATH') && exit;

class UpdategoodnumController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));

		// 判断是否已经存在
		$cookieValue=Dyhb::cookie('homefresh_goodnum');
		$cookieValue=explode(',',$cookieValue);
		if(in_array($nId,$cookieValue)){
			$this->E(Dyhb::L('你已经赞了','Controller/Homefresh'),1);
		}

		// 更新赞
		$oHomefresh=HomefreshModel::F('homefresh_id=?',$nId)->getOne();
		if(empty($oHomefresh->homefresh_id)){
			$this->E(Dyhb::L('你赞成的新鲜事不存在','Controller/Homefresh'));
		}

		$oHomefresh->homefresh_goodnum=$oHomefresh->homefresh_goodnum+1;
		$oHomefresh->save(0,'update');
		if($oHomefresh->isError()){
			$this->E($oHomefresh->getErrorMessage());
		}

		// 发送新的COOKIE
		$cookieValue[]=$nId;
		$cookieValue=implode(',',$cookieValue);
		Dyhb::cookie('homefresh_goodnum',$cookieValue);

		$arrData['num']=$oHomefresh->homefresh_goodnum;

		$this->A($arrData,Dyhb::L('赞','Controller/Homefresh').'+1',1,1);
	}

}
