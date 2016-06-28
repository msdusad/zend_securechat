<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Donation;
use Admin\Form\DonationForm;
use Zend\Validator\File\Size;


class DonationController extends AbstractActionController {

    protected $donationTable;

    public function indexAction() {


        $categories = $this->getDonationTable()->getCategories();

        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $action = $request->getPost('action');
           
            $categoryId = $request->getPost('category_id');

            if ($categoryId != '') {

                //$this->layout('layout/admin');
                // grab the paginator from the AlbumTable
                $paginator = $this->getDonationTable()->fetchAll(true, $categoryId);
                // set the current page to what has been passed in query string, or to 1 if none set
                $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
                // set the number of items per page to 10
                $paginator->setItemCountPerPage();

                return new ViewModel(array(
                    'paginator' => $paginator, 'categories' => $categories,'category'=>$categoryId
                ));
            }

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getDonationTable()->multiDelete($ids);
                    return $this->redirect()->toRoute('admin/donation');
                    break;
                default :
                    break;
            }
            //
        }

        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getDonationTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator, 'categories' => $categories,'category'=>''
        ));
    }

    public function addAction() {

        $form = new DonationForm();
        $form->get('submit')->setValue('Add');
        $categories = $this->getDonationTable()->getCategories();

        $form->get('category_id')->setAttributes(array('options' => $categories));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $find = new Donation();
            $form->setInputFilter($find->getInputFilter());

            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('photo');
            $data = array_merge(
                    $nonFile, //POST 
                    array('photo' => $File['name']) //FILE...
            );

            $form->setData($data);

            if ($form->isValid()) {

                $size = new Size(array('min' => 20000)); //minimum bytes filesize

                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size), $File['name']);
                if (!$adapter->isValid()) {
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }

                    $form->setMessages(array('fileupload' => $error));

                    $find->exchangeArray($form->getData());

                    $this->getDonationTable()->saveList($find);
                } else {
                    $dirName = dirname(__DIR__) . '/../../assets/donation';
                    $adapter->setDestination($dirName);
                    if ($adapter->receive($File['name'])) {

                        $find->exchangeArray($form->getData());

                        $id = $this->getDonationTable()->saveList($find);

                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $thumb = $thumbnailer->create($dirName . '/' . $data['photo'], $options = array());

                        $thumb->resize(72, 72);

                        //$thumb->show();
                        // or/and
                        $thumb->save($dirName . '/thumb/' . $id . '_' . $data['photo']);
                    }
                }
                return $this->redirect()->toRoute('admin/donation');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('find', array(
                        'action' => 'add'
            ));
        }
        $find = $this->getDonationTable()->getListById($id);
        $findClass = new Donation();
        $form = new DonationForm();
        $form->bind($find);
        $form->get('submit')->setAttribute('value', 'Edit');
        $categories = $this->getDonationTable()->getCategories();

        $form->get('category_id')->setAttributes(array('options' => $categories));
        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($findClass->getInputFilter());

            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('photo');
            $data = array_merge(
                    $nonFile, //POST 
                    array('photo' => $File['name']) //FILE...
            );

            $form->setData($data);

            if ($form->isValid()) {

                $size = new Size(array('min' => 20000)); //minimum bytes filesize

                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size), $File['name']);
                if (!$adapter->isValid()) {
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }
                    unset($data['photo']);

                    $findClass->exchangeArray($data);

                    $this->getDonationTable()->saveList($findClass);

                    $form->setMessages(array('fileupload' => $error));
                } else {
                    $dirName = dirname(__DIR__) . '/../../assets/donation';
                    $adapter->setDestination($dirName);
                    if ($adapter->receive($File['name'])) {

                        $findClass->exchangeArray($data);

                        $this->getDonationTable()->saveList($findClass);

                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $thumb = $thumbnailer->create($dirName . '/' . $data['photo'], $options = array());

                        $thumb->resize(72, 72);

                        //$thumb->show();
                        // or/and
                        $thumb->save($dirName . '/thumb/' . $data['id'] . '_' . $data['photo']);
                    }
                }
                return $this->redirect()->toRoute('admin/donation');
            }
        }

        return array(
            'donation' => $find,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/donation');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getDonationTable()->deleteList($id);
            }

            // Redirect to list of find
            return $this->redirect()->toRoute('admin/donation');
        }

        return array(
            'id' => $id,
            'donation' => $this->getDonationTable()->getListById($id)
        );
    }

    public function getDonationTable() {
        if (!$this->donationTable) {
            $sm = $this->getServiceLocator();
            $this->donationTable = $sm->get('Admin\Model\DonationTable');
        }
        return $this->donationTable;
    }

}