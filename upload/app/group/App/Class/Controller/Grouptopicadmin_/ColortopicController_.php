<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子高亮设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ColortopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));
		$sHighlightcolor=trim(G::getGpc('highlight_color'));
		$arrHighlightstyle=G::getGpc('highlight_style');
		$sHighlightbgcolor=trim(G::getGpc('highlight_bgcolor'));

		// 处理样式数据
		$arrColor=array($sHighlightcolor,$arrHighlightstyle,$sHighlightbgcolor);

		if(!Groupadmin_Extend::checkTopicadminRbac($nGroupid,array('group@grouptopicadmin@colortopic'))){
			$this->E(Dyhb::L('你没有帖子高亮设置的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopic_color=serialize($arrColor);
					$oGrouptopic->setAutofill(false);
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),Dyhb::L('主题标题颜色设置成功','Controller/Grouptopicadmin'));
	}

}
