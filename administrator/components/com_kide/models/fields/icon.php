<?php
/**
 * @version		$Id: ordering.php 20196 2012-01-09 02:40:25Z ian $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 *
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @since		1.6
 */
class JFormFieldIcon extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Icon';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		jimport( 'joomla.filesystem.folder');
		$return = '<select name="'.$this->name.'" id="img" onchange="kide_show_img(this.value)">';
		$path = JPATH_ROOT."/components/com_kide/templates/default/images/iconos";
		$files = JFolder::files($path, "\.(png|gif|jpg)");
		$first = '';
		foreach ($files as $file) {
			if (!$first) $first = $file;
			$return .= '<option value="'.$file.'"'.($this->value == $file ? ' selected' : '').'>'.$file.'</option>';
		}
		$return .= '</select>';
		$return .= ' <img id="kide_imagen" src="'.JURI::root().'components/com_kide/templates/default/images/iconos/'.($this->value ? $this->value : $first).'" />';
		return $return;
	}
}