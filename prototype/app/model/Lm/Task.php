<?php
/**
 * 角色任务相关mod层
 * @author 陈晓东 <cxd032404@hotmail.com>
 */


class Lm_Task extends Base_Widget
{
	//声明所用到的表
	protected $table = 'user_character_slk_log';
	protected $table_slkid_map = 'slkid_map';
	protected $table_accept_task_log = 'lm_accept_task_log';
	protected $table_task_complete_log = 'lm_task_complete_log';
    protected $table_pvp_log = 'pvp_log';
    protected $table_pvp_log_user = 'pvp_log_user';
    protected $table_pvp_total_log = 'pvp_total_log';
    protected $table_pve_tower_log_user = 'pve_tower_log_user';
    protected $table_pve_tower_log = 'pve_tower_log';
    protected $table_pvp_log_total = 'pvp_log_total';

	public function createCharacterClkLogTable($Date)
	{
		$table_to_check = Base_Widget::getDbTable($this->table);
		$table_to_process = Base_Widget::getDbTable($this->table)."_".$Date;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function createCharacterClkLogTableByuser($user)
	{
		$table_to_check = Base_Widget::getDbTable($this->table);
		$table_to_process = Base_Widget::getDbTable($this->table)."_user_".$user;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function createPvpLogTable($Date)
	{
		$table_to_check = Base_Widget::getDbTable($this->table_pvp_log);
		$table_to_process = Base_Widget::getDbTable($this->table_pvp_log)."_".$Date;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table_pvp_log . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function createPvpLogTableByuser($table_name)
	{
		$table_to_check = Base_Widget::getDbTable($this->table_pvp_log_user);
		$table_to_process = $table_name;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`'.$this->table_pvp_log_user.'`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function createPveTowerLogTable($Date)
	{
		$table_to_check = Base_Widget::getDbTable($this->table_pve_tower_log);
		$table_to_process = Base_Widget::getDbTable($this->table_pve_tower_log)."_".$Date;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table_pve_tower_log . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function insertTowerLog($DataArr)
    {
        $this->db->begin();
        $position = Base_Common::getUserDataPositionById($DataArr['UserId']);
        $table_name = Base_Common::getUserTable($this->table_pve_tower_log_user,$position);
        $insertUser = $this->db->insert($table_name,$DataArr);
        
        $table_name = $this->createPveTowerLogTable(date("Ym",$DataArr['CreateTowerTime']));
        $insertDate = $this->db->insert($table_name,$DataArr);
        
        if($insertUser && $insertDate){
            $this->db->commit();
            return true;
        }else{
            $this->db->rollback();
            return false;
        }         
    }
    
    public function InsertPvpLog($DataArr)
	{
        $table_to_insert = $this->createPvpLogTable(date("Ym",$DataArr['PvpEnterTime']));
        $insertdate = $this->db->insert($table_to_insert,$DataArr);
        
        $position = Base_Common::getUserDataPositionById($DataArr['UserId']);
        $table_name = Base_Common::getUserTable($this->table_pvp_log_user,$position);
        
        $table_to_insert = $this->createPvpLogTableByuser($table_name);
        $insertuser = $this->db->insert($table_to_insert,$DataArr);
        
        $this->db->begin();
        if($insertdate && $insertuser){
            $this->db->commit();
            return true;
        }else{
            $this->db->rollback();
            return false;
        }		
	}
    	
	public function InsertCharacterSlkLog($DataArr)
	{
		$this->db->begin();
		$Date = date("Ym",$DataArr['CharacterSlkEnterTime']);
		$table_date = $this->createCharacterClkLogTable($Date);	
		$date =  $this->db->insert($table_date,$DataArr);
        
        $position = Base_Common::getUserDataPositionById($DataArr['UserId']);
        $table_user = $this->createCharacterClkLogTableByuser($position['db_fix']);
        $user = $this->db->insert($table_user,$DataArr);
        
		$MapArr = array('AppId'=>$DataArr['AppId'],'PartnerId'=>$DataArr['PartnerId'],'ServerId'=>$DataArr['ServerId'],'UserId'=>$DataArr['UserId'],'EctypeId'=>$DataArr['EctypeId'],'SlkId'=>$DataArr['SlkId'],'CharacterSlkEnterTime'=>$DataArr['CharacterSlkEnterTime']);
		$map = $this->InsertSlkIdMapLog($MapArr);
		if($date&&$map&&$user)
		{
			$this->db->commit();
			return true;	
		}
		else
		{
			$this->db->rollback();
			return false;	
		}
	}
	public function LeaveSlk($CharacterSlkEnterTime,$DataArr,$bindArr,$UserId)
	{
		$this->db->begin();
		$update = $this->updateCharacterSlkLog($CharacterSlkEnterTime,$DataArr,$bindArr);
        $updateUser = $this->updateCharacterSlkLogByuser($UserId,$DataArr,$bindArr);
		$delete = $this->delSlkMap($bindArr);
		if($update&&$delete&&$updateUser)
		{
			$this->db->commit();
			return true;	
		}
		else
		{
			$this->db->rollback();
			return false;	
		}
	}
	public function updateCharacterSlkLog($CharacterSlkEnterTime,$DataArr,$bindArr)
	{
		$Date = date("Ym",$CharacterSlkEnterTime);
		$table_date = $this->createCharacterClkLogTable($Date);		
		return $this->db->update($table_date, $DataArr, '`UserId` = ? and `SlkId` = ? and `EctypeId` = ? and `AppId` = ? and `PartnerId` = ? and `ServerId` = ? ', $bindArr);
	}
    public function updateCharacterSlkLogByuser($UserId,$DataArr,$bindArr)
	{
		$position = Base_Common::getUserDataPositionById($DataArr['UserId']);
        $table_user = $this->createCharacterClkLogTableByuser($position['db_fix']);        	
		return $this->db->update($table_user, $DataArr, '`UserId` = ? and `SlkId` = ? and `EctypeId` = ? and `AppId` = ? and `PartnerId` = ? and `ServerId` = ? ', $bindArr);
	}
	public function delSlkMap($bindArr)
	{
		$table_to_delete = Base_Widget::getDbTable($this->table_slkid_map);
		return $this->db->delete($table_to_delete,'`UserId` = ? and `SlkId` = ? and `EctypeId` = ? and `AppId` = ? and `PartnerId` = ? and `ServerId` = ? ', $bindArr);
	}
	public function InsertSlkIdMapLog($DataArr)
	{
		$table_to_insert = Base_Widget::getDbTable($this->table_slkid_map);
		return $this->db->insert($table_to_insert,$DataArr);
	}
    
	public function GetSlkIdMapLog($DataArr,$fields = "*")
	{
		$table_to_process = Base_Widget::getDbTable($this->table_slkid_map);
		$sql = "select $fields from $table_to_process where `UserId` = ? and `SlkId` = ? and `EctypeId` = ? and `AppId` = ? and `PartnerId` = ? and `ServerId` = ? ";
        return $this->db->getOne($sql,$DataArr,false);
	}
	public function createCharacterAcceptTaskTable($Date)
	{
		$table_to_check = Base_Widget::getDbTable($this->table_accept_task_log);
		$table_to_process = Base_Widget::getDbTable($this->table_accept_task_log)."_".$Date;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table_accept_task_log . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function createPvpTotalTable($Date)
	{
		$table_to_check = Base_Widget::getDbTable($this->table_pvp_total_log);
		$table_to_process = Base_Widget::getDbTable($this->table_pvp_total_log)."_".$Date;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table_pvp_total_log . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
    
    public function InsertPvpTotalLog($DataArr)
	{
		$table_to_insert = $this->createPvpTotalTable(date("Ym",$DataArr['PvpEnterTime']));
		return $this->db->insert($table_to_insert,$DataArr);
	}
    public function getPvpTotalLog($PvpEnterTime,$EctypeId,$fields = '*')
	{
		$table_to_process = $this->createPvpTotalTable(date("Ym",$PvpEnterTime));
		$sql = "SELECT $fields FROM $table_to_process as log where PvpEnterTime = ? and EctypeId = ? ";
		return $this->db->getRow($sql,array($PvpEnterTime,$EctypeId));
	}	
	public function InsertCharacterAcceptTaskLog($DataArr)
	{
		$Date = date("Ym",$DataArr['HeroAcceptTaskTime']);
		$table_date = $this->createCharacterAcceptTaskTable($Date);		
		return $this->db->insert($table_date,$DataArr);
	} 
	public function createCharacterTaskCompleteTable($Date)
	{
		$table_to_check = Base_Widget::getDbTable($this->table_task_complete_log);
		$table_to_process = Base_Widget::getDbTable($this->table_task_complete_log)."_".$Date;
		$exist = $this->db->checkTableExist($table_to_process);
		if($exist>0)
		{
			return $table_to_process;	
		}
		else
		{
			$sql = "SHOW CREATE TABLE " . $table_to_check;
			$row = $this->db->getRow($sql);
			$sql = $row['Create Table'];
			$sql = str_replace('`' . $this->table_task_complete_log . '`', 'IF NOT EXISTS ' . $table_to_process, $sql);
			$create = $this->db->query($sql);
			if($create)
			{
				return $table_to_process;
			}
			else
			{
			 return false;	
			}		 	
		}
	}
	public function InsertCharacterTaskCompleteLog($DataArr)
	{
		$Date = date("Ym",$DataArr['HeroTaskCompleteTime']);
		$table_date = $this->createCharacterTaskCompleteTable($Date);		
		return $this->db->insert($table_date,$DataArr);
	}   
  //获取角色开启副本数据
  public function getSlkOpened($StartDate,$EndDate,$ServerId,$InstMapId,$oWherePartnerPermission)
  {
      //查询列
	$select_fields = array(
		'OpenCount'=>'count(distinct(EctypeId))','SlkId',
		'CharacterSlkEnterDate'=>"from_unixtime(CharacterSlkEnterTime,'%Y-%m-%d')",
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" CharacterSlkEnterTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" CharacterSlkEnterTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      $whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
      
      $group_fields = array('CharacterSlkEnterDate','SlkId');
      $groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereInstMap);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      
      
      $DateStart = date("Ym",strtotime($StartDate));
      $DateEnd = date("Ym",strtotime($EndDate));
      $DateList = array();  
      $Date = $StartDate;      
      do
      {
        $D = date("Ym",strtotime($Date));
        $DateList[] = $D;
        $Date = date("Y-m-d",strtotime("$Date +1 month"));
      }
      while($D!=$DateEnd);
      
      foreach($DateList as $k=>$v)
      {
		$table_name = Base_Widget::getDbTable($this->table)."_".$v;
		$sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;
  		$CharacterSlkArr = $this->db->getAll($sql,false);
          
		foreach($CharacterSlkArr as $key=>$val)
		{
			$StatArr['Slk'][$val['CharacterSlkEnterDate']]['SlkList'][$val['SlkId']]['OpenCount'] += $val['OpenCount'];
		}
      }
   
	return $StatArr;
  }
   //获取角色离开副本数据
  public function getSlkLeft($StartDate,$EndDate,$ServerId,$InstMapId,$oWherePartnerPermission)
  {
      //查询列
	$select_fields = array(
		'LeftCount'=>'count(*)',
		'LeftSlkCount'=>'count(distinct(EctypeId))','SlkId','CharacterLeaveType','UserCount'=>'count(distinct(UserId))',
		'CharacterSlkEnterDate'=>"from_unixtime(CharacterSlkEnterTime,'%Y-%m-%d')",
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" CharacterSlkEnterTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" CharacterSlkEnterTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      $whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
      
      $group_fields = array('CharacterSlkEnterDate','SlkId','CharacterLeaveType');
      $groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereInstMap);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      
      
      $DateStart = date("Ym",strtotime($StartDate));
      $DateEnd = date("Ym",strtotime($EndDate));
      $DateList = array();  
      $Date = $StartDate;      
      do
      {
        $D = date("Ym",strtotime($Date));
        $DateList[] = $D;
        $Date = date("Y-m-d",strtotime("$Date +1 month"));
      }
      while($D!=$DateEnd);
      
      foreach($DateList as $k=>$v)
      {
		$table_name = Base_Widget::getDbTable($this->table)."_".$v;
		$sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;
  		$CharacterSlkArr = $this->db->getAll($sql,false);
          
		foreach($CharacterSlkArr as $key=>$val)
		{
			$StatArr['Slk'][$val['CharacterSlkEnterDate']]['SlkList'][$val['SlkId']][$val['CharacterLeaveType']]['LeftCount'] += $val['LeftCount'];
			$StatArr['Slk'][$val['CharacterSlkEnterDate']]['SlkList'][$val['SlkId']][$val['CharacterLeaveType']]['LeftSlkCount'] += $val['LeftSlkCount'];
			$StatArr['Slk'][$val['CharacterSlkEnterDate']]['SlkList'][$val['SlkId']][$val['CharacterLeaveType']]['Usercount'] += $val['UserCount'];
		}
      }
   
	return $StatArr;
  }  
   //获取角色副本人数数据
  public function getSlkMenberCount($StartDate,$EndDate,$ServerId,$InstMapId,$oWherePartnerPermission)
  {
      //查询列
	$select_fields = array('TeamNum',
		'OpenCount'=>'count(distinct(EctypeId))','SlkId',
		'CharacterSlkEnterDate'=>"from_unixtime(CharacterSlkEnterTime,'%Y-%m-%d')",
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" CharacterSlkEnterTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" CharacterSlkEnterTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      $whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
      
      $group_fields = array('CharacterSlkEnterDate','SlkId','TeamNum');
      $groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereInstMap);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      
      
      $DateStart = date("Ym",strtotime($StartDate));
      $DateEnd = date("Ym",strtotime($EndDate));
      $DateList = array();  
      $Date = $StartDate;      
      do
      {
        $D = date("Ym",strtotime($Date));
        $DateList[] = $D;
        $Date = date("Y-m-d",strtotime("$Date +1 month"));
      }
      while($D!=$DateEnd);
      
      foreach($DateList as $k=>$v)
      {
		$table_name = Base_Widget::getDbTable($this->table)."_".$v;
		$sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;
  		$CharacterSlkArr = $this->db->getAll($sql,false);
          
		foreach($CharacterSlkArr as $key=>$val)
		{
			$StatArr['Slk'][$val['CharacterSlkEnterDate']]['SlkList'][$val['SlkId']][$val['TeamNum']]['OpenCount'] += $val['OpenCount'];
		}
      }
   
	return $StatArr;
  }
  public function getCharacterTaskAccept($StartDate,$EndDate,$ServerId,$TaskId,$TaskType,$oWherePartnerPermission)
  {
      //查询列
	$select_fields = array(
	'AcceptCount'=>'count(*)',
      'UserCount'=>'count(distinct(UserId))',
      'CharacterTaskAcceptDate'=>"from_unixtime(HeroAcceptTaskTime,'%Y-%m-%d')",
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" HeroAcceptTaskTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" HeroAcceptTaskTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      $whereTask = $TaskId?" TaskId = ".$TaskId." ":"";
      $whereTaskType = $TaskType!=999?" TaskType = ".$TaskType." ":"";        
      
      $group_fields = array('CharacterTaskAcceptDate');
      $groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereTask,$whereTaskType);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      
      //初始化结果数组
      $Date = $StartDate;
      $StatArr = array('Task'=>array());         
      do
	{
		$StatArr['Task'][$Date] = array('AcceptCount'=> 0,'UserCount'=> 0);
		$Date = date("Y-m-d",(strtotime($Date)+86400));
	}
	while(strtotime($Date) <= strtotime($EndDate));
      
      $DateStart = date("Ym",strtotime($StartDate));
      $DateEnd = date("Ym",strtotime($EndDate));
      $DateList = array();  
      $Date = $StartDate;      
      do
      {
        $D = date("Ym",strtotime($Date));
        $DateList[] = $D;
        $Date = date("Y-m-d",strtotime("$Date +1 month"));
      }
      while($D!=$DateEnd);
      
      foreach($DateList as $k=>$v){
      $table_name = Base_Widget::getDbTable($this->table_accept_task_log)."_".$v;
      $sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;
  		$CharacterTaskAccpetArr = $this->db->getAll($sql,false);
          
          foreach($CharacterTaskAccpetArr as $key=>$val){
              $StatArr['Task'][$val['CharacterTaskAcceptDate']]['AcceptCount'] += $val['AcceptCount'];
              $StatArr['Task'][$val['CharacterTaskAcceptDate']]['UserCount'] += $val['UserCount'];                
          }
      }
	return $StatArr;
  }
  public function getCharacterTaskComplete($StartDate,$EndDate,$ServerId,$TaskId,$TaskType,$oWherePartnerPermission)
  {
      //查询列
	$select_fields = array(
	'CompleteCount'=>'count(*)',
      'UserCount'=>'count(distinct(UserId))',
      'CharacterTaskCompleteDate'=>"from_unixtime(HeroTaskCompleteTime,'%Y-%m-%d')",
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" HeroTaskCompleteTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" HeroTaskCompleteTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      $whereTask = $TaskId?" TaskId = ".$TaskId." ":"";
      $whereTaskType = $TaskType!=999?" TaskType = ".$TaskType." ":"";        
      
      $group_fields = array('CharacterTaskCompleteDate');
      $groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereTask,$whereTaskType);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      
      //初始化结果数组
      $Date = $StartDate;
      $StatArr = array('Task'=>array());         
      do
	{
		$StatArr['Task'][$Date] = array('CompleteCount'=> 0,'UserCount'=> 0);
		$Date = date("Y-m-d",(strtotime($Date)+86400));
	}
	while(strtotime($Date) <= strtotime($EndDate));
      
      $DateStart = date("Ym",strtotime($StartDate));
      $DateEnd = date("Ym",strtotime($EndDate));
      $DateList = array();  
      $Date = $StartDate;      
      do
      {
        $D = date("Ym",strtotime($Date));
        $DateList[] = $D;
        $Date = date("Y-m-d",strtotime("$Date +1 month"));
      }
      while($D!=$DateEnd);
      
      foreach($DateList as $k=>$v){
      $table_name = Base_Widget::getDbTable($this->table_task_complete_log)."_".$v;
      $sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;

  		$CharacterTaskAccpetArr = $this->db->getAll($sql,false);
         
      foreach($CharacterTaskAccpetArr as $key=>$val)
      {
          $StatArr['Task'][$val['CharacterTaskCompleteDate']]['CompleteCount'] += $val['CompleteCount'];
          $StatArr['Task'][$val['CharacterTaskCompleteDate']]['UserCount'] += $val['UserCount'];                
      }
    }
	return $StatArr;
  }
  public function getCharacterTaskAcceptByType($StartDate,$EndDate,$ServerId,$TaskId,$TaskType,$oWherePartnerPermission,$TaskTypeList)
  {
      //查询列
	$select_fields = array(
	'AcceptCount'=>'count(*)',
  'UserCount'=>'count(distinct(UserId))',
  'TaskType','TaskId',
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" HeroAcceptTaskTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" HeroAcceptTaskTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
  $whereTask = $TaskId?" TaskId = ".$TaskId." ":"";
  $whereTaskType = $TaskType!=999?" TaskType = ".$TaskType." ":"";        
  
  $group_fields = array('TaskType','TaskId');
  $groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereTask,$whereTaskType);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      //初始化结果数组
	foreach($TaskTypeList as $Type => $TypeData)
	{
		$StatArr['TaskType'][$Type] = array('name'=>$TypeData,'AcceptCount'=> 0,'UserCount'=> 0);
	}
      
    $DateStart = date("Ym",strtotime($StartDate));
    $DateEnd = date("Ym",strtotime($EndDate));
    $DateList = array();  
    $Date = $StartDate;      
    do
    {
      $D = date("Ym",strtotime($Date));
      $DateList[] = $D;
      $Date = date("Y-m-d",strtotime("$Date +1 month"));
    }
    while($D!=$DateEnd);
      
    foreach($DateList as $k=>$v)
    {
      $table_name = Base_Widget::getDbTable($this->table_accept_task_log)."_".$v;
      $sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;
  		$CharacterTaskAccpetArr = $this->db->getAll($sql);
          
      foreach($CharacterTaskAccpetArr as $key=>$val)
      {
          $StatArr['TaskType'][$val['TaskType']]['AcceptCount'] += $val['AcceptCount'];
          $StatArr['TaskType'][$val['TaskType']]['UserCount'] += $val['UserCount'];
          
          if(!isset($StatArr['Task'][$val['TaskId']]))
          {
            $StatArr['Task'][$val['TaskId']]['AcceptCount'] = $val['AcceptCount'];
            $StatArr['Task'][$val['TaskId']]['UserCount'] = $val['UserCount'];            		
          }
          else
          {
            $StatArr['Task'][$val['TaskId']]['AcceptCount'] += $val['AcceptCount'];
            $StatArr['Task'][$val['TaskId']]['UserCount'] += $val['UserCount'];            		
          }                
      }
  	}
    
	return $StatArr;
  }
  public function getCharacterTaskCompleteByType($StartDate,$EndDate,$ServerId,$TaskId,$TaskType,$oWherePartnerPermission,$TaskTypeList)
  {
      //查询列
	$select_fields = array(
	'CompleteCount'=>'count(*)',
      'UserCount'=>'count(distinct(UserId))',
      'TaskType','TaskId',
	);
      
	//初始化查询条件
	$whereStartDate = $StartDate?" HeroTaskCompleteTime >= '".strtotime($StartDate)."' ":"";
	$whereEndDate = $EndDate?" HeroTaskCompleteTime <= '".(strtotime($EndDate)+86400-1)."' ":"";
	$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
  	$whereTask = $TaskId?" TaskId = ".$TaskId." ":"";
  	$whereTaskType = $TaskType!=999?" TaskType = ".$TaskType." ":"";        
  
  	$group_fields = array('TaskType','TaskId');
  	$groups = Base_common::getGroupBy($group_fields);

	$whereCondition = array($whereStartDate,$whereEndDate,$whereServer,$oWherePartnerPermission,$whereTask,$whereTaskType);

	//生成查询列
	$fields = Base_common::getSqlFields($select_fields);
	//生成条件列
	$where = Base_common::getSqlWhere($whereCondition);
      
  	//初始化结果数组
	foreach($TaskTypeList as $Type => $TypeData)
	{
		$StatArr['TaskType'][$Type] = array("name"=>$TypeData,'CompleteCount'=> 0,'UserCount'=> 0);
	}        
  $DateStart = date("Ym",strtotime($StartDate));
  $DateEnd = date("Ym",strtotime($EndDate));
  $DateList = array();  
  $Date = $StartDate;      
  do
  {
    $D = date("Ym",strtotime($Date));
    $DateList[] = $D;
    $Date = date("Y-m-d",strtotime("$Date +1 month"));
  }
  while($D!=$DateEnd);
  
	  foreach($DateList as $k=>$v)
	  {
	    $table_name = Base_Widget::getDbTable($this->table_task_complete_log)."_".$v;
	    $sql = "SELECT $fields FROM $table_name as log where 1 ".$where.$groups;
	
			$CharacterTaskAccpetArr = $this->db->getAll($sql,false);
	       
	    foreach($CharacterTaskAccpetArr as $key=>$val)
	    {
	        $StatArr['TaskType'][$val['TaskType']]['CompleteCount'] += $val['CompleteCount'];
	        $StatArr['TaskType'][$val['TaskType']]['UserCount'] += $val['UserCount'];
	        if(!isset($StatArr['Task'][$val['TaskId']]))
	        {
	          $StatArr['Task'][$val['TaskId']]['CompleteCount'] = $val['CompleteCount'];
	          $StatArr['Task'][$val['TaskId']]['UserCount'] = $val['UserCount'];            		
	        }
	        else
	        {
	          $StatArr['Task'][$val['TaskId']]['CompleteCount'] += $val['CompleteCount'];
	          $StatArr['Task'][$val['TaskId']]['UserCount'] += $val['UserCount'];            		
	        }                 
	    }
	}
	return $StatArr;
  }
 	public function getPvpDetail($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$EctypeId,$oWherePartnerPermission,$start,$pagesize)
	{
		$PvpCount = $this->getPvpDetailCount($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$EctypeId,$oWherePartnerPermission);
		if($PvpCount)
		{
				//查询列
			$select_fields = array('*');
			//分类统计列
	
			//初始化查询条件
			$whereStartTime = $StartTime?" PvpEnterTime >= ".strtotime($StartTime)." ":"";
			$whereEndTime = $EndTime?" PvpEnterTime <= ".strtotime($EndTime)." ":"";
			$whereUser = $UserId?" UserId = ".$UserId." ":"";
			$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
	      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
	    	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
	      	$whereLife = $EctypeId?" EctypeId = ".$EctypeId." ":"";			
			
			$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$whereLife,$oWherePartnerPermission);
			
			$order = " order by PvpEnterTime desc";
			$limit = $pagesize?" limit $start,$pagesize":"";
			
			//生成查询列
			$fields = Base_common::getSqlFields($select_fields);
			//生成条件列
			$where = Base_common::getSqlWhere($whereCondition);

		    if($UserId)
		    {
				$position = Base_Common::getUserDataPositionById($UserId);			
				$table_to_process = Base_Common::getUserTable($this->table_pvp_log_user,$position);  		
		    }
		    else
		    {
				$Date = date("Ym",strtotime($StartTime));			
				$table_to_process = Base_Widget::getDbTable($this->table_pvp_log)."_".$Date;     	
		    }
		    $StatArr = array('PvpDetail'=>array());
		
		    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where.$order.$limit;
			$PvpDetailArr = $this->db->getAll($sql,false);
			if(isset($PvpDetailArr))
		    {
	      		$i = 1;
	      		foreach ($PvpDetailArr as $key => $value) 
				{
					$StatArr['PvpDetail'][$i] = $value;
					$i++;
				}
		    }
  	}
  	
	 	$StatArr['PvpCount'] = $PvpCount; 
		return $StatArr;
	}
 	public function getPvpDetailCount($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$EctypeId,$oWherePartnerPermission)
	{
		//查询列
		$select_fields = array('PvpCount'=>'count(*)');
		//初始化查询条件
		$whereStartTime = $StartTime?" PvpEnterTime >= ".strtotime($StartTime)." ":"";
		$whereEndTime = $EndTime?" PvpEnterTime <= ".strtotime($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
	    $whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
      	$whereLife = $EctypeId?" EctypeId = ".$EctypeId." ":"";			
		
		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$whereLife,$oWherePartnerPermission);
		
		
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);

	    if($UserId)
	    {
			$position = Base_Common::getUserDataPositionById($UserId);			
			$table_to_process = Base_Common::getUserTable($this->table_pvp_log_user,$position);  		
	    }
	    else
	    {
			$Date = date("Ym",strtotime($StartTime));			
			$table_to_process = Base_Widget::getDbTable($this->table_pvp_log)."_".$Date;     	
	    }		    	    
	    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where;		
		$PvpCount = $this->db->getOne($sql,false);
		if($PvpCount)
    	{
			return $PvpCount;    
		}
		else
		{
			return 0; 	
		}
	}
 	public function getPvpSummary($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission)
	{			
		  //查询列
		$select_fields = array(
		'Won','Count'=>'count(*)');
		//分类统计列
		$group_fields = array('Won');
		$groups = Base_common::getGroupBy($group_fields);
		//初始化查询条件
		$whereStartTime = $StartTime?" PvpEnterTime >= ".strtotime($StartTime)." ":"";
		$whereEndTime = $EndTime?" PvpEnterTime <= ".strtotime($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
    	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
		
		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$oWherePartnerPermission);
				
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);
	    
		$position = Base_Common::getUserDataPositionById($UserId);			
		$table_to_process = Base_Common::getUserTable($this->table_pvp_log_user,$position);   			
	    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where.$groups;
		$PvpSumaryArr = $this->db->getAll($sql,false);
	    foreach($PvpSumaryArr as $key=>$val)
	    {
	        $StatArr['Won'][$val['Won']] = $val['Count'];
	    }
		return $StatArr;
	}
 	public function getPveTowerFirstKill($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission)
	{			
		  //查询列
		$select_fields = array(
		'TowerIndex','SlkId','FirstKillTime'=>'min(EndTowerTime)');
		//分类统计列
		$group_fields = array('TowerIndex','SlkId');
		$groups = Base_common::getGroupBy($group_fields);
		//初始化查询条件
		$whereStartTime = $StartTime?" CreateTowerTime >= ".strtotime($StartTime)." ":"";
		$whereEndTime = $EndTime?" CreateTowerTime < ".strtotime($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
    	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
		
		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$oWherePartnerPermission);
				
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);
	    
		$position = Base_Common::getUserDataPositionById($UserId);			
		$table_to_process = Base_Common::getUserTable($this->table_pve_tower_log_user,$position);   			
	    $sql = "SELECT $fields FROM $table_to_process as log where TowerIndex > 0 ".$where.$groups;
		$PveFirstKillArr = $this->db->getAll($sql,false);
	    foreach($PveFirstKillArr as $key=>$val)
	    {
	        $StatArr[$val['SlkId']][$val['TowerIndex']] = $val['FirstKillTime'];
	    }
		return $StatArr;
	}
 	public function getPveTowerTotalKill($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission)
	{			
		  //查询列
		$select_fields = array(
		'TowerIndex','SlkId','TotalKill'=>'count(*)');
		$group_fields = array('TowerIndex','SlkId');
		$groups = Base_common::getGroupBy($group_fields);
		//初始化查询条件
		$whereStartTime = $StartTime?" CreateTowerTime >= ".strtotime($StartTime)." ":"";
		$whereEndTime = $EndTime?" CreateTowerTime < ".strtotime($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
    	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
		
		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$oWherePartnerPermission);
				
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);
	    
		$position = Base_Common::getUserDataPositionById($UserId);			
		$table_to_process = Base_Common::getUserTable($this->table_pve_tower_log_user,$position);   			
	    $sql = "SELECT $fields FROM $table_to_process as log where TowerIndex > 0 ".$where.$groups;
		$TotalKillArr = $this->db->getAll($sql,false);
	    foreach($TotalKillArr as $key=>$val)
	    {
	        $StatArr[$val['SlkId']][$val['TowerIndex']] = $val['TotalKill'];
	    }
		return $StatArr;
	}
 	public function getPveTowerFastestKill($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission)
	{			
		  //查询列
		$select_fields = array(
		'TowerIndex','SlkId','FastestKillTime'=>'min(RunTime)');
		//分类统计列
		$group_fields = array('TowerIndex','SlkId');
		$groups = Base_common::getGroupBy($group_fields);
		//初始化查询条件
		$whereStartTime = $StartTime?" CreateTowerTime >= ".strtotime($StartTime)." ":"";
		$whereEndTime = $EndTime?" CreateTowerTime < ".strtotime($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
    	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
		
		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$oWherePartnerPermission);
				
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);
	    
		$position = Base_Common::getUserDataPositionById($UserId);			
		$table_to_process = Base_Common::getUserTable($this->table_pve_tower_log_user,$position);   			
	    $sql = "SELECT $fields FROM $table_to_process as log where TowerIndex > 0 ".$where.$groups;
		$PveFastestKillArr = $this->db->getAll($sql,false);
	    foreach($PveFastestKillArr as $key=>$val)
	    {
	        $StatArr[$val['SlkId']][$val['TowerIndex']] = $val['FastestKillTime'];
	    }
		return $StatArr;
	}
 	public function getSlkDetail($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission,$start,$pagesize)
	{
		$SlkCount = $this->getSlkDetailCount($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission);
		if($SlkCount)
		{
				//查询列
			$select_fields = array('*');
			//分类统计列
	
			//初始化查询条件
			$whereStartTime = $StartTime?" CharacterSlkEnterTime >= ".strtotime($StartTime)." ":"";
			$whereEndTime = $EndTime?" CharacterSlkEnterTime <= ".strtotime($EndTime)." ":"";
			$whereUser = $UserId?" UserId = ".$UserId." ":"";
			$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
	      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
	   	 	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
	
			$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$oWherePartnerPermission);
			
			$order = " order by CharacterSlkEnterTime desc";
			$limit = $pagesize?" limit $start,$pagesize":"";
			
			//生成查询列
			$fields = Base_common::getSqlFields($select_fields);
			//生成条件列
			$where = Base_common::getSqlWhere($whereCondition);

		    $StatArr = array('SlkDetail'=>array());

		    if($UserId)
		    {
				$position = Base_Common::getUserDataPositionById($UserId);			
				$table_to_process = Base_Widget::getDbTable($this->table)."_user_".$position['db_fix'];    		
		    }
		    else
		    {
				$Date = date("Ym",strtotime($StartTime));			
				$table_to_process = Base_Widget::getDbTable($this->table)."_".$Date;   
		    }		
		    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where.$order.$limit;
			$PvpDetailArr = $this->db->getAll($sql,false);
			if(isset($PvpDetailArr))
		    {
	      		$i = 1;
	      		foreach ($PvpDetailArr as $key => $value) 
				{
					$StatArr['SlkDetail'][$i] = $value;
					$i++;
				}
		    }
  	}
  	
	 	$StatArr['SlkCount'] = $SlkCount; 
		return $StatArr;
	}
 	public function getSlkDetailCount($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$oWherePartnerPermission)
	{
		//查询列
		$select_fields = array('SlkCount'=>'count(*)');
		//分类统计列

		//初始化查询条件
		$whereStartTime = $StartTime?" CharacterSlkEnterTime >= ".strtotime($StartTime)." ":"";
		$whereEndTime = $EndTime?" CharacterSlkEnterTime <= ".strtotime($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
	    $whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";

		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$oWherePartnerPermission);
		
		
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);

	    if($UserId)
	    {
			$position = Base_Common::getUserDataPositionById($UserId);			
			$table_to_process = Base_Widget::getDbTable($this->table)."_user_".$position['db_fix'];    		
	    }
	    else
	    {
			$Date = date("Ym",strtotime($StartTime));			
			$table_to_process = Base_Widget::getDbTable($this->table)."_".$Date;   
	    }  	
	    
	    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where;		
		$SlkCount = $this->db->getOne($sql,false);
		if($SlkCount)
    	{
			return $SlkCount;    
		}
		else
		{
			return 0; 	
		}
	}
 	public function getPveTowerSummary($StartTime,$EndTime,$UserId,$InstMapId,$HeroId,$ServerId,$TowerIndex,$oWherePartnerPermission)
	{						
		//查询列
		$select_fields = array('UserId','PassCount'=>'count(*)','FirstPassTime'=>'min(EndTowerTime)','TotalTime'=>'sum(RunTime)/1000');
		//分类统计列
		  $group_fields = array('UserId');
		  $groups = Base_common::getGroupBy($group_fields);
		//初始化查询条件
		$whereStartTime = $StartTime?" CreateTowerTime >= ".($StartTime)." ":"";
		$whereEndTime = $EndTime?" CreateTowerTime <= ".($EndTime)." ":"";
		$whereUser = $UserId?" UserId = ".$UserId." ":"";
		$whereServer = $ServerId?" ServerId = ".$ServerId." ":"";
      	$whereInstMap = $InstMapId?" SlkId = ".$InstMapId." ":"";
    	$whereHero = $HeroId!=-1?" HeroId = ".$HeroId." ":"";
      	$whereIndex = $TowerIndex?" TowerIndex = ".$TowerIndex." ":"";			
		
		$whereCondition = array($whereUser,$whereStartTime,$whereEndTime,$whereServer,$whereInstMap,$whereHero,$whereIndex,$oWherePartnerPermission);
				
		//生成查询列
		$fields = Base_common::getSqlFields($select_fields);
		//生成条件列
		$where = Base_common::getSqlWhere($whereCondition);

	    if($UserId)
	    {
			//echo "1轮<br>";
			$position = Base_Common::getUserDataPositionById($UserId);			
			$table_to_process = Base_Common::getUserTable($this->table_pve_tower_log_user,$position);
		    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where.$groups;
			$PveTowerSummaryArr = $this->db->getAll($sql,false);
			if(isset($PveTowerSummaryArr))
		    {
		  		foreach ($PveTowerSummaryArr as $key => $value) 
				{
					$StatArr['UserList'][$value['UserId']] = $value;
				}
		    }  	 	
		 	$StatArr['UserCount'] = count($StatArr['UserList']);  		
	    }
	    elseif((!$UserId)&&(date('m',$StartTime)==date('m',$EndTime)))
	    {
			//echo "1轮<br>";
			$Date = date("Ym",$StartTime);			
			$table_to_process = Base_Widget::getDbTable($this->table_pve_tower_log)."_".$Date;
		    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where.$groups;
			$PveTowerSummaryArr = $this->db->getAll($sql,false);
			if(isset($PveTowerSummaryArr))
		    {
	      		foreach ($PveTowerSummaryArr as $key => $value) 
				{
					$StatArr['UserList'][$value['UserId']] = $value;
				}
		    }  	 	
		 	$StatArr['UserCount'] = count($StatArr['UserList']);    	
	    }	

	 	
	 	if((!$UserId)&&(date('m',$StartTime)!=date('m',$EndTime)))
	 	{
	 		//echo "256轮<br>";
	 		for($i=0;$i<=255;$i++)
	 		{
			    $position = Base_Common::getUserDataPositionById(sprintf("%03d",$i));
			    $table_to_process = Base_Common::getUserTable($this->table_pve_tower_log_user,$position);
			    $sql = "SELECT $fields FROM $table_to_process as log where 1 ".$where.$groups;
				$PveTowerSummaryArr = $this->db->getAll($sql,false);
				if(isset($PveTowerSummaryArr))
			    {
		      		foreach ($PveTowerSummaryArr as $key => $value) 
					{
						$StatArr['UserList'][$value['UserId']] = $value;
					}
			    }  	 	
			 	$StatArr['UserCount'] = count($StatArr['UserList']);  	 				
	 		}	
	 	}
		return $StatArr;
	}
	
	public function getPvpLogTotal($whereTime)
	{
		$table_to_process = Base_Widget::getDbTable($this->table_pvp_log_total);
		$sql = "truncate $table_to_process";
		$this->db->query($sql);

		for($i=0;$i<=255;$i++)
		{
			$position = Base_Common::getUserDataPositionById(sprintf("%03d",$i));
			$table_to_get = Base_Common::getUserTable($this->table_pvp_log_user,$position);
			$sql = "replace into $table_to_process (ServerId,UserId,Won,PvpCount) select ServerId,UserId,Won,Count(*) from $table_to_get where $whereTime group by ServerId,UserId,Won";
			echo $this->db->query($sql)."-";
		}
	}
	public function getUserByPvpRank($Name)
	{
		$table_name = Base_Widget::getDbTable($this->table_pvp_log_total);
		$sql = "select distinct(ServerId) as ServerId from $table_name";
		$ServerList = $this->db->getAll($sql);
		$oCharacter = new Lm_Character();
		$oUser = new Lm_User();
		foreach($ServerList as $key => $value)
		{
			$sql = "select sum(if(Won=1,10*PvpCount,if(Won=0,2*PvpCount,if(Won=3,-10*PvpCount,0)))) as PvpPoint,UserId from $table_name where ServerId = ".$value['ServerId']." group by UserId order by PvpPoint desc limit 1000";
			$sql = "select UserId,Sum(Point) as PvpPoint from (select (if(Won=1,10*PvpCount,if(Won=0,2*PvpCount,if(Won=3,-10*PvpCount,0)))) as Point,UserId,Won from $table_name where ServerId = ".$value['ServerId'] .") as log group by UserId Order by PvpPoint Desc";
			$ResultAll = $this->db->getAll($sql);
			$PvpAll[$value['ServerId']] = array();
			foreach ($ResultAll as $k=>$v) 
			{
				$PvpRankAll[$value['ServerId']][$k+1]=$v;
				$CharacterInfo = $oCharacter->getCharacterInfoByUser($v['UserId'],$value['ServerId'],'CharacterName');
				$UserInfo = $oUser->getCharacterInfoByUser($v['UserId'],'UserName');
				if(isset($CharacterInfo['0']))
				{
					$PvpRankAll[$value['ServerId']][$k+1]['CharacterName'] = $CharacterInfo['0']['CharacterName'];	
				}
				else
				{
					$PvpRankAll[$value['ServerId']][$k+1]['CharacterName'] = 0; 	
				}
				if(isset($UserInfo['UserName']))
				{
					$PvpRankAll[$value['ServerId']][$k+1]['UserName'] = $UserInfo['UserName'];	
				}
				else
				{
				 	$PvpRankAll[$value['ServerId']][$k+1]['UserName'] = 0;	
				}
			} 				
		}
		$file_path = "/www/web_usercenter/app/etc/";
		$file_name = "PvpRank.php";
		$var = var_export($PvpRankAll,true);
		$text ='<?php $PvpRankAll='.$var.'; return $PvpRankAll;?>';
		file_put_contents($file_path.$file_name,$text);	
		$file_name = "PvpRank-".$Name.".php";		
		file_put_contents($file_path.$file_name,$text);		
	}
}
