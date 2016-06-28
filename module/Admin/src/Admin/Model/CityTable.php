<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class CityTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

   

    public function getIndianStates() {
       
        $sql = "SELECT DISTINCT(state) FROM india limit 3";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        foreach ($res as $row) {
            $rows[] =  $row;
        }
        return $rows;
    }
    public function getIndianMainCities($state) {
       
        $sql = "SELECT DISTINCT(main_city) FROM india WHERE state='$state'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        foreach ($res as $row) {
            $rows[] =  $row;
        }
        return $rows;
    }
     public function getIndianCities($state,$maincity) {
       
        $sql = "SELECT * FROM india WHERE state='$state' AND main_city='$maincity'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        foreach ($res as $row) {
            $rows[] =  $row;
        }
        return $rows;
    }

    public function getUsStates() {
       
        $sql = "SELECT state,state_code FROM usstates limit 3";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        foreach ($res as $row) {
            $rows[] =  $row;
        }
        return $rows;
    }
    public function getUsCities($state) {
       
        $sql = "SELECT * FROM  uscities WHERE state_code='$state'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        foreach ($res as $row) {
            $rows[] =  $row;
        }
        return $rows;
    }

}