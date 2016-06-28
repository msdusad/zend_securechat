<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Impact;
use Admin\Form\ImpactForm;
use Admin\Form\VideosForm;
use Zend\Validator\File\Size;

class ImpactController extends AbstractActionController {

    protected $table;

    public function indexAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $action = $request->getPost('action');

            $ids = $request->getPost('ids');

            switch ($action) {

                case 'multidelete':
                    $paginator = $this->getTable()->multiDelete($ids);
                    break;
                default :
                    break;
            }
            return $this->redirect()->toRoute('admin/impacts');
        }
        //$this->layout('layout/admin');
        // grab the paginator from the AlbumTable
        $paginator = $this->getTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage();

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function addAction() {

        $form = new ImpactForm();

        $categories = $this->getTable()->getCategories();

        $form->get('category_id')->setAttributes(array('options' => $categories));

        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $obj = new Impact();

            $form->setInputFilter($obj->getInputFilter());

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $obj->exchangeArray($form->getData());

                $this->getTable()->save($obj);

                // Redirect to list of cms
                return $this->redirect()->toRoute('admin/impacts');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/impacts', array(
                        'action' => 'add'
            ));
        }
        $data = $this->getTable()->getById($id);

        $form = new ImpactForm();

        $categories = $this->getTable()->getCategories();

        $form->get('category_id')->setAttributes(array('options' => $categories));

        $form->bind($data);
        $form->get('submit')->setAttribute('value', 'Edit');
         $form->get('video_url')->setAttributes(array('value' => 'test'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($data->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTable()->save($form->getData());
                // Redirect to list page
                return $this->redirect()->toRoute('admin/impacts');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deletephotoseAction() {


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/impacts', array(
                        'action' => 'add'
            ));
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ids = $request->getPost('ids');
            $this->getTable()->deletePhotose($ids, $id);
            $this->flashMessenger()->addMessage('Images deleted successfully');
            return $this->redirect()->toRoute('admin/impacts', array('action' => 'photose', 'id' => $id));
        }
    }

    public function uploadAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/impacts', array(
                        'action' => 'add'
            ));
        }
        $ret = array();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('myfile');
            $data = array_merge(
                    $nonFile, //POST 
                    array('myfile' => $File['name']) //FILE...
            );


            $dirName = dirname(__DIR__) . '/../../assets/impact/';

            if (isset($_FILES["myfile"])) {
                $ret = array();

                $error = $_FILES["myfile"]["error"];
                //You need to handle  both cases
                //If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["myfile"]["name"])) { //single file
                    $fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"], $dirName . $fileName);

                    $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                    $thumb = $thumbnailer->create($dirName . $data["myfile"], $options = array());

                    $thumb->resize(100, 150);

                    //$thumb->show();
                    // or/and
                    $thumb->save($dirName . 'thumb/' . $id . '_' . $data["myfile"]);

                    $ret[] = $fileName;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["myfile"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["myfile"]["name"][$i];
                        move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $dirName . $fileName);
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $thumb = $thumbnailer->create($dirName . $data["myfile"], $options = array());

                        $thumb->resize(100, 150);

                        //$thumb->show();
                        // or/and
                        $thumb->save($dirName . 'thumb/' . $id . '_' . $data["myfile"]);
                        $ret[] = $fileName;
                    }
                }
                $this->getTable()->savePhotose($fileName, $id);
            }
        }
        return json_encode($ret);
    }

    public function photoseAction() {

        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/impacts', array(
                        'action' => 'add'
            ));
        }
        $gallery = $this->getTable()->getGallery($id);


        return array('id' => $id, 'gallery' => $gallery, 'message' => $message);
    }

    /*public function videosAction() {

        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        $gallery = $this->getTable()->getVideos($id);

        $form = new VideosForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost(); // Redirect to list of cms

            if ($data['video_url'] == '') {

                $message[0] = 'video url should not empty';
                return array('form' => $form, 'id' => $id, 'gallery' => $gallery, 'message' => $message);
            }

            $valid = preg_match("/^(http\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/watch\?v\=\w+$/", $data['video_url']);
            if ($valid) {
                $this->getTable()->saveVideo($data['video_url'],$id);
                 $this->flashMessenger()->addMessage('Video added successfully');
                 return $this->redirect()->toRoute('admin/impacts', array(
                        'action' => 'videos','id'=>$id
            ));
            } else {
                $message[0] = 'enter valid youtube url';
                return array('form' => $form, 'id' => $id, 'gallery' => $gallery, 'message' => $message);
            }
        }

        return array('form' => $form, 'id' => $id, 'gallery' => $gallery, 'message' => $message);
    }
    */
    public function deletevideosAction() {


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/impacts', array(
                        'action' => 'videos','id'=>$id
            ));
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ids = $request->getPost('ids');
            $this->getTable()->deleteVideos($ids, $id);
            $this->flashMessenger()->addMessage('Videos deleted successfully');
            return $this->redirect()->toRoute('admin/impacts', array('action' => 'videos', 'id' => $id));
        }
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/impacts');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTable()->delete($id);
            }

            // Redirect to list page
            return $this->redirect()->toRoute('admin/impacts');
        }

        return array(
            'id' => $id,
            'impact' => $this->getTable()->getById($id)
        );
    }

    public function getTable() {
        if (!$this->table) {
            $sm = $this->getServiceLocator();
            $this->table = $sm->get('Admin\Model\ImpactTable');
        }
        return $this->table;
    }

}