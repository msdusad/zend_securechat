<?php
 // your configuration file, e.g., config/autoload/global.php
 return array(
    
     'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Admin',
                 'route' => 'admin',
                 'class' => 'dropdown-toggle',
                 'data-toggle' =>'dropdown',
                 'pages' => array(
                     array(
                         'label' => 'Dashboard',
                         'route' => 'admin',
                     ),
                 ),
             ),
//             array(
//                 'label' => 'Page #1',
//                 'route' => 'page-1',
//                 'pages' => array(
//                     array(
//                         'label' => 'Child #1',
//                         'route' => 'page-1-child',
//                     ),
//                 ),
//             ),
//             array(
//                 'label' => 'Logout',
//                 'route' => 'user/logout',
//             ),
         ),
     ),
     'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         ),
     ),
     
 );