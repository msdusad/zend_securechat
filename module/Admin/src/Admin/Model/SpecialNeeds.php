<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class SpecialNeeds implements InputFilterAwareInterface {

    public $id;
    public $title;
    public $content;
    public $country;
    public $location;
    public $link;
    public $video_url;
    public $user_id;
    public $category_id;
    public $special_needs_id;
    public $photo_url;
    public $created_at;
    protected $inputFilter;

    public function exchangeArray($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->category_id = (isset($data['category_id'])) ? $data['category_id'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
        $this->country = (isset($data['country'])) ? $data['country'] : null;
        $this->location = (isset($data['location'])) ? $data['location'] : null;
        $this->link = (isset($data['link'])) ? $data['link'] : null;
        $this->video_url = (isset($data['video_url'])) ? $data['video_url'] : null;
        $this->special_needs_id = (isset($data['special_needs_id'])) ? $data['special_needs_id'] : null;
        $this->photo_url = (isset($data['photo_url'])) ? $data['photo_url'] : null;
        $this->created_at = (isset($data['created_at'])) ? $data['created_at'] : null;
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

            

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}