<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class KideHelper {
	function htmlInJs($html) {
		return addslashes(str_replace(array("\n", "\r"), array('',''), $html));
	}
	function convertText($txt, $id) {
		$params = JComponentHelper::getParams('com_kide');
		$max_strlen = $params->get('msgs_max_strlen', 3000);
		if ($max_strlen > 0 && strlen($txt) > $max_strlen) 
			$txt = substr($txt, 0, $max_strlen);
		$txt = ' '.trim($txt).' ';
		$txt = htmlspecialchars($txt, ENT_NOQUOTES);
		$txt = kideHelper::make_links($txt);
		$txt = kideHelper::convert_smilies($txt);
		$txt = str_replace(array("\n", "\r"), array("<br />", ""), $txt);
		return $txt;
	}
	function opciones($cantidad) {
		for ($i=1; $i<=$cantidad; $i++) 
			echo '<option value="'.$i.'">'.$i.'</option>';
	}
	function getRango($rango, $b="") {
		$rangos = array("special", "admin", "registered", "guest", "guest");
		return $b.$rangos[$rango];
	}
	function getRangos() {
		return array("special", "admin", "registered", "guest", "guest");
	}
	function isAdmin($id=0) {
		if ($id)
			$user = JFactory::getUser($id);
		else
			$user = JFactory::getUser();
		if (substr(JVERSION, 0, 3) == 1.5) {
			if ($user->usertype == "Super Administrator" || $user->usertype == "Administrator")
				return true;
		}
		elseif ($user->authorise('kide.admin', 'com_kide')) 
			return true;
		return false;
	}
	function getLastTime() {
		$db = JFactory::getDBO();
		$db->setQuery("SELECT time FROM #__kide ORDER BY id DESC LIMIT 1");
		return (int)$db->loadResult();
	}
	function updateSesion() {
		$db = JFactory::getDBO();
		$kuser = kideUser::getInstance();
		$params = JComponentHelper::getParams('com_kide');
		$time = time() - $params->get("sesion_time", 100);
		$db->setQuery("DELETE FROM #__kide_sesion WHERE time<".$time);
		$db->query();
		if ($kuser->works) {
			$db->setQuery("INSERT INTO #__kide_sesion (name,userid,rango,time,sesion,img,ocultar,`key`) 
					VALUES ('".$kuser->name."', ".$kuser->id.", ".$kuser->rango.", ".time().",'".$kuser->sesion."','".$kuser->img."',".$kuser->ocultar_sesion.",".$kuser->key.")
					ON DUPLICATE KEY UPDATE name='".$kuser->name."',time=".time().",ocultar=".$kuser->ocultar_sesion.",img='".$kuser->img."'");
			$db->query();
		}
	}
	function make_links($text) {
		$params = JComponentHelper::getParams('com_kide');
		$urls = $params->get("urls_text", "text");
		if ($urls == "text")
			return preg_replace("/(\n| )(http[^ |\n]+)/", '\1<a rel="nofollow" target="_blank" href="\2">'.$params->get("urls_text_personalized", "«link»").'</a>', $text);
		elseif ($urls == "link")
			return preg_replace("/(\n| )(http[^ |\n]+)/", '\1<a rel="nofollow" target="_blank" href="\2">\2</a>', $text);
		return $text;
	}
	function moreSmileys($com) {
		$params = JComponentHelper::getParams('com_kide');
		$show = $params->get("icons_show_".$com, $com=='com'?0:14);
		if (!$show)
			return "";
		
		$smilies = kideHelper::getSmileys();
		$aux = array();
		foreach ($smilies as $s) {
			if (!in_array($s,$aux))
				$aux[] = $s;
		}

		if (count($aux) > $show) {
			if ($params->get('icons_window','popup') == "no_window") {
				return ' <a href="javascript:kide.show(\'KIDE_mas_iconos\')">'.JText::_('COM_KIDE_MAS_ICONOS').'</a>';
			}
			else{
				$xy = explode('x', $params->get('icons_popup_size', '500x500'));
				if (!($xy[0]>0)) $xy[0] = 500;
				if (!isset($xy[1])) $xy[1] = 500;
				if ($params->get('icons_window','popup') == 'popup') {
					$size = ',width='.$xy[0].',height='.$xy[1];
					$onclick = "window.open('".JRoute::_(KIDE_AJAX."&task=more_smileys&window=1")."','kide','menubar=0,resizable=1,location=0,status=0,scrollbars=1".$size."');";
					return ' <a href="'.JRoute::_(KIDE_AJAX.'&task=more_smileys').'" onclick="'.$onclick.'">'.JText::_('COM_KIDE_MAS_ICONOS').'</a>';
				}
				else {
					JHTML::_('behavior.mootools');
					JHTML::_('behavior.modal');
					$rel = "{handler: 'iframe', size: {x: ".$xy[0].", y: ".$xy[1]."}, onClose: function() {}}";
					return ' <a class="modal" href="'.JRoute::_(KIDE_AJAX.'&task=more_smileys').'" rel="'.$rel.'">'.JText::_('COM_KIDE_MAS_ICONOS').'</a>';
				}
			}
		}
		return '';
	}
	function smilies_html($com, $window=null) {	
		$params = JComponentHelper::getParams('com_kide');
		$hide = $params->get('icons_window')=='no_window';
		$show = $com =='ajax' ? 0 : $params->get("icons_show_".$com, 14);
		$smilies = kideHelper::getSmileys();
		$aux = array();
		$return = "";
		$count = 0;
		if ($com=='ajax') 
			$parent = $window ? 'window.opener.parent.' : 'parent.';
		else
			$parent = '';
		foreach ($smilies as $k=>$s) {
			if (!in_array($s,$aux)) {
				$count++;
				$k = str_replace('"', '&quot;', $k);
				$return .= "<a href=\"javascript:{$parent}kide.insertSmile('".addslashes($k)."')\"><img title=\"$k\" alt=\"$k\" src=\"$s\" /></a>\n";
				$aux[] = $s;
			}
			if ($show == $count) {
				if ($hide && $com != "ajax")
					$return .= '<span id="KIDE_mas_iconos" style="display:none">';
				else
					break;
			}
		}
		if ($count >= $show && $show>0 && $hide && $com != "ajax")
			$return .= '</span>';
		return $return;
	}
	function smilies_js() {
		$smilies = kideHelper::getSmileys(true);
		$return = "";
		foreach ($smilies as $k=>$s)
			$return .= "['".addslashes($k)."', 	'".addslashes($s)."'],\n";
		return substr($return, 0, -2);
	}
	function &getSmileys($length=false) {
		static $iconos;
		$tmp = KideTemplate::getInstance();
		$id = $length ? 1 : 0;
		if (!is_array($iconos)) $iconos = array();
		if (isset($iconos[$id])) return $iconos[$id];
		$db = JFactory::getDBO();
		if ($id)
			$db->setQuery("SELECT code,img FROM #__kide_iconos order by LENGTH(code) DESC");
		else
			$db->setQuery("SELECT code,img FROM #__kide_iconos order by `ordering`");
		$data = $db->loadObjectList();
		$iconos[$id] = array();
		foreach ($data as $r) 
			$iconos[$id][$r->code] = $tmp->include_html("iconos", $r->img);
		return $iconos[$id];
	}
	function convert_smilies($text) {
		$smilies = kideHelper::getSmileys(true);
		foreach ($smilies as $k=>$s) {
			$k = str_replace('"', '&quot;', $k);
			$text = str_ireplace(" ".$k, ' <img alt="'.$k.'" src="'.$s.'" title="'.$k.'" class="KIDE_icono" /> ', $text);
		}
		return $text;
	} 
	function getCopy() {
		// Please don't remove this copy, it doesn't clog. Thanks ^_^
		return '<a target="_blank" href="http://www.jonijnm.es">Kide Shoutbox</a>';
	}
}
