<?php

namespace ScnSocialAuth\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class Connections extends AbstractDbMapper implements ConnectionsInterface {

    protected $tableName = 'my_connections';

    /**
     * @param  int                $providerId
     * @param  string             $provider
     * @return UserProviderEntity
     */
    public function findConnections($userId) {
        $sql = $this->getSql();
        $select = $sql->select();
        $select->from($this->tableName)
                ->where(array(
                    'user_id' => $userId,
        ));

        $entity = $this->select($select)->toArray();

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    public function deleteConnections($ids) {

        $id = implode(',', $ids);
        $where = 'id IN(' . $id . ')';
        $entity = $this->delete($where, $this->tableName);
        return $entity;
    }

    public function save($data) {

        $name = $data['name'];
        $email = $data['email'];
        $relation = $data['relation'];
        $userId = $data['user_id'];

        $data = array('user_id' => $userId, 'name' => $name, 'email' => $email, 'relation' => $relation);
        
        $this->insert($data, $this->tableName);

        return true;
    }

}
