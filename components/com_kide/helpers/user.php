<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class kideUser {
	var $encendido;
	var $color;
	var $sesion;
	var $rango;
	var $id;
	var $captcha=1;
	var $gmt;
	var $retardo;
	var $name = "";
	var $works;
	var $sound;
	var $icons_hidden;
	var $token;
	var $img;
	var $ocultar_sesion;
	var $template;
	var $key;
	var $bantime;
	
	function __construct() {
		$user_config = kideUserConfig::getInstance();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$this->sesion = $user_config->load('sesion');
		$this->key = $user_config->load('key');
		$oldid = $user_config->load('userid');
		if (!$this->sesion || !$this->key || ($user->id != $oldid)) {
			if ($this->sesion) {
				$or = '';
				if ($oldid > 0) $or = ' OR userid='.$oldid;
				if ($user->id > 0) $or = ' OR userid='.$user->id;
				$db->setQuery('DELETE FROM #__kide_sesion WHERE sesion="'.$this->sesion.'"'.$or);
				$db->query();
			}
			$this->sesion = md5(mt_rand());
			$user_config->save('sesion', $this->sesion);
			$this->key = rand(1000000,9999999);
			$user_config->save('key', $this->key);
		}
		$this->id = $user->id;
		$user_config->save('userid', $this->id);

		if (!$this->id)
			$this->rango = $params->get("solo_registrados", false) ? 4 : 3;
		elseif (KideHelper::isAdmin())
			$this->rango = 1;
		else
			$this->rango = 2;
		if ($this->rango != 1) {
			$db->setQuery("SELECT * FROM #__kide_bans WHERE (sesion='".$this->sesion."' OR ip='".$_SERVER['REMOTE_ADDR']."') AND time > ".time());
			$ban = $db->loadObject();
			if ($ban) {
				if ($ban->ip != $_SERVER['REMOTE_ADDR']) {
					$db->setQuery("UPDATE #__kide_bans SET ip='".$_SERVER['REMOTE_ADDR']."' WHERE id=".$ban->id);
					$db->query();
				}
				$time = (int)$ban->time;
				if ($time > 0) {
					$this->rango = 5;
					$this->bantime = $time;
				}
			}
		}
		if ($params->get('recaptcha') && $params->get('recaptcha_public') && $params->get('recaptcha_private') && $this->rango == 3) {
			$session = JFactory::getSession();
			$this->captcha = $session->get('kide_captcha', 0);
		}
		if ($user->id) {
			$this->name = $params->get("username", true) ? $user->username : $user->name;
		}
		else {
			if ($user_config->load("name")) {
				$this->name = $user_config->load("name");
			}
			else {
				$this->name = JText::_("COM_KIDE_INVITADO")."_".rand(1000,9999);
				$user_config->save("name", $this->name);
			}
		}
		
		$this->name = substr($this->name, 0, 20); //20 max char nick length
		$this->name = addslashes(htmlspecialchars($this->name, ENT_COMPAT));
		$this->icons_hidden = $user_config->load("icons_hidden", $params->get("icons_hidden", false));
		$this->template = $user_config->load("template", $params->get("template", 'default'));
		$this->works = $this->rango <= 3 ? 1 : 0;
		$this->sound = $params->get("sound", 1) ? $user_config->load("sound", 0) : -1;
		$this->encendido = $user_config->load("encendido", 1);
		$this->color = $user_config->load("color", "");
		$this->token = JRequest::getInt('token', rand(), "POST");
		$this->gmt = $user_config->load("gmt", null);
		$this->retardo = $user_config->load("retardo", null);
		$this->ocultar_sesion = $user_config->load("ocultar_sesion", 0);
		$this->img = kideLinks::getAvatar();
	}

	function &getInstance(){
		static $instance;
		if (!is_object($instance))
			$instance = new kideUser();
		return $instance;
	}
}
