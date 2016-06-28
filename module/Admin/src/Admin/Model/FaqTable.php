<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class FaqTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table cms
            $select = new Select('faq');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Faq());
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

    public function getPage($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function faqList($categoryId) {
       
        if($categoryId!=''){
        $sql = "SELECT * FROM faq WHERE is_active=1 AND category_id=".$categoryId;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
       
        foreach ($res as $row) {
            $rows[] = $row;
        }
        }else{$rows = array();}
        return $rows;
    }
   

    public function savePage(Faq $faq) {
         $answer = stripslashes($faq->answer);
        $data = array(
            'question' => $faq->question,  
            'category_id' => $faq->category_id,     
            'answer' => $answer,
            'is_active' => $faq->is_active,
        );

        $id = (int) $faq->id;
        if ($id == 0) {
           
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPage($id)) {
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
        $sql = "DELETE FROM faq WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

     public function getCategories() {

        $sql = "SELECT * FROM category WHERE type='faq'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

         $rows = array();
        foreach ($res as $row) {
            $rows[$row['id']] = array(
                'value' => $row['id'],
                'label' => $row['title'],
            );
        }
        return $rows;
    }
}