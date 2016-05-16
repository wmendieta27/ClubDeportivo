<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport('joomla.application.component.view');

$tmp = KideTemplate::getInstance();
$tmp->include_html("css", "kide");

class KideViewHistory extends JViewLegacy {
	function display($tmpl = null) {
		$db = JFactory::getDBO();
		$kuser = kideUser::getInstance();
		$model = $this->getModel();
		$params = JComponentHelper::getParams('com_kide');
		$tpl = KideTemplate::getInstance();
		
		$msgs = $model->getMsgs();
		$pags = $model->getPags();
		$fecha = $params->get("formato_fecha", "j-n G:i:s");
		
		$tpl->assignRef('user', $kuser);
		$tpl->assignRef('msgs', $msgs);
		$tpl->assignRef('pags', $pags);
		$tpl->assignRef('fecha', $fecha);
		
		$tpl->display();
	}
}
