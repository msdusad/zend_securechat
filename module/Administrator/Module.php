<?php
namespace Administrator;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Administrator\Model\UserTable;
use Administrator\Model\RoleTable;
use Administrator\Model\UploadTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getParam('application');
        $app->getEventManager()->attach('dispatch', array($this, 'setLayout'),100); 
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Administrator\model\UserTable' => function($sm) {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $table = new UserTable($dbAdapter);
                            return $table;
				},
				/*'Administrator\model\UserProviderTable' => function($sm) {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $table = new UserProviderTable($dbAdapter);
                            return $table;
				},*/
                 'Administrator\model\RoleTable' => function($sm) {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $table = new RoleTable($dbAdapter);
                            return $table;
                }, 
				'Administrator\model\UploadTable' => function($sm) {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $table = new UploadTable($dbAdapter);
                            return $table;
                },      
               ),

        );

    }    

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
     public function setLayout(MvcEvent $e)
    {
        $template = 'layout/admin';
        $resolver = $e->getApplication()->getServiceManager()->get('Zend\View\Resolver\TemplateMapResolver');
        if (true === $resolver->has($template)) {
             $matches    = $e->getRouteMatch();
             $controller = $matches->getParam('controller');
             if($controller =='Administrator\Controller\Administrator'){
                $viewModel = $e->getViewModel();
                $viewModel->setTemplate($template); 
             }
        }
    }

}