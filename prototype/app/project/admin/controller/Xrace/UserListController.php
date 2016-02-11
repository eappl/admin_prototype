<?php
/**
 * 用户管理
 * @author  mars
 */

class Xrace_UserListController extends AbstractController
{
	/**用户列表:UserList
	 * 权限限制  ?ctl=xrace/user.list
	 * @var string
	 */
	protected $sign = '?ctl=xrace/user.list';
	/**
	 * user对象
	 * @var object
	 */
	protected $userList;

	/**
	 * 初始化
	 * (non-PHPdoc)
	 * @see AbstractController#init()
	 */
        protected $page = 1;//初始页码
        protected $pageSize = 10;//初始每页显示个数
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
                $UserId = isset($this->request->UserId)?intval($this->request->UserId):'';
                $Sex = isset($this->request->Sex)?$this->request->Sex:'';
                $AuthState = isset($this->request->AuthState)?$this->request->AuthState:'';
                $SexArr = array('男','女');
                $AuthStateArr = array('未验证','已验证','验证中');
                //获得用户表记录数
                $count = $this->userList->getUserCount();
                //获得当前页码
                $page = intval(max($this->request->page,$this->page));
                //计算偏移值
                $offset = ($page-1)*$this->pageSize;
                //获得当面页的用户列表数据
                $userListArr = $this->userList->getUserList('*',$UserId,$Sex,$AuthState,$offset,$this->pageSize);
                //生成分页的显示HTML
                $page_url = Base_Common::getUrl('','xrace/user.list','index')."&page=~page~";
                $page_content = Base_Common::multi($count, $page_url, $page, $this->pageSize, 10, $maxpage = 100, $prevWord = '上一页', $nextWord = '下一页' ,$style = 'style="float:right"');
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
	
	//添加用户操作
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
        
        //修改用户界面
        public function userListModifyAction() {
            
        }
        
        //修改用户操作
        public function userListUpdateAction() {
            
        }    
        
        
        
        //删除用户操作
        public function userListDeleteAction() {
            
        }

}
