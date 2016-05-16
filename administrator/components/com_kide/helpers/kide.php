<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die;

class KideHelper {
	public static function addSubmenu($vName = 'messages')
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_KIDE_MANAGER_MESSAGES'),
			'index.php?option=com_kide&view=messages',
			$vName == 'messages'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_KIDE_MANAGER_ICONOS'),
			'index.php?option=com_kide&view=iconos',
			$vName == 'iconos'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_KIDE_MANAGER_BANS'),
			'index.php?option=com_kide&view=bans',
			$vName == 'bans'
		);
	}
}
