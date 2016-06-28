<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Admin\Model\Task;
use Admin\Form\TaskForm;
use Admin\Controller\CategoryController;
use Zend\Authentication\AuthenticationService;

class TaskController extends CategoryController {

    protected $categoryTable;
    protected $taskTable;

    public function indexAction() {
        
        $request = $this->getRequest();
        if ($request->isPost()) {
             $action = $request->getPost('action');
           
             $ids = $request->getPost('ids');

            switch($action){
                
                case 'multidelete':
                     $paginator = $this->getTaskTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
             return $this->redirect()->toRoute('admin/tasks');
           
        }
        // grab the paginator from the AlbumTable
        $paginator = $this->getTaskTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {

        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {

            $userId = $auth->getIdentity();
        } else {

            $userId = 0;
        }

        $form = new TaskForm();

        $form->get('submit')->setValue('Add');

        $categories = $this->getCategoryTable()->getCategories();

        $form->get('category_id')->setAttributes(array('options' => $categories));
        $form->get('user_id')->setAttributes(array('value' => $userId));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $task = new Task();
            $form->setInputFilter($task->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $task->exchangeArray($form->getData());
                $this->getTaskTable()->saveTask($task);

                // Redirect to list of albums
                return $this->redirect()->toRoute('admin/tasks');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/tasks', array(
                        'action' => 'add'
            ));
        }
        $task = $this->getTaskTable()->getTask($id);
        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {

            $userId = $auth->getIdentity();
        } else {

            $userId = 0;
        }

        $form = new TaskForm();
        $categories = $this->getCategoryTable()->getCategories();

        $form->get('category_id')->setAttributes(array('options' => $categories));
        $form->get('user_id')->setAttributes(array('value' => $userId));

        $form->bind($task);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($task->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTaskTable()->saveTask($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('admin/tasks');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/tasks');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTaskTable()->deleteTask($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('admin/tasks');
        }

        return array(
            'id' => $id,
            'tasks' => $this->getTaskTable()->getTask($id)
        );
    }

    public function getTaskTable() {
        if (!$this->taskTable) {
            $sm = $this->getServiceLocator();
            $this->taskTable = $sm->get('Admin\Model\TaskTable');
        }
        return $this->taskTable;
    }

}