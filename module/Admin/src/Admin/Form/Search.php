<?php

namespace Admin\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Search extends ProvidesEventsForm {

    public function __construct($name = null) {

        parent::__construct($name);

        $this->add(array(
            'name' => 'search_field',
            'options' => array(
                'label' => 'Search',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'category',
            'attributes' => array(
                'id' => 'category',
                'options' => array(
                    '' => 'Category',
                    'name' => 'Name',
                    'email' => 'Email'                   
                ),
            ),
            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'sorting',
            'attributes' => array(
                'id' => 'sorting',
                'options' => array(
                    '' => 'Results/Sorting',
                    'name' => 'Name',
                    'email' => 'Email'
                    
                ),
            ),
            
        ));
        $submitElement = new Element\Button('submit');
        $submitElement
                ->setLabel('Submit')
                ->setAttributes(array(
                    'type' => 'submit',
        ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->getEventManager()->trigger('init', $this);
    }

}
