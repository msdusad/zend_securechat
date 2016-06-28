<?php

namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

class UserProviderTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
     public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('user_id' => $id));
        $row = $rowset->current();
      
        return $row;
    }
   
}
