<?php
return array(
    'modules' => array(
        'ZfcBase',
        'ZfcUser',
        'BjyAuthorize',
        
        //'ZendDeveloperTools',    	
    	//'ZFTool',
        'WebinoImageThumb',
        'Application',
		'Administrator',
        'Admin',
        'ScnSocialAuth',
        'BjyProfiler', 
       
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    )
);
