<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class DeathUserLanguageTextTable 
{
    protected $tableGateway;
    protected $adapter;
    protected $lsize;
	
	
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
	public function  fetch()
	{
		$resultSet = $this->tableGateway->select();
         return $resultSet;
	}
	public function savedeathuserforlang($data,$user_id)
    {
     	 $result = $this->tableGateway->insert(array('deathuser_id'=>$user_id,'langtext'=>$data)); 
		 return true; 
    }
    public function updatedeathuserforlang($data,$id) {
    	$sql = "update death_user_lang set langtext='".$data."' where id=".$id;
    	//echo $sql;exit;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		
    	
	//	$resultSet = $this->tableGateway->update(array('langtext'=>$data),array('id'=>$id));
	
      return true;   
    }
	public function  getById($user_id)
	{
		//echo $user_id;exit;
		$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;
	}	
	
}
