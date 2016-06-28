<?php

namespace Admin\Form;

use Admin\Entity\Category;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContentFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('content');
        $this->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Category());

        $this->setLabel('Content');

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'content',          
            'options' => array(
                'label' => 'Day 1',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
    }

    /**
     * @return array
     \*/
    public function getInputFilterSpecification()
    {
        return array(
            'content' => array(
                'required' => false,
            )
        );
    }
}
?>
