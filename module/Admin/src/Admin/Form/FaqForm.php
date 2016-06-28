<?php

namespace Admin\Form;

use Zend\Form\Form;

class FaqForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('cms');
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
            'name' => 'question',
            'attributes' => array(
                'type'  => 'text',
                 'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Question',
            ),
        ));
        
          $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'answer',
            'options' => array(
                'label' => 'Answers',
            ),
            'attributes' => array(                
                'class' => 'form-control',
            ),
        ));
         $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'is_active',
             'options' => array(
                'label' => 'Status',
            ),
            'attributes' => array(
                'id' => 'isactive',
                 'class' => 'form-control',
                'options' => array(                   
                    '1' => 'Enable',
                    '0' => 'Disable'                   
                ),
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