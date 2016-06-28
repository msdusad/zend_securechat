<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Rituals;
use Admin\Form\RitualsForm;

class RitualsController extends AbstractActionController {

    protected $ritualsTable;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getRitualsTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/rituals');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getRitualsTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {

        $form = new RitualsForm();

        $form->get('submit')->setValue('Add');

        $traditionals = $this->getRitualsTable()->getTraditionals();
        $form->get('id')->setAttributes(array('value' => 0));
        $form->get('traditional_id')->setAttributes(array('options' => $traditionals));
        $collection = $form->get('content');
        $collection->setOptions(array('count' => 1))->prepareFieldset();
        $request = $this->getRequest();
        
        
        if ($request->isPost()) {

            $rituals = new Rituals();

            $form->setInputFilter($rituals->getInputFilter());

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $rituals->exchangeArray($form->getData());

                $this->getRitualsTable()->save($rituals);

                // Redirect to list of rituals
                return $this->redirect()->toRoute('admin/rituals');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/rituals', array(
                        'action' => 'add'
            ));
        }
        $traditionals = $this->getRitualsTable()->getTraditionals();

        $traditional = $this->getRitualsTable()->getRitual($id);
        $traditionalDays = $this->getRitualsTable()->getRitualDays($id);

        $form = new RitualsForm();
        $form->get('id')->setAttributes(array('value' => $id));
        $form->get('traditional_id')->setAttributes(array('options' => $traditionals));

        $collection = $form->get('content');
        $collection->setOptions(array('count' => count($traditionalDays)))->prepareFieldset();

        $fieldSets = $collection->getFieldsets();
        foreach ($fieldSets as $key => $fieldset) {
            $day = $key + 1;
            $fieldset->get('content')->setValue($traditionalDays[$key]['content']);
            $fieldset->get('content')->setLabel('Day ' . $day);
        }

        $form->bind($traditional);

        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($traditional->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $this->getRitualsTable()->save($form->getData());
                // Redirect to list of rituals
                return $this->redirect()->toRoute('admin/rituals');
            }
        }

        return array(
            'id' => $id,
            'form' => $form
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/rituals');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getRitualsTable()->delete($id);
            }

            // Redirect to list of cms
            return $this->redirect()->toRoute('admin/rituals');
        }
       $ritual = $this->getRitualsTable()->getRitual($id);
        
        return array(
            'id' => $id,
            'rituals' => $this->getRitualsTable()->getTraditional($ritual->traditional_id)
        );
    }

    public function getRitualsTable() {
        if (!$this->ritualsTable) {
            $sm = $this->getServiceLocator();
            $this->ritualsTable = $sm->get('Admin\Model\RitualsTable');
        }
        return $this->ritualsTable;
    }

}