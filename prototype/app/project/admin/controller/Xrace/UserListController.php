<?php
/**
 * 用户管理
 * @author  mars
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
	//用户列表
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
        
        //添加用户界面
	public function userlistAddAction()
	{
            //检查权限
            $PermissionCheck = $this->manager->checkMenuPermission("RaceGroupInsert");
            if($PermissionCheck['return'])
            {
                    $RaceCatalogArr  = $this->oRaceGroup->getAllRaceCatalogList();
                    include $this->tpl('Xrace_Race_RaceGroupAdd');
            }
            else
            {
                    $home = $this->sign;
                    include $this->tpl('403');
            }
	}
	
	//数据库插入用户操作
	public function userListInsertAction()
	{
            //检查权限
            $bind=$this->request->from('RaceGroupName','RaceCatalogId');
            $RaceCatalogArr  = $this->oRaceGroup->getAllRaceCatalogList();
            if(trim($bind['RaceGroupName'])=="")
            {
                    $response = array('errno' => 1);
            }
            elseif(!isset($RaceCatalogArr[$bind['RaceCatalogId']]))
            {
                    $response = array('errno' => 3);
            }
            else
            {
                    $res = $this->oRaceGroup->insertRaceGroup($bind);
                    $response = $res ? array('errno' => 0) : array('errno' => 9);
            }
            echo json_encode($response);
            return true;
	}

}
