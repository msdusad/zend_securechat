<?php

namespace Admin\Form;

use Zend\Form\Form;

class CategoryForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('category');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'sorting',
                'options' => array(  
                    'faq' => 'FAQ',
                    'task' => 'Task',
                    'find' => 'Find',
                    'learn' => 'Learn About',                    
                    'product' => 'Purchase',
                    'impact' => 'Impact',
                    'donation' =>'Donation',
                    'preplanning' => 'Pre Planning',
                    'specianeeds' => 'Specia Needs'
                    
                ),
            ),'options' => array(
                'label' => 'Category Type',
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