<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['base_url'] = 'http://localhost/file_approval_system/';

$config['index_page'] = '';

$config['uri_protocol'] = 'REQUEST_URI';

$config['log_threshold'] = 1; // Add this line - 0=disable, 1=errors, 2=debug, 4=info

$config['log_path'] = ''; // Add this line - empty means use application/logs/

// Error logging directory
if (!isset($config['log_path']) || $config['log_path'] === '') {
    $config['log_path'] = APPPATH . 'logs/';
}

$config['log_file_extension'] = '';

$config['log_file_permissions'] = 0644;

$config['log_date_format'] = 'Y-m-d H:i:s';

// Session Configuration (add these)
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

// Other important settings
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

// Error Views Path (fix the missing error views)
$config['error_views_path'] = '';

// Cache Directory Path
$config['cache_path'] = '';

// Encryption Key (generate one)
$config['encryption_key'] = 'd35005fc55e05957ff5bb861999fb275%';

// Other settings continue below...