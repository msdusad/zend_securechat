<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UserFamilyDetailsTable 
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
	//this one table is created for Education Details
	public function eduDetails(){
	   $edu_sql="SELECT * from edu";
	}
	public function getfamily($id)
	{
		$resultSet = $this->tableGateway->select(array('user_id'=>$id,'personType'=>'servived'));
		return $resultSet;		
	}
	
	public function getfamilyPre($id)
	{
		$resultSet = $this->tableGateway->select(array('user_id'=>$id,'personType'=>'predeceased'));
		return $resultSet;		
	}
	
	public function get_current_value($id){
	$resultSet = $this->tableGateway->select(array('user_id'=>$id));
		return $resultSet->current();
	}
	
	public function insertFamilyDetails($user_id,$user_values)
	 {	    
	    foreach($user_values as $key => $user_value)
        {   
        			$tkey=explode("-",$key);
        			if($tkey[0]=='serv') {
         		         $data = array(
    					'user_id'	   => $user_id,
                  'type'	   => $user_value["type"],
    					'first_name'   =>  $user_value["first_name"],
    					'last_name'         =>  $user_value["last_name"],
    					'middle_name'      =>  $user_value["middle_name"],
    					'personType' => 'servived'
    					);
    				}else {
						$data = array(
    					'user_id'	   => $user_id,
                  'type'	   => $user_value["type"],
    					'first_name'   =>  $user_value["first_name"],
    					'last_name'         =>  $user_value["last_name"],
    					'middle_name'      =>  $user_value["middle_name"],
    					'personType' => 'predeceased'
    					); 				
    				}
    		$result = $this->tableGateway->insert($data); 
				
        }
     return true;
    }
	
	public function update($user_id,$user_values)
	{
	     /* print "<pre>";
	      echo $user_id;
		  print_r($user_values);die;*/
		 $result = $this->tableGateway->delete(array('user_id'=>$user_id));
		 
		foreach($user_values as $key => $user_value)
        {   
        			$tkey=explode("-",$key);
        			if($tkey[0]=='serv') {
         		$data = array(
    					'user_id'	   => $user_id,
                  'type'	   => $user_value["type"],
    					'first_name'   =>  $user_value["first_name"],
    					'last_name'         =>  $user_value["last_name"],
    					'middle_name'      =>  $user_value["middle_name"],
						
    					'personType' => 'servived'
    					);
    				}else {
						$data = array(
    					'user_id'	   => $user_id,
                  'type'	   => $user_value["type"],
    					'first_name'   =>  $user_value["first_name"],
    					'last_name'         =>  $user_value["last_name"],
    					'middle_name'      =>  $user_value["middle_name"],
    					'personType' => 'predeceased'
    					); 				
    				}
    		$result = $this->tableGateway->insert($data); 
				
        }
		return true;	
	}
}