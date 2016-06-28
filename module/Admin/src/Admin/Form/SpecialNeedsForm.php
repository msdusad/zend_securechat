<?php

namespace Admin\Form;

use Zend\Form\Form;

class SpecialNeedsForm extends Form {

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('special_needs');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => ''
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'category_id',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'sorting',
            ), 'options' => array(
                'label' => 'Category',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Caption',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'content',
            'options' => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'nicedit'
            ),
        ));
        $this->add(array(
            'name' => 'country',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Country',
            ),
        ));
        $this->add(array(
            'name' => 'location',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Location',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'video_url',
            'options' => array(
                'label' => 'Video',
            ),
            'attributes' => array(
                'id' => 'video_url',
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'link',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Link to',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Next',
                'id' => 'submitbutton',
                'class' => 'btn btn-danger btn-sm nextbtn',
            ),
        ));
    }

}