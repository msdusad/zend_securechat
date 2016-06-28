<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;

class UsersController extends AbstractActionController {

    protected $userTable;
    protected $userProviderTable;
    protected $userRoleTable;

    /**
     * @var Form
     */
    protected $searchForm;

    public function indexAction() {

        $request = $this->getRequest();
        $form = $this->getSearchForm();
        $searchQuery = $category = $action = '';

        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getUserTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/users');
        }

        // grab the paginator from the AlbumTable
        $paginator = $this->getUserTable()->fetchAll(true, $category, $searchQuery);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array('paginator' => $paginator, 'searchForm' => $form));
    }

    public function deleteAction() {


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/users');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');

                $this->getUserRoleTable()->delete($id);
                $this->getUserTable()->delete($id);
                $this->getUserProviderTable()->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('admin/users');
        }

        return array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id)
        );
    }

    public function deleteaccountAction() {


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('zfcuser/logout');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');

               
                $this->getUserTable()->delete($id);
                
            }
            session_destroy();
            // Redirect to list of albums
            return $this->redirect()->toRoute('zfcuser/login');
        }

        return array(
            'id' => $id,
            'user' => $this->getUserTable()->getUser($id)
        );
    }

    /**
     * Get Country Table from service manager 
     */
    public function getUserProviderTable() {
        if (!$this->userProviderTable) {
            $sm = $this->getServiceLocator();
            $this->userProviderTable = $sm->get('Admin\Model\UserProviderTable');
        }

        return $this->userProviderTable;
    }

    /**
     * Get Country Table from service manager 
     */
    public function getUserRoleTable() {
        if (!$this->userRoleTable) {
            $sm = $this->getServiceLocator();
            $this->userRoleTable = $sm->get('Admin\Model\UserRoleTable');
        }

        return $this->userRoleTable;
    }

    /**
     * Get Country Table from service manager 
     */
    public function getUserTable() {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Admin\Model\UserTable');
        }

        return $this->userTable;
    }

    public function getSearchForm() {
        if (!$this->searchForm) {
            $this->setSearchForm($this->getServiceLocator()->get('admin-search-form'));
        }
        return $this->searchForm;
    }

    public function setSearchForm(Form $searchForm) {
        $this->searchForm = $searchForm;
        $this->flashMessenger()->setNamespace('admin-search-form')->getMessages();

        return $this;
    }

}