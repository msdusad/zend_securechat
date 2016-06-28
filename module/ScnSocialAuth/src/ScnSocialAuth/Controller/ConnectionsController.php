<?php

namespace ScnSocialAuth\Controller;

use Hybrid_Auth;
use Zend\Session\Container;
use ScnSocialAuth\Mapper\Exception as MapperException;
use ScnSocialAuth\Mapper\ConnectionsInterface;
use ScnSocialAuth\Mapper\LoggedInterface;
use ScnSocialAuth\Options\ModuleOptions;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\Loader\StandardAutoloader;
use Zend\Form\Form;

class ConnectionsController extends AbstractActionController {

    /**
     * @var UserProviderInterface
     */
    protected $mapper;

    /**
     * @var UserProviderInterface
     */
    protected $loggedmapper;
    protected $failedLoginMessage = 'Fields should not empty';

    /**
     * @var Form
     */
    protected $connectionsForm;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Hybrid_Auth
     */
    protected $hybridAuth;

    /**
     * User page
     */
    public function indexAction() {

        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $this->getMapper()->deleteConnections($ids);
                    $this->flashMessenger()->addMessage('Connections deleted successfully');
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('scn-social-connections');
        }


        $sm = $this->getServiceLocator();
        $service = $sm->get('zfcuser_user_service');
        $userId = $service->getAuthService()->getIdentity()->getId();
        $connections = $this->getMapper()->findConnections($userId);

        return new ViewModel(array('connections' => $connections, 'message' => $message));
    }

    public function addAction() {

        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $form = $this->getConnectionsForm();

        $hybridAuth = $this->getHybridAuth();

        $providers = $hybridAuth->getConnectedProviders();
        $userProfile = array();
        $currentProvider = $this->getEvent()->getRouteMatch()->getParam('provider');

        if ($currentProvider == '' && !empty($providers)) {

            $currentProvider = $providers[0];
        }
        if ($currentProvider != '') {

            $adapter = $hybridAuth->authenticate($currentProvider);
        }

        foreach ($providers as $provider) {

            if ($hybridAuth->isConnectedWith($provider)) {

                $adapter = $hybridAuth->authenticate($provider);
                $userProfile[] = $adapter->getUserContacts();
            }
        }
        $rows = count($userProfile);
        if ($rows > 0) {
            switch ($rows) {

                case 2:
                    $result = array_merge((array) $userProfile[0], (array) $userProfile[1]);
                    break;
                case 3:
                    $result = array_merge((array) $userProfile[0], (array) $userProfile[1], (array) $userProfile[2]);
                    break;
                case 4:
                    $result = array_merge((array) $userProfile[0], (array) $userProfile[1], (array) $userProfile[2], (array) $userProfile[3]);
                    break;
                default:
                    $result = $userProfile[0];
                    break;
            }
        } else {
            $result = array();
        }
        $request = $this->getRequest();
        $sm = $this->getServiceLocator();
        $service = $sm->get('zfcuser_user_service');
        $userId = $service->getAuthService()->getIdentity()->getId();
        
        $form->get('user_id')->setAttribute('value', $userId);
        if ($request->isPost()) {

            $data = $request->getPost();

            $form->setData($data);
            if (!$form->isValid()) {
               
                return array('contacts' => $result, 'providers' => $providers,
                    'currentProvider' => $currentProvider, 'form' => $form, 'userid' => $userId);
            }

            $this->getMapper()->save($form->getData());
            $this->flashMessenger()->addMessage('Connections added successfully');
            // Redirect to list of connections
            return $this->redirect()->toRoute('scn-social-connections');
        }

        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }

        return new ViewModel(array('contacts' => $result, 'providers' => $providers,
            'currentProvider' => $currentProvider, 'form' => $form, 'userid' => $userId, 'message' => $message));
    }

    public function getHybridAuth() {
        if (!$this->hybridAuth) {

            $this->hybridAuth = $this->getServiceLocator()->get('HybridAuth');
        }

        return $this->hybridAuth;
    }

    public function getConnectionsForm() {
        if (!$this->connectionsForm) {
            $this->setConnectionsForm($this->getServiceLocator()->get('scn-connections-form'));
        }
        return $this->connectionsForm;
    }

    public function setConnectionsForm(Form $connectionsForm) {
        $this->connectionsForm = $connectionsForm;
        $this->flashMessenger()->setNamespace('scn-connections-form')->getMessages();
        return $this;
    }

    /**
     * set mapper
     *
     * @param  UserProviderInterface $mapper
     * @return HybridAuth
     */
    public function setMapper(ConnectionsInterface $mapper) {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * get mapper
     *
     * @return UserProviderInterface
     */
    public function getMapper() {
        if (!$this->mapper instanceof ConnectionsInterface) {
            $this->setMapper($this->getServiceLocator()->get('ScnSocialAuth-ConnectionsMapper'));
        }

        return $this->mapper;
    }

}
