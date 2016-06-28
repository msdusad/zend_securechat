<?php

namespace ZfcUser\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Form\ChangePassword;


class UserController extends AbstractActionController {

    protected $findTable;
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;

    /**
     * User page
     */
    public function indexAction() {

        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $authorize = $this->getServiceLocator()->get('BjyAuthorize\Provider\Identity\ProviderInterface');
        $roles = $authorize->getIdentityRoles();

        if ($roles[0] == 'admin') {

            return $this->redirect()->toRoute('administrator');
        }else{
             return $this->redirect()->toRoute('add-death-user');
        }
		
       return new ViewModel();
    }

    /**
     * Login form
     */
  


    public function loginAction() {
        $request = $this->getRequest();
        $form = $this->getLoginForm();
        $rForm = $this->getRegisterForm();
		
		
        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }
      
        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'registerForm' => $rForm,
                'redirect' => '../',
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'disablelayout' =>1,
            );
        }
        
        $user = $this->getUserService()->getUserMapper()->findByEmail($request->getPost('identity'));

        if(!$user){
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
                    return $this->redirect()->toUrl($this->url('zfcuser')->fromRoute('zfcuser/login') . ($redirect ? '?redirect=' . $redirect : ''));
        }
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url('zfcuser')->fromRoute('zfcuser/login') . ($redirect ? '?redirect=' . $redirect : ''));
        }
        // clear adapters
		$this->layout('layout/custom');
        return $this->forward()->dispatch('zfcuser', array('action' => 'authenticate'));
    }

    /**
     * Logout and clear the identity
     */
    public function logoutAction() {
        
        $user_id = $this->getUserService()->getAuthService()->getIdentity()->getId();

        $this->getFindTable()->deleteLog($user_id);
        
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        $redirect = ($this->getRequest()->getPost()->get('redirect')) ? $this->getRequest()->getPost()->get('redirect') : false;

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
    }

    /**
     * General-purpose authentication action
     */
    public function authenticateAction() {
        // echo '<pre>';print_r($_REQUEST);die;
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {

            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $request = $this->getRequest();
        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $request->getPost()->get('redirect') ? $request->getPost()->get('redirect') : false;

        $result = $adapter->prepareForAuthentication($request);

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        if (!$auth->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl($this->url()->fromRoute('zfcuser/login') . ($redirect ? '?redirect=' . $redirect : ''));
        }

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }
      

        $user_id = $this->getUserService()->getAuthService()->getIdentity()->getId();

        $this->getFindTable()->saveLog($user_id);
        
        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
    }

    /**
     * Register new user
     */
    public function registerAction() {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute('zfcuser/register') . ($redirect ? '?redirect=' . $redirect : '');
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        // save the new user in the database
        // add a role
        $post = $prg;
        $user = $service->register($post);

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }


        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            $request->setPost(new Parameters($post));
            return $this->forward()->dispatch('zfcuser', array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute('zfcuser/login') . ($redirect ? '?redirect=' . $redirect : ''));
    }

    /**
     * Change the users password
     */
    public function changepasswordAction() {

        $form = $this->getChangePasswordForm();
         //$form = new ChangePassword($this->getRequest()->getBaseUrl().'/test/testcaptcha/captcha/');
        
        $prg = $this->prg('zfcuser/changepassword');

        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changePasswordForm' => $form,
            );
        }
        $user = $this->getUserService()->getUserMapper()->findByEmail($prg['identity']);
        $bcrypt = new Bcrypt();
        $securePass = $user->password;
        $password = $prg['credential'];
        
        $this->generateAction($prg['captcha']['id']);

        if (!$bcrypt->verify($password, $securePass)) {
          
            return array(
                'status' => 'orginal',
                'changePasswordForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        if ($prg['credential'] == $prg['newCredential']) {
            return array(
                'status' => 'uniq',
                'changePasswordForm' => $form,
            );
        }
        
        $status = $this->getUserService()->changePassword($form->getData());
        return array(
            'status' => $status,
            'changePasswordForm' => $form,
        );
      

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);
        return $this->redirect()->toRoute('zfcuser/changepassword');
    }

    public function changeEmailAction() {


        $user = $this->getUserService()->getUserMapper()->findById($this->getUserService()->getAuthService()->getIdentity()->getId());


        $form = $this->getChangeEmailForm();
        $form->get('username')->setValue($user->username);
        $form->get('display_name')->setValue($user->displayName);

        $request = $this->getRequest();
        $request->getPost()->set('identity', $this->getUserService()->getAuthService()->getIdentity()->getEmail());

        $fm = $this->flashMessenger()->setNamespace('change-email')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        $prg = $this->prg('zfcuser/changeemail');
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changeEmailForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) { 
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $change = $this->getUserService()->changeEmail($prg);

        if (!$change) {
            $this->flashMessenger()->setNamespace('change-email')->addMessage(false);
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-email')->addMessage(true);
        return $this->redirect()->toRoute('zfcuser/changeemail');
    }

    /**
     * Getters/setters for DI stuff
     */
    public function getUserService() {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService) {
        $this->userService = $userService;
        return $this;
    }

    public function getRegisterForm() {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('zfcuser_register_form'));
        }
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm) {
        $this->registerForm = $registerForm;
    }

    public function getLoginForm() {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('zfcuser_login_form'));
        }
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm) {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                    array('identity' => array($fm[0]))
            );
        }
        return $this;
    }

    public function getChangePasswordForm() {
        if (!$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('zfcuser_change_password_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm) {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(UserControllerOptionsInterface $options) {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions() {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * Get changeEmailForm.
     *
     * @return changeEmailForm.
     */
    public function getChangeEmailForm() {
        if (!$this->changePasswordForm) {
            $this->setChangeEmailForm($this->getServiceLocator()->get('zfcuser_change_email_form'));
        }
        return $this->changeEmailForm;
    }

    /**
     * Set changeEmailForm.
     *
     * @param changeEmailForm the value to set.
     */
    public function setChangeEmailForm($changeEmailForm) {
        $this->changeEmailForm = $changeEmailForm;
        return $this;
    }
     /**
     * set mapper
     *
     * @param  UserProviderInterface $mapper
     * @return HybridAuth
     */
   public function getFindTable() {
        if (!$this->findTable) {
            $sm = $this->getServiceLocator();
            $this->findTable = $sm->get('CremationPlan\Model\FindTable');
        }
        return $this->findTable;
    }
    
    public function generateAction($id)
    {       
        if ($id) { 
            $image = dirname(__DIR__).'./../../captcha/' . $id.'.png';
 
            if (file_exists($image) !== false) {
               
                if (file_exists($image) == true) {
                    unlink($image);
                }
            }
 
        }
 
        return true;
    }



}
