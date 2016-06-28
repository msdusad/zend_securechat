<?php

namespace Admin\Form;

use Zend\Form\Form;

class TaskForm extends Form
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('tasks');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
       $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'category_id',
            'attributes' => array(
                'id' => 'category_id',
                 'class' => 'form-control',
            ),
           'options' => array(
                'label' => 'Category',                
            ),
            
        ));
       $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title',                
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(                
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class'=>'btn btn-lg btn-primary btn-block',
                
            ),
        ));
    }
}