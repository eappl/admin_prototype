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
        protected $pageSize = 2;//初始每页显示个数
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
                $UserId = !empty($this->request->UserId)?intval($this->request->UserId):'';
                $Sex = isset($this->request->Sex)?$this->request->Sex:'';
                $AuthState = isset($this->request->AuthState)?$this->request->AuthState:'';
                $SexArr = array('男','女');
                $AuthStateArr = array('未验证','已验证','验证中');
                //获得用户表记录数
                $count = $this->userList->getUserCount($UserId,$Sex,$AuthState);
                //获得当前页码
                $page = intval(max($this->request->page,$this->page));
                //计算偏移值
                $offset = ($page-1)*$this->pageSize;
                //获得当面页的用户列表数据
                $userListArr = $this->userList->getUserList($UserId,$Sex,$AuthState,$offset,$this->pageSize);
                //生成分页的显示HTML
                $page_url = Base_Common::getUrl('','xrace/user.list','index')."&page=~page~&UserId=".$UserId."&Sex=".$Sex."&AuthState=".$AuthState;
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
	public function userListAddAction()
	{
            //检查权限
            $PermissionCheck = $this->manager->checkMenuPermission("UserListInsert");
            if($PermissionCheck['return'])
            {
                $SexArr = array('男','女');
                $AuthStateArr = array('未验证','已验证','验证中');
                include $this->tpl('Xrace_User_UserListAdd');
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
            $PermissionCheck = $this->manager->checkMenuPermission("UserListInsert");
            if($PermissionCheck['return'])
            {
                $bind=$this->request->from('user_id','nick_name','pwd','confirm_pwd','sex','auth_state');
		if(trim($bind['user_id'])=="")
		{
                    $response = array('errno' => 1);
		}
		elseif(trim($bind['nick_name'])=="")
		{
                    $response = array('errno' => 3);
		}
                elseif (trim($bind['pwd'])=="" || trim($bind['confirm_pwd'])=="") {
                    $response = array('errno' => 5);
                }
                elseif (trim($bind['pwd'])!=trim($bind['confirm_pwd'])) {
                    $response = array('errno' => 7);                
                }
		else
		{
                    unset($bind['confirm_pwd']);
                    $res = $this->userList->insertUser($bind);
                    $response = $res ? array('errno' => 0) : array('errno' => 9);
		}
		echo json_encode($response);
		return true;
            }
            else
            {
                $home = $this->sign;
                include $this->tpl('403');
            }
	}
        
        //修改用户界面
        public function userListModifyAction() {
            //检查权限
            $PermissionCheck = $this->manager->checkMenuPermission("UserListUpdate");
            if($PermissionCheck['return'])
            {
                $SexArr = array('男','女');
                $AuthStateArr = array('未验证','已验证','验证中');
                $UserId = $this->request->user_id;
                $User = $this->userList->getUser($UserId);
                include $this->tpl('Xrace_User_UserListModify');
            }
            else
            {
                $home = $this->sign;
                include $this->tpl('403');
            }

            
        }
        
        //修改用户操作
        public function userListUpdateAction() {
            //检查权限
            $PermissionCheck = $this->manager->checkMenuPermission("UserListUpdate");
            if($PermissionCheck['return'])
            {
                $bind=$this->request->from('user_id','nick_name','pwd','sex','auth_state');
		if(trim($bind['nick_name'])=="")
		{
                    $response = array('errno' => 1);
		}
		elseif(trim($bind['pwd'])=="")
		{
                    $response = array('errno' => 3);
		}
		else
		{
                    $res = $this->userList->updateUser($bind);
                    $response = $res ? array('errno' => 0) : array('errno' => 9);
		}
                echo json_encode($response);
		return true;
            }
            else
            {
                $home = $this->sign;
                include $this->tpl('403');
            }
            
        }    
        
        
        
        //删除用户操作
        public function userListDeleteAction() {
            //检查权限
            $PermissionCheck = $this->manager->checkMenuPermission("UserListUpdate");
            if($PermissionCheck['return'])
            {
                $UserId = $this->request->user_id;
                $this->userList->deleteUser($UserId);
                $this->response->goBack();
            }
            else {
                $home = $this->sign;
                include $this->tpl('403');
            }		
        }

}
