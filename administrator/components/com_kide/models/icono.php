<?php
/**
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2012 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class KideModelIcono extends JModelAdmin
{
	protected $text_prefix = 'COM_KIDE';

	public function getTable($type = 'Icono', $prefix = 'KideTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_kide.icono', 'icono', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	function getImagenes($value) {
		jimport( 'joomla.filesystem.folder');
		$return = '<select class="required" name="jform[img]" id="jform_img" aria-required="true" required="required" onchange="kide_show_img(this.value)">';
		$path = JPATH_ROOT."/components/com_kide/templates/default/images/iconos";
		$files = JFolder::files($path, "\.(png|gif|jpg)");
		$first = '';
		foreach ($files as $file) {
			if (!$first) $first = $file;
			$return .= '<option value="'.$file.'"'.($value == $file ? ' selected' : '').'>'.$file.'</option>';
		}
		$return .= '</select>';
		$return .= ' <img id="kide_imagen" src="'.JURI::root().'components/com_kide/templates/default/images/iconos/'.($value ? $value : $first).'" />';
		return $return;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	public function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kide.edit.icono.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		return $condition;
	}
}