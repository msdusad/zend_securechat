<?php

namespace ScnSocialAuth\Mapper;


interface ConnectionsInterface
{
    
    /**
     * @param  int $userId
     * @return ConnectionsEntity
     */
    public function findConnections($userId);

    public function deleteConnections($ids);
    
    public function save($data);
}
