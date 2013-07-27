<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   我的权限($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MyrbacController extends Controller{

	public $_oRole=null;
	
	public function index(){
		$nRId=intval(G::getGpc('rid','G'));

		if($nRId){
			$oRole=RoleModel::F('role_id=?',$nRId)->getOne();
			if(empty($oRole['role_id'])){
				$this->U('home://public/myrbac');
			}

			$this->_oRole=$oRole;
			$this->assign('oRole',$oRole);

			// 角色权限
			$arrAccesslist=UserModel::M()->getAccessList(null,$nRId);
		}else{
			if($GLOBALS['___login___']!==false){
				$nAuthid=$GLOBALS['___login___']['user_id'];
			}else{
				$nAuthid=$GLOBALS['_commonConfig_']['GUEST_AUTH_ID'];
			}

			// 读取我的角色
			$arrUserroles=UserroleModel::F('user_id=?',$nAuthid)->getAll();
			$this->assign('arrUserroles',$arrUserroles);
			
			// 处理我的权限
			$arrAccesslist=UserModel::M()->getAccessList($nAuthid);
		}

		$arrMyaccesslist=array();
		if(is_array($arrAccesslist)){
			foreach($arrAccesslist as $arrTemp){
				if(is_array($arrTemp)){
					foreach($arrTemp as $arrTempTwo){
						if(is_array($arrTempTwo)){
							foreach($arrTempTwo as $sKey=>$nTemp){
								$arrMyaccesslist[]=$sKey;
							}
						}
					}
				}
			}
		}

		// 读取所有权限列表
		$arrAccessListall=array();

		$arrApps=NodeModel::F('node_status=1 AND node_id!=1 AND node_parentid=?',0)->getAll();
		if(is_array($arrApps)){
			foreach($arrApps as $oApp){
				$arrAccessListall[$oApp['node_name']]['title']=$oApp['node_title'];

				$arrModules=NodeModel::F('node_status=1 AND node_parentid=?',$oApp['node_id'])->getAll();
				if(is_array($arrModules)){
					foreach($arrModules as $oModule){
						$arrActions=NodeModel::F('node_status=1 AND node_parentid=?',$oModule['node_id'])->getAll();
						if(is_array($arrActions)){
							foreach($arrActions as $oAction){
								$sNodename=$oAction['node_name'];
								if(strpos($sNodename,'|')){
									$sNodename=G::subString($sNodename,0,strpos($sNodename,'|'));
								}
								
								$arrAccessListall[$oApp['node_name']]['data'][]=array('name'=>$sNodename,'title'=>$oAction['node_title'],'access'=>Core_Extend::isAdmin() && !$nRId?true:(in_array($sNodename,$arrMyaccesslist)?true:false));
							}
						}
					}
				}
			}
		}

		$this->assign('arrAccessListall',$arrAccessListall);
		
		$this->display('public+myrbac');
	}

	public function myrbac_title_(){
		if($this->_oRole){
			return Dyhb::L('角色权限','Controller').' - '.$this->_oRole['role_name'];
		}else{
			return Dyhb::L('我的权限','Controller');
		}
	}

	public function myrbac_keywords_(){
		return $this->myrbac_title_();
	}

	public function myrbac_description_(){
		return $this->myrbac_title_();
	}

}
