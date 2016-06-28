<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UserPrivacyTable 
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) 
    {
    	
        $this->tableGateway = $tableGateway;
    }
	
	public function insertPrivacyDetails($user_id,$user_values)
	{	    
	   
		foreach($user_values['user'] as $k=>$v)
        {  
      
    		$data = array(
    					'user_id'	   => $user_id,
                  'visibility_categories'	   => $v,
                  'status'=>$user_values['privacySelect'],
    					
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
	
	public function getprivacy($id)
	{
		
		$resultSet = $this->tableGateway->select(array('user_id'=>$id));
		return $resultSet;		
	}
	
	public function updateDetails($user_id,$user_values)
	{
$this->tableGateway->delete(array('user_id'=>$user_id));
		foreach($user_values['user'] as $k=>$v)
        {  
      
    		$data = array(
    					'user_id'	   => $user_id,
                  'visibility_categories'	   => $v,
                  'status'=>$user_values['privacySelect'],
    					
    					);
    					
    				$result = $this->tableGateway->insert($data);  
    		///$result = $this->tableGateway->update(array('visibility_categories'=>$v,'status'=>$user_values['privacySelect'],array('user_id'=>$user_id)));            
        }
		
		return true;	
	}
}