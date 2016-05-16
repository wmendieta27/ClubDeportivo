<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class kideModelHistory extends JModelLegacy {
	function getMsgs() {
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$page = JRequest::getInt('page', 1);
		$limit = $params->get("msgs_history", 50);
		$db->setQuery("SELECT * FROM #__kide ORDER BY id DESC LIMIT ".(($page-1)*$limit).",".$limit);
		$msgs = $db->loadObjectList();
		return $msgs;
	}
	
	function getPags() {
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$page = JRequest::getInt('page', 1);
		$limit = $params->get("msgs_history", 50);
		$limitpages = $params->get("pages_history", 5);
		
		$db->setQuery("SELECT count(*) FROM #__kide");
		$total = $db->loadResult();
		if ($limit > 0) {
			$tmp = $total/$limit;
			$pages = round($tmp);
			if ($tmp-$pages > 0)
				$pages++;
		}
		else 
			$pages = 1;
			
		if (!($limitpages > 0))
			$limitpages = $pages;
			
		$show = "";
		$cshow = 0;
		$mitad = round($limitpages/2);
		$ini = $page-$mitad;
		if ($ini <= 0) $ini = 1; 
		
		for ($i=$ini; $i<=$pages && $cshow<=$limitpages; $i++) {
			if ($i == $page) 
				$show .= " $i";
			else
				$show .= ' <a href="'.JRoute::_(KIDE_URL_HISTORY."&page=".$i).'">'.$i.'</a>';
			$cshow++;
		}
		
		return ($cshow > 1) ? $show : "";
	}
}
