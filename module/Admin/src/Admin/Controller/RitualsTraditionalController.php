<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\RitualsTraditional;
use Admin\Form\RitualsTraditionalForm;

class RitualsTraditionalController extends AbstractActionController {

    protected $traditionalTable;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getTraditionalTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/traditionals');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getTraditionalTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
        
        $form = new RitualsTraditionalForm();
        
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $traditional = new RitualsTraditional();
            $form->setInputFilter($traditional->getInputFilter());

           
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $traditional->exchangeArray($form->getData());
              
                $this->getTraditionalTable()->save($traditional);

                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/traditionals');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('traditionals', array(
                        'action' => 'add'
            ));
        }
        $traditional = $this->getTraditionalTable()->getById($id);

        $form = new RitualsTraditionalForm();
        $form->bind($traditional);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($traditional->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTraditionalTable()->save($form->getData());
                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/traditionals');
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
            return $this->redirect()->toRoute('admin/traditionals');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTraditionalTable()->delete($id);
            }

            // Redirect to list of cms
            return $this->redirect()->toRoute('admin/traditionals');
        }

        return array(
            'id' => $id,
            'traditional' => $this->getTraditionalTable()->getPage($id)
        );
    }

    public function getTraditionalTable() {
        if (!$this->traditionalTable) {
            $sm = $this->getServiceLocator();
            $this->traditionalTable = $sm->get('Admin\Model\RitualsTraditionalTable');
        }
        return $this->traditionalTable;
    }

}