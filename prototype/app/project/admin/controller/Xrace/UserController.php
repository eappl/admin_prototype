<?php
/**
 * 任务管理
 * @author Chen<cxd032404@hotmail.com>
 * $Id: LotoController.php 15195 2014-07-23 07:18:26Z 334746 $
 */

class Xrace_UserController extends AbstractController
{
	/**用户管理相关:User
	 * 权限限制  ?ctl=xrace/user
	 * @var string
	 */
	protected $sign = '?ctl=xrace/user';
	/**
	 * game对象
	 * @var object
	 */
	protected $oSportsType;

	/**
	 * 初始化
	 * (non-PHPdoc)
	 * @see AbstractController#init()
	 */
	public function init()
	{
		parent::init();
		$this->oUser = new Xrace_User();

	}
	//任务配置列表页面
	public function indexAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission(0);
		if($PermissionCheck['return'])
		{
			$SexList = $this->oUser->getSexList();
			$params['Sex'] = isset($SexList[strtoupper(trim($this->request->Sex))])?strtoupper(trim($this->request->Sex)):"";
			$params['Name'] = urldecode(trim($this->request->Name))?urldecode(trim($this->request->Name)):"";
			$params['NickName'] = urldecode(trim($this->request->NickName))?urldecode(trim($this->request->NickName)):"";

			$params['Page'] = abs(intval($this->request->Page))?abs(intval($this->request->Page)):1;
			$params['PageSize'] = 5;
			$params['getCount'] = 1;
			$UserList = $this->oUser->getUserLst($params);
			//导出EXCEL链接
			$export_var = "<a href =".(Base_Common::getUrl('','xrace/user','user.list.download',$params+array("export"=>1)))."><导出表格></a>";
			//翻页参数
			$page_url = Base_Common::getUrl('','xrace/user','index',$params+array("export"=>0))."&Page=~page~";
			$page_content =  base_common::multi($UserList['UserCount'], $page_url, $params['Page'], $params['PageSize'], 10, $maxpage = 100, $prevWord = '上一页', $nextWord = '下一页');
			foreach($UserList['UserList'] as $UserId => $UserInfo)
			{
				$UserList['UserList'][$UserId]['sex'] = isset($SexList[$UserInfo['sex']])?$SexList[$UserInfo['sex']]:"保密";
			}
			include $this->tpl('Xrace_User_UserList');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	//任务配置列表页面
	public function userListDownloadAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("UserListDownload");
		if($PermissionCheck['return'])
		{
			$SexList = $this->oUser->getSexList();
			$params['Sex'] = isset($SexList[strtoupper(trim($this->request->Sex))])?strtoupper(trim($this->request->Sex)):"";
			$params['Name'] = urldecode(trim($this->request->Name))?urldecode(trim($this->request->Name)):"";
			$params['NickName'] = urldecode(trim($this->request->NickName))?urldecode(trim($this->request->NickName)):"";

			$params['PageSize'] = 500;

			$oExcel = new Third_Excel();
			$FileName= $this->manager->name().'用户列表';
			$oExcel->download($FileName)->addSheet('用户列表');
			//标题栏
			$title = array("用户ID","微信openId","姓名","昵称","性别","出生年月");
			$oExcel->addRows(array($title));
			$Count = 1;$params['Page'] =1;
			do
			{
				$UserList = $this->oUser->getUserLst($params);
				$Count = count($UserList['UserList']);
				foreach($UserList['UserList'] as $UserId => $UserInfo)
				{
					//生成单行数据
					$t = array();
					$t['user_id'] = $UserInfo['user_id'];
					$t['open_wx_id'] = $UserInfo['wx_open_id'];
					$t['open_wx_id'] = $UserInfo['wx_open_id'];
					$t['name'] = $UserInfo['name'];
					$t['nick_name'] = $UserInfo['nick_name'];
					$t['sex'] = isset($SexList[$UserInfo['sex']])?$SexList[$UserInfo['sex']]:"保密";
					$oExcel->addRows(array($t));
					unset($t);
				}
				$params['Page']++;
				$oExcel->closeSheet()->close();
			}
			while($Count>0);
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
}
