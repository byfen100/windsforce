<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   防灌水处理控制器($)*/

!defined('DYHB_PATH') && exit;

class SecoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->display();
	}

	public function seccode_option(){
		$this->index();
	}

	public function bUpdate_option_(){
		if(isset($_POST['options']['seccode_image_width_size'])){
			if($_POST['options']['seccode_image_width_size']<Seccode::SECCODE_IMAGE_WIDTH_MIN_SIZE){
				$_POST['options']['seccode_image_width_size']=Seccode::SECCODE_IMAGE_WIDTH_MIN_SIZE;
			}elseif($_POST['options']['seccode_image_width_size']>Seccode::SECCODE_IMAGE_WIDTH_MAX_SIZE){
				$_POST['options']['seccode_image_width_size']=Seccode::SECCODE_IMAGE_WIDTH_MAX_SIZE;
			}
		}

		if(isset($_POST['options']['seccode_image_height_size'])){
			if($_POST['options']['seccode_image_height_size']<Seccode::SECCODE_IMAGE_HEIGHT_MIN_SIZE){
				$_POST['options']['seccode_image_height_size']=Seccode::SECCODE_IMAGE_HEIGHT_MIN_SIZE;
			}elseif($_POST['options']['seccode_image_height_size']>Seccode::SECCODE_IMAGE_HEIGHT_MAX_SIZE){
				$_POST['options']['seccode_image_height_size']=Seccode::SECCODE_IMAGE_HEIGHT_MAX_SIZE;
			}
		}
	}

}
