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
		$this->oRace = new Xrace_Race();

	}
	//任务配置列表页面
	public function indexAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission(0);
		if($PermissionCheck['return'])
		{
			$RaceCatalogId = isset($this->request->RaceCatalogId)?intval($this->request->RaceCatalogId):0;
			$RaceCatalogArr  = $this->oRace->getAllRaceCatalogList();
			$RaceStageArr = $this->oRace->getAllRaceStageList($RaceCatalogId);
			$RaceGroupArr = $this->oRace->getAllRaceGroupList($RaceCatalogId,'RaceGroupId,RaceGroupName');
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
								$t[$k] = "<a href='".Base_Common::getUrl('','xrace/race.stage','race.stage.group.detail',array('RaceStageId'=>$value['RaceCatalogId'],'RaceGroupId'=>$key)) ."'>".$RaceGroupArr[$v]['RaceGroupName']."</a>";
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
						$RaceStageList[$value['RaceCatalogId']]['RaceStageList'][$key]['SelectedGroupList'] = "<a href='".Base_Common::getUrl('','xrace/race.stage','race.stage.group.list',array('RaceStageId'=>$key)) ."'>尚未配置</a>";
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

			$RaceCatalogArr  = $this->oRace->getAllRaceCatalogList();
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
		$RaceCatalogArr  = $this->oRace->getAllRaceCatalogList();
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
			$res = $this->oRace->insertRaceStage($bind);
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
			$RaceCatalogArr  = $this->oRace->getAllRaceCatalogList();
			$oRaceStage = $this->oRace->getRaceStage($RaceStageId,'*');
			$RaceGroupArr = $this->oRace->getAllRaceGroupList($oRaceStage['RaceCatalogId'],'RaceGroupId,RaceGroupName');
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
		$RaceCatalogArr  = $this->oRace->getAllRaceCatalogList();
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
			$SelectedRacedGroup = $this->oRace->getRaceStageGroupByStage($bind['RaceStageId'],"RaceStageId,RaceGroupId");
			foreach($SelectedRacedGroup as $key => $GroupInfo)
			{
				if(!isset($bind['comment']['SelectedRaceGroup'][$GroupInfo['RaceGroupId']]))
				{
					$this->oRace->deleteRaceStageGroup($GroupInfo['RaceStageId'],$GroupInfo['RaceGroupId']);
				}
			}
			$bind['comment'] = json_encode($bind['comment']);
			$res = $this->oRace->updateRaceStage($bind['RaceStageId'],$bind);
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
		if($PermissionCheck['return'])
		{
			$RaceStageId = intval($this->request->RaceStageId);
			//获取当前分站信息
			$oRaceStage = $this->oRace->getRaceStage($RaceStageId,'*');
			//解包压缩数组
			$oRaceStage['comment'] = json_decode($oRaceStage['comment'],true);
			//如果已选分组的数据不存在，用默认空数组替代
			$oRaceStage['comment']['SelectedRaceGroup'] = isset($oRaceStage['comment']['SelectedRaceGroup'])?$oRaceStage['comment']['SelectedRaceGroup']:array();
			foreach($oRaceStage['comment']['SelectedRaceGroup'] as $RaceGroupId => $RaceGroupInfo)
			{
				//获取赛事分组信息
				$RaceGroupInfo = $this->oRace->getRaceGroup($RaceGroupId,'*');
				//如果获取到
				if($RaceGroupInfo['RaceGroupId'])
				{
					$oRaceStage['comment']['SelectedRaceGroup'][$RaceGroupId] = array('RaceGroupInfo' => $RaceGroupInfo, 'RaceStageGroupInfo' => array());
					$RaceStageGroupInfo = $this->oRace->getRaceStageGroup($RaceStageId,$RaceGroupId);
					$StartTime = date("Y-m-d H:i:s",time()+86400);
					$EndTime = date("Y-m-d H:i:s",time()+86400*2);
					$oRaceStage['comment']['SelectedRaceGroup'][$RaceGroupId]['RaceStageGroupInfo'] = isset($RaceStageGroupInfo['RaceStageId'])?$RaceStageGroupInfo:array('PriceList'=>0,'SingleUser'=>1,'TeamUser'=>1,'StartTime'=>$StartTime,'EndTime'=>$EndTime);
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
				$RaceStageGroupInfo = $this->oRace->getRaceStageGroup($bind['RaceStageId'],$RaceGroupId);
				//如果获取到
				if($RaceStageGroupInfo['RaceStageId'])
				{
					//更新
					$res = $this->oRace->updateRaceStageGroup($bind['RaceStageId'],$RaceGroupId,$GroupInfo);
				}
				else
				{
					//新建
					$GroupInfo['RaceStageId'] = $bind['RaceStageId'];
					$GroupInfo['RaceGroupId'] = $RaceGroupId;
					$res = $this->oRace->insertRaceStageGroup($GroupInfo);
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
			$this->oRace->deleteRaceStage($RaceStageId);
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
		$RaceGroupArr = $this->oRace->getAllRaceGroupList($RaceCatalogId);
		if($RaceStageId)
		{
			$oRaceStage = $this->oRace->getRaceStage($RaceStageId,'*');
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
	//更新任务信息
	public function raceStageGroupDetailAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("RaceStageModify");
		if($PermissionCheck['return'])
		{
			$RaceStageId = intval($this->request->RaceStageId);
			$RaceGroupId = intval($this->request->RaceGroupId);
			//获取当前分站信息
			$oRaceStage = $this->oRace->getRaceStage($RaceStageId,'*');
			$oRaceGroup = $this->oRace->getRaceGroup($RaceGroupId,'*');
			//解包压缩数组
			$oRaceStage['comment'] = json_decode($oRaceStage['comment'],true);
			//如果已选分组的数据不存在，用默认空数组替代
			$RaceStageGroupInfo = $this->oRace->getRaceStageGroup($RaceStageId,$RaceGroupId);
			$StartTime = date("Y-m-d H:i:s",time()+86400);
			$EndTime = date("Y-m-d H:i:s",time()+86400*2);
			$RaceStageGroupInfo = is_array($RaceStageGroupInfo)?$RaceStageGroupInfo:array('PriceList'=>0,'SingleUser'=>1,'TeamUser'=>1,'StartTime'=>$StartTime,'EndTime'=>$EndTime);
			$RaceStageGroupInfo['comment'] = isset($RaceStageGroupInfo['comment'])?json_decode($RaceStageGroupInfo['comment'],true):array();
			$RaceStageGroupInfo['comment']['DetailList'] = isset($RaceStageGroupInfo['comment']['DetailList'])?$RaceStageGroupInfo['comment']['DetailList']:array();
			$RaceStageGroupInfo['comment']['DetailList'] = array('1'=>array('Tpoint'=>array()),'2'=>array('Tpoint'=>array()),'2'=>array('Tpoint'=>array()));
			$this->oSports = new Xrace_Sports();
			$SportTypeArr = $this->oSports->getAllSportsTypeList();
			print_r($SportTypeArr);
			foreach($RaceStageGroupInfo['comment']['DetailList'] as $RaceSportsTypeId => $RaceSportsInfo)
			{
				if(isset($SportTypeArr[$RaceSportsTypeId]))
				{
					$RaceStageGroupInfo['comment']['DetailList'][$RaceSportsTypeId]['SportsTypeName'] = $SportTypeArr[$RaceSportsTypeId]['SportsTypeName'];
				}
				else
				{
					unset($RaceStageGroupInfo['comment']['DetailList'][$RaceSportsTypeId]);
				}
			}
			print_R($RaceStageGroupInfo);
			include $this->tpl('Xrace_Race_RaceStageGroupDetail');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
}
