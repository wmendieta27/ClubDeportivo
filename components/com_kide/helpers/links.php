<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

class kideLinks {
	var $link;
	var $r;
	var $l;
	var $Itemid;
	var $v;
	var $params;
	
	function __construct() {
		$this->params = JComponentHelper::getParams('com_kide');
		$u = JURI::getInstance();
		$perfil = $this->params->get('perfil_link');
		if ($perfil == 'cb')
			$this->setLink('com_comprofiler', 'task=userProfile&user=');
		elseif ($perfil == "cbe")
			$this->setLink("com_cbe", "task=userProfile&user=");
		elseif ($perfil == 'cbe25')
			$this->setLink('com_cbe', 'view=profile&userid='); 
		elseif ($perfil == 'js')
			$this->setLink('com_community', 'view=profile&userid=');
		elseif ($perfil == 'kunena') {
			$this->setLink('com_kunena', 'func=profile&userid=');
			//$this->setLink('com_kunena', 'func=fbprofile&task=showprf&userid='); //old kunena link
		}
		elseif ($perfil == 'aup')
			$this->setLink('com_alphauserpoints', 'view=account&userid=');
		
		$this->v = substr(JVERSION, 0, 3);
		$com = substr(JVERSION, 0, 3) == "1.5" ? 'user' : 'users';
		if (!$this->l)
			$this->l = JRoute::_('index.php?option=com_'.$com.'&view=login&return='.base64_encode($u->toString()));
		if (!$this->r)
			$this->r = JRoute::_('index.php?option=com_'.$com.'&view=register');
	}
	
	function &getInstance(){
		static $instance;
		if (!is_object($instance))
			$instance = new kideLinks();
		return $instance;
	}
	function getRegisterURL() {
		$class = kideLinks::getInstance();
		return $class->r;
	}
	function getLoginURL() {
		$class = kideLinks::getInstance();
		return $class->l;
	}
	function getUserLink($userid) {
		$class = kideLinks::getInstance();
		if (!(int)$userid || !$class->link) return '';
		return JRoute::_($class->link.$userid);
	}
	
	function setLink($com, $link) {
		$db = JFactory::getDBO();
		
		$tmp = $this->v == '1.5' ? '=0' : '<=1';
		$db->setQuery("SELECT id FROM #__menu WHERE link LIKE 'index.php?option=".$com."%' AND access ".$tmp." AND published='1' LIMIT 1");
		$this->Itemid = $db->loadResult();
			
		$this->Itemid = $this->Itemid > 0 ? '&Itemid='.$this->Itemid : '';
		$this->link = 'index.php?option='.$com.$this->Itemid.'&'.$link;
		
		if ($com == 'com_comprofiler') {
			$this->l = JRoute::_('index.php?option=com_comprofiler&task=login'.$this->Itemid);
			$this->r = JRoute::_('index.php?option=com_comprofiler&task=registers'.$this->Itemid);
		}
		elseif ($com == 'com_community') {
			$this->l = JRoute::_('index.php?option=com_community&view=frontpage'.$this->Itemid);
			$this->r =  JRoute::_('index.php?option=com_community&view=register'.$this->Itemid);
		}
		elseif ($com == 'com_cbe') {
			$this->r = JRoute::_('index.php?option=com_cbe&task=registers'.$this->Itemid);
		}
	}
	function getAvatar() {
		$class = kideLinks::getInstance();
		return $class->_getAvatar();
	}
	function _getAvatar() {
		static $avatar;
		if (!$avatar) {
			$user = JFactory::getUser();
			if ($this->params->get('perfil_fb'))
				$avatar = $this->getAvatarFB();
			if (!$avatar)
				$avatar = $this->getAvatarJoomla();
			if (!$avatar)
				$avatar = 'http://www.gravatar.com/avatar/'.md5($user->id?$user->email:(session_id()?session_id():rand())).'?s=50&d='.$this->params->get('gravatar_d', 'identicon');
			$avatar = htmlspecialchars($avatar);
		}
		return $avatar;
	}
	function getAvatarFB() {
		if (!class_exists('Facebook'))
			require_once(KIDE_LIBS.'fb/facebook.php');
		$config = array();
		$config['appId'] = $this->params->get('fb_app_id');
		$config['secret'] = $this->params->get('fb_app_secret');
		$facebook = new Facebook($config);
		$uid = $facebook->getUser();
		return $uid ? 'http://graph.facebook.com/'.$uid.'/picture' : '';
	}
	function getAvatarJoomla() {
		$user = JFactory::getUser();
		$avatar = '';
		if ($user->id) {
			$db = JFactory::getDBO();
			$perfil = $this->params->get('perfil_link');
			if ($perfil == 'js') {
				$db->setQuery('SELECT thumb FROM #__community_users WHERE userid='.$user->id);
				$tmp = $db->loadResult();
				if ($tmp && strpos($tmp, '/default_thumb.jpg') === false && file_exists(JPATH_ROOT.'/'.$tmp))
					$avatar = JURI::root().$tmp;
			}
			elseif ($perfil == 'kunena') {
				$db->setQuery('SELECT avatar FROM #__kunena_users WHERE userid='.$user->id);
				$tmp = $db->loadResult();
				if ($tmp && file_exists(JPATH_ROOT.'/media/kunena/avatars/'.$tmp))
					$avatar = JURI::root().'media/kunena/avatars/'.$tmp;
			}
			elseif ($perfil == 'cb') {
				$db->setQuery('SELECT avatar FROM #__comprofiler WHERE user_id='.$user->id);
				$tmp = $db->loadResult();
				if ($tmp && strpos($tmp, '/default_thumb.jpg') === false) {
					if (file_exists(JPATH_ROOT.'/images/comprofiler/tn'.$tmp))
						$avatar = JURI::root().'images/comprofiler/tn'.$tmp;
					elseif (file_exists(JPATH_ROOT.'/images/comprofiler/'.$tmp))
						$avatar = JURI::root().'images/comprofiler/'.$tmp;
				}
			}
			elseif ($perfil == "cbe") {
				$db->setQuery("SELECT avatar FROM #__cbe WHERE user_id=".$user->id);
				$tmp = $db->loadResult();
				if ($tmp && strpos($tmp, "/default_thumb.jpg") === false) {
					if (file_exists(JPATH_ROOT."/images/cbe/tn".$tmp))
						$avatar = JURI::root()."images/cbe/tn".$tmp;
					elseif (file_exists(JPATH_ROOT."/images/cbe/".$tmp))
						$avatar = JURI::root()."images/cbe/".$tmp;
				}
			}
			elseif ($perfil == 'cbe25') {
				$db->setQuery('SELECT thumb FROM #__cbe_users WHERE userid='.$user->id);
				$tmp = $db->loadResult();
				if ($tmp && strpos($tmp, '/default_thumb.jpg') === false && file_exists(JPATH_ROOT.'/'.$tmp))
					$avatar = JURI::root().$tmp;
			} 
			elseif ($perfil == 'aup') {
				$db->setQuery('SELECT avatar FROM #__alpha_userpoints WHERE userid='.$user->id);
				$tmp = $db->loadResult();
				if ($tmp && file_exists(JPATH_ROOT.'/components/com_alphauserpoints/assets/images/avatars/'.$tmp)) 
					$avatar = JURI::root().'components/com_alphauserpoints/assets/images/avatars/'.$tmp;
			}
			elseif ($perfil == 'agora') {
				$db->setQuery('SELECT id FROM #__agora_users WHERE jos_id='.$user->id.' AND use_avatar = 1');
				$tmp = $db->loadResult();
				if ($tmp > 0) 
					$avatar = JURI::root().'components/com_agora/img/pre_avatars/'.$tmp;
			}
		}
		return $avatar;
	}
}
