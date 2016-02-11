<?php
/**
 * 
 * @author 
 */


class Xrace_User extends Base_Widget
{
	//声明所用到的表
	protected $table = 'user_profile';
        
        /**
         * 查询用户表总条数
         * 
         */
        
        public function getUserCount($fields = "*"){
            $table_to_process = Base_Widget::getDbTable($this->table);
            $sql = "SELECT $fields FROM " . $table_to_process;
            $count = $this->db->query($sql);
            return $count;
        }
        
        /**
         * 
	 * 查询全部
         * @param string $查询字段
         * @param type $name Description
         * @param $pageSize
         * @param $isLimit
         * @param $fields
	 * @return array
	 */
	public function getUserList($fields = "*",$UserId,$Sex,$AuthState,$offset,$pageSize)
	{
            $UserId = trim($UserId);
            $Sex = trim($Sex);
            $AuthState = trim($AuthState);
            //初始化查询条件
            $whereUserId = ($UserId)?" user_id = $UserId":"";
            $whereSex = ($Sex)?" sex = '$Sex'":"";
            $whereAuthState = ($AuthState)?" auth_state = '$AuthState'":"";
            $whereCondition = array($whereUserId,$whereSex,$whereAuthState);
            //生成条件列
            $where = Base_common::getSqlWhere($whereCondition);
            $limit = " limit $offset,$pageSize";
            $table_to_process = Base_Widget::getDbTable($this->table);
            $sql = "SELECT $fields FROM " . $table_to_process."  where 1 ".$where." ORDER BY user_id ASC " . $limit;
            $UserList = $this->db->getAll($sql);
            return $UserList;
	}

}
