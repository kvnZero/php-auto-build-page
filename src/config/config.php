<?php
// output_path
define('OUTPUT_PATH', BASE_DIR.'/static/');

// post_path
define('POST_PATH', BASE_DIR.'/post/');

// post_path
define('TEMPLATE_PATH', BASE_DIR.'/template/');

// cache_system, ['Redis', 'File']
define('CACHE_SYSTEM', 'Redis');

//cache_expires_time, second
define('CACHE_TIME', 60*60*24*3); // 3days

define('CURRENT_PAGE', param('page'));

define('CURRENT_POST', param('post'));

define('DEBUG_ALLWAYS_BUILD', false);

define('POST_STATIC_FILE_MAD5', true);
