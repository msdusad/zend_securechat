<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class DeathUserTributeTable 
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
	public function savedeathusertribute($v,$user_id)
    {
    	//print_r($v['tribure_msg']);exit;
    	
    		$result = $this->tableGateway->insert(array('deathuser_id'=>$user_id,'email_id'=>$v['tribute_email'],'description'=>$v['tribure_msg'],'offer_img'=>$v['tofferimg'],'offer_type'=>$v['toffertype'],'created_at'=>date('Y-m-d H:i:s'))); 
	
       
       return true; 
    }
	public function  getById($user_id)
	{
		//echo $user_id;exit;
		$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;
	}
	public function  getByIdEmail($user_id,$email)
	{
		$sql = "SELECT * FROM deathuser_tributes WHERE deathuser_id=".$user_id." and email_id='".$email."'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
		/*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id,'email_id'=>$email));
         return $resultSet;*/
	}
	public function getdeathusertribute($data) {
		$resultSet = $this->tableGateway->select(array('deathuser_id'=>$data['deathuserid'],'offer_type'=>$data['gtype']));
      $rows = array();
        foreach ($resultSet as $row) {
       		$rows[] = $row;
        }
       return $rows;
	}	
	
}
