<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Impact implements InputFilterAwareInterface {

    public $id;
    public $title;    
    public $content;  
    public $country;  
    public $location;  
    public $category_id;
    public $impact_id;  
    public $video_url;
      
    protected $inputFilter;

    public function exchangeArray($data) {
               
        $this->id = (isset($data['id'])) ? $data['id'] : null;
         $this->category_id = (isset($data['category_id'])) ? $data['category_id'] : null;  
        $this->title = (isset($data['title'])) ? $data['title'] : null;      
        $this->content = (isset($data['content'])) ? $data['content'] : null;
        $this->country = (isset($data['country'])) ? $data['country'] : null;
        $this->location = (isset($data['location'])) ? $data['location'] : null;
        $this->impact_id = (isset($data['impact_id'])) ? $data['impact_id'] : null;      
        $this->video_url = (isset($data['video_url'])) ? $data['video_url'] : null;
        
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
                        'name' => 'title',
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
                        'name' => 'video_url',
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
                                    'max' => 1000,
                                ),
                            ),
                        ),
            )));
             
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}