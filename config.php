<?php
namespace reks;


// Do not change these.
$config=array(); 
include dirname(__FILE__) . '/cache/autoconfig.php';
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'routes.php';
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules.php';


// Override settings below!


/**
 * 
 * Enter description here ...
 * @var string
 */
$config['language'] = 'en';



/**
 * Logging directory. Must be writable.
 * @var string
 */
$config['log_dir'] = $app->APP_PATH . '/logs';

/**
 * What to log to the logs.
 * There are these types: Log::E_DEBUG, Log::E_ERROR, Log::E_INFO, Log::E_WARN
 * You can use many with the bitwise "|" separator.
 * @var int
 */
$config['log_level'] = Log::E_ERROR | Log::E_INFO | Log::E_WARN;

/**
 * Do we want to remove index.php from URL? 
 * In most cases YES, but we need external .htaccess file, not all servers
 * supports this.
 * @var boolean true if we want to remove index.php and false if not.
 */
$config['remove_scriptpath'] = false;

