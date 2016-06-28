<?php

namespace ScnSocialAuth\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class ScnContacts extends AbstractDbMapper implements ScnContactsInterface  {

    protected $tableName = 'contacts';

    
    public function importContacts($datas) {

     
        foreach ($datas AS $key => $value) {
              
      $this->insert($value, 'contacts');
        
        } 
        return true;
    }
    public function getContact($email){
     
        $sql = $this->getSql();
        $select = $sql->select();
          $select->from('contacts')
                ->where(array(
                    'email' => $email,
        ));
        

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));
        return $entity;
    }

    public function getContacts($userId) {

        $sql = $this->getSql();
        $select = $sql->select();
        $select->from('contacts')
                ->where(array(
                    'user_id' => $userId,
        ));

        // echo  $select->getSqlString();die;
        $entity = $this->select($select)->toArray();

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    

}
