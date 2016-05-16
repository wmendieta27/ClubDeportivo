<?php

/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2012 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 

 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KideViewIconos extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/kide.php';

		$state	= $this->get('State');

		JToolBarHelper::title(JText::_('COM_KIDE_MANAGER_ICONOS'));
		JToolBarHelper::addNew('icono.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('icono.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'iconos.delete','JTOOLBAR_DELETE');
		$user = JFactory::getUser();
		if ($user->authorise('core.admin', 'com_kide'))
			JToolBarHelper::preferences('com_kide');
	}
}
