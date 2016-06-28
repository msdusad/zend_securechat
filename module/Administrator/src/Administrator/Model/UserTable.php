<?php
namespace Administrator\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Sql\Where;
use Zend\Db\ResultSet\ResultSet;

class UserTable extends AbstractTableGateway
{
    protected $table = 'user';
    protected $user_linker ='user_role_linker';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
        $this->resultSetPrototype->setObjectPrototype(new User());
        $this->initialize();
        
    }
	
    
	
     // Get All user Information
    // @author developed by Trs Software Solutions
     // @return array
     
	 public function tables()
	 {
		$sql = "SHOW TABLES"; 
		$abc = "SELECT *FROM user_role";
		
		$add = "ALTER TABLE user ADD deleted int(11)NOT NULL DEFAULT 0 " ;
		 
		$del = "ALTER TABLE user DROP deleted ";
		$statement = $this->adapter->query($add); 
		return $statement->execute(); 
		
	 }
	 public function fetch()
	 {
		$sql = new Sql($this->adapter);
		$subselect2 =   $sql->select()
							->from(array('u' =>'user'));
		$statement = $sql->prepareStatementForSqlObject($subselect2);
        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
		return $resultSet->toArray();
		
	 }
    public function fetchAll()
    {
        $sql = new Sql($this->adapter);
		$subselect2 =   $sql->select()
							->from(array('u' =>'user'))
       	                    ->join($this->user_linker, 'user_role_linker.user_id = u.user_id',  	array('role_id'))
                            ->join('user_role', 'user_role.id = user_role_linker.role_id',array('role_name' =>'role_id'))
                            ->where(array('u.deleted'=>0));	
		
		
		$statement = $sql->prepareStatementForSqlObject($subselect2);
        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
		return $resultSet->toArray();
    }

	public function getUser($user_id)
    {
		$sql = new Sql($this->adapter);
		$subselect2 =   $sql->select()
                            ->from(array('u' =>'user'))
                            ->join($this->user_linker, 'user_role_linker.user_id = u.user_id', array('role_id'))
                            ->where(array('u.deleted'=>0,'u.user_id'=>$user_id));
		$statement = $sql->prepareStatementForSqlObject($subselect2);
        $results = $statement->execute();
		
		return $results->current();
	}
	public function changePassword($user_id,$password)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $encpassword = $bcrypt->create($password);
		$data = array('password'=>$encpassword);
        $resultSet=$this->update($data, array('user_id' => $user_id));
        return $resultSet;
    }
		
	public function updateStatus($state,$user_id)
	{
		//$data =array('state'=>$state);
		$data =array('status'=>$state);
		$res = $this->update($data,array('user_id'=>$user_id));
		return $res;
	}
    public function deleteUser($user_id)
    {
        $data =array('deleted'=>1);
		$res = $this->update($data,array('user_id'=>$user_id));
		return $res;
    }
    
    
    
    // unused 
	
	
	
	public function updateResetpasswordToken($token,$email)
    {
        
        $data = array('passtoken'=>$token,'passexpiry'=>time());
        $res =$this->update($data,array('email'=>$email));
        return $res;
    }


    
   
     // Vaidate Token exist or not  in our user table in database
     // @token  rendom string
     // @return  user Email ID/False;
     // 
     //
    public function validateToken($token)
    {
        $res = $this->select(array('passtoken'=>$token));
        if($res)
        {
           $resultset = $res->current();
		   $data =array('email'=>$resultset->email,'passexpiry'=>$resultset->passexpiry);
           return $data;
        }else{
          return false;  
        }
        
       
    }
	// Update the user's password
    public function updatePassword($email,$password)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $password = $bcrypt->create($password);
        $data = array('password'=> $password,'passtoken'=>'','passexpiry'=>'');
        $this->update($data, array('email' =>$email));
    }
	
	
	
	public function getTokenMatch($token)
    {
        $sql = new Sql($this->adapter);
		$select =   $sql->select()
                            ->from('user_token')                            
                            ->where(array('token'=>$token));					
		$statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
		return $results->current();
	} 	
   public function getUserByEmailId($user_email)
    {
        $sql = new Sql($this->adapter);
		$select =   $sql->select()
                            ->from('user')                            
                            ->where(array('email'=>$user_email));
		//echo $select->getSqlString();exit;					
		$statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
		return $results->current();
	} 
	
	public function getActiveUsers()
    {
        $sql = new Sql($this->adapter);		
		$select =   $sql->select()
		                    ->columns(array('email','state'))
                            ->from('user')  
							->join('user_plan','user_plan.user_id = user.user_id',array('plan_id'),'inner')
							->where(array('state'=>0,'user_plan.plan_id'=>12));
					
		$statement = $sql->prepareStatementForSqlObject($select);		
	    $results = $statement->execute();
	    return $results;
	}
	
    public function updateUserStateByEmail($email)
    {       
        $data = array('state'=>0);
        $this->update($data, array('email' =>$email));
    }
	
}
?>