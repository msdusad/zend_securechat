<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Crypt\Password\Bcrypt;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;
use ZfcUser\Mapper\LoggedInterface as LoggedMapperInterface;
use ZfcUser\Options\UserServiceOptionsInterface;
use ZfcUser\Options\UserLoggedOptionsInterface;
use Role\Model\UserRoleLinker as Role;

class User extends EventProvider implements ServiceManagerAwareInterface
{

    
    /**
     * @var UserMapperInterface
     */
    public  $userMapper;
     /**
     * @var LoggedMapperInterface
     */
    public  $loggedMapper;

    /**
     * @var AuthenticationService
     */
    protected $authService;

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
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Entity\UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function register(array $data)
    {
        $class = $this->getOptions()->getUserEntityClass();
        $user  = new $class;
        $form  = $this->getRegisterForm();
        $form->setHydrator(new ClassMethods());
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $user = $form->getData();
        /* @var $user \ZfcUser\Entity\UserInterface */

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());
        $user->setPassword($bcrypt->create($user->getPassword()));

        if ($this->getOptions()->getEnableUsername()) {
            $user->setUsername($data['username']);
        }
        if ($this->getOptions()->getEnableDisplayName()) {
            $user->setDisplayName($data['display_name']);
        }
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user, 'form' => $form));
        
        return $user;
    }

    /**
     * change the current users password
     *
     * @param array $data
     * @return boolean
     */
    public function changePassword(array $data)
    {
        $currentUser = $this->getAuthService()->getIdentity();

        $oldPass = $data['credential'];
        $newPass = $data['newCredential'];

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());

        if (!$bcrypt->verify($oldPass, $currentUser->getPassword())) {
            return 'Current';
        }

        $pass = $bcrypt->create($newPass);
        $currentUser->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $currentUser));

        return true;
    }

    public function changeEmail(array $data)
    {
        $currentUser = $this->getAuthService()->getIdentity();

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());

        if (!$bcrypt->verify($data['credential'], $currentUser->getPassword())) {
            return false;
        }

        $currentUser->setEmail($data['newIdentity']);
        $currentUser->setUserName($data['username']);
         $currentUser->setDisplayName($data['display_name']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $currentUser));

        return true;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapperInterface $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }
    /**
     * getUserMapper
     *
     * @return LoggedMapperInterface
     */
    public function getLoggedMapper()
    {
        if (null === $this->loggedMapper) {
            $this->loggedMapper = $this->getServiceManager()->get('zfcuser_logged_mapper');
        }
        return $this->loggedMapper;
    }

    /**
     * setUserMapper
     *
     * @param LoggedMapperInterface $userMapper
     * @return User
     */
    public function setLoggedMapper(LoggedMapperInterface $userMapper)
    {
        $this->loggedMapper = $userMapper;
        return $this;
    }
    
    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = $this->getServiceManager()->get('zfcuser_auth_service');
        }
        return $this->authService;
    }

    /**
     * setAuthenticationService
     *
     * @param AuthenticationService $authService
     * @return User
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * @return Form
     */
    public function getRegisterForm()
    {
        if (null === $this->registerForm) {
            $this->registerForm = $this->getServiceManager()->get('zfcuser_register_form');
        }
        return $this->registerForm;
    }

    /**
     * @param Form $registerForm
     * @return User
     */
    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * @return Form
     */
    public function getChangePasswordForm()
    {
        if (null === $this->changePasswordForm) {
            $this->changePasswordForm = $this->getServiceManager()->get('zfcuser_change_password_form');
        }
        return $this->changePasswordForm;
    }

    /**
     * @param Form $changePasswordForm
     * @return User
     */
    public function setChangePasswordForm(Form $changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * get service options
     *
     * @return UserServiceOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserServiceOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * set service options
     *
     * @param UserServiceOptionsInterface $options
     */
    public function setOptions(UserServiceOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $locator
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}