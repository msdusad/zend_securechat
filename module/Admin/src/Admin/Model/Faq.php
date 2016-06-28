<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Faq implements InputFilterAwareInterface {

    public $id;
    public $category_id;
    public $question;
    public $answer;  
    public $is_active;
    protected $inputFilter;

    public function exchangeArray($data) {        
       
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->category_id = (isset($data['category_id'])) ? $data['category_id'] : null;
        $this->question = (isset($data['question'])) ? $data['question'] : null;
        $this->answer = (isset($data['answer'])) ? $data['answer'] : null;        
        $this->is_active = (isset($data['is_active'])) ? $data['is_active'] : null;        
    }

    // Add the following method:
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
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'question',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 100,
                                ),
                            ),
                        ),
            )));
             $inputFilter->add($factory->createInput(array(
                'name'     => 'is_active',
                'filters' => array(
                            array('name' => 'Null'),
                        ),
                
            )));
              $inputFilter->add($factory->createInput(array(
                'name'     => 'answer',
                 'required' => true,
                        
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    
                                ),
                            ),
                        ),
                
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}