<?php

header('Content-type: text/html; charset=utf-8');
$encodings = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? explode(', ', strtolower($_SERVER['HTTP_ACCEPT_ENCODING'])) : array();
if (in_array('gzip', $encodings) && !ini_get('zlib.output_compression') && !ini_get('session.use_trans_sid') && extension_loaded('zlib') && function_exists('ob_gzhandler'))
	ob_start("ob_gzhandler");

function get($n, $v=null) {
	return isset($_GET[$n]) ? $_GET[$n] : $v;
}

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/configuration.php');
require_once(dirname(__FILE__).'/database.php');

$config = new JConfig;
$db = new Database($config->host, $config->user, $config->password, $config->db, $config->dbprefix);

$rows = $db->loadObjectList("SELECT name,img,userid FROM #__kide_sesion WHERE ocultar=0 AND time>".(time() - get("st", 100)). " ORDER BY name ASC");

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<xml>';
foreach ($rows as $row)
	echo '<user name="'.htmlspecialchars($row->name).'" img="'.$row->img.'" id="'.$row->userid.'" />';
echo '</xml>';