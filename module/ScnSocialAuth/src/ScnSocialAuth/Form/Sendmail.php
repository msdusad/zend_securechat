<?php

namespace ScnSocialAuth\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Sendmail extends ProvidesEventsForm {

    public function __construct($name = null) {

        parent::__construct($name);

        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => 'Subject',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => '',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'message',
            'options' => array(
                'label' => 'Message',
            ),
            'attributes' => array(                
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'hidden',
                'id'=>'identity'
            ),
        ));
        $this->add(array(
            'name' => 'provider',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'hidden',
                'id'=>'provider'
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
                ->setLabel('Send')
                ->setAttributes(array(
                    'type' => 'submit','class'=>'btn btn-block btn-default floatright'
        ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->getEventManager()->trigger('init', $this);
    }

}
