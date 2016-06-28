<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'socialuser' => 'ScnSocialAuth\Controller\UserController',
            'socialhybrid' => 'ScnSocialAuth\Controller\HybridAuthController',
            'socialcontact' => 'ScnSocialAuth\Controller\ContactsController',
            'socialconnections' => 'ScnSocialAuth\Controller\ConnectionsController',
        ),
        'factories' => array(
            'ScnSocialAuth-HybridAuth' => 'ScnSocialAuth\Service\HybridAuthControllerFactory',
            'ScnSocialAuth-User' => 'ScnSocialAuth\Service\UserControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'scnsocialauthprovider' => 'ScnSocialAuth\Controller\Plugin\ScnSocialAuthProvider',
        ),
    ),
    'router' => array(
        'routes' => array(
            'scn-social-auth-hauth' => array(
                'type' => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/scn-social-auth/hauth',
                    'defaults' => array(
                        'controller' => 'socialhybrid',
                        'action' => 'index',
                    ),
                ),
            ),
            'scn-social-contact' => array(
                'type' => 'Segment',
                'priority' => 2000,
                'options' => array(
                    'route' => '/contacts[/:provider][/:return]',
                    'constraints' => array(
                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                         'return' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'socialcontact',
                        'action' => 'index',
                    ),
                ),
            ),
            'scn-social-accounts' => array(
                'type' => 'Segment',
                'priority' => 2000,
                'options' => array(
                    'route' => '/accounts[/:provider][/:return]',
                    'constraints' => array(
                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'return' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'socialcontact',
                        'action' => 'accounts',
                    ),
                ),
            ),
            'scn-social-connections' => array(
                'type' => 'Segment',
                'priority' => 2000,
                'options' => array(
                    'route' => '/connections[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'socialconnections',
                        'action' => 'index',
                    ),
                ),
            ),
            'scn-social-message' => array(
                'type' => 'Segment',
                'priority' => 2000,
                'options' => array(
                    'route' => '/sendmessage',
                    'defaults' => array(
                        'controller' => 'socialcontact',
                        'action' => 'send',
                    ),
                ),
            ),
            'scn-social-auth-user' => array(
                'type' => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'authenticate',
                            ),
                        ),
                    ),
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'socialuser',
                                'action' => 'login',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'socialuser',
                                        'action' => 'provider-login',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'socialuser',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'socialuser',
                                'action' => 'register',
                            ),
                        ),
                    ),
                    'add-provider' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add-provider',
                            'defaults' => array(
                                'controller' => 'socialuser',
                                'action' => 'add-provider',
                            ),
                        ),
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'ScnSocialAuth_ZendDbAdapter' => 'Zend\Db\Adapter\Adapter',
            'ScnSocialAuth_ZendSessionManager' => 'Zend\Session\SessionManager',
        ),
        'factories' => array(
            'HybridAuth' => 'ScnSocialAuth\Service\HybridAuthFactory',
            'ScnSocialAuth-ModuleOptions' => 'ScnSocialAuth\Service\ModuleOptionsFactory',
            'ScnSocialAuth-UserProviderMapper' => 'ScnSocialAuth\Service\UserProviderMapperFactory',
            'ScnSocialAuth-ConnectionsMapper' => 'ScnSocialAuth\Service\ConnectionsMapperFactory',
            'ScnSocialAuth-ScnContactsMapper' => 'ScnSocialAuth\Service\ScnContactsMapperFactory',
            'ScnSocialAuth\Authentication\Adapter\HybridAuth' => 'ScnSocialAuth\Service\HybridAuthAdapterFactory',
            'ZfcUser\Authentication\Adapter\AdapterChain' => 'ScnSocialAuth\Service\AuthenticationAdapterChainFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'socialSignInButton' => 'ScnSocialAuth\View\Helper\SocialSignInButton',
        ),
        'factories' => array(
            'scnUserProvider' => 'ScnSocialAuth\Service\UserProviderViewHelperFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'scn-social-auth' => __DIR__ . '/../view'
        ),
    ),
);
