<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城商品控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopgoodsController extends InitController{

	public function play(){
		$sType=trim(G::getGpc('type','G'));

		if($sType=='buy'){
			$this->display('shopgoods+playbuy');
		}elseif($sType=='order'){
			$this->display('shopgoods+playorder');
		}elseif($sType=='sold'){
			$this->display('shopgoods+playsold');
		}elseif($sType=='favorite'){
			$this->display('shopgoods+playfavorite');
		}else{
			$this->display('shopgoods+playindex');
		}
	}

	public function add_order(){
		$sOrdersn=date('Ymd').G::randString(10);

		$this->assign('sOrdersn',$sOrdersn);
		
		$this->display('shopgoods+playaddorder');
	}

	public function hot(){
		
		$arrWhere=array();
		$arrWhere['shopgoods_ishot']=1;
		$arrwhere['shopgoods_status']=0;
		$nEverynum=12;
		$nCount=ShopgoodsModel::F("shopgoods_status=0 AND shopgoods_ishot=1")->getCounts();
		$oPage=Page::RUN($nCount,$nEverynum,G::getGpc('page','G'));
		$arrShopgoods=ShopgoodsModel::F()->where($arrWhere)->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrShopgoods',$arrShopgoods);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->display('shopgoods+hot');
	}

	public function dateview(){
		$this->display('shopgoods+dateview');
	}

	public function original(){
		$this->display('shopgoods+original');
	}

	public function like(){
		$this->display('shopgoods+like');
	}

	public function category(){
		$this->display('shopgoods+category');
	}

	public function view(){
		$nId=intval(G::getGpc('id','G'));
		
		if(empty($nId)){
			$this->E('你没有指定查看商品的ID');
		}
		
		$oShopgoods=ShopgoodsModel::F('shopgoods_id=?',$nId)->getOne();
		if(empty($oShopgoods['shopgoods_id'])){
			$this->E('你查看的商品不存在或者已经下架');
		}
		
		// 取得商品相册
		$arrShopgoodsgallerysDatas=array();
		
		if($oShopgoods['shopgoods_originalimg']){
			$arrShopgoodsgallerysDatas[]=array(
				'name'=>'',
				'description'=>'',
				'imgoriginal'=>$oShopgoods['shopgoods_originalimg'],
				'thumburl'=>$oShopgoods['shopgoods_thumb'],
				'imageurl'=>$oShopgoods['shopgoods_img'],
				'create_dateline'=>'',
			);
		}
		
		$arrShopgoodsgallerys=ShopgoodsgalleryModel::F('shopgoods_id=?',$nId)->order('shopgoodsgallery_id DESC')->getAll();
		if(is_array($arrShopgoodsgallerys)){
			foreach($arrShopgoodsgallerys as $oShopgoodsgallery){
				$arrShopgoodsgallerysDatas[]=array(
					'name'=>$oShopgoodsgallery['shopgoodsgallery_name'],
					'description'=>$oShopgoodsgallery['shopgoodsgallery_description'],
					'imgoriginal'=>$oShopgoodsgallery['shopgoodsgallery_imgoriginal'],
					'thumburl'=>$oShopgoodsgallery['shopgoodsgallery_thumburl'],
					'imageurl'=>$oShopgoodsgallery['shopgoodsgallery_url'],
					'create_dateline'=>$oShopgoodsgallery['create_dateline'],
				);
			}
		}
		
		$this->assign('arrShopgoodsgallerysDatas',$arrShopgoodsgallerysDatas);

		// 商品属性及其值
		$arrShopattributevaluesData=Shopgoods_Extend::getShopattributevalue($oShopgoods);
		$this->assign('arrShopattributevaluesData',$arrShopattributevaluesData);

		// 读取商品评价
		$arrWhere=array(
			'shopgoodscomment_status'=>1,
			'shopgoods_id'=>$nId,
		);

		$nCommentpage=intval(G::getGpc('comment_page','G'));
		if($nCommentpage<1){
			$nCommentpage=1;
		}

		$nTotalRecord=ShopgoodscommentModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,10,$nCommentpage);
		$arrShopgoodscomments=ShopgoodscommentModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),10)->getAll();

		$this->assign('arrShopgoodscomments',$arrShopgoodscomments);
		$this->assign('sPageNavbar',$oPage->P('paginationcomment'.'@pagenav','span','current','disabled','comment_page'));
		$this->assign('nTotalShopgoodscomment',$nTotalRecord);

		$this->assign('oShopgoods',$oShopgoods);

		$this->display('shopgoods+view');
	}

	public function add_comment(){
		$nShopgoodsid=intval(G::getGpc('shopgoods_id','P'));
		
		$oShopgoodscomment=new ShopgoodscommentModel();
		$oShopgoodscomment->save(0);

		if($oShopgoodscomment->isError()){
			$this->E($oShopgoodscomment->getErrorMessage());
		}

		$arrData=$oShopgoodscomment->toArray();
		$arrData['space']=Dyhb::U('home://space@?id='.$arrData['user_id']);
		$arrData['avatar']=Core_Extend::avatar($arrData['user_id'],'small');
		$arrData['create_dateline']=Core_Extend::timeFormat($arrData['create_dateline']);
		$arrData['shopgoodscomment_content']=nl2br($arrData['shopgoodscomment_content']);

		// 评论数量
		$arrData['totalnum']=ShopgoodscommentModel::F('shopgoodscomment_status=1 AND shopgoods_id=?',$nShopgoodsid)->all()->getCounts();
	
		$this->A($arrData,'发布商品评论成功');
	}

}
