<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Template;
use Admin\Form\TemplateForm;

class TemplateController extends AbstractActionController {

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
            return $this->redirect()->toRoute('admin/cremation-templates');
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
        $form = new TemplateForm();
        
        $form->get('submit')->setValue('Add');
        
        $languages = $this->getTemplateTable()->getLanguages();

        $form->get('language_id')->setAttributes(array('options' => $languages));

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $template = new Template();
            
            $form->setInputFilter($template->getInputFilter());
           
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $template->exchangeArray($form->getData());
              
                $this->getTemplateTable()->save($template);

                // Redirect to list of template
                return $this->redirect()->toRoute('admin/cremation-templates');
            }
        }
        return array('form' => $form);
    }

   
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/cremation-templates', array(
                        'action' => 'add'
            ));
        }
        $template = $this->getTemplateTable()->getTemplate($id);

        $form = new TemplateForm();
        
        $languages = $this->getTemplateTable()->getLanguages();

        $form->get('language_id')->setAttributes(array('options' => $languages));
        
        $form->bind($template);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTemplateTable()->save($form->getData());
                // Redirect to list of template
                return $this->redirect()->toRoute('admin/cremation-templates');
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
            return $this->redirect()->toRoute('admin/cremation-templates');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTemplateTable()->delete($id);
            }
            // Redirect to list of template
            return $this->redirect()->toRoute('admin/cremation-templates');
        }

        return array(
            'id' => $id,
            'template' => $this->getTemplateTable()->getTemplate($id)
        );
    }

    public function getTemplateTable() {
        if (!$this->templateTable) {
            $sm = $this->getServiceLocator();
            $this->templateTable = $sm->get('Admin\Model\TemplateTable');
        }
        return $this->templateTable;
    }

}