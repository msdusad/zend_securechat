<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class SpecialNeedsTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($category,$start,$limit) {

        $sql = "SELECT sn.title,sn.content,sn.id,sng.photo_url FROM special_needs as sn 
            LEFT JOIN special_needs_gallery AS sng ON sng.special_needs_id = sn.id WHERE sn.status='0'";
        if ($category != 0) {
            $sql .=" AND sn.category_id=" . $category;
        }
        $sql .= " GROUP BY sn.id LIMIT $start,$limit";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function fetchTotal($category) {

        $sql = "SELECT sn.title,sn.content,sn.id,sng.photo_url FROM special_needs as sn 
            LEFT JOIN special_needs_gallery AS sng ON sng.special_needs_id = sn.id WHERE sn.status='0'";
        if ($category != 0) {
            $sql .=" AND sn.category_id=" . $category;
        }
        $sql .= " GROUP BY sn.id";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return count($rows);
    }


    public function getById($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePhotose($photo, $id = null) {

        $sql = "INSERT INTO special_needs_gallery (`id`, `special_needs_id`, `photo_url`) VALUES (NULL, '$id', '$photo');";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
    }

    public function save(SpecialNeeds $class) {

        $data = array(
            'user_id' => $class->user_id,
            'category_id' => $class->category_id,
            'title' => $class->title,
            'content' => $class->content,
            'country' => $class->country,
            'location' => $class->location,
            'link' => $class->link,
            'video_url' => $class->video_url,
        );

        $id = (int) $class->id;
        if ($id == 0) {

            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getById($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $id;
    }

    public function delete($id) {
        
        $sql = "UPDATE special_needs SET status='1' WHERE id =" . $id;
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

    public function multiDelete($ids) {
        $id = implode(",", $ids);
        $sql = "DELETE FROM special_needs WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

    public function getCategories() {

        $sql = "SELECT c.*,COUNT(sn.id) as profiles FROM category AS c LEFT JOIN special_needs as sn ON c.id=sn.category_id WHERE c.type='specianeeds' GROUP BY c.id";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getCategory($id) {

        $sql = "SELECT title FROM category WHERE id='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = '';
        foreach ($res as $row) {
            $rows = $row['title'];
        }
        return $rows;
    }
    public function getCategoryOption() {

        $sql = "SELECT * FROM category WHERE type='specianeeds'";
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

    public function getGallery($id) {

        $sql = "SELECT * FROM special_needs_gallery WHERE special_needs_id='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getGalleryById($id) {

        $sql = "SELECT * FROM special_needs_gallery WHERE id ='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows = $row;
        }
        return $rows;
    }

    public function deletePhotose($ids, $impactId) {
        $imageId = implode(",", $ids);

        foreach ($ids as $id) {
            $imagePath = $this->getGalleryById($id);

            $fileName = dirname(__DIR__) . '/../../assets/specialneeds/' . $imagePath['photo_url'];
            $thumbName = dirname(__DIR__) . '/../../assets/specialneeds/thumb/' . $impactId . '_' . $imagePath['photo_url'];
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            if (file_exists($thumbName)) {
                unlink($thumbName);
            }
        }

        $sql = "DELETE FROM special_needs_gallery WHERE id IN (" . $imageId . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }
    
    public function getUser($id) {

        $sql = "SELECT display_name,username FROM user WHERE user_id='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = '';
        foreach ($res as $row) {
            $rows = $row['username']. ' '.$row['display_name'];
        }
        return $rows;
    }

}