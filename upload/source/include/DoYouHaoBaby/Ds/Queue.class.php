<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   队列，实现 先进先出的容器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Queue extends StackQueue{

	public function in($Item){
		if(!$this->isValidType($Item)){
			Dyhb::E('Parameter $Item is invalid and can not add to queue!');
		}

		array_unshift($this->_arrElements,$Item);
	}

	public function out(){
		if(!$this->getLength()){
			return null;
		}

		return array_shift($this->_arrElements);
	}

	public function peek(){
		return reset($this->_arrElements);
	}

}
