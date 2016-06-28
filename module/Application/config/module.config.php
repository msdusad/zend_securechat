<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'city' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/city',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'city',
        			),
        		),
        	),
          'states' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/states',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'states',
        			),
        		),
        	),
           
        	'postalcode' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/postalcode',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'postalcode',
        			),
        		),
        	),
        	
        	
        	'searchfilter' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/searchfilter',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'searchfilter',
        			),
        		),
        	),
		'view-obituary' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/view-obituary',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewObituary',
        			),
        		),
        	),
		'view-memorial' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/view-memorial',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewMemorial',
        			),
        		),
        	),
        	'view-all-sp-obituary' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/view-all-sp-obituary',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewAllSpObituary',
        			),
        		),
        	),
        	'view-all-sp-memorial' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/view-all-sp-memorial',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewAllSpMemorial',
        			),
        		),
        	),
        	'view-all-obituary' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/view-all-obituary',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewAllObituary',
        			),
        		),
        	),
        	'view-all-memorial' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/view-all-memorial',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewAllMemorial',
        			),
        		),
        	),
        	'getkeywords' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/getkeywords',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'getkeywords',
        			),
        		),
        	),
        	'obituaryPagination' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/obituaryPagination',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'obituaryPagination',
        			),
        		),
        	),
			'memorialPagination' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/memorialPagination',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'memorialPagination',
        			),
        		),
        	),
        	'specialMentionObPagination' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/specialMentionObPagination',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'specialMentionObPagination',
        			),
        		),
        	),
		   'specialMentionMmPagination' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/specialMentionMmPagination',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'specialMentionMmPagination',
        			),
        		),
        	),
            'search' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/search[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'search',
        			),
        		),
        	),						
			'check' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/check[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'check',
        			),
        		),
        	),			
			'antim' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/antim[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'antim',
        			),
        		), 
        	),
			'edu' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/edu[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'edu',
        			),
        		), 
        	),
			'ajax1' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/ajax1[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'ajax1',
        			),
        		), 
        	),
			'employement' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/employement[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'employement',
        			),
        		), 
        	),
			'achive' => array(
        		'type' => 'segment',
        		'options' => array(
        			       'route'    => '/achive[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'achive',
        			),
        		), 
        	),
			
			'digital' => array(
        		'type' => 'segment',
        		'options' => array(
        			       'route'    => '/digital[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'digital',
        			),
        		), 
        	),
			
			'honors' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/honors[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'honors',
        			),
        		), 
        	),
			
			'add-death-user' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/add-death-user',
                            
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'addDeathUser',
        			),
        		),
        	),
			
				'travel' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/travel',
                            
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'travel',
        			),
        		),
        	),
			
			
				'travelajax' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/travelajax',
                            
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'travelajax',
        			),
        		),
        	),


'groceries' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/groceries',
                            
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'groceries',
                    ),
                ),
            ),



'billing' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/billing',
                            
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'billing',
                    ),
                ),
            ),

'functions' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/functions',
                            
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'functions',
                    ),
                ),
            ),

'getdata' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/getdata',
                            
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'getdata',
                    ),
                ),
            ),

'shoppingcart' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/shoppingcart',
                            
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'shoppingcart',
                    ),
                ),
            ),







			
			
			'delete' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/delete[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'delete',
        			),
        		),
        	),
        	'languagetext' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/languagetext[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'languagetext',
        			),
        		),
        	),
        
			'family' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/family[/:action]',
                            'constraints' => array(
                            'search' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        
                           ),
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'family',
        			),
        		),
        	),
			'tributes' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/tributes',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'tributes',
        			),
        		),
        	),
        	'privacy' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/privacy',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'privacy',
        			),
        		),
        	),
			'guestbook' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/guestbook',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'guestbook',
        			),
        		),
        	),
'donate' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/donate',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'donate',
        			),
        		),
        	),
'sendguestoffer' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/sendguestoffer',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'sendguestoffer',
        			),
        		),
        	),
        	'getguestbook' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/getguestbook',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'getguestbook',
        			),
        		),
        	),

        'sendtributeoffer' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/sendtributeoffer',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'sendtributeoffer',
        			),
        		),
        	),
			'view-account' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/view-account',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'viewAccount',
        			),
        		),
        	),
			'edit-profile' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/edit-profile',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'editProfile',
        			),
        		),
        	),
        	'editdeathuser' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/editdeathuser',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'editdeathuser',
        			),
        		),
        	),
			'edit-employement' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/edit-employement',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'editEmployement',
        			),
        		),
        	),
			'edit-achivement' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/edit-achivement',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'editAchivement',
        			),
        		),
        	),
			'edit-persional' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/edit-persional',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'editPersional',
        			),
        		),
        	),
			'edit-digital' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/edit-digital',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'editDigital',
        			),
        		),
        	),
			
			'more' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/more',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'more',
        			),
        		),
        	),
			
			
			'upload' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/upload',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'upload',
        			),
        		),
        	),
			'uploadd' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/uploadd',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'uploadd',
        			),
        		),
        	),
			'delete-file' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/delete-file',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'deleteFile',
        			),
        		),
        	),
			'third' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/third',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'third',
        			),
        		),
        	),
			'style' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/style',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'style',
        			),
        		),
        	),
			'show' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/show',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'show',
        			),
        		),
        	),
			'play' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/play',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'play',
        			),
        		),
        	),
			'download' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/download',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'download',
        			),
        		),
        	),
			'achievement' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/achievement',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'achievement',
        			),
        		),
        	),
			'personal' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/personal',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'personal',
        			),
        		),
        	),
			'footprint' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/footprint',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Index',
        				'action'     => 'footprint',
        			),
        		),
        	),
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),	
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/custom'           => __DIR__ . '/../view/layout/custom.phtml',    
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',    
            'layout/top_menu'           => __DIR__ . '/../view/layout/top_menu.phtml', 
             'download/download-csv'           => __DIR__ . '/../view/layout/download-csv.phtml',    
            'layout/top_links'           => __DIR__ . '/../view/layout/top_links.phtml',   
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view/',
        ),
    ),
	
	
	
	

 
);
