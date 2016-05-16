<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.filesystem.folder' );

class JFormFieldKideTemplates extends JFormField
{

	protected $type = 'PhocaHead';

	protected function getInput() {
		$folders = JFolder::folders(JPATH_ROOT.'/components/com_kide/templates');
		$s = array();
		foreach ($folders as $f) $s[] = (object)array('text'=>$f);
		return JHTML::_('select.genericlist', $s, $this->name, 'class="inputbox"', 'text', 'text', $this->value, $this->id );
	}
}