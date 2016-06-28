<?php

namespace ScnSocialAuth\Mapper;



interface ScnContactsInterface
{        
    /**
     * @param  ContactsInterface $data
     * @return array
     */
    public function importContacts($data);
    /**
     * @param  string $email  
     *   
     * @return ContactsEntity
     */
     public function getContact($email);
    /**
     * @param  int                $userId  
     *   
     * @return ContactsEntity
     */
    public function getContacts($userId);
   
}
