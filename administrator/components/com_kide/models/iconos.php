<?php
/**
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2012 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class KideModelIconos extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'code', 'a.code',
				'img', 'a.img',
				'ordering', 'a.ordering',
			);
		}

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if (!$orderCol || !$orderDirn) {
			$orderCol = 'a.ordering';
			$orderDirn = 'ASC';
			$this->state->set('list.ordering', $orderCol);
			$this->state->set('list.direction', $orderDirn);
		}
		$db->setQuery('SELECT * FROM #__kide_iconos AS a ORDER BY '.$orderCol.' '.$orderDirn);
		return $db->getQuery(false);
	}
}