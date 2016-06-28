<?php

namespace Admin\Form;

use Zend\Form\Form;

class VideosForm extends Form
{
 public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('impact_videos');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
         $this->add(array(
            'name' => 'impact_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
      
       
        $this->add(array(
            'name' => 'video_url',
            'attributes' => array(
                'type'  => 'text',
                 'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Video Url',
            ),
        ));
       
         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add Video',
                'id' => 'submitbutton',
                'class'=>'btn btn-lg btn-primary btn-block',
            ),
        ));
    }
}