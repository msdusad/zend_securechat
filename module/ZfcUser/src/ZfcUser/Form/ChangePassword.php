<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;
use ZfcUser\Module as ZfcUser;
use Zend\Captcha;
class ChangePassword extends ProvidesEventsForm
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authOptions;

    public function __construct($urlcaptcha = null,$name = null, AuthenticationOptionsInterface $options)
    {
       
        $this->setAuthenticationOptions($options);
        parent::__construct($name);

        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(array(
            'name' => 'credential',
            'options' => array(
                'label' => 'Current Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => 'New Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'options' => array(
                'label' => 'Verify New Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));
        $dirdata = getcwd().'/module/ZfcUser/'; 
                               //pass captcha image options
        $captchaImage = new Captcha\Image(  array(
                'font' => $dirdata . 'fonts/arial.ttf',
                'width' => 150,
                'height' => 75,
                'dotNoiseLevel' => 40,
                'lineNoiseLevel' => 3)
        );
      $path = $urlcaptcha.'../module/ZfcUser/captcha';
        $captchaImage->setImgDir($dirdata.'captcha');
        $captchaImage->setImgUrl($path);
 
        //add captcha element...
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please verify you are human',
                'captcha' => $captchaImage,
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Update',
                'type'  => 'submit',
                'class'=> 'btn btn-default btn-sm'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * Set Authentication-related Options
     *
     * @param AuthenticationOptionsInterface $authOptions
     * @return Login
     */
    public function setAuthenticationOptions(AuthenticationOptionsInterface $authOptions)
    {
        $this->authOptions = $authOptions;
        return $this;
    }

    /**
     * Get Authentication-related Options
     *
     * @return AuthenticationOptionsInterface
     */
    public function getAuthenticationOptions()
    {
        return $this->authOptions;
    }
}
