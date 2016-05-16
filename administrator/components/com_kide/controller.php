<?php

/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die;
class KideController extends JControllerLegacy
{
	var $default_view = "messages";
	
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/kide.php';

		// Load the submenu.
		KideHelper::addSubmenu(JRequest::getCmd('view', 'messages'));

		parent::display();

		return $this;
	}
}