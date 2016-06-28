<?php 
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class DeathUserMetaTable 
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
	public function getDeathUserMEtaDetails($user_id)
    {  
	 return $this->tableGateway->select(array('deathuser_id'=>$user_id));  
	
	// $lastinsert_id = $this->tableGateway->lastInsertValue;
	// $lastinsert = $this->tableGateway->select(array('deathuser_id'=>$lastinsert_id));
     
    }
	
public function saveMeta(array $user,$user_id)
 {
 	$email="";
	 foreach($user as $metaKey=>$metaVal)
       {
       	
       	//echo $metaKey.'  '.$metaVal;exit;
	$metaVal1[]= $metaKey;
    if($metaKey =='school' or $metaKey =='college' or $metaKey ==      'others' or $metaKey =='university'){
         $result =$this->tableGateway->select(array('meta_key'=>$metaKey,'deathuser_id'=>$user_id));
         $count =$result->current();
	if(!empty($count)){
         $meta_value_imp = implode(',',$metaVal);
         $this->tableGateway->update(array('meta_key'=>$metaKey,'meta_value'=>$meta_value_imp),array('deathuser_id'=>$user_id));
	}else{
		$meta_value_imp = implode(',',$metaVal);
        $this->tableGateway->insert(array('deathuser_id'=>$user_id,'meta_key'=>$metaKey,'meta_value'=>$meta_value_imp));
        }
		}else{
	         $result =$this->tableGateway->select(array('meta_key'=>$metaKey,'deathuser_id'=>$user_id));
             $count =$result->current();
	   if(!empty($count)){
	   	    
           // $this->tableGateway->update(array('meta_key'=>$metaKey,'meta_value'=>$metaVal),array('deathuser_id'=>$user_id));
				$this->tableGateway->insert(array('deathuser_id'=>$user_id,'meta_key'=>$metaKey,'meta_value'=>$metaVal));		
		}else{
			//print_r($metaKey); exit;
            $this->tableGateway->insert(array('deathuser_id'=>$user_id,'meta_key'=>$metaKey,'meta_value'=>$metaVal));
          }
	  }
	   
	}
				 
       return true;;  
    }

}	
?>