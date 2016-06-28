<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class RitualsTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('rituals');
            $dbSelect = $select->columns(array('id'))
                    ->join('rituals_traditional', 'rituals.traditional_id = rituals_traditional.id', array('name'), 'left')
                    ->join('rituals_days', 'rituals.id = rituals_days.ritual_id', array('num' => new \Zend\Db\Sql\Expression('COUNT(rituals_days.ritual_id)')), 'left');

            $dbSelect->group(array('rituals_days.ritual_id'));
            $dbSelect->order(array('rituals.id asc'));
            // echo  $dbSelect->getSqlString();die;
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Rituals());
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

    public function getRitual($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function getTraditional($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM rituals_traditional WHERE id =" . $id;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        $traditional = array();
        foreach ($res as $row) {
            $traditional = $row;
        }

        return $traditional;
    }

    public function save(Rituals $rituals) {


        $data = array(
            'traditional_id' => $rituals->traditional_id,
            'cultural' => $rituals->cultural,
            'preference' => $rituals->preference,
            'practical' => $rituals->practical,
            'interfaith' => $rituals->interfaith,
            'other' => $rituals->other
        );

        $id = (int) $rituals->id;
        if ($id == 0) {

            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
            $this->saveDays($rituals->content, $id);
        } else {
            if ($this->getRitual($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                $this->saveDays($rituals->content, $id);
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function getRitualDays($id) {

        $sql = "SELECT content FROM rituals_days WHERE ritual_id =" . $id;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        $days = array();
        foreach ($res as $row) {
            $days[] = $row;
        }

        return $days;
    }

    public function saveDays($days, $ritual_id = null, $id = null) {

        $id = (int) $id;
        $ritual_id = (int) $ritual_id;
        $availableDays = $this->getDaysById($ritual_id);
        $ids = array();
        foreach ($availableDays as $value) {
            $ids[] = $value['id'];
        }

        if (count($ids) <= 0) {
            $last = count($days);
            $sql = "INSERT INTO rituals_days (`id`, `ritual_id`, `day`, `content`) VALUES ";
            foreach ($days as $key => $value) {
                $day = $key + 1;
                $content = $value['content'];
                if ($day == $last) {
                    $sql .= "(NULL, $ritual_id, $day, '$content');";
                } else {
                    $sql .= "(NULL, $ritual_id, $day, '$content'),";
                }
            }

            $statement = $this->tableGateway->getAdapter()->query($sql);
            $statement->execute();
        } else {
            $sql = '';
            foreach ($days as $key => $value) {

                $day = $key + 1;
                $content = $value->content;
                if (isset($ids[$key])) {

                    $sql .= "UPDATE rituals_days SET content = '$content' WHERE id = $ids[$key];";
                } else {
                    $sql .= "INSERT INTO rituals_days (`id`, `ritual_id`, `day`, `content`) VALUES (NULL, $ritual_id, $day, '$content');";
                }
            }
            $statement = $this->tableGateway->getAdapter()->query($sql);
            $statement->execute();
        }
    }

    public function delete($id) {
        if (count($this->getDaysById($id)) > 0) {

            $this->deleteDays($id);
            
        } 
        $this->tableGateway->delete(array('id' => $id));
    }

    public function deleteDays($id) {

        $sql = "DELETE FROM rituals_days WHERE ritual_id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

    public function multiDelete($ids) {

        $id = implode(",", $ids);
        
        if (count($ids) > 0 ) {

            $this->deleteDays($id);
            
        } 
        $sql = "DELETE FROM rituals WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

    public function getTraditionals() {

        $sql = "SELECT * FROM rituals_traditional";
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

    public function getDaysById($id) {

        $sql = "SELECT * FROM rituals_days WHERE ritual_id=" . $id;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();
        $rows = array();

        foreach ($res as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

}