<?php

namespace Administrator\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
	public $password;
    public $created;
    public $deleted;
    
    protected $inputFilter;
	
   

    /**
     * Used by ResultSet to pass each database row to the entity
     */
    public function exchangeArray($data)
    {
        $this->user_id      = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->email        = (isset($data['email'])) ? $data['email'] : null;
        $this->first_name   = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->last_name    = (isset($data['last_name'])) ? $data['last_name'] : null;
        $this->password     = (isset($data['password'])) ? $data['password'] : null;
        $this->state        = (isset($data['state'])) ? $data['state'] : null;
        $this->deleted      = (isset($data['deleted'])) ? $data['deleted'] : null;
        $this->created      = (isset($data['created'])) ? $data['created'] : null;
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
        
  
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        
        
        
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $this->inputFilter = $inputFilter;        
        }

        return $this->inputFilter;
    }
    
}
