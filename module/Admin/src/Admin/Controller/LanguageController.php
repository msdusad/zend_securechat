<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Language;
use Admin\Form\LanguageForm;

class LanguageController extends AbstractActionController {

    protected $languageTable;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getLanguageTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/language');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the getLanguageTable
        $paginator = $this->getLanguageTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
      
        $form = new LanguageForm();
        
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $class = new Language();
            
            $form->setInputFilter($class->getInputFilter());
            
            $form->setData($request->getPost());

            if ($form->isValid()) {
               
                $class->exchangeArray($form->getData());
              
                $this->getLanguageTable()->save($class);

                // Redirect to list of language
                return $this->redirect()->toRoute('admin/language');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/language', array(
                        'action' => 'add'
            ));
        }
        $faq = $this->getLanguageTable()->getLanguage($id);

        $form = new LanguageForm();
        $class = new Language();
        
        $form->bind($faq);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($class->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getLanguageTable()->save($form->getData());
                // Redirect to list of faq
                return $this->redirect()->toRoute('admin/language');
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
            return $this->redirect()->toRoute('admin/language');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getLanguageTable()->delete($id);
            }

            // Redirect to list of cms
            return $this->redirect()->toRoute('admin/language');
        }

        return array(
            'id' => $id,
            'language' => $this->getLanguageTable()->getLanguage($id)
        );
    }

    public function getLanguageTable() {
        if (!$this->languageTable) {
            $sm = $this->getServiceLocator();
            $this->languageTable = $sm->get('Admin\Model\LanguageTable');
        }
        return $this->languageTable;
    }

}