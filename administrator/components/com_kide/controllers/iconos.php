<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KideControllerIconos extends JControllerAdmin
{
	public function getModel($name = 'Icono', $prefix = 'KideModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}