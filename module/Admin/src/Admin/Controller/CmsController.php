<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Cms;
use Admin\Form\CmsForm;

class CmsController extends AbstractActionController {

    protected $cmsTable;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getCmsTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/cms');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getCmsTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
        $form = new CmsForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $cms = new Cms();
            $form->setInputFilter($cms->getInputFilter());

           
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $cms->exchangeArray($form->getData());
              
                $this->getCmsTable()->savePage($cms);

                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/cms');
            }
        }
        return array('form' => $form);
    }

    
     public function pageAction() {
         
           $this->layout()->setTemplate('layout/layout');
           $id = $this->params()->fromRoute('id');
           return array('page' => $this->getCmsTable()->getFrontPage($id));
     }
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cms', array(
                        'action' => 'add'
            ));
        }
        $cms = $this->getCmsTable()->getPage($id);

        $form = new CmsForm();
        $form->bind($cms);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($cms->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCmsTable()->savePage($form->getData());
                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/cms');
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
            return $this->redirect()->toRoute('admin/cms');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCmsTable()->deletePage($id);
            }

            // Redirect to list of cms
            return $this->redirect()->toRoute('admin/cms');
        }

        return array(
            'id' => $id,
            'cms' => $this->getCmsTable()->getPage($id)
        );
    }

    public function getCmsTable() {
        if (!$this->cmsTable) {
            $sm = $this->getServiceLocator();
            $this->cmsTable = $sm->get('Admin\Model\CmsTable');
        }
        return $this->cmsTable;
    }

}