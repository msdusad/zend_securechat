<?php

namespace ScnSocialAuth\Controller;

use Hybrid_Auth;
use ScnSocialAuth\Controller\UserController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use ScnSocialAuth\Mapper\ScnContactsInterface;

class ContactsController extends UserController {

    /**
     * @var Form
     */
    protected $emailForm;
    /**
     * @var Form
     */
    protected $contactsMapper;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Hybrid_Auth
     */
    protected $hybridAuth;

    /**
     * @var Email_Message
     */
    protected $emailMessage;

    /**
     * User page
     */
    public function indexAction() {

          if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $returnUrl = $this->getEvent()->getRouteMatch()->getParam('return');
        $id = $this->zfcUserAuthentication()->getAuthService()->getIdentity()->getId();
        $form = $this->getEmailForm();

        $hybridAuth = $this->getHybridAuth();

        $providers = $hybridAuth->getConnectedProviders();


        $currentProvider = $this->getEvent()->getRouteMatch()->getParam('provider');

        if ($currentProvider == '' && !empty($providers)) {

            $currentProvider = $providers[0];
        }
        if ($currentProvider != '') {

            $adapter = $hybridAuth->authenticate($currentProvider);
        }

        $userProfile = array();

        foreach ($providers as $provider) {

            if ($hybridAuth->isConnectedWith($provider)) {

                $adapter = $hybridAuth->authenticate($provider);
                //$adapter->logout();
                $userProfile[] = $adapter->getUserContacts();
            }
        }
        $rows = count($userProfile);
        $result = array();
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
        }
        if ($hybridAuth->isConnectedWith($currentProvider)) {

            $userProfile = $adapter->getUserContacts();
        } else {

            $userProfile = array();
            $providers = array();
        }


        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            switch ($action) {

                case 'import':

                    if ($_FILES['csv']['size'] > 0) {

                        //get the csv file 
                        $file = $_FILES['csv']['tmp_name'];
                        $handle = fopen($file, "r");

                        $row = 0;
                        $contacts = array();
                        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

                          
                            if(count($data)<7){
                                $this->flashMessenger()->addMessage('excel format is wrong  check with sample excel file');
                                return $this->redirect()->toRoute('scn-social-contact');
                            }
                            if ($row != 0) {
                                $contacts[$row]['first_name'] = $data[0];
                                $contacts[$row]['last_name'] = $data[1];
                                $contacts[$row]['email'] = $data[2];
                                $contacts[$row]['location'] = $data[3];
                                $contacts[$row]['home'] = $data[4];
                                $contacts[$row]['company'] = $data[5];
                                $contacts[$row]['job_title'] = $data[6];
                                $originalDate = $data[7];
                                $newDate = date("Y-m-d", strtotime($originalDate));
                                $contacts[$row]['birthday'] = $newDate;
                                $contacts[$row]['user_id'] = $id;
                            }
                            $row++;
                        }
                        $this->getScnContactsMapper()->importContacts($contacts);
                        fclose($handle);
                        $this->flashMessenger()->addMessage('contacts import successfully');
                        return $this->redirect()->toRoute('scn-social-contact');
                    }
                    break;
                case 'export':
                    $users = $request->getPost('users');
                    $first_name = $request->getPost('first_name');
                    $last_name = $request->getPost('last_name');
                    $email = $request->getPost('email');
                    $location = $request->getPost('location');
                    $home = $request->getPost('home');
                    $company = $request->getPost('company');
                    $job_title = $request->getPost('job_title');
                    $birthday = $request->getPost('birthday');
                    if ($users != '') {

                        header("Content-type: text/csv");
                        header("Content-Disposition: attachment; filename=contacts.csv");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                        $output = fopen('php://output', 'w');
// output the column headings
                        fputcsv($output, array('First Name', 'Last Name', 'Email', 'Current Location', 'Home Location', 'Company', 'Job Title', 'Birthday'));
//

                        $f = fopen("contacts.csv", "w");
                        $resultset = array();
                        foreach ($users as $key => $value) {

                            $resultset[$key]['first_name'] = $first_name[$key];
                            $resultset[$key]['last_name'] = $last_name[$key];
                            $resultset[$key]['email'] = $email[$key];
                            $resultset[$key]['location'] = $location[$key];
                            $resultset[$key]['home'] = $home[$key];
                            $resultset[$key]['company'] = $company[$key];
                            $resultset[$key]['job_title'] = $job_title[$key];
                            $resultset[$key]['birthday'] = $birthday[$key];
                            fputcsv($output, $resultset[$key]);
                        }
                        fclose($output);
                        exit;
                    }
                    // return $this->redirect()->toRoute('scn-social-contact');
                    break;
                default :
            }
        }
        $contactList = $this->getScnContactsMapper()->getContacts($id);

        $contacts = array();
        if (count($contactList) > 0) {
            foreach ($contactList as $key => $list) {
                $contacts[$key]->displayName = $list['first_name'] . ' ' . $list['last_name'];
                $contacts[$key]->email = $list['email'];
                $contacts[$key]->identity = $list['id'];
                $contacts[$key]->from = 'database';
                $contacts[$key]->photoURL = '';
                $contacts[$key]->location = $list['location'];
                $contacts[$key]->home = $list['home'];
                $contacts[$key]->company = $list['company'];
                $contacts[$key]->jobTitle = $list['job_title'];
                $contacts[$key]->birthday = $list['birthday'];
            }
            $result = array_merge((array) $result, (array) $contacts);
        }
        if (isset($returnUrl) && $returnUrl != '') {

            return $this->redirect()->toRoute($returnUrl);
        }
         $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }


        return new ViewModel(array('contacts' => $result, 'providers' => $providers,
            'currentProvider' => $currentProvider, 'emailForm' => $form, 'message' => $message));
    }

    public function getHybridAuth() {
        if (!$this->hybridAuth) {

            $this->hybridAuth = $this->getServiceLocator()->get('HybridAuth');
        }

        return $this->hybridAuth;
    }

    public function getEmailForm() {
        if (!$this->emailForm) {
            $this->setEmailForm($this->getServiceLocator()->get('scn-email-form'));
        }
        return $this->emailForm;
    }

    public function setEmailForm(Form $emailForm) {
        $this->emailForm = $emailForm;
        $this->flashMessenger()->setNamespace('scn-email-form')->getMessages();
        return $this;
    }

    public function sendAction() {

        $hybridAuth = $this->getHybridAuth();

        $request = $this->getRequest();

        $currentProvider = $request->getPost('provider');

        $adapter = $hybridAuth->authenticate($currentProvider);

        $available_providers_list = array(
            "Google",
            "Yahoo",
            "Facebook",
        );

        $identity = $request->getPost('identity');

        $subject = $request->getPost('subject');

        $message = $request->getPost('message');

        if (in_array($currentProvider, $available_providers_list)) {

            if ($currentProvider == 'Facebook') {

                $identity = $identity . '@facebook.com';
            }
            $this->emailMessage = new Message();
            $sm = $this->getServiceLocator();
            $service = $sm->get('zfcuser_user_service');
            $from = $service->getAuthService()->getIdentity()->getEmail();
            $this->emailMessage->addFrom($from, "")
                    ->addTo($identity)
                    ->setSubject($subject);
            $this->emailMessage->setBody($message);

            $transport = new SendmailTransport();
            $transport->send($this->emailMessage);

            $apiCallStatus = 'send';
        } else {
            $params = array(
                'identity' => array($identity),
                'subject' => $subject,
                'message' => $message
            );
            $apiCallStatus = $adapter->sendMessageById($params);
        }

        if (empty($apiCallStatus)) {
            $data = array(
                'result' => false,
                'data' => $identity
            );
        } else {
            $data = array(
                'result' => true,
                'data' => $identity
            );
        }
        return $this->getResponse()->setContent(json_encode($data));
    }

    public function accountsAction() {

        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $hybridAuth = $this->getHybridAuth();

        //$providers = $hybridAuth->getConnectedProviders();

        $currentProvider = $this->getEvent()->getRouteMatch()->getParam('provider');
        $returnUrl = $this->getEvent()->getRouteMatch()->getParam('return');

        if ($hybridAuth->isConnectedWith($currentProvider)) {

            $adapter = $hybridAuth->authenticate($currentProvider);
            $adapter->logout();

            return $this->redirect()->toRoute($returnUrl);
        }
        $providers = $hybridAuth->getConnectedProviders();

        return new ViewModel(array('providers' => $providers));
    }
/**
     * set mapper
     *
     * @param  ContactsInterface $mapper
     * @return HybridAuth
     */
    public function setScnContactsMapper(ScnContactsInterface $mapper) {
        $this->contactsMapper = $mapper;

        return $this;
    }

    /**
     * get mapper
     *
     * @return ContactsInterface
     */
    public function getScnContactsMapper() {
        if (!$this->contactsMapper instanceof ScnContactsInterface) {
            $this->setScnContactsMapper($this->getServiceLocator()->get('ScnSocialAuth-ScnContactsMapper'));
        }

        return $this->contactsMapper;
    }
}
