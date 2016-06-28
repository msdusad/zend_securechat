<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Category;
use Admin\Form\CategoryForm;

class CategoryController extends AbstractActionController {

    protected $categoryTable;

    public function indexAction() {
        
        $request = $this->getRequest();
        if ($request->isPost()) {
             $action = $request->getPost('action');
           
             $ids = $request->getPost('ids');

            switch($action){
                
                case 'multidelete':
                     $paginator = $this->getCategoryTable()->multiDelete($ids);
                    break;
                default :
                    break;
                
                
                
            }
             return $this->redirect()->toRoute('admin/category');
           
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getCategoryTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {
        $form = new CategoryForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) { 
                $category->exchangeArray($form->getData());
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of categories
                return $this->redirect()->toRoute('admin/category');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category', array(
                        'action' => 'add'
            ));
        }
        $category = $this->getCategoryTable()->getCategory($id);

        $form = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) { 
                $this->getCategoryTable()->saveCategory($form->getData());
                // Redirect to list of categories
                return $this->redirect()->toRoute('admin/category');
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
            return $this->redirect()->toRoute('admin/category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCategoryTable()->deleteCategory($id);
            }

            // Redirect to list of categories
            return $this->redirect()->toRoute('admin/category');
        }

        return array(
            'id' => $id,
            'category' => $this->getCategoryTable()->getCategory($id)
        );
    }

    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Admin\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

}