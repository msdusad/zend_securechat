<?php

namespace ScnSocialAuth\Entity;

class Connections implements ConnectionsInterface
{
    protected $id;
    
    protected $userId;

    protected $name;

    protected $email;
    
    protected $relation;

    public function setId($id) {
        
        $this->id = $id;

        return $this;
    }
    /**
     * @return the $userId
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setUserId($userId) {
        
        $this->userId = $userId;

        return $this;
    }
    /**
     * @return the $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * @param  integer      $userId
     * @return UserProvider
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return the $providerId
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  integer      $providerId
     * @return UserProvider
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return the $provider
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param  string       $provider
     * @return UserProvider
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    public function getRelation() {
        
        return $this->relation;
    }

    
}
