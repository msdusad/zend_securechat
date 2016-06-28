<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class TaskTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated=false)
    {
        if($paginated) {
            // create a new Select object for the table album
            $select = new Select('tasks');
            $dbSelect = $select->columns(array('id','title','description'))
                    ->join('user', 'tasks.user_id = user.user_id', array('user' =>'display_name','user_id'), 'left')
                    ->join('category', 'tasks.category_id = category.id', array('category' => 'title','category_id' =>'id'), 'left');
           
            $dbSelect->order(array('tasks.id asc'));
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Task());
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

    public function getTask($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTask(Task $task)
    {
        $data = array(
            
            'title'  => $task->title,
            'description' => $task->description,
            'category_id' => $task->category_id,
            'user_id' => $task->user_id,
            
        );
      

        $id = (int)$task->id;
        if ($id == 0) {  
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTask($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteTask($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    public function multiDelete($ids) {

        $id = implode(",", $ids);

        $sql = "DELETE FROM tasks WHERE id IN (".$id.")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }
    
}