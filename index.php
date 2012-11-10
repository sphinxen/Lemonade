<?php
error_reporting(-1);

define('BASE', dirname($_SERVER['SCRIPT_NAME']) . (substr(dirname($_SERVER['SCRIPT_NAME']), -1) == '/' ? '' : '/'));
define('ROOT', realpath(dirname(__FILE__)).'/');


require_once ROOT.'system/core/bootstrap.php';
require_once ROOT.'system/core/lemonade_blender.php';

$lemonade = Lemonade_blender::GetInstance();

$lemonade->FrontController();

