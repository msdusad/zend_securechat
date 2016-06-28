<?php

return array(
    'controllers' => array(
        'invokables' => array(           
            'CremationPlan\Controller\Index' => 'CremationPlan\Controller\IndexController'
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'cremation-plans' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/cremation-plans[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'CremationPlan\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
           
        ),
    ),
    'view_manager' => array(       
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);