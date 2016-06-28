<?php
namespace Administrator\Model;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Update;

class RoleTable extends AbstractTableGateway
{

    public $table = 'user_role';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function fetchAll()
    {
        $sql = new Sql($this->adapter);
		$select =   $sql->select()
						->from($this->table);
       	$where = new Where();
        //$where->notEqualTo('id',1);
        //$select->where($where);				
		$statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
		return $resultSet->toArray();
    }
    
    public function changeRole($user_id,$role_id)
    {
	
        $sql = new Sql($this->adapter);
        $update = $sql->update();
        $update->table('user_role_linker');
        $update->set(array('role_id'=>$role_id));
        $update->where(array('user_id' => $user_id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        return $result; 
    }
    
}
