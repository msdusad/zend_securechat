<?php

namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e) {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getServiceConfig() {
        return array(
            'invokables' => array(
            ),
            'factories' => array(
                // register UserTable to service manager                
                'Admin\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('AdminTableGateway');
                    $table = new Model\UserTable($tableGateway);
                    return $table;
                },
                'AdminTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                // register UserTable to service manager                
                'Admin\Model\UserProviderTable' => function($sm) {
                    $tableGateway = $sm->get('AdminProviderTableGateway');
                    $table = new Model\UserProviderTable($tableGateway);
                    return $table;
                },
                'AdminProviderTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserProvider());
                    return new TableGateway('user_provider', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\UserRoleTable' => function($sm) {
                    $tableGateway = $sm->get('AdminRoleTableGateway');
                    $table = new Model\UserRoleTable($tableGateway);
                    return $table;
                },
                'AdminRoleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserRole());
                    return new TableGateway('user_role_linker', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\CategoryTable' => function($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new Model\CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\TaskTable' => function($sm) {
                    $tableGateway = $sm->get('TaskTableGateway');
                    $table = new Model\TaskTable($tableGateway);
                    return $table;
                },
                'TaskTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Task());
                    return new TableGateway('tasks', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\CmsTable' => function($sm) {
                    $tableGateway = $sm->get('CmsTableGateway');
                    $table = new Model\CmsTable($tableGateway);
                    return $table;
                },
                'CmsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Cms());
                    return new TableGateway('cms', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\FaqTable' => function($sm) {
                    $tableGateway = $sm->get('FaqTableGateway');
                    $table = new Model\FaqTable($tableGateway);
                    return $table;
                },
                'FaqTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Faq());
                    return new TableGateway('faq', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\DonationTable' => function($sm) {
                    $tableGateway = $sm->get('DonationTableGateway');
                    $table = new Model\DonationTable($tableGateway);
                    return $table;
                },
                'DonationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Donation());
                    return new TableGateway('donation', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\EmailTable' => function($sm) {
                    $tableGateway = $sm->get('EmailTableGateway');
                    $table = new Model\EmailTable($tableGateway);
                    return $table;
                },
                'EmailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Email());
                    return new TableGateway('templates', $dbAdapter, null, $resultSetPrototype);
                },
				 'Admin\Model\EmailTable' => function($sm) {
                    $tableGateway = $sm->get('EmailTableGateway');
                    $table = new Model\EmailTable($tableGateway);
                    return $table;
                },
                'EmailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Email());
                    return new TableGateway('templates', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\RitualsTraditionalTable' => function($sm) {
                    $tableGateway = $sm->get('RitualsTraditionalTableGateway');
                    $table = new Model\RitualsTraditionalTable($tableGateway);
                    return $table;
                },
                'RitualsTraditionalTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\RitualsTraditional());
                    return new TableGateway('rituals_traditional', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\RitualsTable' => function($sm) {
                    $tableGateway = $sm->get('RitualsTableGateway');
                    $table = new Model\RitualsTable($tableGateway);
                    return $table;
                },
                'RitualsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Rituals());
                    return new TableGateway('rituals', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\GothraTable' => function($sm) {
                    $tableGateway = $sm->get('GothraTableGateway');
                    $table = new Model\GothraTable($tableGateway);
                    return $table;
                },
                'GothraTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Gothra());
                    return new TableGateway('gothra', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\HindusimTable' => function($sm) {
                    $tableGateway = $sm->get('HindusimTableGateway');
                    $table = new Model\HindusimTable($tableGateway);
                    return $table;
                },
                'HindusimTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Hindusim());
                    return new TableGateway('hindusim', $dbAdapter, null, $resultSetPrototype);
                },
                 'Admin\Model\ImpactTable' => function($sm) {
                    $tableGateway = $sm->get('ImpactTableGateway');
                    $table = new Model\ImpactTable($tableGateway);
                    return $table;
                },
                'ImpactTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Impact());
                    return new TableGateway('impacts', $dbAdapter, null, $resultSetPrototype);
                },
                 'Admin\Model\LanguageTable' => function($sm) {
                    $tableGateway = $sm->get('LanguageTableGateway');
                    $table = new Model\LanguageTable($tableGateway);
                    return $table;
                },
                'LanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Language());
                    return new TableGateway('languages', $dbAdapter, null, $resultSetPrototype);
                },
                 'Admin\Model\TemplateTable' => function($sm) {
                    $tableGateway = $sm->get('TemplateTableGateway');
                    $table = new Model\TemplateTable($tableGateway);
                    return $table;
                },
                'TemplateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Template());
                    return new TableGateway('cremation_templates', $dbAdapter, null, $resultSetPrototype);
                },
                   'Admin\Model\ProtectTable' => function($sm) {
                    $tableGateway = $sm->get('ProtectTableGateway');
                    $table = new Model\ProtectTable($tableGateway);
                    return $table;
                },
                'ProtectTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Protect());
                    return new TableGateway('protect', $dbAdapter, null, $resultSetPrototype);
                },
                  'Admin\Model\CityTable' => function($sm) {
                    $tableGateway = $sm->get('CityTableGateway');
                    $table = new Model\CityTable($tableGateway);
                    return $table;
                },
                'CityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\City());
                    return new TableGateway('india', $dbAdapter, null, $resultSetPrototype);
                },
                  'Admin\Model\SpecialNeedsTable' => function($sm) {
                    $tableGateway = $sm->get('SpecialNeedsTableGateway');
                    $table = new Model\SpecialNeedsTable($tableGateway);
                    return $table;
                },
                'SpecialNeedsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\SpecialNeeds());
                    return new TableGateway('special_needs', $dbAdapter, null, $resultSetPrototype);
                },                        
                        
                'admin_search_form' => function($sm) {
                    $form = new Form\Search(null);
                    $form->setInputFilter(new Form\SearchFilter());
                    return $form;
                },
            )
        );
    }

}
