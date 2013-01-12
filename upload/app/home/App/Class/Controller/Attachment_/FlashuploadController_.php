<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Flash上传逻辑处理($)*/

!defined('DYHB_PATH') && exit;

class FlashuploadController extends Controller{

	public function index(){
		require(Core_Extend::includeFile('function/Upload_Extend'));

		try{
			$_POST['attachmentcategory_id']=intval(G::getGpc('attachmentcategory_id'));

			$arrUploadids=Upload_Extend::uploadFlash();
			echo ($arrUploadids[0]);

			$this->cache_site_();
		}catch(Exception $e){
			echo '<div class="upload-error">'.
						sprintf('&#8220;%s&#8221; has failed to upload due to an error',htmlspecialchars($_FILES['Filedata']['name'])).'</strong><br />'.
						htmlspecialchars($e->getMessage()).
				'</div>';
			exit;
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
