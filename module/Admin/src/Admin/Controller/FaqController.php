<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Faq;
use Admin\Form\FaqForm;

class FaqController extends AbstractActionController {

    protected $faqTable;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getFaqTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/faq');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getFaqTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
      
        $form = new FaqForm();
        
        $categories = $this->getFaqTable()->getCategories();
       
        $form->get('category_id')->setAttributes(array('options' => $categories));
        
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $faq = new Faq();
            $form->setInputFilter($faq->getInputFilter());
            
            $form->setData($request->getPost());

            if ($form->isValid()) {
               
                $faq->exchangeArray($form->getData());
              
                $this->getFaqTable()->savePage($faq);

                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/faq');
            }
        }
        return array('form' => $form);
    }

    
     public function faqAction() {
         
         $faqCategories = $this->getFaqTable()->getCategories();
          
         foreach($faqCategories as $category){
         
             $faqList[$category['label']] = $this->getFaqTable()->faqList($category['value']);
         }
        
          return new ViewModel(array(
            'faqList' => $faqList
        ));
     }
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('faq', array(
                        'action' => 'add'
            ));
        }
        $faq = $this->getFaqTable()->getPage($id);

        $form = new FaqForm();
       
        $categories = $this->getFaqTable()->getCategories();
       
        $form->get('category_id')->setAttributes(array('options' => $categories));
               
        $faqClass = new Faq();
        
        $form->bind($faq);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($faqClass->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) { 
                $this->getFaqTable()->savePage($form->getData());
                // Redirect to list of faq
                return $this->redirect()->toRoute('admin/faq');
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
            return $this->redirect()->toRoute('admin/faq');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getFaqTable()->deletePage($id);
            }

            // Redirect to list of cms
            return $this->redirect()->toRoute('admin/faq');
        }

        return array(
            'id' => $id,
            'faq' => $this->getFaqTable()->getPage($id)
        );
    }

    public function getFaqTable() {
        if (!$this->faqTable) {
            $sm = $this->getServiceLocator();
            $this->faqTable = $sm->get('Admin\Model\FaqTable');
        }
        return $this->faqTable;
    }

}