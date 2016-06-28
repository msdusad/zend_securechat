<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class EmailTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table cms
            $select = new Select('templates');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Email());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                    // our configured select object
                    $select,
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

    public function getTemplate($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getTemplates() {
       
        $sql = "SELECT * FROM templates";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        $email = array();
        foreach ($res as $row) {
           $email[] = $row;
        }
        
        return $email;
    }

    public function getSample($id) {

        $rowset = $this->tableGateway->select(array('url_key' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePage(Email $email) {

        $data = array(
            'title' => $email->title,
            'url_key' => $email->url_key,
            'content' => $email->content,
            'is_active' => $email->is_active,
        );

        $id = (int) $email->id;
        if ($id == 0) {

            $this->tableGateway->insert($data);
        } else {
            if ($this->getTemplate($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletePage($id) {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function multiDelete($ids) {
        $id = implode(",", $ids);
        $sql = "DELETE FROM templates WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

}