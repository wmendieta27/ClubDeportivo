<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KideViewBan extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

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
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title(JText::_('COM_KIDE_MANAGER_BAN'));

		JToolBarHelper::apply('ban.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('ban.save', 'JTOOLBAR_SAVE');
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('ban.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('ban.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
