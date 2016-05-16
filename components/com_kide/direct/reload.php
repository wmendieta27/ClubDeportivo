<?php

header('Content-type: text/html; charset=utf-8');
$encodings = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? explode(', ', strtolower($_SERVER['HTTP_ACCEPT_ENCODING'])) : array();
if (in_array('gzip', $encodings) && !ini_get('zlib.output_compression') && !ini_get('session.use_trans_sid') && extension_loaded('zlib') && function_exists('ob_gzhandler'))
	ob_start("ob_gzhandler");

function get($n, $v=null) {
	return isset($_POST[$n]) ? $_POST[$n] : $v;
}

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/configuration.php');
require_once(dirname(__FILE__).'/database.php');
require_once(dirname(__FILE__).'/user_config.php');

$config = new JConfig;
$db = new Database($config->host, $config->user, $config->password, $config->db, $config->dbprefix);
$user = kideUserConfig::getInstance();

$rows = $db->loadObjectList("SELECT * FROM #__kide where id>'".get('id',0)."' AND token!='".get('token')."' ORDER BY id ASC");
if (get('order') == 'top')
	krsort($rows);

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<xml>';
echo '<privados>0</privados>';
if ($rows) {
	echo '<last_id>'.$rows[count($rows)-1]->id.'</last_id>';
	echo '<last_time>'.$rows[count($rows)-1]->time.'</last_time>';
	foreach ($rows as $row) {
		echo '<mensaje uid="'.$row->userid.'" img="'.$row->img.'" time="'.$row->time.'" id="'.$row->id.'" hora="'.gmdate(get('formato_hora'), $row->time + get('gmt',0)*3600).'" name="'.htmlspecialchars($row->name).'" url="'.htmlspecialchars($row->url).'" date="'.gmdate(get('formato_fecha'), $row->time + get('gmt',0)*3600).'" color="'.$row->color.'" rango="'.$row->rango.'" sesion="'.$row->sesion.'">';
		echo '<![CDATA['.$row->text.']]>';
		echo '</mensaje>';
	}
}
echo '</xml>';