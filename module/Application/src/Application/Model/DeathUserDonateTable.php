<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class DeathUserDonateTable 
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
	public function saveDonate($user,$userid){
		// print_r($user); exit;
		$result = $this->tableGateway->insert(array('deathuser_id'=>$userid,'category'=>$user['category1'],'currencytype'=>$user['currency'],'amount'=>$user['amount'],'securitycard'=>$user['securitynum'],'created_at'=>date('Y-m-d H:i:s'))) ; 
	}
}
