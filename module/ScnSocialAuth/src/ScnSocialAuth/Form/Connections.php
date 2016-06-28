<?php

namespace ScnSocialAuth\Form;


use Zend\Form\Form;

class Connections extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('my_connections');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
         $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
               
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                 'class' => 'form-control',
                 'id' =>'name'
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                 'class' => 'form-control',
                 'id' =>'email'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
         $this->add(array(
            'name' => 'relation',
            'attributes' => array(
                'type'  => 'text',
                 'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Relation',
            ),
        ));
          
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add',
                'id' => 'submitbutton',
                'class'=>'btn  btn-primary btn-block',
            ),
        ));
    }
}