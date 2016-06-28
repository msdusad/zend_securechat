<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class AdministratorController extends AbstractActionController
{
	protected $updateTable;

	
    public function indexAction()
    {
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
        $this->layout('layout/admin');
	//	return new ViewModel();
    }
    /**
     * **********************************************************************************
     *                  USER MANAGEMENT SECTION
     * **********************************************************************************
     **/
    
     /**
      * User manager display all active and inactive user listing action process here
      * @users User data Object
      * @author developed by Trs Software Solutions
      * @return array
      **/
     public function usermanagerAction()
     {
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home'); 
        
        $users = $this->getUserTable()->fetchAll();
        $roles = $this->getRoleTable()->fetchAll();
		//echo '<pre>';print_r($roles);die; 
		
		
		$page =1;
	    $perPage = 15;
	    $userPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter((array)$users));
	    $userPaginator->setCurrentPageNumber($page);
        $userPaginator->setItemCountPerPage($perPage);	
	    
        $this->layout('layout/admin');
        return array(
            'users'    => $userPaginator,
			'count' => count($users),
			'records_number' => $perPage,
            'roles'=>$roles,
            
        );	
     }
     
     /**
      * User manager display all active and inactive user listing pagination action process here
      * @users User data Object
      * @author developed by Trs Software Solutions
      * @return array
      **/
	 public  function userPaginatorAction()
	 {
	   
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
        
        $users = $this->getUserTable()->fetchAll();
        $roles = $this->getRoleTable()->fetchAll();
        
		
        $page =  (int)$this->getRequest()->getPost('page', 0);
        $perPage = (int)$this->getRequest()->getPost('per_page', 0);
        
        $userPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter((array)$users));
        $userPaginator->setCurrentPageNumber($page);
        $userPaginator->setItemCountPerPage($perPage);	
	
        $view = new ViewModel();
        $view->setTerminal(true)
           ->setTemplate('partial/user-paginator')
           ->setVariables(array(
                'users'    => $userPaginator,
			    'count' => count($records),
			    'records_number' => $perPage,
                'roles'=>$roles,
		    ));
           
        return $view;
	 }
     
     /**
      * User manager display all active and inactive user listing pagination layout action process here
      * @users User data Object
      * @author developed by Trs Software Solutions
      * @return array
      **/
	 public function userPaginatorLayoutAction()
	 {
	    
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
        
        $users = $this->getUserTable()->fetchAll();
	
        $page =  (int)$this->getRequest()->getPost('page', 0);
        $perPage = (int)$this->getRequest()->getPost('per_page', 0);
        
        $userPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter((array)$users));
        $userPaginator->setCurrentPageNumber($page);
        $userPaginator->setItemCountPerPage($perPage);	
	   
        $view = new ViewModel();
        $view->setTerminal(true)
           ->setTemplate('partial/user-paginator-layout')
           ->setVariables(array(
                'users'    => $userPaginator,
			    'count' => count($records),
			    'records_number' => $perPage,
		    ));
          
        return $view;
	 }
     
     /**
      * View single user profile action process here
      * @id user id
      * @author developed by Trs Software Solutions
      * @return array
      **/
     public function profileAction()
     {
         if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
         
        $id = (int) $this->params()->fromRoute('id', 0);
      
        if (!$id &&!is_int($id)) {
            return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermanager'
            ));
        }
        
        try {
          $user =  $this->getUserTable()->getUser($id); 
		  
         }catch (\Exception $ex) {
             return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermamager'
            ));
        }
        $this->layout('layout/admin');
        return array('user'=>$user);
     }
     
     /**
      * Delete user Profile Action Process here
      * @user_id user id
      * @author developed by Trs Software Solutions
      * @return void
      **/
     
     public function deleteuserAction()
     {
         if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
         
         $current_user_id =$this->zfcUserAuthentication()->getIdentity()->getId();
         
         $user_id = (int) $this->params()->fromRoute('id', 0);
        
         if (!$user_id &&!is_int($user_id)) return $this->redirect()->toRoute('administrator', array('action' => 'usermanager'));
        
        
        try {
            
          if($current_user_id ==$user_id):
             
             $this->flashMessenger()->addErrorMessage('Current User Profile can not be deleted !');
             return $this->redirect()->toRoute('administrator', array('controller' => 'administrator', 'action' => 'usermanager'));
          endif;
            
          $user =  $this->getUserTable()->deleteUser($user_id);
          
          $this->flashMessenger()->addSuccessMessage('User Profile Deleted Successfully !');
          return $this->redirect()->toRoute('administrator', array('controller' => 'administrator', 'action' => 'usermanager'));
         
         }catch (\Exception $ex){
             return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermanager'
            ));
        }
     }
    /**
     * Change user password forcefully action process here
     * @user_id user_id
     * @current_user_id Logined user ID
     * @author  developer by Trs Software solutions
     * @return void
     **/
     public function changePasswordAction()
     {
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
       $current_user_id =$this->zfcUserAuthentication()->getIdentity()->getId();
       $user_id = (int) $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        if (!$user_id &&!is_int($user_id)) return $this->redirect()->toRoute('administrator', array('action' => 'usermanager'));
        
        try {
            
            $password = $request->getPost('password');
           $this->getUserTable()->changePassword($user_id,$password);
           $this->flashMessenger()->addSuccessMessage('User password update Successfully !');
            return $this->redirect()->toRoute('administrator', array('controller' => 'administrator', 'action' => 'usermanager'));
        }catch (\Exception $ex){
             return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermanager'
            ));
        }
     } 
     
     /**
     * Change user Role forcefully action process here
     * @user_id user_id
     * @current_user_id Logined user ID
     * @author  developer by Trs Software solutions
     * @return void
     **/
     public function changeRoleAction()
     {
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
        
        $current_user_id =$this->zfcUserAuthentication()->getIdentity()->getId();
          
        $user_id = (int) $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        
        if (!$user_id &&!is_int($user_id)) return $this->redirect()->toRoute('administrator', array('action' => 'usermanager'));
        
        try {
              $role_id = $request->getPost('role_id');
              if(empty($role_id) && !is_int($role_id))
              {
                  $this->flashMessenger()->addErrorMessage('Please Select Role !');
                  return $this->redirect()->toRoute('administrator', array('controller' => 'administrator', 'action' => 'usermanager'));

              }
            
             $this->getRoleTable()->changeRole($user_id,$role_id);
            
              if($current_user_id ==$user_id):
                 //forcefully logout user
                  $this->getResponse()->setHeaders($this->getResponse()->getHeaders()
                                     ->addHeaderLine('Location','/user/logout'));
                  $this->getResponse()->setStatusCode(302);
                  return $this->getResponse();
              endif;
               $this->flashMessenger()->addSuccessMessage('Update Role successfully!');
               return $this->redirect()->toRoute('administrator', array('controller' => 'administrator', 'action' => 'usermanager'));
         
         
         }catch (\Exception $ex){
             return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermanager'
            ));
        }
		
     } 
    
     /**
      * Change user Status like active/deactive action process here
      * @user_id Get User Id
      * @author developed by Trs Software Solutions
      * @return void
      **/
	  // its done
     public function userstatusAction()
     {
        
        if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
        
        $user_id = (int) $this->params()->fromRoute('id', 0);
       
        
        if (!$user_id) {
            return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermanager'
            ));
        }
         
            
        try {
            $state =$this->getUserTable()->getUser($user_id);
            //$status =($state['state']==1)? 0: 1;
            $status =($state['status']==1)? 0: 1;
            $this->getUserTable()->updateStatus($status,$user_id);
            echo 'success';die;
        }
        catch (\Exception $ex) {
             return $this->redirect()->toRoute('administrator', array(
                'action' => 'usermanager'
            ));
        }
      
     }
	 
	 public function uploadAction()
	 { 
		 $res = $this->getUploadTable()->create();
		
		
		
		
	  /*  foreach($res as $res1){
	     $resall[] = $res1;
	   }
	    print "<pre>";
		print_r($resall);die; */
		 
	
		if (!$this->zfcUserAuthentication()->hasIdentity())
		{
			return $this->redirect()->toRoute('home');           
		}
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId(); 
		
		$results = $this->getUploadTable()->fetchByAdmin($user_id);
			if($this->getRequest()->isPost())
			{
				$uploads_dir ='public/addthis/upload';
				$tmp_name = $_FILES["file"]["tmp_name"];
				$name = uniqid()."".$_FILES["file"]["name"];
				$filename = $name;
				
				$path = $_FILES['file']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				
				 move_uploaded_file($tmp_name, "$uploads_dir/$filename");
				
				$data = array('user_id'=>$user_id,'file_name'=>$name,'name'=>$path,'extension'=>$ext,'identity'=>'admin');
				
				$this->getUploadTable()->savefile($data); 
				
				$this->redirect()->toRoute('administrator', array(
                'action' => 'upload'));
				return array('results'=>$results);
				
			}
		$this->layout('layout/admin');
		return array('results'=>$results);
		 
	 }
	 
	 public function deleteFileAction()
	 {
		if (!$this->zfcUserAuthentication()->hasIdentity())
		{
			return $this->redirect()->toRoute('home');           
		}
		$file_id = (int) $this->params()->fromRoute('id', 0);
		$results = $this->getUploadTable()->deleteById($file_id);
		
		return $this->redirect()->toRoute('administrator', array('action' => 'upload')); 
	 }
	 
	 function fileStatusAction()
	 {
		if(!$this->zfcUserAuthentication()->hasIdentity())return $this->redirect()->toRoute('home');
        
        $file_id = (int) $this->params()->fromRoute('id', 0);
       
        if (!$file_id) {
            return $this->redirect()->toRoute('administrator', array(
                'action' => 'upload'
            ));
        }
        try {
            $state =$this->getUploadTable()->getFile($file_id);
			//print_r($state);die;
            $status =($state['status']==1)? 0: 1;
            $this->getUploadTable()->updateStatus($status,$file_id);
            echo 'success';die;
        }
        catch (\Exception $ex) {
             return $this->redirect()->toRoute('administrator', array(
                'action' => 'upload'
            ));
        }
	 }
     
     /**
	 * GetUserTale method is used for getting the object of user Table from the service manager. 
	 * 
     * @author developed by Trs Software Solutions
	 * @return  entity object
	 * */
    private function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Administrator\Model\userTable');
        }
        return $this->userTable;
    }
    /**
     * Get Role Table object through service manager to perform CURD Operations
     * 
     * @author developed by Trs Software Solutions
     * @return get Instance of RoleTable Model
     **/
    private function getRoleTable()
    {
        if (!$this->roleTable) {
            $sm = $this->getServiceLocator();
            $this->roleTable = $sm->get('Administrator\Model\RoleTable');
        }

        return $this->roleTable;
    }
	private function getUploadTable()
    {
        if (!$this->uploadTable) {
            $sm = $this->getServiceLocator();
            $this->uploadTable = $sm->get('Administrator\Model\UploadTable');
        }

        return $this->uploadTable;
    }
}
