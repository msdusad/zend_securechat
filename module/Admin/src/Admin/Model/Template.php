<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Template implements InputFilterAwareInterface {

    public $id;
    public $language_id;
    public $title;
    public $language;
    public $content;
    public $is_active;
    protected $inputFilter;

    public function exchangeArray($data) {


        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->language_id = (isset($data['language_id'])) ? $data['language_id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->language = (isset($data['language'])) ? $data['language'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
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
                        'name' => 'language_id',
                        'required' => true,
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
                        'name' => 'is_active',
                        'filters' => array(
                            array('name' => 'Null'),
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}