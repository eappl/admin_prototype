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
	 * 查询全部
         * @param $start
         * @param $pageSize
         * @param $isLimit
         * @param $fields
	 * @return array
	 */
	public function getUserList($offset,$pageSize,$isLimit = FALSE,$fields = "*")
	{
                $limit = $isLimit ? "" :($pageSize?" limit $offset,$pageSize":"");
		$table_to_process = Base_Widget::getDbTable($this->table);
		$sql = "SELECT $fields FROM " . $table_to_process.$limit;
		$UserList = $this->db->getAll($sql);
		return $UserList;
	}

}
