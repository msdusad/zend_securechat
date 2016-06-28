<?php

namespace Admin\Form;

use Zend\Form\Form;

class RitualsTraditionalForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('rituals_traditional');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
       
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                 'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Traditional Name',
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