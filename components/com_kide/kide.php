<?php

/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/defines.php');

if (JRequest::getCmd('no_html')) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-type: text/html; charset=utf-8');
}
else {
	require_once (KIDE_HELPERS."head.php");
}

require_once (KIDE_HELPERS."kide.php");
require_once (KIDE_HELPERS."template.php");
require_once (KIDE_HELPERS."user.php");
require_once (KIDE_DIRECT."user_config.php");
require_once (KIDE_HELPERS."links.php");

$controller = JRequest::getCmd('controller', 'kide');
if (!file_exists(KIDE_PHP.'controllers/'.$controller.'.php'))
	$controller = 'kide';
require_once (KIDE_PHP.'controllers/'.$controller.'.php');

$controller = 'KideController'.$controller;
$controller = new $controller();
$controller->execute(JRequest::getCmd('task'));
if (JRequest::getCmd('no_html')) exit;
$controller->redirect();