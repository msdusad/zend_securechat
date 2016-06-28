<?php

namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ImpactTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('impacts');
            $dbSelect = $select->columns(array('id', 'title'))
                    ->join('category', 'impacts.category_id = category.id', array('category_id' => 'title'), 'left');

            $dbSelect->order(array('impacts.id asc'));
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Impact());
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

    public function getById($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePhotose($photo, $impactid = null, $id = null) {

        $sql = "INSERT INTO impact_gallery (`id`, `impact_id`, `photo_url`) VALUES (NULL, '$impactid', '$photo');";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
    }

    public function save(Impact $impact) {

        $data = array(
            'category_id' => $impact->category_id,
            'title' => $impact->title,
            'content' => $impact->content,
            'country' => $impact->country,
            'location' => $impact->location,
        );

        $id = (int) $impact->id;
        if ($id == 0) {

            $this->tableGateway->insert($data);
        } else {
            if ($this->getById($id)) {
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
        $sql = "DELETE FROM impacts WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

    public function getCategories() {

        $sql = "SELECT * FROM category WHERE type='impact'";
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

        $sql = "SELECT * FROM impact_gallery WHERE impact_id='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getGalleryById($id) {

        $sql = "SELECT * FROM impact_gallery WHERE id ='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows = $row;
        }
        return $rows;
    }

    public function deletePhotose($ids,$impactId) {
        $imageId = implode(",", $ids);

        foreach ($ids as $id) {
            $imagePath = $this->getGalleryById($id);

            $fileName = dirname(__DIR__) . '/../../assets/impact/' . $imagePath['photo_url'];
            $thumbName = dirname(__DIR__) . '/../../assets/impact/thumb/'.$impactId.'_'. $imagePath['photo_url'];
            if (file_exists($fileName)) {
                unlink($fileName);
            }
             if (file_exists($thumbName)) {
                unlink($thumbName);
            }
        }

        $sql = "DELETE FROM impact_gallery WHERE id IN (" . $imageId . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }
    
    
     public function getVideos($id) {

        $sql = "SELECT * FROM impact_videos WHERE impact_id='$id'";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $res = $statement->execute();

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function saveVideo($video, $impactid = null, $id = null) {
        
        $sql = "INSERT INTO impact_videos (`id`, `impact_id`, `video_url`) VALUES (NULL, '$impactid', '$video');";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        
    }
     public function deleteVideos($ids) {
       
           $id = implode(",", $ids);
        $sql = "DELETE FROM impact_videos WHERE id IN (" . $id . ")";
        $statement = $this->tableGateway->getAdapter()->query($sql);
        $statement->execute();
        return true;
    }

}