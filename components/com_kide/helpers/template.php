<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

class KideTemplate {
	var $def = 'default';
	var $tuser;
	var $view;
	
	function __construct() {
		$ktuser = KideUser::getInstance();
		$this->tuser = $ktuser->template;
		if (!file_exists(KIDE_TPL_PHP.$this->tuser.'/'))
			$this->tuser = 'default';
		$this->view = JRequest::getCmd('view', 'kide');
		$this->check_language();
	}
	function check_language() {
		if (file_exists(KIDE_TPL_PHP.$this->tuser.'/template.xml')) {
			$xml = simplexml_load_file(KIDE_TPL_PHP.$this->tuser.'/template.xml');
			if (isset($xml->languages) && isset($xml->languages[0])) {
				$folder = isset($xml->languages[0]['folder']) ? ((string)$xml->languages[0]['folder']).'/' : '';
				$path = KIDE_TPL_PHP.$this->tuser.'/'.$folder;
				$this->load_language($path);
			}
		}
	}
	function load_language($path, $default="en-GB") {
		$user = JFactory::getUser();
		$language = JFactory::getLanguage();
		if ($this->lc($path, $user->getParam("language")))
			return;
		elseif ($this->lc($path, $language->getTag()))
			return;
		else
			$this->lc($path, $default);
	}
	function lc($path, $tag) {
		if (file_exists($path.$tag.".ini")) {
			$language->_load($path.$tag.".ini");
			return true;
		}
		return false;
	}
	function &getInstance() {
		static $class;
		if (!is_object($class)) 
			$class = new KideTemplate;
		return $class;
	}
	function assignRef($name, &$var) {
		$this->$name = $var;
	}
	function assign($name, $var) {
		$this->$name = $var;
	}
	function display($tmpl='') {
		$file = $tmpl ? $this->view.'.'.$tmpl.'.php' : $this->view.'.php';
		$tpl = file_exists(KIDE_TPL_PHP.$this->tuser.'/tmpl/'.$file) ? $this->tuser : $this->def;
		include KIDE_TPL_PHP.$tpl.'/tmpl/'.$file;
	}
	function include_php($file) {
		$tpl = file_exists(KIDE_TPL_PHP.$this->tuser.'/'.$file) ? $this->tuser : $this->def;
		require_once(KIDE_TPL_PHP.$tpl.'/'.$file);
	}
	function include_html($folder, $file) {
		$document =JFactory::getDocument();
		if ($folder == 'css' || $folder == 'js') $file .= '.'.$folder;
		if ($folder != 'css' && $folder != 'js' && $folder != 'sound') $f = 'images/'.$folder.'/';
		else $f = $folder.'/';
		$tpl = file_exists(KIDE_TPL_PHP.$this->tuser.'/'.$f.$file) ? $this->tuser : $this->def;
		
		if ($folder == "css")
			$document->addStyleSheet(KIDE_TPL_HTML.$tpl.'/'.$f.$file);
		elseif ($folder == "js")
			$document->addScript(KIDE_TPL_HTML.$tpl.'/'.$f.$file);
		else
			return KIDE_TPL_HTML.$tpl.'/'.$f.$file;
	}
}