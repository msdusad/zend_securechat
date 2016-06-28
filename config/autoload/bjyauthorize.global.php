<?php

return array(
    'bjyauthorize' => array(
        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',
        /* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         *
         * for ZfcUser, this will be your default identity provider
         */
        'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',
        /* If you only have a default role and an authenticated role, you can
         * use the 'AuthenticationIdentityProvider' to allow/restrict access
         * with the guards based on the state 'logged in' and 'not logged in'.
         *
         * 'default_role'       => 'guest',         // not authenticated
         * 'authenticated_role' => 'user',          // authenticated
         * 'identity_provider'  => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
         */

        /* role providers simply provide a list of roles that should be inserted
         * into the Zend\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Zend\Db adapter.
         */
        'role_providers' => array(
            /* here, 'guest' and 'user are defined as top-level roles, with
             * 'admin' inheriting from user
             */
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array(),
                'user' => array('children' => array(
                        'admin' => array(),
                    )),
            ),
            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar), parent(varchar))
            'BjyAuthorize\Provider\Role\ZendDb' => array(
                'table' => 'user_role',
                'identifier_field_name' => 'id',
                'role_id_field' => 'role_id',
                'parent_role_field' => 'parent_id',
            ),
        // this will load roles from
        // the 'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' service
        //'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
        // class name of the entity representing the role
        //  'role_entity_class' => 'My\Role\Entity',
        // service name of the object manager
        //'object_manager'    => 'My\Doctrine\Common\Persistence\ObjectManager',
        //),
        ),
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'pants' => array(),
            ),
        ),
        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    array(array('guest', 'user'), 'pants', 'wear')
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                // ...
                ),
            ),
        ),
        /* Currently, only controller and route guards exist
         *
         * Consider enabling either the controller or the route guard depending on your needs.
         */
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            'BjyAuthorize\Guard\Controller' => array(
				
                array('controller' => 'index', 'action' => 'index', 'roles' => array('guest', 'user')),
                array('controller' => 'index', 'action' => 'stuff', 'roles' => array('user')),
                // You can also specify an array of actions or an array of controllers (or both)
                // allow "guest" and "admin" to access actions "list" and "manage" on these "index",
                // "static" and "console" controllers
                array(
                    'controller' => array('index', 'static', 'console'),
                    'action' => array('list', 'manage'),
                    'roles' => array('guest', 'admin')
                ),
                array(
                    'controller' => array('search', 'administration'),
                    'roles' => array('admin')
                ),
                /* Events controller */
                array('controller' => 'EventCalendar\Controller\Events', 'roles' => array('guest', 'user','admin')),
                /**/

                /* Memoralize controller */
                array('controller' => 'Memoralize\Controller\Index', 'roles' => array()),
                array('controller' => 'Memoralize\Controller\Infos', 'roles' => array()),
                array('controller' => 'Memoralize\Controller\Media', 'roles' => array()),
                array('controller' => 'Memoralize\Controller\Donation', 'roles' => array()),
                array('controller' => 'Cremation\Controller\Index', 'roles' => array()),
                
                array('controller' => 'Obituary\Controller\Index', 'roles' => array()),
                array('controller' => 'Obituary\Controller\Infos', 'roles' => array()),
                array('controller' => 'Obituary\Controller\Media', 'roles' => array()),
                
                /**/
                array('controller' => 'socialuser', 'roles' => array()),
                array('controller' => 'socialhybrid', 'roles' => array()),
                array('controller' => 'socialcontact', 'roles' => array()),
                array('controller' => 'socialconnections', 'roles' => array('user', 'admin')),
                array('controller' => 'zfcuser', 'roles' => array()),
                array('controller' => 'sharedtasks', 'roles' => array()),
                array('controller' => 'Emails\Controller\Index', 'roles' => array()),
                array('controller' => 'Emails\Controller\Group', 'roles' => array()),
                // Below is the default index action used by the ZendSkeletonApplication
                array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user','admin')),
				array('controller' => 'Application\Controller\Travel', 'roles' => array('guest', 'user','admin')),

// array('controller' => 'Application\Controller\Travel', 'roles' => array('guest', 'user','admin')),
// array('controller' => 'Application\Controller\Travel', 'roles' => array('guest', 'user','admin')),
// array('controller' => 'Application\Controller\Travel', 'roles' => array('guest', 'user','admin')),
// array('controller' => 'Application\Controller\Travel', 'roles' => array('guest', 'user','admin')),
// array('controller' => 'Application\Controller\Travel', 'roles' => array('guest', 'user','admin')),



                array('controller' => 'Admin\Controller\Index', 'roles' => array('user', 'admin')),
                array('controller' => 'Admin\Controller\Users', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Category', 'roles' => array('admin')),
                array('controller' => 'Admin\Controller\Task', 'roles' => array('admin')),
                array('controller' => 'Admin\Controller\Cms', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Faq', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Donate', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Email', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Traditional', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Rituals', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Protect', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Gothra', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Hindusim', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Impacts', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Language', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Template', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Admin\Controller\Special', 'roles' => array('guest', 'user', 'admin')),
                               
                array('controller' => 'CremationPlan\Controller\Find', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'CremationPlan\Controller\Product', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'CremationPlan\Controller\Garland', 'roles' => array('guest', 'user', 'admin')), 
                array('controller' => 'CremationPlan\Controller\Orders', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'CremationPlan\Controller\Index', 'roles' => array('guest', 'user', 'admin')),
			
                
                array('controller' => 'PrePlanning\Controller\Pre', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'PrePlanning\Controller\Index', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'PrePlanning\Controller\Links', 'roles' => array('guest', 'user', 'admin')),
				 array('controller' => 'PrePlanning\Controller\State', 'roles' => array('guest', 'user', 'admin')),
				
				
					array('controller' => 'IMandir\Controller\Pre', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'IMandir\Controller\Index', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'IMandir\Controller\Links', 'roles' => array('guest', 'user', 'admin')),
                # array('controller' => 'IMandir\Controller\State', 'roles' => array('guest', 'user', 'admin')),   
                
                /*donation*/
                 array('controller' => 'Donation\Controller\Cause', 'roles' => array('guest', 'user', 'admin')),
                 array('controller' => 'Donation\Controller\Charity', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Donation\Controller\Index', 'roles' => array('guest', 'user', 'admin')),
                
                /*shopping*/  
                array('controller' => 'Shopping\Controller\Category', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Shopping\Controller\Item', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Shopping\Controller\Index', 'roles' => array('guest', 'user', 'admin')),
                array('controller' => 'Shopping\Controller\Whishlist', 'roles' => array('guest', 'user', 'admin')),
                
				// add this
				
				array('controller' => 'Administrator\Controller\Administrator','roles' => array('admin')),
				array('controller' => 'Menu\Controller\Menu','roles' => array('admin')),
				
				array('controller' => 'Pages\Controller\AdminPages','roles' => array('admin')),
				
				array('controller' => 'Pages\Controller\Page','roles' => array('guest', 'user', 'admin')),
				array('controller' => 'Mailbox\Controller\Mail','roles' => array('admin')),
				
				
				// end here
            ),
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
				array('route' => 'state', 'roles' => array('guest','user', 'admin')),
				# array('route' => 'state', 'roles' => array('guest','user', 'admin')),
                 array('route' => 'application', 'roles' => array('guest','user', 'admin')),
                 array('route' => 'city', 'roles' => array('guest','user', 'admin')),
                 array('route' => 'states', 'roles' => array('guest','user', 'admin')),
                 array('route' => 'faq', 'roles' => array('guest','user', 'admin')),
                 array('route' => 'search', 'roles' => array('guest','user', 'admin')),
                
                 array('route' => 'postalcode', 'roles' => array('guest','user', 'admin')),
                
				
					  array('route' => 'searchfilter', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'obituaryPagination', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'memorialPagination', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'view-all-memorial', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'view-all-obituary', 'roles' => array('guest','user', 'admin')), 
					  array('route' => 'view-all-sp-memorial', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'view-all-sp-obituary', 'roles' => array('guest','user', 'admin')), 
						array('route' => 'view-obituary', 'roles' => array('guest','user', 'admin')),                  
						array('route' => 'view-memorial', 'roles' => array('guest','user', 'admin')),                 

					  array('route' => 'searchfilter', 'roles' => array('guest','user', 'admin')),   
					  array('route' => 'getkeywords', 'roles' => array('guest','user', 'admin')),               
					  array('route' => 'obituaryPagination', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'memorialPagination', 'roles' => array('guest','user', 'admin')),
					  array('route' => 'specialMentionObPagination', 'roles' => array('guest','user', 'admin')), 
					  array('route' => 'specialMentionMmPagination', 'roles' => array('guest','user', 'admin')),                  
					  array('route' => 'view-all-memorial', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'view-all-obituary', 'roles' => array('guest','user', 'admin')), 
					  array('route' => 'view-all-sp-memorial', 'roles' => array('guest','user', 'admin')),                 
					  array('route' => 'view-all-sp-obituary', 'roles' => array('guest','user', 'admin')), 
						array('route' => 'view-obituary', 'roles' => array('guest','user', 'admin')),                  
						array('route' => 'view-memorial', 'roles' => array('guest','user', 'admin')),                 

                 array('route' => 'special-needs', 'roles' => array('guest','user', 'admin')),				 				
                  // add this 
                  
                  array('route' => 'check', 'roles' => array('guest','user', 'admin')),				                  
                  array('route' => 'antim', 'roles' => array('guest','user', 'admin')),    
                  array('route' => 'add-death-user', 'roles' => array('guest','user', 'admin')),  
				   array('route' => 'languagetext', 'roles' => array('user', 'admin')), 
                  array('route' => 'edu', 'roles' => array('guest','user', 'admin')),
				   array('route' => 'ajax1', 'roles' => array('user', 'admin')),
				 array('route' => 'employement', 'roles' => array('user', 'admin')), 
				 array('route' => 'honors', 'roles' => array('user', 'admin')),
				 array('route' => 'achive', 'roles' => array('user', 'admin')),
				 array('route' => 'digital', 'roles' => array('user', 'admin')),
                  array('route' => 'delete', 'roles' => array('user', 'admin')),
				  
                  array('route' => 'family', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'tributes', 'roles' => array('guest','user', 'admin')),		
                  array('route' => 'privacy', 'roles' => array('guest','user', 'admin')),			
                  array('route' => 'view-account', 'roles' => array('guest','user', 'admin')),
                  array('route' => 'sendguestoffer', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'sendtributeoffer', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'getguestbook', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'guestbook', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'donate', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'upload', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'show', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'play', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'download', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'uploadd', 'roles' => array('guest','user', 'admin')),	
				 array('route' => 'travel', 'roles' => array('guest','user', 'admin')),	
				 array('route' => 'travelajax', 'roles' => array('guest','user', 'admin')),	

 array('route' => 'groceries', 'roles' => array('guest','user', 'admin')),
  array('route' => 'billing', 'roles' => array('guest','user', 'admin')),
   array('route' => 'functions', 'roles' => array('guest','user', 'admin')),
    array('route' => 'getdata', 'roles' => array('guest','user', 'admin')),
     array('route' => 'shoppingcart', 'roles' => array('guest','user', 'admin')),


                  array('route' => 'delete-file', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'third', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'edit-profile', 'roles' => array('guest','user', 'admin')),
                  array('route' => 'editdeathuser', 'roles' => array('guest','user', 'admin')),
                  array('route' => 'edit-employement', 'roles' => array('guest','user', 'admin')),				  
                  array('route' => 'edit-achivement', 'roles' => array('guest','user', 'admin')),				  
                  array('route' => 'edit-digital', 'roles' => array('guest','user', 'admin')),				  
                  array('route' => 'edit-persional', 'roles' => array('guest','user', 'admin')),				  
                  array('route' => 'style', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'more', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'personal', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'achievement', 'roles' => array('guest','user', 'admin')),				
                  array('route' => 'footprint', 'roles' => array('guest','user', 'admin')),				
                  
                  // end here
                           
                 /* donation */
                 array('route' => 'donation', 'roles' => array('guest','user', 'admin')),
                 array('route' => 'admin/donation-cause', 'roles' => array('admin')),
                 array('route' => 'admin/donation-charity', 'roles' => array('admin')),
                
                  /* shopping */
                 array('route' => 'admin/shopping-category', 'roles' => array('admin')),
                 array('route' => 'admin/shopping-item', 'roles' => array('admin')),
                 array('route' => 'shopping', 'roles' => array('guest', 'user', 'admin')),
                 array('route' => 'whish-list', 'roles' => array('guest', 'user', 'admin')),
                
                
                array('route' => 'zfcuser', 'roles' => array('user')),
                array('route' => 'zfcuser/logout', 'roles' => array('user')),
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'zfcuser/register', 'roles' => array('guest', 'admin')),
                array('route' => 'zfcuser/changepassword', 'roles' => array('user')),
                array('route' => 'zfcuser/changeemail', 'roles' => array('user')),
                array('route' => 'scn-social-contact', 'roles' => array('user')),
                array('route' => 'scn-social-accounts', 'roles' => array('user')),
                array('route' => 'scn-social-connections', 'roles' => array('user')),
                array('route' => 'scn-social-message', 'roles' => array('user')),
                array('route' => 'scn-social-auth-hauth', 'roles' => array('user', 'guest')),
                array('route' => 'scn-social-auth-user/authenticate', 'roles' => array('user', 'guest')),
                array('route' => 'scn-social-auth-user', 'roles' => array('user')),
                array('route' => 'scn-social-auth-user/logout', 'roles' => array('user')),
                array('route' => 'scn-social-auth-user/login', 'roles' => array('guest')),
                array('route' => 'scn-social-auth-user/register', 'roles' => array('guest', 'admin')),
                array('route' => 'scn-social-auth-user/login/provider', 'roles' => array('guest')),
                /* events */
                array('route' => 'event-calendar-events', 'roles' => array('guest','user', 'admin')),
                array('route' => 'event-calendar-json', 'roles' => array('guest','user', 'admin')),
                /* events */              
                array('route' => 'memoralize', 'roles' => array('user', 'guest')),
                array('route' => 'memoralize-create', 'roles' => array('user', 'guest')),
                array('route' => 'memoralize-create-infos', 'roles' => array('user', 'guest')),
                array('route' => 'memoralize-create-media', 'roles' => array('user', 'guest')),
                array('route' => 'memoralize-create-themes', 'roles' => array('user', 'guest')),
                
                array('route' => 'obituary/view-obituary', 'roles' => array('user', 'guest')),
                array('route' => 'obituary', 'roles' => array('user', 'guest')),
                array('route' => 'obituary-create', 'roles' => array('user', 'guest')),
                array('route' => 'obituary-create-infos', 'roles' => array('user', 'guest')),
                array('route' => 'obituary-create-media', 'roles' => array('user', 'guest')),
                array('route' => 'obituary-create-themes', 'roles' => array('user', 'guest')),
                array('route' => 'viewobituary', 'roles' => array('guest','user', 'admin')),
                
                // Below is the default index action used by the ZendSkeletonApplication
                // Below is the default index action used by the [ZendSkeletonApplication](https://github.com/zendframework/ZendSkeletonApplication)
                array('route' => 'home', 'roles' => array('guest', 'user')),
                array('route' => 'about', 'roles' => array('guest', 'user')),
                array('route' => 'sharedtasks', 'roles' => array('guest', 'user')),
                array('route' => 'page', 'roles' => array('guest', 'user')),
                array('route' => 'delete-account', 'roles' => array('user')),
                array('route' => 'cremation-plans', 'roles' => array('guest', 'user')),
                array('route' => 'pre-planning', 'roles' => array('user', 'guest')),
                array('route' => 'i-mandir', 'roles' => array('user', 'guest')),
                
                array('route' => 'cremation', 'roles' => array('guest', 'user')),
                //email management
                array('route' => 'inbox', 'roles' => array('guest', 'user')),
                array('route' => 'sent-items', 'roles' => array('guest', 'user')),
                array('route' => 'compose-mail', 'roles' => array('guest', 'user')),
                array('route' => 'reply-mail', 'roles' => array('guest', 'user')),
                array('route' => 'forward-mail', 'roles' => array('guest', 'user')),
                array('route' => 'maildetail', 'roles' => array('guest', 'user')),
                //email groups
                array('route' => 'contact-groups', 'roles' => array('guest', 'user')),
                array('route' => 'contact-group-users', 'roles' => array('guest', 'user')),
                //admin
                array('route' => 'admin', 'roles' => array('admin')),
                array('route' => 'category', 'roles' => array('admin')),
                array('route' => 'admin/users', 'roles' => array('admin')),
                array('route' => 'admin/language', 'roles' => array('admin')),
                array('route' => 'admin/category', 'roles' => array('admin')),
                array('route' => 'admin/tasks', 'roles' => array('admin')),
                array('route' => 'admin/cms', 'roles' => array('admin')),
                array('route' => 'admin/traditionals', 'roles' => array('admin')),
                array('route' => 'admin/rituals', 'roles' => array('admin')),
                array('route' => 'admin/hindusim', 'roles' => array('admin')),
                array('route' => 'admin/gothra', 'roles' => array('admin')),
                array('route' => 'admin/protect', 'roles' => array('admin')),
                array('route' => 'admin/faq', 'roles' => array('admin')),
                
                array('route' => 'admin/pre-planning', 'roles' => array('admin')),
                array('route' => 'admin/preplanning-links', 'roles' => array('admin')),
                
				array('route' => 'admin/i-mandir', 'roles' => array('admin')),
                array('route' => 'admin/imandir-links', 'roles' => array('admin')),
                                
                
                array('route' => 'admin/find', 'roles' => array('admin')),
                array('route' => 'admin/products', 'roles' => array('admin')),
                array('route' => 'admin/garlands', 'roles' => array('admin')),
                array('route' => 'admin/orders', 'roles' => array('admin')),
                array('route' => 'admin/impacts', 'roles' => array('admin')),               
                array('route' => 'admin/templates', 'roles' => array('admin')),
                array('route' => 'admin/cremation-templates', 'roles' => array('admin')),
                array('route' => 'scnsocialauth/user', 'roles' => array('admin')),
				
                
				//Administrator Controller 
               array('route' => 'administrator','roles' => array('admin')),
			   
               array('route' => 'menu','roles' => array('admin')),
			   array('route' => 'page','roles' => array('user','guest','admin')),
			   array('route' => 'admin-pages','roles' => array('admin')),
			   array('route' => 'mail','roles' => array('admin')),
               
            ),
        ),
    ),
);