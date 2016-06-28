<?php

namespace ScnSocialAuth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Loader\StandardAutoloader;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {

        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'invokables' => array(
            ),
            'factories' => array(
                'scn-email-form' => function($sm) {
                    $form = new Form\Sendmail();
                    $form->setInputFilter(new Form\SendmailFilter());
                    return $form;
                },
                  'scn_module_options' => function ($sm) {
                    $config = $sm->get('Configuration');
                   return new Options\ModuleOptions(isset($config['scn-social-auth']) ? $config['scn-social-auth'] : array());
                },
                'scn-connections-form' => function($sm) {
                    $form = new Form\Connections();
                    $form->setInputFilter(new Form\ConnectionsFilter());
                    return $form;
                },
            )
        );
    }

    public function onBootstrap($e) {

        $loader = new StandardAutoloader(array('autoregister_zf' => true));

        $loader->registerNamespace('Hybrid', APPLICATION_PATH . '/vendor/hybridauth/hybridauth/Hybrid');

        // Register the "Scapi" vendor prefix:
        $loader->registerPrefix('Hybrid', APPLICATION_PATH . '/vendor/hybridauth/hybridauth/Hybrid');

        // Optionally, specify the autoloader as a "fallback" autoloader;
        // this is not recommended.
        $loader->setFallbackAutoloader(true);

        // Register with spl_autoload:
        $loader->register();
    }
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(                               
                'UserLoginWidget' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\UserLoginWidget($sm);
                    $viewHelper->setViewTemplate($locator->get('scn_social_auth_module_options')->getLoginWidgetViewTemplate());
                    $viewHelper->setLoginForm($locator->get('zfcuser_login_form'));
                    return $viewHelper;
                },
            ),
        );

    }

}
