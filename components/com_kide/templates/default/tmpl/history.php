<?php 

/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die(); 

?>

<div style="font-weight: bold; padding-bottom: 15px"><a href="<?php echo JRoute::_(KIDE_URL."&view=kide"); ?>"><?php echo JText::_("COM_KIDE_VOLVER"); ?></a></div>

<div align="center"><?php echo $this->pags; ?></div>

<table width="100%" border="1">
	<?php foreach ($this->msgs as $r) : ?>
	<tr>
		<td class="KIDE_history_td"><?php echo gmdate($this->fecha, $r->time + $this->user->gmt*3600); ?></td>
		<td class="KIDE_history_td">
			<?php $url = kideLinks::getUserLink($r->userid); ?>
			<?php if ($url) : ?>
			<a href="<?php echo $url; ?>">
			<?php endif; ?>
				<span class="<?php echo KideHelper::getRango($r->rango, 'KIDE_'); ?>">
					<?php echo $r->name; ?>
				</span>
			<?php if ($url) : ?>
			</a>
			<?php endif; ?>
		</td>
		<td <?php echo $r->color ? 'style="color:#'.$r->color.'"' : 'class="'.KideHelper::getRango($r->rango, 'KIDE_dc_').'"'; ?>>
			<?php echo $r->text; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<div align="center"><?php echo $this->pags; ?></div>

<div style="font-weight: bold; padding-top: 15px"><a href="<?php echo JRoute::_(KIDE_URL."&view=kide"); ?>"><?php echo JText::_("COM_KIDE_VOLVER"); ?></a></div>
