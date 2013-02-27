<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   会员排行($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UsertopController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('siteusertop')){
			$this->E(Dyhb::L('你没有权限访问会员排行','Controller/Stat'));
		}
		
		$nTopusernum=$GLOBALS['_cache_']['home_option']['topuser_num'];
		if($nTopusernum<1){
			$nTopusernum=1;
		}

		// 活跃用户
		$this->get_activeuser($nTopusernum);

		// 最新加入会员
		$this->get_newuser($nTopusernum);

		// 会员积分排行
		$this->get_credituser($nTopusernum);

		// 会员粉丝排行
		$this->get_fanuser($nTopusernum);

		// 会员在线时间
		$this->get_oltimeuser($nTopusernum);
		
		$this->display('stat+usertop');
	}

	protected function get_activeuser($nTopusernum){
		$arrActiveusers=UserModel::F('user_status=?',1)->order('update_dateline DESC')->limit(0,$nTopusernum)->getAll();
		$this->assign('arrActiveusers',$arrActiveusers);
	}

	protected function get_newuser($nTopusernum){
		$arrNewusers=UserModel::F('user_status=?',1)->order('create_dateline DESC')->limit(0,$nTopusernum)->getAll();
		$this->assign('arrNewusers',$arrNewusers);
	}

	protected function get_credituser($nTopusernum){
		$arrCreditusers=$this->get_userorder_('usercount_extendcredit1',$nTopusernum);
		$this->assign('arrCreditusers',$arrCreditusers);
	}

	protected function get_fanuser($nTopusernum){
		$arrFanusers=$this->get_userorder_('usercount_fans',$nTopusernum);
		$this->assign('arrFanusers',$arrFanusers);
	}

	protected function get_oltimeuser($nTopusernum){
		$arrOltimeusers=$this->get_userorder_('usercount_oltime',$nTopusernum);
		$this->assign('arrOltimeusers',$arrOltimeusers);
	}

	protected function get_userorder_($sType,$nTopusernum){
		$arrUsers=array();

		$arrUsercounts=UsercountModel::F()->order($sType.' DESC')->limit(0,$nTopusernum)->getAll();
		if(is_array($arrUsercounts)){
			foreach($arrUsercounts as $oUsercount){
				$arrUsers[]=UserModel::F('user_id=?',$oUsercount['user_id'])->getOne();
			}
		}

		return $arrUsers;
	}

	public function usertop_title_(){
		return Dyhb::L('会员排行','Controller/Stat');
	}

	public function usertop_keywords_(){
		return $this->usertop_title_();
	}

	public function usertop_description_(){
		return $this->usertop_title_();
	}
	
}
