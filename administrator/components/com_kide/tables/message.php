<?php
/**
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2012 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

class KideTableMessage extends JTable {
	public function __construct(&$db) {
		parent::__construct('#__kide', 'id', $db);
	}
}
