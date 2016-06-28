<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UserTributesTable 
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
	
	public function insertTributesDetails($user_id,$user_values)
	{	    
	   
		foreach($user_values as $user_value)
        {  
    		$data = array(
    					'user_id'	=> $user_id,
                 		'email'	   => $user_value["email"],
    					'password'  => $user_value["password"],
    					'message'   => $user_value["message"],
    					'offerings' => $user_value["offerings"],
						'email1'	   => $user_value["email1"],
						'password1' => $user_value["password1"],
						'message1'  => $user_value["message1"],
						'offerings1'=> $user_value["offerings1"]
    					);
    		$result = $this->tableGateway->insert($data);            
        }
		return true;
    }
	
	
	public function saveMetaData($user,$user_id)
    {
	   foreach($user as $metaKey=>$metaVal)
       {
          $result =$this->tableGateway->select(array('meta_key'=>$metaKey,'user_id'=>$user_id));
          $count =$result->current();
		  if(!empty($count)){
            $this->tableGateway->update(array('meta_key'=>$metaKey,'meta_value'=>$metaVal),array('user_id'=>$user_id));
		  }else{
            $this->tableGateway->insert(array('user_id'=>$user_id,'meta_key'=>$metaKey,'meta_value'=>$metaVal));
          }
       }
       return true;;  
    }
	
	public function gettributes($id)
	{
		$resultSet = $this->tableGateway->select(array('user_id'=>$id));
		return $resultSet;		
	}
	
	public function updateDetails($user_id,$data)
	{
		
		foreach($data as $datak=>$datav)
		{
	   $value = $datav;
	   $result = $this->tableGateway->update($value,array('user_id'=>$user_id,));
		}
		return true;	
	}
}