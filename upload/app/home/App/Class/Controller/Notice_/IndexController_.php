<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   提醒列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();
		
		$sType=trim(G::getGpc('type','G'));
		if(!$sType){
			$sType='new';
		}

		$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];

		if($sType=='new'){
			$arrWhere['notice_isread']=0;
		}else{
			$arrWhere['notice_isread']=1;
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$nTotalRecord=NoticeModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['notice_list_num'],G::getGpc('page','G'));

		$arrNoticeLists=NoticeModel::F()->where($arrWhere)->all()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$arrOptionData['notice_list_num'])->getAll();

		// 最后处理结果
		$arrNoticedatas=array();
		if(is_array($arrNoticeLists)){
			foreach($arrNoticeLists as $nKey=>$oNotice){
				$arrData=@unserialize($oNotice['notice_data']);
		
				$arrTempdata=array();
				if(is_array($arrData)){
					foreach($arrData as $nK=>$sValueTemp){
						$sTempkey='{'.$nK.'}';

						// @开头表示URL，调用Dyhb::U来生成地址
						if(strpos($nK,'@')===0){
							$sValueTemp=Dyhb::U($sValueTemp);
						}

						$arrTempdata[$sTempkey]=$sValueTemp;
					}

					// 标记已经阅读
					$oNotice->notice_isread=1;
					$oNotice->setAutofill(false);
					$oNotice->save(0,'update');

					if($oNotice->isError()){
						$this->E($oNotice->getErrorMessage());
					}
				}

				$arrNoticedatas[]=array(
					'user_id'=>$oNotice['notice_authorid'],
					'notice_username'=>$oNotice['notice_authorusername'],
					'notice_content'=>strtr($oNotice['notice_template'],$arrTempdata),
					'create_dateline'=>$oNotice['notice_fromnum']>1?$oNotice['update_dateline']:$oNotice['create_dateline'],
					'notice_fromnum'=>$oNotice['notice_fromnum'],
				);
			}
		}
		
		$this->assign('nTotalNotice',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrNoticedatas',$arrNoticedatas);
		$this->assign('sNoticeType',$sType);
		$this->assign('sType',$sType);

		$this->display('notice+index');
	}

	public function index_title_(){
		$sType=trim(G::getGpc('type','G'));

		switch($sType){
			case 'new':
				return Dyhb::L('未读提醒','Controller/Notice');
				break;
			case 'isread':
				return Dyhb::L('已读提醒','Controller/Notice');
			default:
				return Dyhb::L('未读提醒','Controller/Notice');
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
