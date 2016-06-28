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

class UploadTable extends AbstractTableGateway
{
    protected $table = 'upload';
   
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
        $this->resultSetPrototype->setObjectPrototype(new User());
        $this->initialize();
        
    }
	
	public function create()
	{
		//$add = "ALTER TABLE upload ADD deleted int(11)NOT NULL DEFAULT 0  " ;
		//$statement = $this->adapter->query($add); 
		//return $statement->execute();
// $addqw =  "ALTER TABLE user_family_details ADD type1 text(200)NOT NULL";
// $addqw = "ALTER TABLE user_family_details DROP COLUMN first_name1";
//$table = "SHOW TABLES";
//$strcture =  "SHOW COLUMNS FROM user_family_details";
//$strcture =  "select * FROM user_family_details where user_id='42'";
//$strcture =  "select * FROM death_user";
//$del = "DELETE FROM user_family_details WHERE user_id = '42' ";
//$statement = $this->adapter->query($strcture); 
//return $statement->execute();
	
/* $university = "CREATE TABLE IF NOT EXISTS `universitydetails` (
  `u_id` int(21) NOT NULL AUTO_INCREMENT,
  `universityname` varchar(234) NOT NULL,
  `cityname` varchar(234) NOT NULL,
  `countryname` varchar(234) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ";
  */  
  
  /*  $other_insert = "INSERT INTO `othertable` (`other_id`, `othername`, `cityname`, `countryname`, `created`) VALUES
(1, 'Jnu University', 'Dehli', 'India', '2015-05-30 08:06:22'),
(2, 'Jnu University', 'Dehli', 'India', '2015-05-30 08:06:26')"; */
  
 /*  $other = "CREATE TABLE IF NOT EXISTS `othertable` (
  `other_id` int(21) NOT NULL AUTO_INCREMENT,
  `othername` varchar(234) NOT NULL,
  `cityname` varchar(234) NOT NULL,
  `countryname` varchar(241) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`other_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3" ;
  */
   
    // $str ="SELECT * FROM user_tributes";
    // $strcture =  "SHOW COLUMNS FROM user_tributes";
    // $strcture =  "SHOW COLUMNS FROM collegename";
   // $strcture =  "SHOW COLUMNS FROM universitydetails";
    // $strcture =  "SHOW COLUMNS FROM user_family_details";
    
    // $addcolumn ="ALTER TABLE `user_tributes` ADD  COLUMN `email1` VARCHAR( 123 ),ADD  COLUMN `password1` VARCHAR( 123 ),ADD  COLUMN `message1` VARCHAR( 123 ), ADD  COLUMN `offerings1` VARCHAR( 123 ) ";
// $addqw =  "ALTER TABLE user_family_details ADD type1 text(200)NOT NULL";
      // $statement = $this->adapter->query($strcture); 
		// return $statement->execute();
	}
	
	
	public function fetchByAdmin($userid)
	{
		$sql = new Sql($this->adapter);
		$select = $sql->select()
					  ->from('upload')
					  ->where(array('user_id'=>$userid,'identity'=>'admin','deleted'=>'0'));
					  
		$statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
		return $resultSet->toArray();
	}
	
	public function savefile($data)
    {
		$sql = new Sql($this->adapter);
		 $insert = $sql->insert($this->table);
         $insert->values($data);
         $selectString1 = $sql->getSqlStringForSqlObject($insert);
         $statement1 = $this->adapter->query($selectString1);
         $result = $statement1->execute();  
         return true;
    }
	
	public function deleteById($id)
    {
		$sql = new Sql($this->adapter);
		$data = array('deleted'=>1);
		 $update=$sql->update($data);
            $update->table($this->table);
            $update->set($data);
            $update->where(array('id'=>$id));
            $selectString= $sql->getSqlStringForSqlObject($update);
            $statement = $this->adapter->query($selectString);
            $result = $statement->execute();
			
            return true;
	}
	
	public function getFile($id)
	{
		$sql = new Sql($this->adapter);
		$select = $sql->select()
					  ->from('upload')
					  ->where(array('id'=>$id));
					  
		$statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
		return $resultSet->current();
		
	}
	public function updateStatus($status,$file_id)
	{
		$data = array('status'=>$status);
		
		$this->update($data,array('id'=>$file_id));
		return true;
	}
	
}
?>





