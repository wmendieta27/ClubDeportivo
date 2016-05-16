<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'ban.cancel' || document.formvalidator.isValid(document.id('kide-form'))) {
			Joomla.submitform(task, document.getElementById('kide-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_kide&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="kide-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_KIDE_EDIT_BAN'); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('sesion'); ?>
				<?php echo $this->form->getInput('sesion'); ?></li>
				
				<li><?php echo $this->form->getLabel('ip'); ?>
				<?php echo $this->form->getInput('ip'); ?></li>
				
				<li><?php echo $this->form->getLabel('time'); ?>
				<?php echo $this->form->getInput('time'); ?></li>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>