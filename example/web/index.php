<?php
/**
 * 应用唯一入口
 * 
 * @author lxj
 */

/* 应用名称（应用文件夹名称，区分大小写） */
define('APP_NAME', 'example');

<<<<<<< HEAD
/* 入口目录 */
define('SITE_PATH', getcwd());

=======
>>>>>>> refs/remotes/origin/1.0.0
/* 应用配置 */
$_config = include(__DIR__ . '/../config.php');

/* 引入函数 */
require(__DIR__ . '/../../ez/autoload.php');
<<<<<<< HEAD
include(__DIR__ . '/../function.php');
=======
>>>>>>> refs/remotes/origin/1.0.0

/* 应用开始 */
\ez\core\Ez::start();
