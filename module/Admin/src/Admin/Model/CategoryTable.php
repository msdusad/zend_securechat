<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class CategoryTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
		$res = $this->tableGateway = $tableGateway;		
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table category
            $select = new Select('category');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Category());
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

    public function getCategories() {
       
        $sql = "SELECT * FROM category";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        // set the first option
        $rows[0] = array(
            'value' => '0',
            'label' => 'Select',
            'selected' => TRUE,
            'disabled' => FALSE
        );

        foreach ($res as $row) {
            $rows[$row['id']] = array(
                'value' => $row['id'],
                'label' => $row['title'],
            );
        }
        return $rows;
    }

    public function getCategoriesByType($type) {
       
        $sql = "SELECT * FROM category WHERE type='".$type."'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        // set the first option
        $rows[0] = array(
            'value' => '0',
            'label' => 'Select',
            'selected' => TRUE,
            'disabled' => FALSE
        );

        foreach ($res as $row) {
            $rows[$row['id']] = array(
                'value' => $row['id'],
                'label' => $row['title'],
            );
        }
        return $rows;
    }
    public function getCategory($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCategory(Category $category) {
        $data = array(
            'title' => $category->title,
            'type' => $category->type,
        );

        $id = (int) $category->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategory($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategory($id) {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function multiDelete($ids) {

        $id = implode(",", $ids);
        $sql = "DELETE FROM tasks WHERE category_id IN (".$id."); DELETE FROM category WHERE id IN(".$id.")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

}