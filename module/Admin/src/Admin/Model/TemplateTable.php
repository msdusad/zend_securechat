<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class TemplateTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
 public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('cremation_templates');
            $dbSelect = $select->columns(array('id', 'title','is_active'))
                    ->join('languages', 'cremation_templates.language_id = languages.id', array('language' => 'name'), 'left');
            $dbSelect->order(array('cremation_templates.id asc'));
           // echo  $dbSelect->getSqlString();die;
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Template());
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
    
    public function getTemplate($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
   
    public function getLanguages(){
     
        $sql = "SELECT * FROM languages WHERE 1";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[$row['id']] = array(
                'value' => $row['id'],
                'label' => $row['name'],
            );
        }
        return $rows;
    }

    public function save(Template $datas) {
        
        $data = array(
            'language_id' => $datas->language_id,
            'title' => $datas->title,            
            'content' => $datas->content,
            'is_active' => $datas->is_active,
        );

        $id = (int) $datas->id;
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

    public function delete($id) {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function multiDelete($ids) {
        $id = implode(",", $ids);
        $sql = "DELETE FROM cremation_templates WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

}