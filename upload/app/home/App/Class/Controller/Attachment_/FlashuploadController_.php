<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Flash上传逻辑处理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入附件上传函数 */
require(Core_Extend::includeFile('function/Upload_Extend'));

class FlashuploadController extends Controller{

	public function index(){
		// flash上传验证
		$bSessionExists=Core_Extend::flashuploadAuth();

		if($bSessionExists===false){
			echo '<div class="upload-error">'.
					'<strong style="color:red;">You do not have access to upload</strong>'.
				'</div>';
			exit;
		}

		try{
			$_POST['attachmentcategory_id']=intval(G::getGpc('attachmentcategory_id'));

			$arrUploadids=Upload_Extend::uploadFlash();
			echo ($arrUploadids[0]);

			// 保存home今日数据
			OptionModel::uploadOption('todayattachmentnum',$GLOBALS['_option_']['todayattachmentnum']+1);
			OptionModel::uploadOption('todaytotalnum',$GLOBALS['_option_']['todaytotalnum']+1);

			$this->cache_site_();

			// 更新积分
			Core_Extend::updateCreditByAction('postattachment',$GLOBALS['___login___']['user_id']);

			exit();
		}catch(Exception $e){
			echo '<div class="upload-error">'.
						sprintf('&#8220;%s&#8221; has failed to upload due to an error',htmlspecialchars($_FILES['Filedata']['name'])).'</strong><br />'.
						htmlspecialchars($e->getMessage()).
				'</div>';
			exit;
		}
	}

	protected function cache_site_(){
		// 更新附件专辑附件数量统计
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id'));

		if($nAttachmentcategoryid>0){
			$oAttachmentcategory=Dyhb::instance('AttachmentcategoryModel');
			$oAttachmentcategory->updateAttachmentnum($nAttachmentcategoryid);

			if($oAttachmentcategory->isError()){
				$this->E($oAttachmentcategory->getErrorMessage());
			}
		}
		
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
