<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商品控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShopgoodsController extends InitController{

	protected $_arrUploaddata=array();

	public function filter_(&$arrMap){
		$arrMap['shopgoods_name']=array('like',"%".G::getGpc('shopgoods_name')."%");
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('shopgoods',false);

		$this->display(Admin_Extend::template('shop','shopgoods/index'));
	}

	protected function get_maxuploadsize_(){
		$this->assign('nUploadMaxFilesize',ini_get('upload_max_filesize'));
		$this->assign('nUploadSize',Core_Extend::getUploadSize());
	}

	public function add(){
		$this->bAdd_();

		$this->get_shopcategorytree_();
		
		$this->display(Admin_Extend::template('shop','shopgoods/add'));
	}

	public function bAdd_(){
		Core_Extend::loadCache("shop_option");
		
		$arrOptionData=$GLOBALS['_cache_']['shop_option'];
		$arrShopgoodsimgsizes=explode('|',$arrOptionData['shopgoods_imgsize']);
		$arrShopgoodsthumbimgsizes=explode('|',$arrOptionData['shopgoods_thumbimgsize']);

		$this->assign('arrOptionData',$arrOptionData);
		$this->assign('arrShopgoodsimgsizes',$arrShopgoodsimgsizes);
		$this->assign('arrShopgoodsthumbimgsizes',$arrShopgoodsthumbimgsizes);
		
		$this->shopgoodstype_();

		$this->get_maxuploadsize_();
	}

	public function get_shopcategorytree_(){
		$oShopcategory=Dyhb::instance('ShopcategoryModel');
		$oShopcategoryTree=$oShopcategory->getShopcategoryTree();
		
		$this->assign('oShopcategoryTree',$oShopcategoryTree);
	}

	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');

		$this->strtotime_();

		$arrUploaddata=array();

		// 商品图片上传
		$arrUploadgoodsimgdata=$this->shopgoodsimg_();

		// 商品相册图片上传
		$arrUploadgallerydata=$this->shopgoodsgallery_();
		
		if(!empty($arrUploadgoodsimgdata)){
			$arrUploaddata['shopgoodsimg']=$arrUploadgoodsimgdata;
		}

		if(!empty($arrUploadgallerydata)){
			$arrUploaddata['shopgalleryimg']=$arrUploadgallerydata;
		}
		
		$this->_arrUploaddata=$arrUploaddata;

		parent::insert('shopgoods',$nId);
	}

	public function bEdit_(){
		$this->bAdd_();

		// 读取商品相册图片
		$arrUploadgallerys=ShopgoodsgalleryModel::F('shopgoods_id=?',intval(G::getGpc('value','G')))->order('shopgoodsgallery_id DESC')->getAll();
		$this->assign('arrUploadgallerys',$arrUploadgallerys);
		
		$this->shopgoodstype_();

		$this->get_maxuploadsize_();
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		$this->bEdit_();
		
		$this->get_shopcategorytree_();
		
		parent::edit('shopgoods',$nId,false);
		$this->display(Admin_Extend::template('shop','shopgoods/add'));
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');

		$this->strtotime_();
	
		$arrUploaddata=array();

		// 商品图片上传
		$arrUploadgoodsimgdata=$this->shopgoodsimg_();

		// 商品相册图片上传
		$arrUploadgallerydata=$this->shopgoodsgallery_();
		
		if(!empty($arrUploadgoodsimgdata)){
			$arrUploaddata['shopgoodsimg']=$arrUploadgoodsimgdata;
		}

		if(!empty($arrUploadgallerydata)){
			$arrUploaddata['shopgalleryimg']=$arrUploadgallerydata;
		}
		
		$this->_arrUploaddata=$arrUploaddata;
		
		// 更新已经上传相册的信息
		$this->update_galleryinfo_();

		// 更新商品属性值
		$this->update_attributevalue_($nId);

		parent::update('shopgoods',$nId);
	}

	protected function update_attributevalue_($nId){
		$arrShopattributeidlist=G::getGpc('shopattributeid_list','P');
		$arrShopattributevaluelist=G::getGpc('shopattributevalue_list','P');
		$nShopgoodsid=intval($nId);
		$nShopattributeid=intval(G::getGpc('shopattribute_id','P'));

		if(!empty($arrShopattributeidlist)){
			foreach($arrShopattributeidlist as $nKey=>$nShopattributeid){
				$oShopattributevalue=ShopattributevalueModel::F('shopattribute_id=? AND shopgoods_id=?',$nShopattributeid,$nShopgoodsid)->getOne();
				if(empty($oShopattributevalue['shopattributevalue_id'])){
					$oShopattributevalue=new ShopattributevalueModel();
				}

				$sShopattributevalue=isset($arrShopattributevaluelist[$nShopattributeid])?$arrShopattributevaluelist[$nShopattributeid]:'';
				
				if(is_array($sShopattributevalue)){
					$sShopattributevalue=serialize($sShopattributevalue);
				}

				$oShopattributevalue->shopattribute_value=trim($sShopattributevalue);
				$oShopattributevalue->shopgoods_id=$nShopgoodsid;
				$oShopattributevalue->shopattribute_id=$nShopattributeid;

				if(empty($oShopattributevalue['shopattributevalue_id'])){
					$oShopattributevalue->save(0);
				}else{
					$oShopattributevalue->save(0,'update');
				}

				if($oShopattributevalue->isError()){
					$this->E($oShopattributevalue->getErrorMessage());
				}
			}
		}
	}
	
	protected function update_galleryinfo_(){
		if(isset($_POST['gallerydescription'])){
			$arrGallerydescriptions=G::getGpc('gallerydescription','P');
			$arrGallerynames=G::getGpc('galleryname','P');
			
			if(is_array($arrGallerydescriptions)){
				foreach($arrGallerydescriptions as $nKey=>$sGallerydescription){
					$oShopgoodsgallery=ShopgoodsgalleryModel::F('shopgoodsgallery_id=?',$nKey)->getOne();
					if(!empty($oShopgoodsgallery['shopgoodsgallery_id'])){
						$oShopgoodsgallery->shopgoodsgallery_description=$sGallerydescription;
						$oShopgoodsgallery->shopgoodsgallery_name=$arrGallerynames[$nKey];
						$oShopgoodsgallery->save(0,'update');
						
						if($oShopgoodsgallery->isError()){
							$this->E($oShopgoodsgallery->getErrorMessage());
						}
					}
				}
			}
		}
	}

	protected function shopgoodsgallery_(){
		require_once(Core_Extend::includeFile('function/Upload_Extend'));
		
		Core_Extend::loadCache("shop_option");
		$arrOptionData=$GLOBALS['_cache_']['shop_option'];

		$arrUploadoption=array(
			'upload_single'=>0,
		);

		$bNeedupload=false;
		if(is_array($_FILES['shopgoodsgallery']['error'])){
			foreach($_FILES['shopgoodsgallery']['error'] as $nErrorno){
				if($nErrorno!=4){
					$bNeedupload=true;
					break;
				}
			}
		}

		$arrData=array();
		if($bNeedupload===true){
			$arrData=$this->uploadAFile_('',$arrUploadoption);
		}

		return $arrData;
	}

	protected function shopgoodsimg_(){
		require_once(Core_Extend::includeFile('function/Upload_Extend'));
		
		Core_Extend::loadCache("shop_option");
		$arrOptionData=$GLOBALS['_cache_']['shop_option'];

		$arrShopgoodsimgsizes=explode('|',$arrOptionData['shopgoods_imgsize']);
		
		$arrData=array();
		
		if(isset($_FILES['shopgoodsimg'])){
			if($_FILES['shopgoodsimg']['error']!=4){
				$arrUploadoption=array();
				if(isset($_FILES['shopgoodsthumbimg']) && $_FILES['shopgoodsthumbimg']['error']!=4){
					$arrUploadoption=array(
						'upload_thumb_size'=>$arrOptionData['shopgoods_imgsize'],
						'upload_thumb'=>'thumb'.$arrShopgoodsimgsizes[0].'_',
					);
				}
				
				$arrData=$this->uploadAFile_('shopgoodsimg',$arrUploadoption);
			}
		}
		
		if(isset($_FILES['shopgoodsthumbimg'])){
			if($_FILES['shopgoodsthumbimg']['error']!=4){
				$arrThumbdata=$this->uploadAFile_('shopgoodsthumbimg',array('upload_create_thumb'=>0,'flash_inputname'=>'shopgoodsthumbimg'));
				$arrData['shopgoods_thumb']=$arrThumbdata['shopgoods_originalimg'];
			}
		}

		if(isset($_FILES['shopgoodsimg'])){
			unset($_FILES['shopgoodsimg']);
		}
		
		if(isset($_FILES['shopgoodsthumbimg'])){
			unset($_FILES['shopgoodsthumbimg']);
		}
		
		return $arrData;
	}
	
	protected function uploadAFile_($sFilename,$arrUploadoption=array()){
		$arrOptionData=$GLOBALS['_cache_']['shop_option'];
		$arrShopgoodsimgsizes=explode('|',$arrOptionData['shopgoods_imgsize']);
		$arrShopgoodsthumbimgsizes=explode('|',$arrOptionData['shopgoods_thumbimgsize']);
	
		if(empty($sFilename) || isset($_FILES[$sFilename])){
			try{
				$arrData=array();
				
				$arrDefaultUploadoption=array(
					'upload_allowed_type'=>'jpg|jpeg|gif|bmp|png',
					'upload_path'=>NEEDFORBUG_PATH.'/data/upload/app/shop/shopgoods',
					'upload_create_thumb'=>1,
					'flash_inputname'=>'shopgoodsimg',
					'upload_thumb_size'=>$arrShopgoodsthumbimgsizes[0].','.$arrShopgoodsimgsizes[0].'|'.$arrShopgoodsthumbimgsizes[1].','.$arrShopgoodsimgsizes[1],
					'upload_thumb'=>'thumb'.$arrShopgoodsthumbimgsizes[0].'_,thumb'.$arrShopgoodsimgsizes[0].'_',
					'upload_single'=>1,
				);
				
				$arrUploadoption=array_merge($arrDefaultUploadoption,$arrUploadoption);

				if($arrUploadoption['upload_single']==1){
					$arrUploadinfos=Upload_Extend::uploadFlash(true,true,false,$arrUploadoption);
				}else{
					$arrUploadinfos=Upload_Extend::uploadNormal(true,false,$arrUploadoption);
				}

				if(is_array($arrUploadinfos)){
					foreach($arrUploadinfos as $nKey=>$arrUploadinfo){
						$arrData[$nKey]['shopgoods_originalimg']=str_replace(G::tidyPath(NEEDFORBUG_PATH.'/data/upload/app/shop/shopgoods').'/','',G::tidyPath($arrUploadinfo['savepath'])).'/'.$arrUploadinfo['savename'];
			
						if($arrUploadoption['upload_create_thumb']==1){
							$arrData[$nKey]['shopgoods_img']=str_replace(G::tidyPath(NEEDFORBUG_PATH.'/data/upload/app/shop/shopgoods').'/','',G::tidyPath($arrUploadinfo['thumbpath'])).'/thumb'.$arrShopgoodsimgsizes[0].'_'.$arrUploadinfo['savename'];
							
							if(strpos($arrUploadoption['upload_thumb'],',')){
								$arrData[$nKey]['shopgoods_thumb']=str_replace(G::tidyPath(NEEDFORBUG_PATH.'/data/upload/app/shop/shopgoods').'/','',G::tidyPath($arrUploadinfo['thumbpath'])).'/thumb'.$arrShopgoodsthumbimgsizes[0].'_'.$arrUploadinfo['savename'];
							}
						}
						
						$arrData[$nKey]['shopgoods_name']=$arrUploadinfo['name'];
					}
				}
				
				if($arrUploadoption['upload_single']==1){
					$arrData=$arrData[0];
				}

				return $arrData;
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}else{
			return false;
		}
	}

	public function AUpdateObject_($oModel){
		$arrUploaddata=$this->_arrUploaddata;
		
		if(isset($arrUploaddata['shopgoodsimg'])){
			$oModel->shopgoods_originalimg=$arrUploaddata['shopgoodsimg']['shopgoods_originalimg'];
			$oModel->shopgoods_thumb=$arrUploaddata['shopgoodsimg']['shopgoods_thumb'];
			$oModel->shopgoods_img=$arrUploaddata['shopgoodsimg']['shopgoods_img'];
		}

		if(isset($arrUploaddata['shopgalleryimg'])){
			if(is_array($arrUploaddata['shopgalleryimg'])){
				foreach($arrUploaddata['shopgalleryimg'] as $arrUploadgallerydata){
					$arrSavedata=array(
						'shopgoods_id'=>$oModel->shopgoods_id,
						'shopgoodsgallery_url'=>$arrUploadgallerydata['shopgoods_img'],
						'shopgoodsgallery_thumburl'=>$arrUploadgallerydata['shopgoods_thumb'],
						'shopgoodsgallery_imgoriginal'=>$arrUploadgallerydata['shopgoods_originalimg'],
						'shopgoodsgallery_name'=>$arrUploadgallerydata['shopgoods_name'],
					);

					$oShopgoodsgallery=new ShopgoodsgalleryModel($arrSavedata);
					$oShopgoodsgallery->save(0);

					if($oShopgoodsgallery->isError()){
						$this->E($oShopgoodsgallery->getErrorMessage());
					}
				}
			}
		}
	}

	public function AInsertObject_($oModel){
		$this->AUpdateObject_($oModel);
	}

	protected function aInsert($nId=null){
		// 更新商品属性值
		$this->update_attributevalue_($nId);
	}

	protected function strtotime_(){
		$_POST['shopgoods_promotestartdate']=strtotime($_POST['shopgoods_promotestartdate']);
		$_POST['shopgoods_promoteenddate']=strtotime($_POST['shopgoods_promoteenddate']);
	}
	
	public function showimg(){
		$sImgurl=trim(G::getGpc('imgurl','G'));
		
		if(empty($sImgurl)){
			$this->E('没有指定传递的图片');
		}
		
		$this->assign('sImgurl',$sImgurl);
		
		$this->display(Admin_Extend::template('shop','shopgoods/showimg'));
	}
	
	public function delete_gallery(){
		$nGalleryid=intval(G::getGpc('value','G'));
		
		if(empty($nGalleryid)){
			$this->E('你没有指定待删除商品图片ID');
		}
		
		$oShopgoodsgallery=ShopgoodsgalleryModel::F('shopgoodsgallery_id=?',$nGalleryid)->getOne();
		if(empty($oShopgoodsgallery['shopgoodsgallery_id'])){
			$this->E('你要删除商品图片不存在');
		}
		
		$sShopgoodsgalleryUrl=NEEDFORBUG_PATH.'/data/upload/app/shop/shopgoods/';
		foreach(array('shopgoodsgallery_url','shopgoodsgallery_thumburl','shopgoodsgallery_imgoriginal') as $sValue){
			if(is_file($sShopgoodsgalleryUrl.$sValue)){
				@unlink($sShopgoodsgalleryUrl.$sValue);
			}
		}
		
		$oShopgoodsgallery->destroy();
		
		$this->A(array('id'=>$nGalleryid),'删除商品图片成功');
	}
	
	protected function shopgoodstype_(){
		$arrShopgoodstypes=ShopgoodstypeModel::F()->getAll();
		
		$this->assign('arrShopgoodstypes',$arrShopgoodstypes);
	}
	
	public function get_attribute(){
		$nShopgoodsid=intval(G::getGpc('shopgoods_id','G'));
		$nShopgoodstypeid=intval(G::getGpc('shopgoodstype_id','G'));
		$nReturnmessage=intval(G::getGpc('return_message','G'));
		
		$arrData=array();
		if($nShopgoodstypeid<1){
			$arrData['content']='';
		}else{
			$oShopgoodstype=ShopgoodstypeModel::F('shopgoodstype_id=?',$nShopgoodstypeid)->getOne();
			if(empty($oShopgoodstype['shopgoodstype_id'])){
				$this->E('你请求的商品类型不存在');
			}
			
			$arrShopattributes=ShopattributeModel::F('shopgoodstype_id=?',$nShopgoodstypeid)->getAll();
			if(!is_array($arrShopattributes)){
				$arrData['content']='';
			}else{
				$this->assign('arrShopattributes',$arrShopattributes);

				// 读取属性值
				$arrShopattributevalueData=array();

				if($nShopgoodsid>0){
					$arrShopattributevalues=ShopattributevalueModel::F('shopgoods_id=?',$nShopgoodsid)->getAll();
					if(is_array($arrShopattributevalues)){
						foreach($arrShopattributevalues as $oShopattributevalue){
							$arrShopattributevalueData[$oShopattributevalue['shopattribute_id']]=$oShopattributevalue;
						}
					}
				}

				$this->assign('arrShopattributevalueData',$arrShopattributevalueData);

				$sAttributecontent=$this->display(Admin_Extend::template('shop','shopgoods/attribute'),'','text/html',true);
				$arrData['content']=$sAttributecontent;
			}
		}
	
		$this->A($arrData,'加载商品属性成功',1,$nReturnmessage==1?1:0);
	}

}
