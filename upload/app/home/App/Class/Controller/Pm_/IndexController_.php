<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   短消息列表($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();
		
		$sType=trim(G::getGpc('type','G'));

		if(in_array($sType,array('system','systemnew'))){
			$sFormAction=Dyhb::U('home://pm/readselect');
		}elseif($sType=='my'){
			$sFormAction=Dyhb::U('home://pm/delmyselect');
		}else{
			$sFormAction=Dyhb::U('home://pm/delselect');
		}

		if($sType=='new'){
			$arrWhere['pm_isread']=0;
			$arrWhere['pm_type']='user';
		}elseif($sType=='system' || $sType=='systemnew'){
			$arrWhere['pm_type']='system';
		}else{
			$arrWhere['pm_type']='user';
		}

		if($sType!='system' && $sType!='systemnew'){
			if($sType=='my'){
				// 我发送的消息如果被对方删除了，这里status=1的话就无法取出来 && 我的发件箱状态为1
				$arrWhere['pm_msgfromid']=$GLOBALS['___login___']['user_id'];
				$arrWhere['pm_mystatus']=1;
			}else{
				$arrWhere['pm_status']=1;
				$arrWhere['pm_msgtoid']=$GLOBALS['___login___']['user_id'];
			}

			$arrReadPms=array();
		}else{
			// 已删短消息
			$arrSystemdeleteMessages=PmsystemdeleteModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
			if(is_array($arrSystemdeleteMessages)){
				foreach($arrSystemdeleteMessages as $oSystemdeleteMessage){
					$arrDeletePms[]=$oSystemdeleteMessage['pm_id'];
				}
			}else{
				$arrDeletePms=array();
			}

			$arrNotinPms=$arrDeletePms;

			// 已读短消息
			$arrSystemreadMessages=PmsystemreadModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
			if(is_array($arrSystemreadMessages)){
				foreach($arrSystemreadMessages as $oSystemreadMessage){
					$arrReadPms[]=$oSystemreadMessage['pm_id'];
					if($sType=='systemnew'){
						$arrNotinPms[]=$oSystemreadMessage['pm_id'];
					}
				}
			}else{
				$arrReadPms=array();
			}
			
			if(!empty($arrNotinPms)){
				$arrWhere['pm_id']=array('NOT IN',$arrNotinPms);
			}
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$nTotalRecord=PmModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['pm_list_num'],G::getGpc('page','G'));

		$arrPmLists=PmModel::F()->where($arrWhere)->all()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$arrOptionData['pm_list_num'])->getAll();
		
		$this->assign('nTotalPm',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrPmLists',$arrPmLists);
		$this->assign('sPmType',$sType);
		$this->assign('arrReadPms',$arrReadPms);
		$this->assign('sType',($sType?$sType:'user'));
		$this->assign('sFormAction',$sFormAction);

		$this->display('pm+index');
	}

	public function index_title_(){
		$sType=trim(G::getGpc('type','G'));

		switch($sType){
			case 'new':
				return Dyhb::L('未读短消息','Controller/Pm');
				break;
			case 'user':
				return Dyhb::L('私人短消息','Controller/Pm');
				break;
			case 'my':
				return Dyhb::L('已发短消息','Controller/Pm');
				break;
			case 'systemnew':
				return Dyhb::L('未读公共短消息','Controller/Pm');
				break;
			case 'system':
				return Dyhb::L('公共短消息','Controller/Pm');
				break;
			default:
				return Dyhb::L('私人短消息','Controller/Pm');
				break;
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
