<?php

namespace ScnSocialAuth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Form\Login as LoginForm;
use ZfcUser\Form\Register as RegisterForm;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ScnSocialAuth\Options;

class UserLoginWidget extends AbstractHelper 
{
    protected $sm;
    /**
     * Login Form
     * @var LoginForm
     */
    protected $loginForm;
    
     /**
     * register Form
     * @var LoginForm
     */
    protected $registerForm;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;
    /**
     * __invoke
     *
     * @access public
     * @param array $options array of options
     * @return string
     */
    
    public function __construct($sm) {
        $this->sm = $sm;

    }
    public function __invoke($options = array())
    {
        if (array_key_exists('render', $options)) {
            $render = $options['render'];
        } else {
            $render = true;
        }
        if (array_key_exists('redirect', $options)) {
            $redirect = $options['redirect'];
        } else {
            $redirect = false;
        }
        $socialSignIn = true;
        $options = $this->sm->getServiceLocator()->get('scn_module_options');
       if ($socialSignIn) {
                $socialSignIn = false;
            }
      echo '<div class="socialbuttons"><ul>';
        foreach ($options->getEnabledProviders() as $provider) {
           
          $redirectArg = $redirect ? '?redirect=' . $redirect : '';
            echo
            '<li class="sprites social-media-'.strtolower($provider).'"><a class="social '.$provider.'" href="'
            . $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider))
            . $redirectArg . '">' . ucfirst($provider) . '</a></li>';
            
            //$this->socialSignInButton($provider, $redirect);
        }
        echo '</ul></div>';
        
//        $vm = new ViewModel(array(
//            'loginForm' => $this->getLoginForm(),
//            'redirect'  => $redirect,
//            'registerForm' => $this->getRegisterForm(),
//            'options' => $options
//            
//        ));
//        $vm->setTemplate($this->viewTemplate);
//        if ($render) {
//            return $this->getView()->render($vm);
//        } else {
//            return $vm;
//        }
    }
    
    public function getRegisterForm() {
       
        return $this->registerForm;
    }

    public function setRegisterForm(RegisterForm $registerForm) {
        $this->registerForm = $registerForm;
    }

   
    /**
     * Retrieve Login Form Object
     * @return LoginForm
     */
    public function getLoginForm()
    {
        return $this->loginForm;
    }

    /**
     * Inject Login Form Object
     * @param LoginForm $loginForm
     * @return ZfcUserLoginWidget
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }
    
    /**
     * @param string $viewTemplate
     * @return ZfcUserLoginWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }

   
    
}
