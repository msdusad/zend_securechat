<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Db\ResultSet\ResultSet;

class Module {

    public function onBootstrap($e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $config = $e->getApplication()->getServiceManager()->get('config');

        $routeMatch = $e->getRouteMatch();
        $actionName = strtolower($routeMatch->getParam('action', 'not-found')); // get the action name

        if (isset($config['module_layouts'][$moduleNamespace][$actionName])) {
            $controller->layout($config['module_layouts'][$moduleNamespace][$actionName]);
        } elseif (isset($config['module_layouts'][$moduleNamespace]['default'])) {
            $controller->layout($config['module_layouts'][$moduleNamespace]['default']);
        }
    }, 100);
     
    
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(                
                'Application\Model\DeathUserTable' => function($sm) {
                    $tableGateway = $sm->get('DeathUserTableGateway');
                    $table = new Model\DeathUserTable($tableGateway);
                    return $table;
                },
                'DeathUserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('death_user', $dbAdapter, null, $resultSetPrototype);
                },
                'ObituaryVisibilityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('obituary_visibility_categories', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Application\Model\DeathUserMetaTable' => function($sm) {
                    $tableGateway = $sm->get('DeathUserMetaTableGateway');
                    $table = new Model\DeathUserMetaTable($tableGateway);
                    return $table;
                },
                'DeathUserMetaTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('death_usermeta', $dbAdapter, null, $resultSetPrototype);
                }, 
                            
				'Application\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new Model\UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
              'Application\Model\DeathUserGuestBookTable' => function($sm) {
                    $tableGateway = $sm->get('DeathUserGuestBookTableGateway');
                    $table = new Model\DeathUserGuestBookTable($tableGateway);
                    return $table;
                },
                'DeathUserGuestBookTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('deathuser_guestbook', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\DeathUserTributeTable' => function($sm) {
                    $tableGateway = $sm->get('DeathUserTributeTableGateway');
                    $table = new Model\DeathUserTributeTable($tableGateway);
                    return $table;
                },
                'DeathUserTributeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('deathuser_tributes', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\LanguageTextTable' => function($sm) {
                    $tableGateway = $sm->get('LanguageTextTableGateway');
                    $table = new Model\LanguageTextTable($tableGateway);
                    return $table;
                },
                'LanguageTextTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('language_text', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\DeathUserLanguageTextTable' => function($sm) {
                    $tableGateway = $sm->get('DeathUserLanguageTextTableGateway');
                    $table = new Model\DeathUserLanguageTextTable($tableGateway);
                    return $table;
                },
                'DeathUserLanguageTextTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('death_user_lang', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\UserTributesTable' => function($sm) {
                    $tableGateway = $sm->get('UserTributesTableGateway');
                    $table = new Model\UserTributesTable($tableGateway);
                    return $table;
                },
                'UserTributesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('user_tributes', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\UserPrivacyTable' => function($sm) {
                    $tableGateway = $sm->get('UserPrivacyTableGateway');
                    $table = new Model\UserPrivacyTable($tableGateway);
                    return $table;
                },
                'UserPrivacyTableGateway' => function ($sm) {
                	
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('death_user_visibility', $dbAdapter, null, $resultSetPrototype);
                },
                
				'Application\Model\UserFamilyDetailsTable' => function($sm) {
                    $tableGateway = $sm->get('UserFamilyDetailsTableGateway');
                    $table = new Model\UserFamilyDetailsTable($tableGateway);
                    return $table;
                },
                'UserFamilyDetailsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('user_family_details', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\UploadTable' => function($sm) {
                    $tableGateway = $sm->get('UploadTableGateway');
                    $table = new Model\UploadTable($tableGateway);
                    return $table;
                },
                'UploadTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('upload', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\ImageUploadTable' => function($sm) {
                    $tableGateway = $sm->get('ImageUploadTableGateway');
                    $table = new Model\ImageUploadTable($tableGateway);
                    return $table;
                },
                'ImageUploadTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('death_user_image', $dbAdapter, null, $resultSetPrototype);
                },
				
                'userRole' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $role = $serviceLocator->get('BjyAuthorize\Service\Authorize')->getIdentity();
                    return $role;
                },
				'Application\Model\SchoolTable' => function($sm) {
                    $tableGateway = $sm->get('SchoolTableGateway');
                    $table = new Model\SchoolTable($tableGateway);
                    return $table;
                },
                'SchoolTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('school', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\CollegeTable' => function($sm) {
                    $tableGateway = $sm->get('CollegeTableGateway');
                    $table = new Model\CollegeTable($tableGateway);
                    return $table;
                },
                'CollegeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('collegename', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\UniversityTable' => function($sm) {
                    $tableGateway = $sm->get('UniversityTableGateway');
                    $table = new Model\UniversityTable($tableGateway);
                    return $table;
                },
                'UniversityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('universitydetails', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Application\Model\OtherTable' => function($sm) {
                    $tableGateway = $sm->get('OtherTableGateway');
                    $table = new Model\OtherTable($tableGateway);
                    return $table;
                },
                'OtherTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('othertable', $dbAdapter, null, $resultSetPrototype);
                },
             'Application\Model\SearchObituaryTable' => function($sm) {
                    $tableGateway = $sm->get('SearchObituaryTableGateway');
                    $table = new Model\SearchObituaryTable($tableGateway);
                    return $table;
                },
                'SearchObituaryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('obituary', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\DeathUserDonateTable' => function($sm) {
                    $tableGateway = $sm->get('DeathUserDonateTableGateway');
                    $table = new Model\DeathUserDonateTable($tableGateway);
                    return $table;
                },
                'DeathUserDonateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('deathuser_donate', $dbAdapter, null, $resultSetPrototype);
                },
				'Application\Model\CauseofDeathTable' => function($sm) {
                    $tableGateway = $sm->get('CauseofDeathTableGateway');
                    $table = new Model\CauseofDeathTable($tableGateway);
                    return $table;
                },
                'CauseofDeathTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('causeof_death', $dbAdapter, null, $resultSetPrototype);
                },
				 'Application\Model\CountryofDeathTable' => function($sm) {
                    $tableGateway = $sm->get('CountryofDeathTableGateway');
                    $table = new Model\CountryofDeathTable($tableGateway);
                    return $table;
                },
                'CountryofDeathTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('countries', $dbAdapter, null, $resultSetPrototype);
                }
				
            ),
        );
    }

}
