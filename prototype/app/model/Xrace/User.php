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
        
        public function getUserCount($UserId,$Sex,$AuthState,$fields = "*"){
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
            $table_to_process = Base_Widget::getDbTable($this->table);
            $sql = "SELECT $fields FROM " . $table_to_process."  where 1 ".$where;
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
	public function getUserList($UserId,$Sex,$AuthState,$offset,$pageSize,$fields = "*")
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
        
        /**
	 * 获取单条记录
	 * @param integer $UserId
	 * @param string $fields
	 * @return array
	 */
	public function getUser($UserId, $fields = '*')
	{
            $UserId = intval($UserId);
            $table_to_process = Base_Widget::getDbTable($this->table);
            return $this->db->selectRow($table_to_process, $fields, '`user_id` = ?', $UserId);
	}
        
	/**
	 * 插入
	 * @param array $bind
	 * @return boolean
	 */        
        public function insertUser(array $bind) {
            $table_to_process = Base_Widget::getDbTable($this->$table);
            return $this->db->insert($table_to_process, $bind);
        }
        
	/**
	 * 修改
	 * @param array $bind
	 * @return boolean
	 */          
        public function updateUser(array $bind) {
            $table_to_process = Base_Widget::getDbTable($this->$table);
            return $this->db->update($table_to_process, $bind, '`user_id` = ?', $bind['user_id']);           
        }
        
        /**
	 * 删除
	 * @param integer $UserId
	 * @return boolean
	 */
	public function deleteUser($UserId)
	{
            $UserId = intval($UserId);
            $table_to_process = Base_Widget::getDbTable($this->$table);
            return $this->db->delete($table_to_process, '`user_id` = ?', $UserId);
	}
        
        
        
        

}
