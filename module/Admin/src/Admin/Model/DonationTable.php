<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class DonationTable {

    protected $tableGateway;
    protected $_basePath;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @param string
     */
    public function setBasePath($path) {
        $this->_basePath = $path;
    }

    public function fetchAll($paginated = false, $search = '') {
        if ($paginated) {
            // create a new Select object for the table cms
            $select = new Select('donation');

            $dbSelect = $select->columns(array('id', 'photo', 'category_id', 'name', 'address'));
            if ($search != '') {
                $dbSelect->where("category_id = '$search'");
            }
            //  echo  $dbSelect->getSqlString();die;
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Donation());
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

    public function getListById($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveList(Donation $find) {

        $data = array(
            'category_id' => $find->category_id,
            'name' => $find->name,
            'address' => $find->address,
            'photo' => $find->photo,
            'amount' => $find->amount,
        );

        if ($data['photo'] == '') {
            unset($data['photo']);
        }


        $id = (int) $find->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getListById($id)) {

                $list = $this->getListById($id);
                $dirName = dirname(__DIR__) . '/../../assets/donation/';
                if (file_exists($dirName . 'thumb/' . $list->id . '_' . $list->photo) && isset($data['photo'])) {

                    unlink($dirName . 'thumb/' . $list->id . '_' . $list->photo);
                }
                if (file_exists($dirName . $list->photo) && isset($data['photo'])) {

                    unlink($dirName . $list->photo);
                }

                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $id;
    }

    public function deleteList($id) {

        $list = $this->getListById($id);
        if ($list != '') {

            $dirName = dirname(__DIR__) . '/../../assets/donation/';

            if (file_exists($dirName . 'thumb/' . $list->id . '_' . $list->photo)) {

                unlink($dirName . 'thumb/' . $list->id . '_' . $list->photo);
            }
            if (file_exists($dirName . $list->photo)) {

                unlink($dirName . $list->photo);
            }
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function multiDelete($ids) {

        $id = implode(",", $ids);
        $sql = "SELECT id,photo FROM donation WHERE id IN (" . $id . ")";
        $sqlStatement = $this->tableGateway->getAdapter()->query($sql);
        $res = $sqlStatement->execute();
        $dirName = dirname(__DIR__) . '/../../assets/donation/';

        foreach ($res as $photo) {
            if (file_exists($dirName . 'thumb/' . $photo['id'] . '_' . $photo['photo'])) {

                unlink($dirName . 'thumb/' . $photo['id'] . '_' . $photo['photo']);
            }
            if (file_exists($dirName . $photo['photo'])) {

                unlink($dirName . $photo['photo']);
            }
        }

        $sql = "DELETE FROM donation WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

    public function getCategories() {

        $sql = "SELECT * FROM category WHERE type='donation'";
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