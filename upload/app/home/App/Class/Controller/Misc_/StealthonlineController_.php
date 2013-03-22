<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   隐身状态控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StealthonlineController extends Controller{

	public function index(){
		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('未登录用户无法转换在线状态','Controller/Misc'));
		}

		$nUserid=intval(G::getGpc('uid'));
		$nUn=intval(G::getGpc('un'));

		if($GLOBALS['___login___']['user_id']!=$nUserid){
			$this->E(Dyhb::L('你只可以转换自己的在线状态','Controller/Misc'));
		}

		// 保存用户配置
		$oUser=UserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
		if($oUser->user_isstealth==($nUn==1?0:1)){
			$this->E(Dyhb::L('当前在线状态不需要转换','Controller/Misc'));
		}

		$oUser->user_isstealth=$nUn==1?0:1;
		$oUser->setAutofill(false);
		$oUser->save(0,'update');

		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}

		// 更新在线表的数据
		$oOnline=OnlineModel::F('user_id=?',$nUserid)->getOne();
		if(!empty($oOnline['user_id'])){
			$oDb=Db::RUN();

			$oDb->query('UPDATE '.OnlineModel::F()->query()->getTablePrefix().'online SET online_isstealth="'.($nUn==1?0:1).'" WHERE user_id='.$nUserid);
		}

		$this->S($nUn==1?Dyhb::L('设置状态为在线成功','Controller/Misc'):Dyhb::L('设置状态为隐身成功','Controller/Misc'));
	}

}
