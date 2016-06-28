<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Admin\Model\SpecialNeeds;
use Admin\Form\SpecialNeedsForm;
use Zend\Validator\File\Size;

class SpecialNeedsController extends AbstractActionController {

    protected $table;

    
    public function indexAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        $this->layout()->setTemplate('layout/layout');
        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {

            $userId = $auth->getIdentity();
           
        } else {

            $userId = 0;           
           
        }
        $request = $this->getRequest();
        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }
        
        // grab the paginator from the AlbumTable
        $specialneeds = $this->getTable()->fetchAll($id,0,8);
        $total = $this->getTable()->fetchTotal($id);

        $categories = $this->getTable()->getCategories();

        $category = $this->getTable()->getCategory($id);
        if($category==''){
            $category = 'ALL';
        }

        return new ViewModel(array(
            'specialneeds' => $specialneeds, 'categories' => $categories, 'category' => $category,
            'message'=>$message,'id'=>$id,'userid'=>$userId,'total'=>$total
        ));
    }
    public function ajaxAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        $html = '';
         $request = $this->getRequest();
        if ($request->isPost()) {
            $start = $request->getPost('start');
            // grab the paginator from the AlbumTable
        $specialneeds = $this->getTable()->fetchAll($id,$start,8);
        if(count($specialneeds)>0){
        $html .='<ul class="thumbnails">';
        foreach($specialneeds as $key => $val){
            $url = $this->getServiceLocator()->get('viewhelpermanager')->get('url');
            if($val['photo_url']!=''){$imgUrl= 'thumb/' . $val['id'] . '_' . $val['photo_url'];}else{$imgUrl='no-image.jpg';}
            $img = $request->getBasePath().'/../module/Admin/assets/specialneeds/'.$imgUrl;
            $html .= '<li class="span4"><div class="thumbnail"><img height="160" src="'.$img.'"/>';   
            $html .= '<div class="caption"><h4>'.ucfirst($val['title']).'</h4>
                                    <p>'.substr($val['content'], 0, 100).'</p>
                                    <p>
                                        <a href="'.$url('special-needs', array('action' => 'details', 'id' => $val['id'])).'" class="readmore">Read More</a>
                                    </p>
                                </div>
                            </div>
                        </li>';
            }
            $html .= '</ul>';

        }
        }
        echo $html;exit;

        
    }

    public function detailsAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $this->layout()->setTemplate('layout/layout');
        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {

            $userId = $auth->getIdentity();
           
        } else {

            $userId = 0;           
           
        }

        $specialneeds = $this->getTable()->getById($id);

        $gallery = $this->getTable()->getGallery($id);

        $categories = $this->getTable()->getCategories();

        $category = $this->getTable()->getCategory($specialneeds->category_id);
        
        $userName = $this->getTable()->getUser($specialneeds->user_id);

        return new ViewModel(array(
            'specialneeds' => $specialneeds, 'gallery' => $gallery, 'categories' => $categories, 
            'category' => $category,'userName'=>$userName,'userid'=>$userId
        ));
    }

    public function addAction() {

        $this->layout()->setTemplate('layout/layout');
        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {

            $userId = $auth->getIdentity();
           
        } else {

            $userId = 0;
            return $this->redirect()->toRoute('zfcuser/login');
           
        }
        $form = new SpecialNeedsForm();

        $categories = $this->getTable()->getCategoryOption();

        $form->get('category_id')->setAttributes(array('options' => $categories));
        $form->get('user_id')->setValue($userId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $find = new SpecialNeeds();
            $form->setInputFilter($find->getInputFilter());

            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('video_url');
            $data = array_merge(
                    $nonFile, //POST 
                    array('video_url' => $File['name']) //FILE...
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

                    $id = $this->getTable()->save($find);
                } else {
                    $dirName = dirname(__DIR__) . '/../../assets/specialneeds';
                    $adapter->setDestination($dirName);
                    if ($adapter->receive($File['name'])) {

                        $find->exchangeArray($form->getData());

                        $id = $this->getTable()->save($find);
                        
                    }
                }
                
                // Redirect to list of cms
                return $this->redirect()->toRoute('special-needs', array('action' => 'photose', 'id' => $id));
            }
        }
        return array('form' => $form);
    }    
   

    public function deletephotoseAction() {


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('special-needs', array(
                        'action' => 'add'
            ));
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ids = $request->getPost('ids');
            $this->getTable()->deletePhotose($ids, $id);
            $this->flashMessenger()->addMessage('Images deleted successfully');
            return $this->redirect()->toRoute('special-needs', array('action' => 'photose', 'id' => $id));
        }
    }

    public function uploadAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('special-needs', array(
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


            $dirName = dirname(__DIR__) . '/../../assets/specialneeds/';

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

                    $thumb->resize(215, 160);

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

                        $thumb->resize(215, 160);

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

        $this->layout()->setTemplate('layout/layout');
        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasMessages()) {

            $message = $flashMessenger->getMessages();
        } else {

            $message = '';
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('special-needs', array(
                        'action' => 'add'
            ));
        }
        $gallery = $this->getTable()->getGallery($id);


        return array('id' => $id, 'gallery' => $gallery, 'message' => $message);
    }

    public function deleteAction() {
         
        $id = (int) $this->params()->fromRoute('id', 0);
        $this->layout()->setTemplate('layout/layout');
        $auth = new AuthenticationService();

        if (!$auth->hasIdentity()) {

            return $this->redirect()->toRoute('zfcuser/login');           
        } 
        if (!$id) {
            return $this->redirect()->toRoute('special-needs');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTable()->delete($id);
                $this->flashMessenger()->addMessage('Post deleted successfully');
            }
            
            // Redirect to list page
            return $this->redirect()->toRoute('special-needs');
        }

        return array(
            'id' => $id,
            'specialneeds' => $this->getTable()->getById($id)
        );
    }

    public function getTable() {
        if (!$this->table) {
            $sm = $this->getServiceLocator();
            $this->table = $sm->get('Admin\Model\SpecialNeedsTable');
        }
        return $this->table;
    }

}