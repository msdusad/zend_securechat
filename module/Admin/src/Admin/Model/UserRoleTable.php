<?php

namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

class UserRoleTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function delete($id)
    {
        $this->tableGateway->delete(array('user_id' => $id));
    }
}
