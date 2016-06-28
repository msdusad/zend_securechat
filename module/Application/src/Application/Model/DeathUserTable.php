<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class DeathUserTable 
{
    protected $tableGateway;
    protected $adapter;
		
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
     public function saveDeathUser(array $user) 
	  { 
           
         $result = $this->tableGateway->insert($user); 
			$lastinsert_id = $this->tableGateway->lastInsertValue;
			$lastinsert = $this->tableGateway->select(array('deathuser_id'=>$lastinsert_id));
         return $lastinsert_id;
    }
	public function all_info($user_id){
		
		$rowset = $this->tableGateway->select(array('id'=>$user_id));
		$row = $rowset->current();
		
		  return $row;
	}
    public function getDeathUsers($user_id)
    {
        $id = (int) $user_id;
        $rowset = $this->tableGateway->select(array('id' => $user_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $user_id");
        }
        return $row;     
    }
	
	
	public function  fetch()
	{
		$resultSet = $this->tableGateway->select();
         return $resultSet;
	}
	public function  fetchUser($user_id)
	{
		$resultSet = $this->tableGateway->select(array('user_id'=>$user_id));
         return $resultSet;
	}
	
	public function getUser($token)
	{
		$resultSet = $this->tableGateway->select(array('steptoken'=>$token,'deleted'=>0));
		return  $resultSet->current();
	}
	public function getDeathUserDetails($id)
    {
       $resultSet = $this->tableGateway->select(array('deathuser_id'=>$id));
       return  $resultSet->current();
    }
	// only this
	public function updateDeathUser($data,$user_id)
    {
		//print_r($user_id); exit;
       $resultSet = $this->tableGateway->update($data,array('deathuser_id'=>$user_id));
       return true; 
    }
    
	public function deleteDeathUser($deathUser_id,$user_id)
    {
       return $this->tableGateway->delete(array('user_id'=>$user_id,'deathUser_id'=>$deathUser_id)); 
	   
    }
	public function updateSubmit($id)
	{
		$data = array('submit'=>'save');
		$resultSet = $this->tableGateway->update($data,array('deathuser_id'=>$id));
       return $resultSet; 
	}
	
	
	// add this function on userTributesTable
	/*public function gettributes($id)
	{
		$sql = new Sql($this->adapter);
        $subselect2 =   $sql->select()
                            ->from('user_tributes')
							->where(array('user_id'=>$id));
       	                    
		$statement = $sql->prepareStatementForSqlObject($subselect2);
        
        return $statement->execute();		
	}
	// add this function on userFamilyDetailsTable
    public function getfamily($id)
	{
	
		$sql = new Sql($this->adapter);
        $subselect2 =   $sql->select()
                            ->from('user_family_details')
							->where(array('user_id'=>$id));       	                    
		$statement = $sql->prepareStatementForSqlObject($subselect2);        
        return $statement->execute();		
	}
	*/
	
	
	
}
