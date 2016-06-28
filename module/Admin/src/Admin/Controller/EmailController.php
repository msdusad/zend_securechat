<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Email;
use Admin\Form\EmailForm;

class EmailController extends AbstractActionController {

    protected $templateTable;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getTemplateTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/templates');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getTemplateTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
        $form = new EmailForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $template = new Email();
            $form->setInputFilter($template->getInputFilter());

           
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $template->exchangeArray($form->getData());
              
                $this->getTemplateTable()->savePage($template);

                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/templates');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('templates', array(
                        'action' => 'add'
            ));
        }
        $template = $this->getTemplateTable()->getTemplate($id);

        $form = new EmailForm();
        $form->bind($template);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTemplateTable()->savePage($form->getData());
                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/templates');
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
            return $this->redirect()->toRoute('admin/templates');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTemplateTable()->deletePage($id);
            }

            // Redirect to list of cms
            return $this->redirect()->toRoute('admin/templates');
        }

        return array(
            'id' => $id,
            'template' => $this->getTemplateTable()->getTemplate($id)
        );
    }

    public function getTemplateTable() {
        if (!$this->templateTable) {
            $sm = $this->getServiceLocator();
            $this->templateTable = $sm->get('Admin\Model\EmailTable');
        }
        return $this->templateTable;
    }

}