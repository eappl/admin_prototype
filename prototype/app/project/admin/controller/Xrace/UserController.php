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
	//用户列表
	public function indexAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission(0);
		if($PermissionCheck['return'])
		{
			$SexList = $this->oUser->getSexList();
			$AuthStatusList = $this->oUser->getAuthStatus();
			$params['Sex'] = isset($SexList[strtoupper(trim($this->request->Sex))])?substr(strtoupper(trim($this->request->Sex)),0,8):"";
			$params['Name'] = urldecode(trim($this->request->Name))?substr(urldecode(trim($this->request->Name)),0,8):"";
			$params['NickName'] = urldecode(trim($this->request->NickName))?substr(urldecode(trim($this->request->NickName)),0,8):"";
			$params['AuthStatus'] = isset($AuthStatusList[strtoupper(trim($this->request->AuthStatus))])?substr(strtoupper(trim($this->request->AuthStatus)),0,8):"";

			$params['Page'] = abs(intval($this->request->Page))?abs(intval($this->request->Page)):1;
			$params['PageSize'] = 5;
			$params['getCount'] = 1;
			$UserList = $this->oUser->getUserLst($params);
			//导出EXCEL链接
			$export_var = "<a href =".(Base_Common::getUrl('','xrace/user','user.list.download',$params))."><导出表格></a>";
			//翻页参数
			$page_url = Base_Common::getUrl('','xrace/user','index',$params)."&Page=~page~";
			$page_content =  base_common::multi($UserList['UserCount'], $page_url, $params['Page'], $params['PageSize'], 10, $maxpage = 100, $prevWord = '上一页', $nextWord = '下一页');
			foreach($UserList['UserList'] as $UserId => $UserInfo)
			{
				$UserList['UserList'][$UserId]['sex'] = isset($SexList[$UserInfo['sex']])?$SexList[$UserInfo['sex']]:"保密";
				$UserList['UserList'][$UserId]['auth_state'] = isset($AuthStatusList[$UserInfo['auth_state']])?$AuthStatusList[$UserInfo['auth_state']]:"未知";
			}
			include $this->tpl('Xrace_User_UserList');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
	//用户列表下载
	public function userListDownloadAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("UserListDownload");
		if($PermissionCheck['return'])
		{
			$SexList = $this->oUser->getSexList();
			$AuthStatusList = $this->oUser->getAuthStatus();
			$params['Sex'] = isset($SexList[strtoupper(trim($this->request->Sex))])?substr(strtoupper(trim($this->request->Sex)),0,8):"";
			$params['Name'] = urldecode(trim($this->request->Name))?substr(urldecode(trim($this->request->Name)),0,8):"";
			$params['NickName'] = urldecode(trim($this->request->NickName))?substr(urldecode(trim($this->request->NickName)),0,8):"";
			$params['AuthStatus'] = isset($AuthStatusList[strtoupper(trim($this->request->AuthStatus))])?substr(strtoupper(trim($this->request->AuthStatus)),0,8):"";

			$params['PageSize'] = 500;

			$oExcel = new Third_Excel();
			$FileName= ($this->manager->name().'用户列表');
			$oExcel->download($FileName)->addSheet('用户');
			//标题栏
			$title = array("用户ID","微信openId","姓名","昵称","性别","出生年月","实名认证状态");
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
					$t['auth_state'] = isset($AuthStatusList[$UserInfo['auth_state']])?$AuthStatusList[$UserInfo['auth_state']]:"未知";

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
	//用户列表下载
	public function userDetailAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission("UserListDownload");
		if($PermissionCheck['return'])
		{
			$SexList = $this->oUser->getSexList();
			$AuthStatusList = $this->oUser->getAuthStatus();
			$UserId = trim($this->request->UserId);
			$UserInfo = $this->oUser->getUserInfo($UserId);
			$UserInfo['sex'] = isset($SexList[$UserInfo['sex']])?$SexList[$UserInfo['sex']]:"保密";
			$UserInfo['auth_state'] = isset($AuthStatusList[$UserInfo['auth_state']])?$AuthStatusList[$UserInfo['auth_state']]:"未知";
			//$UserInfo['thumb'] = "http://admin.xrace.com/upload/RaceCatalogIcon/79228797gw1em2ejx8xktj20w01kw4fe.jpg";
			//echo urlencode(($UserInfo['thumb'] ));
			include $this->tpl('Xrace_User_UserDetail');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}
}
