<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class UserProvider {

    public $id;   
    public $providerId;
    public $provider;
  

    public function exchangeArray($data) {
      
        $this->id = (isset($data['user_id'])) ? $data['user_id'] : $this->id;
        $this->provider = (isset($data['provider'])) ? $data['provider'] : $this->provider;       
        $this->providerId = (isset($data['provider_id'])) ? $data['provider_id'] : $this->providerId;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'user_id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'email',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'provider',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
