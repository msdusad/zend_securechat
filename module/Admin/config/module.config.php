<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Users' => 'Admin\Controller\UsersController',
            'Admin\Controller\Category' => 'Admin\Controller\CategoryController',
            'Admin\Controller\Task' => 'Admin\Controller\TaskController',
            'Admin\Controller\Cms' => 'Admin\Controller\CmsController',
            'Admin\Controller\Faq' => 'Admin\Controller\FaqController',
            'Admin\Controller\Donate' => 'Admin\Controller\DonationController',
            'Admin\Controller\Email' => 'Admin\Controller\EmailController',
            'Admin\Controller\Traditional' => 'Admin\Controller\RitualsTraditionalController',
            'Admin\Controller\Rituals' => 'Admin\Controller\RitualsController',
            'Admin\Controller\Gothra' => 'Admin\Controller\GothraController',
            'Admin\Controller\Hindusim' => 'Admin\Controller\HindusimController',
            'Admin\Controller\Impacts' => 'Admin\Controller\ImpactController',
            'Admin\Controller\Language' => 'Admin\Controller\LanguageController',
            'Admin\Controller\Template' => 'Admin\Controller\TemplateController',
            'Admin\Controller\Protect' => 'Admin\Controller\ProtectController',
            'Admin\Controller\Special' => 'Admin\Controller\SpecialNeedsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'page' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/page[/:id]',
                    'constraints' => array(                        
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Cms',
                        'action'     => 'page',
                    ),
                ),
            ),
            'faq' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/faq[/:id]',
                    'constraints' => array(                        
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Faq',
                        'action'     => 'faq',
                    ),
                ),
            ),
            'special-needs' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/special-needs[/:action][/:id]',
                    'constraints' => array( 
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Special',
                        'action'     => 'index',
                    ),
                ),
            ),
             'delete-account' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/delete-account[/:id]',
                    'constraints' => array(                        
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Users',
                        'action'     => 'deleteaccount',
                    ),
                ),
            ),
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'users' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/users[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Users',
                                'action' => 'index',
                            ),
                        ),
                    ),
                     'language' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/language[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Language',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'cremation-templates' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/cremation-templates[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Template',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'protect' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/protect[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Protect',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'gothra' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/gothra[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Gothra',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'hindusim' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/hindusim[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Hindusim',
                                'action' => 'index',
                            ),
                        ),
                    ),
                     'templates' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/templates[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Email',
                                'action' => 'index',
                            ),
                        ),
                    ),
                     'traditionals' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/traditionals[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Traditional',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'rituals' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/rituals[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Rituals',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'impacts' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/impacts[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Impacts',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'category' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/category[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Category',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'tasks' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/tasks[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Task',
                                'action' => 'index',
                            ),
                        ),
                    ),
                     'cms' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/cms[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Cms',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'faq' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/faq[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Faq',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'donation' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/donation[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Donate',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/left-menu' => __DIR__ . '/../view/admin/layout/left_menu.phtml',
            //'layout/admin' => __DIR__ . '/../../Application/view/layout/admin_layout.phtml',
            'layout/admin_top_menu' => __DIR__ . '/../../Application/view/layout/admin_top_menu.phtml',
            'layout/footer' => __DIR__ . '/../../Application/view/layout/footer.phtml',
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404' => __DIR__ . '/../../Application/view/error/404.phtml',
            'error/index' => __DIR__ . '/../../Application/view/error/index.phtml',
            'pagination' => __DIR__ . '/../../Application/view/layout/admin_pagination.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => array(
        'Admin' => array(
            'default' => 'layout/admin',
            'deleteaccount' => 'layout/layout',
            'faq' =>'layout/layout'
        )
    ),
);
