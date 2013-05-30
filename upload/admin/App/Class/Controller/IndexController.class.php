<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   后台首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		if($GLOBALS['___login___']===false){
			UserModel::M()->clearThisCookie();// 清理COOKIE
			$this->assign('__JumpUrl__',Dyhb::U('public/login'));
			$this->E(Dyhb::L('你没有登录','Controller'));
		}

		// 后台页面跳转计算
		unset($_GET['c']);
		unset($_GET['a']);

		// fheader跳转地址
		if(isset($_GET['fheader'])){
			$nFheader=intval($_GET['fheader']);
			unset($_GET['fheader']);

			$this->assign('nFheader',$nFheader);
		}

		// fmenu跳转地址
		if(isset($_GET['fmenu'])){
			$nFmenu=intval($_GET['fmenu']);
			unset($_GET['fmenu']);

			if(isset($_GET['fmenucurid'])){
				$nCurrentid=intval($_GET['fmenucurid']);
				unset($_GET['fmenucurid']);
			}else{
				$nCurrentid=0;
			}

			if(isset($_GET['fmenutitle'])){
				$sFmenutitle=trim($_GET['fmenutitle']);
				unset($_GET['fmenutitle']);
			}else{
				$sFmenutitle=Dyhb::L("应用",'MenuHeader');
			}

			$this->assign('nCurrentid',$nCurrentid);
			$this->assign('nFmenu',$nFmenu);
			$this->assign('sFmenutitle',$sFmenutitle);
		}

		// fmain跳转地址
		$sFmainController=trim(G::getGpc('fmainc','G'));
		$sFmainAction=trim(G::getGpc('fmaina','G'));

		if(!empty($sFmainController)){
			if(empty($sFmainAction)){
				$sFmainAction='index';
			}
			
			if(isset($_GET['fmainc'])){
				unset($_GET['fmainc']);
			}

			if(isset($_GET['fmaina'])){
				unset($_GET['fmaina']);
			}

			$sFmainUrl=Dyhb::U($sFmainController.'/'.$sFmainAction,$_GET);

			$this->assign('sFmainUrl',$sFmainUrl);
		}

		$this->display();
	}

}
