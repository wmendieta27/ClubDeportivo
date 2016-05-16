<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

class kideUserConfig {
	var $config = array();
	var $version = 3;
	var $name = "kide_config";
	var $time = 2592000;

	function __construct() {
		if ($this->cookie($this->name)) {
			$this->config = $this->getArray($this->cookie($this->name));
		}
		if (!isset($this->config["v"]) || (int)$this->config["v"] < $this->version) {
			$this->config = array();
			setcookie($this->name, "v=".$this->version, time() + $this->time, "/");
			$this->config["v"] = $this->version;
		}
	}
	function &getInstance(){
		static $instance;
		if (!is_object($instance)) {
			$instance = new kideUserConfig();
		}
		return $instance;
	}
	function getArray($config) {
		$aux = array();
		if (strlen($config) > 2) {
			$opciones = explode(";", $config);
			foreach ($opciones as $opcion) {
				$opcion = explode("=", $opcion);
				if ($opcion[0])
					$aux[$opcion[0]] = isset($opcion[1])?$opcion[1]:'';
			}
		}
		return $aux;
	}
	function getString($config) {
		$return = "";
		foreach ($config as $key=>$value)
			$return .= "$key=$value;";
		return $return;
	}
	function save($param, $value='') {
		if ($value === null || $value === '')
			unset($this->config[$param]);
		else
			$this->config[$param] = urlencode($value);
		setcookie($this->name, $this->getString($this->config), time() + $this->time, "/");
	}
	function load($param, $default=null) {
		return isset($this->config[$param]) ? urldecode($this->config[$param]) : $default;
	}
	function get($c, $d=null) {
		return isset($_GET[$c]) ? $_GET[$c] : $d;
	}
	function cookie($c, $d=null) {
		return isset($_COOKIE[$c]) ? $_COOKIE[$c] : $d;
	}
}

$kconfig = kideUserConfig::getInstance();

if ($kconfig->get('param') && $kconfig->get('funcion')) {
	
	header("Content-Type: text/html; charset=utf-8");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	if ($kconfig->get('funcion') == "load") {
		print_r($kconfig);
		//echo $kconfig->load($kconfig->get('param'));
	}
	else {	
		$kconfig->save($kconfig->get('param'), $kconfig->get('value'));
	}
}
