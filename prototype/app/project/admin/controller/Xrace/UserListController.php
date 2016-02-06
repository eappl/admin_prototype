<?php
/**
 * 任务管理
 * @author Chen<cxd032404@hotmail.com>
 * $Id: LotoController.php 15195 2014-07-23 07:18:26Z 334746 $
 */

class Xrace_UserListController extends AbstractController
{
	/**运动类型列表:SportsTypeList
	 * 权限限制  ?ctl=xrace/sports&ac=sports.type
	 * @var string
	 */
	protected $sign = '?ctl=xrace/user.list';
	/**
	 * game对象
	 * @var object
	 */
	protected $userList;

	/**
	 * 初始化
	 * (non-PHPdoc)
	 * @see AbstractController#init()
	 */
        protected $page = 1;
        protected $pageSize = 100;
        public function init()
	{
		parent::init();
		$this->userList = new Xrace_User();

	}
	//任务配置列表页面
	public function indexAction()
	{
		//检查权限
		$PermissionCheck = $this->manager->checkMenuPermission(0);
		if($PermissionCheck['return'])
		{
                        $page = intval(max($this->request->page,$this->page));
                        $offset = ($page-1)*$this->pageSize;
			$userListArr = $this->userList->getUserList($offset,$this->pageSize);
                        $page_url = Base_Common::getUrl('','xrace/user.list','index')."&page=~page~";
                        $page_content = Base_Common::multi(count($userListArr), $page_url, $page, $this->pageSize, 10, $maxpage = 100, $prevWord = '上一页', $nextWord = '下一页');
			include $this->tpl('Xrace_User_UserList');
		}
		else
		{
			$home = $this->sign;
			include $this->tpl('403');
		}
	}

}
