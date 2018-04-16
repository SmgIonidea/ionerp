<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
if (C_DEBUG && DB_QUERIES_LOG) {
    $hook['post_controller'] = array(
        'function' => 'query_log',
        'filename' => 'common.php',
        'filepath' => 'core',
        'params' => 'db'
    );
}

if (SITE_IS_DOWN) {
    $hook['post_controller'] = array(
        'function' => 'site_down',
        'filename' => 'common.php',
        'filepath' => 'core'        
    );
}