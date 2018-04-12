<?php

/**
 * Declare only the constants for global configuration
 */
defined('SITE_IS_DOWN') OR define('SITE_IS_DOWN', '0');
defined('SITE_DOWN_MSG') OR define('SITE_DOWN_MSG', 'SITEDOWN MESSAGE HERE...');
defined('GLOBAL_NOTICE') OR define('GLOBAL_NOTICE', '');
defined('APP_HOST') OR define('APP_HOST', $_SERVER['HTTP_HOST']);
defined('APP_HOST') OR define('APP_HOST', $_SERVER['HTTP_HOST']);
defined('APP_VER') OR define('APP_VER', 'IonERP');
defined('APP_BASE') OR define('APP_BASE', 'http://' . APP_HOST . '/' . APP_VER . '/');
defined('DB_HOST') OR define('DB_HOST', $_SERVER['HTTP_HOST']);
defined('DB_NAME') OR define('DB_NAME', 'ionerp');
defined('DB_USER') OR define('DB_USER', 'root');
defined('DB_PASS') OR define('DB_PASS', '');
defined('ENC_KEY') OR define('ENC_KEY', 'cinaacabcdef123ef4578z1k2l3i4p5o67q890n98b');

/* * ***** DEFAULT DEBUG ******* */
defined('DEBUG') OR define('DEBUG', '1'); //array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4);
/* * ***** CUSTOM DEBUG ******* */
defined('C_DEBUG') OR define('C_DEBUG', '1');
defined('DEBUG_SCRIPT') OR define('DEBUG_SCRIPT', '1');
defined('CUSTOM_APP_LOG') OR define('CUSTOM_APP_LOG', 'APP');
defined('DB_QUERIES_LOG') OR define('DB_QUERIES_LOG', '0');
?>

