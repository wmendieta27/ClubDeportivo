<?php

/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class KideControllerKide extends JControllerLegacy {
	function display($cachable = false, $urlparams = false) {
		parent::display($cachable, $urlparams);
	}
	function direct() {
		$f = JRequest::getVar('file').'.php';
		if (file_exists(KIDE_DIRECT.$f))
			require_once(KIDE_DIRECT.$f);
		exit;
	}
	function catpcha_check() {
		$params = JComponentHelper::getParams('com_kide');
		if (!function_exists('_recaptcha_qsencode'))
			require_once (KIDE_LIBS."recaptchalib.php");
		$resp = recaptcha_check_answer($params->get('recaptcha_private'), $_SERVER["REMOTE_ADDR"], JRequest::getVar('recaptcha_challenge_field', '', 'POST'), JRequest::getVar('recaptcha_response_field', '', 'POST'));
		if ($resp->is_valid) {
			$session = JFactory::getSession();
			$session->set('kide_captcha', 1);
			echo 'ok';
		}
		else
			echo 'Error: '.$resp->error;
	}
	function borrar() {
		$id = JRequest::getVar('id', 0, "POST");
		$kuser = kideUser::getInstance();
		$db = JFactory::getDBO();
		
		if ($id) {
			if ($kuser->rango == 1)
				$db->setQuery("DELETE FROM #__kide WHERE id=".$id);
			else
				$db->setQuery("DELETE FROM #__kide WHERE id=".$id." AND sesion='".$kuser->sesion."'");
			$db->query();
		} 
	}
	function banear() {
		$kuser = kideUser::getInstance();
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		
		if ($kuser->rango == 1) {
			$sesion = JRequest::getVar('sesion', "", "POST");
			$dias = JRequest::getInt('dias', 0, "POST");
			$horas = JRequest::getInt('horas', 0, "POST");
			$minutos = JRequest::getInt('minutos', 0, "POST");
			$t = (($dias*24+$horas)*60+$minutos)*60;
			if ($t > 0 && $sesion) {
				$t += time();
				$db->setQuery("DELETE FROM #__kide_bans WHERE time<".time());
				$db->query();
				$db->setQuery("SELECT id FROM #__kide_bans WHERE sesion='".$sesion."'");
				$id = $db->loadResult();
				if ($id) 
					$db->setQuery("UPDATE #__kide_bans SET time=".$t." WHERE id=".$id);
				else
					$db->setQuery("INSERT INTO #__kide_bans (sesion, time) VALUES ('".$sesion."', ".$t.")");
				$db->query();
				echo str_replace("%s1", $sesion, str_replace("%s2", gmdate($params->get("formato_fecha", "j-n G:i:s"), $t+$kuser->gmt*3600), JText::_("COM_KIDE_IP_BANEADA")));
			}
		}
	}
	function more_smileys() {
		echo '<style>img {border:0}</style>'.KideHelper::smilies_html('ajax', JRequest::getInt('window', 0));
	}
	function sesiones() {
		kideHelper::updateSesion();
		if (JRequest::getInt('show_sessions')) {
			$db = JFactory::getDBO();
			$id = JRequest::getVar('id', 0, "POST");
			$params = JComponentHelper::getParams('com_kide');
			header("Content-type: text/xml");
			echo '<?xml version="1.0" encoding="UTF-8" ?>';
			echo '<xml>';
			$db->setQuery("SELECT * FROM #__kide_sesion WHERE ocultar=0 AND userid!=0  AND time>".(time() - $params->get("sesion_time", 100)). " ORDER BY name ASC");
			$users = $db->loadObjectList();
			if ($users) {
				foreach ($users as $u)
					echo '<user rango="'.$u->rango.'" name="'.htmlspecialchars($u->name).'" class="'.KideHelper::getRango($u->rango, 'KIDE_').'" sesion="'.$u->sesion.'" profile="'.kideLinks::getUserLink($u->userid).'" userid="'.$u->userid.'" img="'.$u->img.'" />';
			}
			$db->setQuery("SELECT * FROM #__kide_sesion WHERE ocultar=0 AND userid=0 AND time>".(time() - $params->get("sesion_time", 100)). " ORDER BY name ASC");
			$users = $db->loadObjectList();
			if ($users) {
				foreach ($users as $u)
					echo '<user rango="'.$u->rango.'" name="'.htmlspecialchars($u->name).'" class="'.KideHelper::getRango($u->rango, 'KIDE_').'" sesion="'.$u->sesion.'" profile="" userid="0" img="'.$u->img.'" />';
			}
			echo '</xml>';
		}
	}
	function reload() {
		$db = JFactory::getDBO();
		//$db->setQuery("SET NAMES 'utf8'");
		$kuser = kideUser::getInstance();
		$params = JComponentHelper::getParams('com_kide');
		
		$db->setQuery("SELECT * FROM #__kide where id>'".JRequest::getInt('id', 0, "POST")."' AND token!=".$kuser->token." ORDER BY id ASC");
		$rows = $db->loadObjectList();
		if ($params->get('order', 'bottom') == 'top')
			krsort($rows);
		header("Content-type: text/xml");
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		echo '<xml>';
		echo '<privados>0</privados>';
		if ($rows) {
			echo '<last_id>'.$rows[count($rows)-1]->id.'</last_id>';
			echo '<last_time>'.$rows[count($rows)-1]->time.'</last_time>';
			foreach ($rows as $row) {
				echo '<mensaje uid="'.$row->userid.'" img="'.$row->img.'" time="'.$row->time.'" id="'.$row->id.'" hora="'.gmdate($params->get("formato_hora", "G:i--"), $row->time + $kuser->gmt*3600).'" name="'.htmlspecialchars($row->name).'" url="'.htmlspecialchars($row->url).'" date="'.gmdate($params->get("formato_fecha", "j-n G:i:s"), $row->time + $kuser->gmt*3600).'" color="'.$row->color.'" rango="'.$row->rango.'" sesion="'.$row->sesion.'">';
				echo '<![CDATA['.$row->text.']]>';
				echo '</mensaje>';
			}
		}
		echo '</xml>';
	}
	function add() {
		header("Content-type: text/xml");
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$kuser = kideUser::getInstance();
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		
		if ($kuser->rango == 4 || !$kuser->captcha) {
			echo '<xml></xml>';
			exit;
		}
		if ($kuser->rango == 5) {
			echo '<xml banned="1"></xml>';
			exit;
		}
		
		$banear = JRequest::getInt('banear', 0, "POST");
		if ($banear) {
			$tiempo = time() + $params->get("banear_minutos", 5)*60;
			$db->setQuery("DELETE FROM #__kide_bans WHERE time<".time());
			$db->query();
			$db->setQuery("INSERT INTO #__kide_bans (ip, sesion, time) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$kuser->sesion."', ".$tiempo.")");
			$db->query();
			$db->setQuery('INSERT INTO #__kide (name,userid,text,time,color,rango,sesion,ip,img,url) VALUES ("System", 0, "'.str_replace("%name", $kuser->name, JText::_("COM_KIDE_USER_BANEADO")).'", '.time().', "'.$params->get('color_sp', '000').'", 0, 0, "", "", "")');
			$db->query();
			echo '<xml banned="1"></xml>';
			exit;
		}
		
		$txt = JRequest::getVar('txt', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$id = 0;
		if ($txt && $txt != JText::_("COM_KIDE_NOSPAM")) {
			$db->setQuery('SHOW TABLE STATUS LIKE "'.$db->getPrefix().'kide"');
			$status = $db->loadObject();
			$txt = kideHelper::convertText($txt, $status->Auto_increment);
			
			$db->setQuery("SELECT id,text,sesion,token FROM #__kide ORDER BY id DESC LIMIT 1");
			$lastmsg = $db->loadObject();
			
			if ((!$kuser->rango || $kuser->rango == 3) && $lastmsg && $lastmsg->text == $txt && $lastmsg->token == $kuser->token) {
				echo '<xml banned="0" id="0" tiempo="" hora="">';
				echo '<txt><![CDATA['.$txt.']]></txt>';
				echo '<img />';
				echo '</xml>';
				exit;
			}

			$db->setQuery('INSERT INTO #__kide (name,userid,text,time,color,rango,token,sesion,img,url) VALUES ("'.$kuser->name.'", '.$kuser->id.', "'.addslashes($txt).'", '.time().', "'.$kuser->color.'", '.$kuser->rango.', '.$kuser->token.', "'.$kuser->sesion.'", "'.$kuser->img.'", "'.kideLinks::getUserLink($kuser->id).'")');
			$db->query();
			
			$db->setQuery("SELECT id FROM #__kide WHERE token=".$kuser->token." AND name='".$kuser->name."' ORDER BY id DESC");
			$id = $db->loadResult();

			$save = $params->get("msgs_saved", 500);
			if ($save > 0 && $lastmsg) {
				$var = $lastmsg->id + 1 - $save;
				if ($var > 0) {
					$db->setQuery("DELETE FROM #__kide WHERE id < ".$var);
					$db->query();
				}
			}
		}
		$t = time();
		echo '<xml txt="1" banned="0" id="'.$id.'" img="'.$kuser->img.'" tiempo="'.$t.'" hora="'.gmdate($params->get("formato_hora", "G:i--"), $t + $kuser->gmt*3600).'">';
		if ($txt)
			echo '<txt><![CDATA['.$txt.']]></txt>';
		echo '</xml>';
	}
	function retardo() {
		echo time()."|ok";
	}
}
