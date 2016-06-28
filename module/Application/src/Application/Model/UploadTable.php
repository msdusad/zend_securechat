<?php

namespace Application\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;

class UploadTable 
{
    //protected $table = 'upload';
    
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
	public function fetch()
    {
		$resultSet = $this->tableGateway->select();
         return $resultSet;
    }
   public function insert_obituaries($data){
   
      $this->tableGateway->insert($data);
		// $lastInsertValue = $this->tableGateway->lastInsertValue;
    return $lastInsertValue; 
   }
	public function getById($user_id)
	{
		$id  = (int) $user_id;
        $rowset = $this->tableGateway->select(array('user_id' => $id,'identity' => 'user','status'=>'1'));
        return $rowset;
	}
	public function delete($user_id,$id)
	{
		  $this->tableGateway->delete(array('id' => (int) $id,'user_id'=>$user_id));
		  return true;
	}
	
    public function savefile($data)
    {
		$this->tableGateway->insert($data);
		$lastInsertValue = $this->tableGateway->lastInsertValue;
        return $lastInsertValue; 
    }
	public function fetchAll()
	{
		$result = $this->tableGateway->select(array('identity' => 'user','status'=>'1'));
		return $result;
	}
	public function getName($id)
	{
		$id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
		
        return $row;
	}
	public function query()
	{
		//$tran = "TRUNCATE TABLE upload";
		//$result = $this->tableGateway->query($tran);
		//print_r(result);die;
		return $result->execute(); 
	}
	
	 public function deleteaa($id)
    {
        if($this->getUser($id)){
         $data = array('status'=>1);
         $this->tableGateway->update($data, array('user_id' => $id));

        }
    }
	
	/** fetch results for third window according to docs
	*  return only those result that uploaded by admin
	*
	*/
	public function fetchFromAdmin()
    {
		$rowset = $this->tableGateway->select(array('identity' => 'admin','status'=>'1','deleted'=>'0'));
		return $rowset;
	}
	
	
	
	/*public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }*/
		/*$sql = new Sql($this->adapter);
		$select = $sql->select()
					  ->from($this->table)
					  ->where('id='.$id);
		
		$statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute()->current();
		return $results;*/
	
	
  
}

/*
namespace Application\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;

class UploadTable extends AbstractTableGateway
{
    protected $table = 'upload';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();  
    } 
	public function create()
	{
			$string =  "CREATE TABLE IF NOT EXISTS `upload` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`file_name` varchar(250) NOT NULL,
			`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
			
			//$add = "ALTER TABLE upload ADD name VARCHAR(250)";
			//$ext = "ALTER TABLE upload ADD extension VARCHAR(250)";
			//$tran = "TRUNCATE TABLE upload";
			$result = $this->adapter->query($tran);
			return $result->execute(); 
	}
	
    public function savefile($data)
    {
		
       $this->insert($data);
	   return true;
    }
	public function fetchAll()
	{
		$sql = new Sql($this->adapter);
		$select = $sql->select()
					  ->from($this->table);
		
		$statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
		return $results;
		
	}
	
	public function getName($id)
	{
		$sql = new Sql($this->adapter);
		$select = $sql->select()
					  ->from($this->table)
					  ->where('id='.$id);
		
		$statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute()->current();
		return $results;
	}
	
    
    
}
*/
