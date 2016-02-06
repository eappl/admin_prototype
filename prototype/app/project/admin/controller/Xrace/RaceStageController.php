<?php
/**
 * 任务管理
 * @author Chen<cxd032404@hotmail.com>
 * $Id: LotoController.php 15195 2014-07-23 07:18:26Z 334746 $
 */

class Xrace_RaceStageController extends AbstractController
{
	/**运动类型列表:RaceStageList
	 * 权限限制  ?ctl=xrace/sports&ac=sports.type
	 * @var string
	 */
	protected $sign = '?ctl=xrace/race.stage';
	/**
	 * race对象
	 * @var object
	 */
	protected $oRaceStage;

	/**
	 * 初始化
	 * (non-PHPdoc)
	 * @see AbstractController#init()
	 */
	public function init()
	{
		parent::init();
		$this->oRaceStage = new Xrace_Race();

	}
	//任务配置列表页面
	public function indexAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission(0);
		if($PermissionCheck['return'])
		{
			$RaceCatalogId = isset($this->request->RaceCatalogId)?intval($this->request->RaceCatalogId):0;
			$RaceCatalogArr  = $this->oRaceStage->getAllRaceCatalogList();
			$RaceStageArr = $this->oRaceStage->getAllRaceStageList($RaceCatalogId);
			$RaceGroupArr = $this->oRaceStage->getAllRaceGroupList($RaceCatalogId,'RaceGroupId,RaceGroupName');
			$RaceStageList = array();
			foreach($RaceStageArr as $key => $value)
			{
				$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key] = $value;
				$RaceStageList[$value['RaceCatalogId']]['RaceStageCount'] = isset($RaceStageList[$value['RaceCatalogId']]['RaceStageCount'])?$RaceStageList[$value['RaceCatalogId']]['RaceStageCount']+1:1;
				$RaceStageList[$value['RaceCatalogId']]['RowCount'] = $RaceStageList[$value['RaceCatalogId']]['RaceStageCount']+1;

				if(isset($RaceCatalogArr[$value['RaceCatalogId']]))
				{
					$RaceStageList[$value['RaceCatalogId']]['RaceCatalogName'] = isset($RaceStageList[$value['RaceCatalogId']]['RaceCatalogName'])?$RaceStageList[$value['RaceCatalogId']]['RaceCatalogName']:$RaceCatalogArr[$value['RaceCatalogId']]['RaceCatalogName'];

				}
				else
				{
					$RaceStageList[$value['RaceCatalogId']]['RaceCatalogName'] = 	"未定义";
				}

				if(isset($RaceCatalogArr[$value['RaceCatalogId']]))
				{
					$value['comment'] = json_decode($value['comment'],true);
					$t = array();
					if(isset($value['comment']['SelectedRaceGroup']) && is_array($value['comment']['SelectedRaceGroup']))
					{
						foreach($value['comment']['SelectedRaceGroup'] as $k => $v)
						{
							if(isset($RaceGroupArr[$v]))
							{
								$t[$k] = "<a href='".$this->sign."&ac=race.stage.group.list&RaceStageId=".$key."&RaceGroupId=".$v."'>".$RaceGroupArr[$v]['RaceGroupName']."</a>";
							}
						}
					}
					if(count($t))
					{
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['SelectedGroupList'] = implode("/",$t);
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['RaceDetail'] = 1;
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['GroupCount'] = count($t);
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['RowCount'] = $RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['GroupCount']+1;
					}
					else
					{
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['SelectedGroupList'] = "尚未配置";
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['RaceDetail'] = 0;
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['GroupCount'] = 0;
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['RowCount'] = 1;
					}
				}
				else
				{
					$RaceStageArr[$key]['RaceCatalogName'] = 	"未定义";
				}
			}
			include $this->tpl('Xrace_Race_RaceStageList');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	//添加任务填写配置页面
	public function raceStageAddAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("RaceStageInsert");
		if($PermissionCheck['return'])
		{
			include('Third/ckeditor/ckeditor.php');

			$editor =  new CKEditor();
			$editor->BasePath = '/js/ckeditor/';
			$editor->config['height'] = "50%";
			$editor->config['width'] ="80%";

			$RaceCatalogArr  = $this->oRaceStage->getAllRaceCatalogList();
			include $this->tpl('Xrace_Race_RaceStageAdd');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	
	//添加新任务
	public function raceStageInsertAction()
	{
		//检查权限
		$bind=$this->request->from('RaceStageName','RaceCatalogId');
		$SelectedRaceGroup = $this->request->from('SelectedRaceGroup');
		$RaceCatalogArr  = $this->oRaceStage->getAllRaceCatalogList();
		if(trim($bind['RaceStageName'])=="")
		{
			$response = array('errno' => 1);
		}
		elseif(!isset($RaceCatalogArr[$bind['RaceCatalogId']]))
		{
			$response = array('errno' => 3);
		}
		elseif(count($SelectedRaceGroup['SelectedRaceGroup'])==0)
		{
			$response = array('errno' => 4);
		}
		else
		{
			$bind['comment']['SelectedRaceGroup'] = $SelectedRaceGroup['SelectedRaceGroup'];
			$bind['comment'] = json_encode($bind['comment']);
			$res = $this->oRaceStage->insertRaceStage($bind);
			$response = $res ? array('errno' => 0) : array('errno' => 9);
		}
		echo json_encode($response);
		return true;
	}
	
	//修改任务信息页面
	public function raceStageModifyAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("RaceStageModify");
		if($PermissionCheck['return'])
		{
			$RaceStageId = intval($this->request->RaceStageId);
			$RaceCatalogArr  = $this->oRaceStage->getAllRaceCatalogList();
			$oRaceStage = $this->oRaceStage->getRaceStage($RaceStageId,'*');
			$RaceGroupArr = $this->oRaceStage->getAllRaceGroupList($oRaceStage['RaceCatalogId'],'RaceGroupId,RaceGroupName');
			$oRaceStage['comment'] = json_decode($oRaceStage['comment'],true);
			foreach($RaceGroupArr as $RaceGroupId => $value)
			{
				if(in_array($RaceGroupId,$oRaceStage['comment']['SelectedRaceGroup']))
				{
					$RaceGroupArr[$RaceGroupId]['selected'] = 1;
				}
				else
				{
					$RaceGroupArr[$RaceGroupId]['selected'] = 0;
				}
			}
			include $this->tpl('Xrace_Race_RaceStageModify');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	
	//更新任务信息
	public function raceStageUpdateAction()
	{
		$bind=$this->request->from('RaceStageId','RaceStageName','RaceCatalogId','StageStartDate','StageEndDate');
		$SelectedRaceGroup = $this->request->from('SelectedRaceGroup');
		$RaceCatalogArr  = $this->oRaceStage->getAllRaceCatalogList();
		if(trim($bind['RaceStageName'])=="")
		{
			$response = array('errno' => 1);
		}
		elseif(intval($bind['RaceStageId'])<=0)
		{
			$response = array('errno' => 2);
		}
		elseif(!isset($RaceCatalogArr[$bind['RaceCatalogId']]))
		{
			$response = array('errno' => 3);
		}
		elseif(count($SelectedRaceGroup['SelectedRaceGroup'])==0)
		{
			$response = array('errno' => 4);
		}
		else
		{
			$bind['comment']['SelectedRaceGroup'] = $SelectedRaceGroup['SelectedRaceGroup'];
			$SelectedRacedGroup = $this->oRaceStage->getRaceStageGroupByStage($bind['RaceStageId'],"RaceStageId,RaceGroupId");
			foreach($SelectedRacedGroup as $key => $GroupInfo)
			{
				if(!isset($bind['comment']['SelectedRaceGroup'][$GroupInfo['RaceGroupId']]))
				{
					//echo "to_delete:".$GroupInfo['RaceStageId']."-".$GroupInfo['RaceGroupId']."<br>";
					$this->oRaceStage->deleteRaceStageGroup($GroupInfo['RaceStageId'],$GroupInfo['RaceGroupId']);
				}
			}
			$bind['comment'] = json_encode($bind['comment']);
			$res = $this->oRaceStage->updateRaceStage($bind['RaceStageId'],$bind);
			$response = $res ? array('errno' => 0) : array('errno' => 9);
		}
		echo json_encode($response);
		return true;
	}
	//更新任务信息
	public function raceStageGroupListAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("RaceStageModify");
		$PermissionCheck['return'] = "1";
		if($PermissionCheck['return'])
		{
			//http://admin.xrace.com/?ctl=xrace/race.stage&ac=race.stage.group&RaceStageId=1&RaceGroupId=3
			$RaceStageId = intval($this->request->RaceStageId);
			//获取当前分站信息
			$oRaceStage = $this->oRaceStage->getRaceStage($RaceStageId,'*');
			//解包压缩数组
			$oRaceStage['comment'] = json_decode($oRaceStage['comment'],true);
			//如果已选分组的数据不存在，用默认空数组替代
			$oRaceStage['comment']['SelectedRaceGroup'] = isset($oRaceStage['comment']['SelectedRaceGroup'])?$oRaceStage['comment']['SelectedRaceGroup']:array();
			foreach($oRaceStage['comment']['SelectedRaceGroup'] as $RaceGroupId => $RaceGroupInfo)
			{
				//获取赛事分组信息
				$RaceGroupInfo = $this->oRaceStage->getRaceGroup($RaceGroupId,'*');
				//如果获取到
				if($RaceGroupInfo['RaceGroupId'])
				{
					$oRaceStage['comment']['SelectedRaceGroup'][$RaceGroupId] = array('RaceGroupInfo' => $RaceGroupInfo, 'RaceStageGroupInfo' => array());
					$RaceStageGroupInfo = $this->oRaceStage->getRaceStageGroup($RaceStageId,$RaceGroupId);
					$oRaceStage['comment']['SelectedRaceGroup'][$RaceGroupId]['RaceStageGroupInfo'] = isset($RaceStageGroupInfo['RaceStageId'])?$RaceStageGroupInfo:array('PriceList'=>0,'SingleUser'=>1,'TeamUser'=>1);
				}
				else
				{
					//删除当前分组
					unset($oRaceStage['comment']['SelectedRaceGroup'][$RaceGroupId]);
				}
			}
			include $this->tpl('Xrace_Race_RaceStageGroup');

		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	//更新任务信息
	public function raceStageGroupUpdateAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("RaceStageModify");
		if($PermissionCheck['return'])
		{
			$bind = $this->request->from('SelectedGroup','RaceStageId');
			//循环获取到的数据
			foreach($bind['SelectedGroup'] as $RaceGroupId => $GroupInfo)
			{
				//循环获取数据库内存储的赛段详情
				$RaceStageGroupInfo = $this->oRaceStage->getRaceStageGroup($bind['RaceStageId'],$RaceGroupId);
				//如果获取到
				if($RaceStageGroupInfo['RaceStageId'])
				{
					//更新
					$res = $this->oRaceStage->updateRaceStageGroup($bind['RaceStageId'],$RaceGroupId,$GroupInfo);
				}
				else
				{
					//新建
					$GroupInfo['RaceStageId'] = $bind['RaceStageId'];
					$GroupInfo['RaceGroupId'] = $RaceGroupId;
					$res = $this->oRaceStage->insertRaceStageGroup($GroupInfo);
				}
			}
			$this->response->goBack();
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	
	//删除任务
	public function raceStageDeleteAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("RaceStageDelete");
		if($PermissionCheck['return'])
		{
			$RaceStageId = intval($this->request->RaceStageId);
			$this->oRaceStage->deleteRaceStage($RaceStageId);
			$this->response->goBack();
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	//删除任务
	public function getSelectedGroupAction()
	{
		$RaceCatalogId = intval($this->request->RaceCatalogId);
		$RaceStageId = intval($this->request->RaceStageId);
		$RaceGroupArr = $this->oRaceStage->getAllRaceGroupList($RaceCatalogId);
		if($RaceStageId)
		{
			$oRaceStage = $this->oRaceStage->getRaceStage($RaceStageId,'*');
			$oRaceStage['comment'] = json_decode($oRaceStage['comment'],true);
		}
		else
		{
			$oRaceStage['comment']['SelectedRaceGroup'] = array();
		}
		foreach($RaceGroupArr as $RaceGroupId => $RaceGroupInfo)
		{
			if(in_array($RaceGroupId,$oRaceStage['comment']['SelectedRaceGroup']))
			{
				$t[$RaceGroupId] = '<input type="checkbox"  name="SelectedRaceGroup[]" value='.$RaceGroupId.' checked>'.$RaceGroupInfo['RaceGroupName'];
			}
			else
			{
				$t[$RaceGroupId] = '<input type="checkbox"  name="SelectedRaceGroup[]" value='.$RaceGroupId.'>'.$RaceGroupInfo['RaceGroupName'];
			}
		}
		$text = implode("  ",$t);
		$text = (trim($text!=""))?$text:"暂无分类";
		echo $text;
		die();
	}
}
