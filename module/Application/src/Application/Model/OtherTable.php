<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class OtherTable{
	
	protected $tableGateway;
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
	
     	
	public function other_name_all(){
	
	$select = $this->tableGateway->select();
	return $select;
	}
	

	
}