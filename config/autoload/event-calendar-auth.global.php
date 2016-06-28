<?php
/**
 * EventCalendar Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * Zend\Db\Adapter\Adapter DI Alias
     *
     * Please specify the DI alias for the configured Zend\Db\Adapter\Adapter
     * instance that ZfcUser should use.
     */
    'zend_db_adapter' => 'Zend\Db\Adapter\Adapter',

    /**
     * Zend\Session\SessionManager DI Alias
     *
     * Please specify the DI alias for the configured Zend\Session\SessionManager
     * instance that ScnSocialAuth should use.
     */
    'zend_session_manager' => 'Zend\Session\SessionManager',

   
);

/**
 * You do not need to edit below this line
 */
return array(
    'event-calendar-auth' => $settings,
    'service_manager' => array(
        'aliases' => array(
            'ScnSocialAuth_ZendDbAdapter' => (isset($settings['zend_db_adapter'])) ? $settings['zend_db_adapter']: 'Zend\Db\Adapter\Adapter',
            'ScnSocialAuth_ZendSessionManager' => (isset($settings['zend_session_manager'])) ? $settings['zend_session_manager']: 'Zend\Session\SessionManager',
        ),
    ),
);
