<?php

namespace ScnSocialAuth\Entity;

interface ConnectionsInterface
{
     /**
     * @return the $id
     */
    public function getId();

    /**
     * @param  integer  $id
     * @return ConnectionsInterface
     */
    public function setId($id);

    /**
     * @return the $userId
     */
    public function getUserId();

    /**
     * @param  integer   $userId
     * @return ConnectionsInterface
     */
    public function setUserId($userId);

    /**
     * @return the $name
     */
    public function getName();

    /**
     * @param  string  $name
     * @return ConnectionsInterface
     */
    public function setName($name);

    /**
     * @return the $email
     */
    public function getEmail();

    /**
     * @param  string  $email
     * @return ConnectionsInterface
     */
    public function setEmail($email);
    
    /**
     * @return the $relation
     */
    public function getRelation();

    /**
     * @param  string   $relation
     * @return ConnectionsInterface
     */
    public function setRelation($relationl);
}
