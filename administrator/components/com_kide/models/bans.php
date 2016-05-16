<?php
/**
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2012 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class KideModelBans extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'sesion', 'a.sesion',
				'ip', 'a.ip',
				'time', 'a.time',
			);
		}

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$orderCol	= $this->state->get('list.ordering');
		if (!$orderCol) $orderCol = 'a.id';
		$orderDirn	= $this->state->get('list.direction');
		if (!$orderDirn) $orderDirn = 'ASC';
		$db->setQuery('SELECT * FROM #__kide_bans AS a ORDER BY '.$orderCol.' '.$orderDirn);
		return $db->getQuery(false);
	}
}