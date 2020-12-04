<?php
// output_path
define('OUTPUT_PATH', BASE_DIR.'/static/');

// cache_system, ['Redis', 'File']
define('CACHE_SYSTEM', 'Redis');

//cache_expires_time, second
define('CACHE_TIME', 60*60*24*3); // 3days

define('CURRENT_PAGE', htmlspecialchars($_GET['page']));

define('CURRENT_POST', htmlspecialchars($_GET['post']));
