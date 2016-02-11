<?php
/**
 * FaqType配置管理
 * @author Chen<cxd032404@hotmail.com>
 * $Id: Type.php 15195 2014-07-23 07:18:26Z 334746 $
 */

class Config_Faq_Type extends Base_Widget
{
	/**
	 * FaqType表名
	 * @var string
	 */
	protected $table = 'faq_type';

	/**
	 * 获取单条记录
	 * @param integer $FaqTypeId
	 * @param string $fields
	 * @return array
	 */
	public function getRow($FaqTypeId,$field = '*')
	{
		$FaqTypeId = trim($FaqTypeId);

		return $this->db->selectRow($this->getDbTable(), $field, '`FaqTypeId` = ?', $FaqTypeId);
	}

	/**
	 * 获取单个字段
	 * @param integer $FaqTypeId
	 * @param string $field
	 * @return string
	 */
	public function getOne($FaqTypeId,$field)
	{
		$FaqTypeId = trim($FaqTypeId);

		return $this->db->selectOne($this->getDbTable(), $field, '`FaqTypeId` = ?', $FaqTypeId);
	}

	/**
	 * 插入
	 * @param array $bind
	 * @return boolean
	 */
	public function insert(array $bind)
	{
		return $this->db->insert($this->getDbTable(), $bind);
	}

	/**
	 * 删除
	 * @param integer $FaqTypeId
	 * @return boolean
	 */
	public function delete($FaqTypeId)
	{
		$FaqTypeId = trim($FaqTypeId);

		return $this->db->delete($this->getDbTable(),'`FaqTypeId` = ?', $FaqTypeId);
	}

	/**
	 * 更新
	 * @param integer $FaqTypeId
	 * @param array $bind
	 * @return boolean
	 */
	public function update($FaqTypeId, array $bind)
	{
		$FaqTypeId = trim($FaqTypeId);

		return $this->db->update($this->getDbTable(), $bind, '`FaqTypeId` = ? ', $FaqTypeId);
	}

	public function getAll($fields = "*")
	{

			$sql = "SELECT $fields FROM " . $this->getDbTable() . " ORDER BY FaqTypeId ASC";
			$return = $this->db->getAll($sql);		

		if(count($return))
		{
			foreach($return as $key => $value)
			{
				$AllFaqType[trim($value['FaqTypeId'])] = $value;	
			}	
		}
		return $AllFaqType;
	}

}
