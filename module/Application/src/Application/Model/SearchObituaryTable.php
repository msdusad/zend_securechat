<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class SearchObituaryTable 
{
    protected $tableGateway;
    protected $adapter;
	
	
    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }
  
	//get initial obituary details of first 6 records like image and name
	public function  getfetch()
	{
		$sql = "SELECT id, obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,gender FROM obituary_infos obinfo where obinfo.special_mention_id=0 limit 6";
//echo $sql;exit;       
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
		
	}
	public function  getAllObituaries()
	{
		$sql = "SELECT id,obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,gender FROM obituary_infos obinfo where obinfo.special_mention_id=0 limit 30";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
	}
public function  getAllMemorials()
	{
		$sql = "SELECT id,memoralize_id, (select mi.image_url from memoralize_image mi where mi.memoralize_id=minfo.memoralize_id and mi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,gender FROM memoralize_infos minfo where minfo.special_mention_id=0 limit 30";
       
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
	}
	
public function  getObPaginationfetch($count,$limit)
	{
		$sql = "SELECT obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM obituary_infos obinfo limit ".$count.", ".$limit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
		
	}
public function  getSpObPaginationfetch($spid,$count,$limit)
	{
		$sql = "SELECT obituary_id,(select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM obituary_infos obinfo where obinfo.special_mention_id=".$spid." limit ".$count.", ".$limit;
//echo $sql;exit;      
      $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     	  $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
		
	}	
public function  getSpMmPaginationfetch($spid,$count,$limit)
	{
		$sql = "SELECT memoralize_id (select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo where obinfo.special_mention_id=".$spid." limit ".$count.", ".$limit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
	}	
	public function  getMmPaginationfetch($count,$limit)
	{
		$sql = "SELECT memoralize_id, (select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo limit ".$count.", ".$limit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
		
	}
	public function getSpecialCategories() {
		$sql="select * from special_mention";	
		 $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
	}
	public function  getSpObituaryfetch()
	{

	    $statement=$this->tableGateway->getAdapter()->query("CALL special_mention_obituary(6)");
       $res = $statement->execute();
        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
//print_r($rows);exit;
$stmt=$res->getResource();
$stmt->nextRowSet();
       return $rows;
	
    	 
	}
	public function getSpmentionfetch() {
			$sql = "SELECT * FROM special_mention";
    
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
	}
	//get total obitury records count
	public function getObituaryCount(){
	$sql = "SELECT count(*) as obCount FROM obituary_infos obinfo where obinfo.special_mention_id=0";
    
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
	}
	
	//get initial memorials details of first 6 records like image and name
public function  getMemorialfetch()
	{
		$sql = "SELECT id,memoralize_id, (select mi.image_url from memoralize_image mi where mi.memoralize_id=minfo.memoralize_id and mi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,gender FROM memoralize_infos minfo where minfo.special_mention_id=0 limit 6 ";
       
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
     		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
		
		
	}
	public function  getSpMemorialfetch()
	{
 		$statement2=$this->tableGateway->getAdapter()->query("CALL special_mention_memorial(6)");
       $res2 = $statement2->execute();
        $rows2 = array();
        foreach ($res2 as $row) {
            $rows2[] = $row;
        }
//print_r($rows2);exit;
$stmt2=$res2->getResource();
$stmt2->nextRowSet();
       return $rows2;		
	
	}
	//get total memorials records count
	public function getMemorialCount(){
	$sql = "SELECT count(*) as mCount FROM memoralize_infos where special_mention_id=0";
    
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
	}
	public function getCountriesfetch(){
		$sql = "SELECT id, country FROM countries";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
		$rows = array();
        foreach ($res as $row) {
            $rows[$row['id']] = $row['country'];
            
        }
      return $rows;
	}
	public function getStatesBYCountryid($countryid){
		$sql = "SELECT state,state_code FROM usstates where country_id=".$countryid;
    
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[$row['state_code']] = $row['state'];
        }
      return $rows;
	}
	//get total countries list to append countries dropdown 
	public function getCitiesfetch(){
		$sql = "SELECT state,state_code FROM usstates";
    
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[$row['state_code']] = $row['state'];
        }
      return $rows;
	}
	//get states according to selection of country
	public function getStatesFetch($countryid){
		$sql = "SELECT state,state_code FROM usstates where country_id=".$countryid;
    
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[$row['state_code']] = $row['state'];
        }
      return $rows;
	}
	public function getCityFetch($stateid){
		$sql = "SELECT uc.city FROM uscities uc where uc.state_code='".$stateid."'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
        if($row['city']!=null) {
            $rows[$row['city']] = $row['city'];
         }
        }
       return $rows;
	}
	
	public function getPostalCodeFetch($city,$stateid){
		
      $sql = "SELECT uc.zip FROM uscities uc where uc.state_code='".$stateid."' and uc.city='".$city."'";
       $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
        if($row['zip']!=null) {
            $rows[$row['zip']] = $row['zip'];
         }
        }
       return $rows;
	}
	
	public function getObituarySearchfilterFetch($data){
		
       
      $sql = "SELECT id, obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,gender FROM obituary_infos obinfo where  ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		/*if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}*/
		$sql .=" and obinfo.special_mention_id=0";
		$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getAllObituaryFetch($data) {
		 $sql = "SELECT id,obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,gender FROM obituary_infos obinfo where ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		/*if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}*/
		$sql .=" and obinfo.special_mention_id=0";
		$sql .=" limit 30";
		//echo $sql;exit;
	    $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
      return $rows;
	}
	public function getAllMemorialFetch($data){
		$sql = "SELECT memoralize_id, (select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo where  ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		/*if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}*/
		$sql .=" and obinfo.special_mention_id=0";
		$sql .=" limit 30";
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
     
       return $rows;
	}
public function getSPObituarySearchfilterFetch($data){
		
		if($data['pastdays']=='>365') {
				$startdate=date('Y-m-d');
		}else {
			$pdod=$data['pastdays'];
			$startdate=date('Y-m-d');
			$enddate = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($startdate)));
			
		}
		//echo $startdate." ".$enddate;exit;
		if($data['postalCode']=='') {
			$data['postalCode']=0;
		}
		if($data['specialMention']=='') {
			$data['specialMention']=0;
		}
		//echo "CALL special_mention_obituary_filter(6,'".$data['country']."','".$data['state']."','".$data['city']."','".$data['postalCode']."','".$data['firstname']."','".$data['middlename']."','".$data['lastname']."','".$data['gender']."','".$data['nationality']."','".$data['keywords']."','".$startdate."','".$enddate."','".$data['causeofdeath']."','".$data['specialMention']."')";exit;
		$statement2=$this->tableGateway->getAdapter()
		->query("CALL special_mention_obituary_filter(6,'".$data['country']."','".$data['state']."','".$data['city']."','".$data['postalCode']."','".$data['firstname']."','".$data['middlename']."','".$data['lastname']."','".$data['gender']."','".$data['nationality']."','".$data['keywords']."','".$startdate."','".$enddate."','".$data['causeofdeath']."','".$data['specialMention']."')");
       $res2 = $statement2->execute();
        $rows2 = array();
        foreach ($res2 as $row) {
            $rows2[] = $row;
        }
			//print_r($rows2);exit;
			$stmt2=$res2->getResource();
			$stmt2->nextRowSet();
       return $rows2;
		
		
		
     /* $sql = "SELECT id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM obituary_infos obinfo where ";
		if($data['country']!="") {
				
        	$sql .=" obinfo.country='USA'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}
		//$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;*/
	}
	public function getViewAllSPObituarySearchfilter($data){
		
      $sql = "SELECT id,obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM obituary_infos obinfo where ";
		
			$specialMention=$data['specialMention'];
			$sql .=" obinfo.special_mention_id='".$specialMention."'";
		
		$sql .=" limit 30";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getSPObituarySearchfilterFetchCnt($data){
		
      $sql = "SELECT count(*) as obspCount FROM obituary_infos obinfo where";
		
			$specialMention=$data['specialMention'];
			$sql .=" obinfo.special_mention_id='".$specialMention."'";
		
		//$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getViewAllSPObituarySearchfilterFetch($data){
		
      $sql = "SELECT id,obituary_id, (select oi.image_url from obituary_image oi where oi.obituary_id=obinfo.obituary_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM obituary_infos obinfo where ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}
		$sql .=" limit 30";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getSPObituarySearchfilterFetchCount($data){
		
      $sql = "SELECT count(*) as obspCount FROM obituary_infos obinfo where ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}
		//$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getObituarySearchfilterFetchCount($data){
	$sql = "SELECT count(*) as obCount FROM obituary_infos obinfo where  ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		/*if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}*/
		$sql .=" and obinfo.special_mention_id=0";
		//$sql .=" limit 6";
		//echo $sql;exit;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
		$rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
	}
	public function getMemorialSearchfilterFetch($data){
		
      $sql = "SELECT memoralize_id,(select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo where ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		/*if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}*/
		$sql .=" and obinfo.special_mention_id=0";
		$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getSPMemorialSearchfilterFetch($data){
		
		if($data['pastdays']=='>365') {
				$startdate=date('Y-m-d');
		}else {
			$pdod=$data['pastdays'];
			$startdate=date('Y-m-d');
			$enddate = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($startdate)));
		}
		if($data['postalCode']=='') {
			$data['postalCode']=0;
		}
		if($data['specialMention']=='') {
			$data['specialMention']=0;
		}
		$statement2=$this->tableGateway->getAdapter()
		->query("CALL special_mention_memorial_filter(6,'".$data['country']."','".$data['state']."','".$data['city']."','".$data['postalCode']."','".$data['firstname']."','".$data['middlename']."','".$data['lastname']."','".$data['gender']."','".$data['nationality']."','".$data['keywords']."','".$startdate."','".$enddate."','".$data['causeofdeath']."','".$data['specialMention']."')");
       $res2 = $statement2->execute();
        $rows2 = array();
        foreach ($res2 as $row) {
            $rows2[] = $row;
        }
			//print_r($rows2);exit;
			$stmt2=$res2->getResource();
			$stmt2->nextRowSet();
       return $rows2;
				
		
     /* $sql = "SELECT (select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo where  ";
		if($data['country']!="") {
					
        	$sql .=" obinfo.country='USA'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}
		//$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;*/
	}
	public function getViewAllSPMemorialSearchfilter($data){
		
      $sql = "SELECT memoralize_id,(select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo where  ";
			$specialMention=$data['specialMention'];
			$sql .=" obinfo.special_mention_id='".$specialMention."'";
		
		$sql .=" limit 30";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getViewAllSPMemorialSearchfilterFetchCnt($data){
		
      $sql = "SELECT count(*) as spmmCount FROM memoralize_infos obinfo where  ";
		
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .="  obinfo.special_mention_id='".$specialMention."'";
		}
		//$sql .=" limit 2";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getViewAllSPMemorialSearchfilterFetch($data){
		
      $sql = "SELECT memoralize_id,(select oi.image_url from memoralize_image oi where oi.memoralize_id=obinfo.memoralize_id and oi.main_image=1) as imageUrl, first_name, last_name, middle_name,special_mention_id,id,gender FROM memoralize_infos obinfo where  ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}
		$sql .=" limit 30";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getViewAllSPMemorialSearchfilterFetchCount($data){
		
      $sql = "SELECT count(*) as spmmCount FROM memoralize_infos obinfo where  ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";	
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}
		//$sql .=" limit 2";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getMemorialSearchfilterFetchCount($data){
		
      $sql = "SELECT count(*) as mCount FROM memoralize_infos obinfo where  ";
		if($data['country']!="") {
			$country=$data['country'];
        	$sql .=" obinfo.country='".$country."'";
		}   
		if($data['state']!="") {
			$state=$data['state'];
			$sql .=" and obinfo.state='".$state."'";
		}  
		if($data['cities']!="") {
			$city=$data['cities'];
			$sql .=" and obinfo.city='".$city."'";
		} 
		if($data['postalCode']!="") {
			$postalCode=$data['postalCode'];
			$sql .=" and obinfo.postal_code='".$postalCode."'";
		}
		if($data['filterVar']!="") {
			$filterVar=$data['filterVar'];
			$sql .=" and obinfo.first_name like '".$filterVar."%'";
		}
		if($data['firstname']!="") {
			$firstname=$data['firstname'];
			$sql .=" and obinfo.first_name like '".$firstname."%'";
		}
		if($data['middlename']!="") {
			$middlename=$data['middlename'];
			$sql .=" and obinfo.middle_name like '".$middlename."%'";
		}
		if($data['lastname']!="") {
			$lastname=$data['lastname'];
			$sql .=" and obinfo.last_name like '".$lastname."%'";
		}
		if($data['gender']!="") {
			$gender=$data['gender'];
			$sql .=" and obinfo.gender='".$gender."'";
		}
		if($data['nationality']!="") {
			$nationality=$data['nationality'];
			$sql .=" and obinfo.nationality='".$nationality."'";
		}
		if($data['keywords']!="") {
			$keywords=$data['keywords'];
			$sql .=" and obinfo.keywords='".$keywords."'";
		}
		if($data['pastdays']!="") {
			if($data['pastdays']=='>365') {
			//$pdod=explode('-',$data['pastdays']);
			$cdate=date('Y-m-d');
			
			$sql .=" and (obinfo.dod <= '".$cdate."')";		
			}else {
			$pdod=$data['pastdays'];
			$cdate=date('Y-m-d');
			$days_ago = date('Y-m-d', strtotime('-'.$pdod.' days', strtotime($cdate)));
			$sql .=" and (obinfo.dod between '".$days_ago."' and '".$cdate."')";
			}
			
		}
		if($data['causeofdeath']!="") {
			$causeofdeath=$data['causeofdeath'];
			$sql .=" and obinfo.cause_of_death='".$causeofdeath."'";
		}
		/*if($data['specialMention']!="") {
			$specialMention=$data['specialMention'];
			$sql .=" and obinfo.special_mention_id='".$specialMention."'";
		}*/
		$sql .=" and obinfo.special_mention_id=0";
		//$sql .=" limit 6";
   //echo $sql;exit;
       $statement = $this->tableGateway->getAdapter()->query($sql);
       $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
       
      //  print_r($rows);exit;
       return $rows;
	}
	public function getObituaryfetch($obituaryid){
		  $id = (int) $obituaryid;
        $csql="select obituary_id, (select mi.image_url from obituary_image mi where mi.obituary_id=minfo.obituary_id and mi.main_image=1) as imageUrl,minfo.first_name, minfo.last_name, minfo.middle_name,minfo.gender,minfo.dob,minfo.dod,minfo.age,minfo.death_place,minfo.school,minfo.ug,minfo.ug_specialization,minfo.family,minfo.special_mention_id from obituary_infos minfo where minfo.id=".$id;
        //echo $csql;exit;
 		$cstatement = $this->tableGateway->getAdapter()->query($csql);
        $res = $cstatement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
        return $rows;  
	}
	public function getKeywordsfetch($key) {
		if($key!="") {
		$csql="select * from keywords where keyword like '%".$key."%' and is_delete=0";
        //echo $csql;exit;
 		$cstatement = $this->tableGateway->getAdapter()->query($csql);
        $res = $cstatement->execute();
       $rows = array();
        foreach ($res as $row) {
        	array_push($rows, $row['keyword']);
            //$rows[] = $row;
        
        }
        return $rows; 
     }else {
			return "";     
     }
	}
public function getMemoralizefetch($memorialid){
		  $id = (int) $memorialid;
        $csql="select memoralize_id, (select mi.image_url from memoralize_image mi where mi.memoralize_id=minfo.memoralize_id and mi.main_image=1) as imageUrl,minfo.first_name, minfo.last_name, minfo.middle_name,minfo.gender,minfo.dob,minfo.dod,minfo.age,minfo.death_place,minfo.school,minfo.ug,minfo.ug_specialization,minfo.family,minfo.special_mention_id from memoralize_infos minfo where minfo.id=".$id;
        //echo $csql;exit;
 		$cstatement = $this->tableGateway->getAdapter()->query($csql);
        $res = $cstatement->execute();
       $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        
        }
        return $rows;  
	}
	
	public function getLanguages() {
		$sql = "SELECT uc.city FROM uscities uc";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       $rows = array();
        foreach ($res as $row) {
        if($row['city']!=null) {
            $rows[$row['city']] = $row['city'];
         }
        }
       return $rows;
	}
	
}
