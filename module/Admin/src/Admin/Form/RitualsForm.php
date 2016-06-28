<?php

namespace Admin\Form;

use Zend\Form\Form;

class RitualsForm extends Form {

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('rituals');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));


        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'traditional_id',
            'attributes' => array(
                'id' => 'traditional_id',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Traditional',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'content',
            'options' => array(
                'count' => 0,
                'should_create_template' => false,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'Admin\Form\ContentFieldset'
                )
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'cultural',
            'options' => array(
                'label' => 'Cultural',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'preference',
            'options' => array(
                'label' => 'Preference',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'practical',
            'options' => array(
                'label' => 'Practical',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'interfaith',
            'options' => array(
                'label' => 'Interfaith',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'other',
            'options' => array(
                'label' => 'Other',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-lg btn-primary btn-block',
            ),
        ));
    }

}