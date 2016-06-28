<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false, $category = '', $searchQuery = '') {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('user');
            $dbSelect = $select->columns(array('user_id', 'email', 'display_name','created_at'))
                   // ->join('user_provider', 'user.user_id = user_provider.user_id', array('provider', 'provider_id'), 'left')
                    ->join('user_role_linker', 'user.user_id = user_role_linker.user_id', array('role_id'), 'left');
           
            if ($searchQuery != '') {
                switch ($category) {
                    case 'name':
                        $dbSelect->where("user.display_name LIKE '%$searchQuery%'");
                        break;
                    case 'email':
                        $dbSelect->where("user.email LIKE '%$searchQuery%'");
                        break;
                    case 'username':
                        $dbSelect->where("user.username LIKE '%$searchQuery%'");
                        break;
                    default:
                        break;
                }
            }
            $dbSelect->where("user_role_linker.role_id != '1'");
            $dbSelect->order(array('user.user_id asc'));
           
// create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new User());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                    // our configured select object
                    $dbSelect,
                    // the adapter to run it against
                    $this->tableGateway->getAdapter(),
                    // the result set to hydrate
                    $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        $resultSet = $this->tableGateway->select();
         
        return $resultSet;
    }
    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function delete($id)
    {
        if($this->getUser($id)){
         $data = array('status'=>1);
         $this->tableGateway->update($data, array('user_id' => $id));
        }
    }
    
    public function multiDelete($ids) {

        $id = implode(",", $ids);
        $sql = "DELETE FROM user_role_linker WHERE user_id IN(".$id."); DELETE FROM user WHERE user_id IN(".$id.")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }
}
