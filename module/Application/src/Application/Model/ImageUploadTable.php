<?php

namespace Application\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;

class ImageUploadTable 
{
    //protected $table = 'upload';
    
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
	
	
    public function savefile($data)
    {
		$this->tableGateway->insert($data);
		$lastInsertValue = $this->tableGateway->lastInsertValue;
        return $lastInsertValue; 
    }
	public function getById($user_id)
	{
		$id  = (int) $user_id;
        $rowset = $this->tableGateway->select(array('deathuser_id' => $id));
        return $rowset;
	}
	public function getByIdOrderBydate($user_id)
	{
		
		$id  = (int) $user_id;
		//echo $id;exit;
        $rowset = $this->tableGateway->select(function (Select $select) {
     $select->where->like('deathuser_id', $id);
     $select->order('created ASC')->limit(1);
});

//print_r($rowset);exit;        
        return $rowset;
	}
	public function delete($id)
	{
		  $this->tableGateway->delete(array('id' => (int) $id));
		  return true;
	}
	public function updatedeathuser($user_id){
		$id  = (int) $user_id;
$this->tableGateway->update(array('main_image'=>1),array('id'=>$id));	
	}
  
}
