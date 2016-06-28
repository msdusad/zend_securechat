<?php
namespace Application\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;


class LanguageTextTable 
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
	public function  getText($ltext)
	{
	
	//echo $lsize;
	$resultSet = $this->tableGateway->select(function(Select $select) use($ltext){
		$ltemp=explode(',',$ltext);
	$lsize= sizeof($ltemp);
			
		for($j=0;$j<$lsize;$j++){
			
			if($j==0) {
				$select->where(array('language' => $ltemp[$j]));
			}else {
				$select->where(array('language' => $ltemp[$j]),\Zend\Db\Sql\Where::OP_OR);
			}	
				
		}
          
          
      });
      $rows = array();
        foreach ($resultSet as $row) {
            $rows[] = $row;
        }
      return $rows;
	}
	public function  getById($id)
	{
		$resultSet = $this->tableGateway->select(array('id'=>$id));
         return $resultSet;
	}		
	public function  getByIdType($data)
	{
		$lngtext=array();
		foreach($data as $k=>$v){
			$resultSet = $this->tableGateway->select(array('id'=>$k));
		
		
			foreach ($resultSet as $row) {
            $lngtext[] = $row[$v];
        }
         
      }
     $lgtxt=implode("          ",$lngtext);
     //echo $lgtxt;exit;
      return $lgtxt;		
		
		
	}	
}
