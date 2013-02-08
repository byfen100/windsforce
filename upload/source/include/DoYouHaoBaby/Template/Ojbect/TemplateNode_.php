<?php
/* [DoYouHaoBaby!] (C)WindsForce Team Start This From 2010.10.04.
   Template对象: Node($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TemplateNode extends TemplateObj{

	private $_sNodeName;

	public function __construct($sSource,$sNodeName){
		parent::__construct($sSource);
		$this->_sNodeName=$sNodeName;
	}

	public function getNodeName(){
		return $this->_sNodeName;
	}

	public function getAttribute(){
		foreach($this->_arrChildTemplateObj as $oChild){
			if(G::isKindOf($oChild,'TemplateNodeAttribute')){
				return $oChild;
			}
		}

		Dyhb::E(Dyhb::L('没有头标签','__DYHB__@Dyhb'));
	}

	public function getBody(){
		while(!empty($this->_arrChildTemplateObj)){
			$oChild=array_pop($this->_arrChildTemplateObj);
			if(get_class($oChild)=='TemplateObj'){
				return $oChild;
			}
		}

		return null;
	}

}

class TemplateNodeAttribute extends TemplateObj{

	public $_arrAttributes=array();
	private $_arrAttributeOriginNames=array();

	public function setAttribute($sName,$sValue){
		$sOriginName=$sName;
		$sName=strtolower($sName);
		$this->_arrAttributeOriginNames[$sName]=$sOriginName;// 属性名原文
		$sOldValue=isset($this->_arrAttributes[$sName])?$this->_arrAttributes[$sName]:null;// 属性名&属性值对
		$this->_arrAttributes[$sName]=$sValue;

		return $sOldValue;
	}

	public function getAttribute($sName){
		return isset($this->_arrAttributes[$sName])?$this->_arrAttributes[$sName]:null;
	}

	public function getAttributeOriginName($sAttrName){
		return isset($this->_arrAttributeOriginNames[$sAttrName])?$this->_arrAttributeOriginNames[$sAttrName]:null;
	}

}

class TemplateNodeTag extends TemplateObj{

	const TYPE_HEAD=1;
	const TYPE_TAIL=2;
	private $_sName;
	private $_nType;
	private $_sAttributeSource;
	public function __construct($sSource,$sName,$nType){
		if(!in_array($nType,array(self::TYPE_HEAD,self::TYPE_TAIL))){
			Dyhb::E(Dyhb::L('参数 $nType 必须为 TemplateNodeTag::TYPE_HEAD 或 TemplateNodeTag::TYPE_TAIL','__DYHB__@Dyhb'));
		}

		parent::__construct($sSource);

		$this->_sName=$sName;
		$this->_nType=$nType;
	}

	public function getTagType(){
		return $this->_nType;
	}

	public function getTagName(){
		return $this->_sName;
	}

	public function getTagTopName(){
		list($sTopName,)=explode(':',$this->_sName);

		return $sTopName;
	}

	public function setTagAttributeSource($sAttributeSource){
		$sOldValue=$this->_sAttributeSource;
		$this->_sAttributeSource=$sAttributeSource;

		return $sOldValue;
	}

	public function getTagAttributeSource(){
		return $this->_sAttributeSource;
	}

	public function matchTail(TemplateNodeTag $oTailTag){
		if($oTailTag->getTagType()!=self::TYPE_TAIL){
			Dyhb::E(Dyhb::L('参数 $oTailTag 必须是一个尾标签','__DYHB__@Dyhb'));
		}

		$sTailName=$oTailTag->getTagName();

		return preg_match("/^{$sTailName}/i",$this->getTagName());
	}

}
