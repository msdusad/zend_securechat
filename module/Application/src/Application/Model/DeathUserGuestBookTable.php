<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class DeathUserGuestBookTable 
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
	public function savedeathuserguestbook($v,$user_id)
    {
    	/*$imgtemp=explode(',',$v['offerimg']);
    	$typetemp=explode(',',$v['offertype']);
    	for($i=0;$i<count($imgtemp);$i++) {
    		$result = $this->tableGateway->insert(array('deathuser_id'=>$user_id,'email_id'=>$v['guest_email1'],'description'=>$v['guest_msg1'],'offer_img'=>$imgtemp[$i],'offer_type'=>$typetemp[$i],'created_at'=>date('Y-m-d H:i:s'))); 
		}*/
		$result = $this->tableGateway->insert(array('deathuser_id'=>$user_id,'email_id'=>$v['guest_email1'],'description'=>$v['guest_msg1'],'offer_img'=>$v['offerimg'],'offer_type'=>$v['offertype'],'created_at'=>date('Y-m-d H:i:s')));
       
       return true; 
    }
	public function  getById($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT * FROM deathuser_guestbook WHERE deathuser_id='$user_id' GROUP BY description";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getcandlecount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'light a candle', '') ) ) / LENGTH('light a candle') )) AS cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getdovecount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'release a dove', '') ) ) / LENGTH('release a dove') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  gettreecount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'plant a tree', '') ) ) / LENGTH('plant a tree') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getnamaskarcount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'namaskar', '') ) ) / LENGTH('namaskar') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getdeadcount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'day of the dead', '') ) ) / LENGTH('day of the dead') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getdiyacount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'light a diya', '') ) ) / LENGTH('light a diya') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getwreathcount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'wreath', '') ) ) / LENGTH('wreath') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getthalicount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'thali', '') ) ) / LENGTH('thali') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getomcount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'om', '') ) ) / LENGTH('om') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getcandlescount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'floating candles', '') ) ) / LENGTH('floating candles') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
public function  gethavankundcount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'havan kund', '') ) ) / LENGTH('havan kund') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getashokachakracount($user_id)
	{
		 /*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id));
         return $resultSet;*/
		$sql = "SELECT SUM(ROUND (( LENGTH(offer_type) - LENGTH( REPLACE ( offer_type, 'ashoka chakra', '') ) ) / LENGTH('ashoka chakra') )) as cofferings FROM `deathuser_guestbook` WHERE `deathuser_id`='$user_id'";
//echo $sql;exit;       
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
	}
	public function  getByIdEmail($user_id,$email)
	{
		$sql = "SELECT * FROM deathuser_guestbook WHERE deathuser_id=".$user_id." and email_id='".$email."'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        return $res;
		/*$resultSet = $this->tableGateway->select(array('deathuser_id'=>$user_id,'email_id'=>$email));
      return $resultSet;*/
	}	
	public function getdeathuserguestbook($data) {
		$resultSet = $this->tableGateway->select(array('deathuser_id'=>$data['deathuserid'],'offer_type'=>$data['gtype']));
      $rows = array();
        foreach ($resultSet as $row) {
       		$rows[] = $row;
        }
       return $rows;
	}
}
