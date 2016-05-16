<?php
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class kideHead {
	function add_tags() {
		$kuser = kideUser::getInstance();
		$tpl = KideTemplate::getInstance();
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$user_config = kideUserConfig::getInstance();
		$order = $params->get('order', 'bottom');
		$doc = JFactory::getDocument();
		$doc->addScript(KIDE_HTML."js/base.js");
		$tpl->include_html("js", "kide");
		
		$db->setQuery("SELECT id FROM #__kide ORDER BY id DESC LIMIT 1");
		$id = $db->loadResult();

		$doc->addScriptDeclaration('
	/*<![CDATA[*/
	kide.img_encendido = ["'.$tpl->include_html("botones", "encendido_0.gif").'", "'.$tpl->include_html("botones", "encendido_1.gif").'", "'.$tpl->include_html("botones", "encendido_2.gif").'"];
	kide.sound_on = "'.$tpl->include_html("botones", "sound_on.png").'";
	kide.sound_off = "'.$tpl->include_html("botones", "sound_off.png").'";
	kide.sound_src = "'.$tpl->include_html("sound", "msg.swf").'";
	kide.img_blank = "'.$tpl->include_html("otras", "blank.png").'";
	kide.ajax_url = "'.KIDE_AJAX.'";
	kide.direct_url = "'.KIDE_HTML.'direct/";
	kide.url = "'.kideLinks::getUserLink($kuser->id).'";
	kide.popup_url = "'.JRoute::_(KIDE_URL."&view=kide".(JRequest::getCmd('tmpl')=="component"?"":"&tmpl=component")).'";
	kide.order = "'.$order.'";
	kide.formato_hora = "'.$params->get("formato_hora", "G:i--").'";
	kide.formato_fecha = "'.$params->get("formato_fecha", "j-n G:i:s").'";
	
	kide.template = "'.$kuser->template.'";
	kide.gmt = "'.$user_config->load("gmt").'";
	kide.token = '.$kuser->token.';
	kide.sesion = "'.$kuser->sesion.'";
	kide.rango = '.$kuser->rango.';
	kide.rangos = ["'.implode('","', KideHelper::getRangos()).'"];
	kide.works = '.$kuser->works.';
	kide.direct = '.($params->get("direct", 0) ? 'true' : 'false').';
	kide.show_avatar = '.($params->get("show_avatar", 0) ? 'true' : 'false').';
	kide.avatar_maxheight = "'.$params->get('avatar_maxheight', '30px').'";
	kide.refresh_time = '.$params->get("refresh_time", 6).'000;
	kide.refresh_time_sesion = '.$params->get("refresh_time_sesion", 60).'000;
	kide.refresh_time_privates = '.$params->get("refresh_time_privates", 7).'000;
	kide.solo_registrado = '.($params->get("solo_registrados", 0)&&!$kuser->id?'true':'false').';
	kide.boton_enviar = '.($params->get('button_send', 0)?'true':'false').';
	kide.fast_init = '.($params->get('fast_init', 1)?'true':'false').';
	
	kide.encendido = '.(int)$kuser->encendido.';
	kide.n = '.(int)$id.';
	kide.name = "'.$kuser->name.'";
	kide.userid = '.$kuser->id.';
	kide.sound = '.$kuser->sound.';
	kide.color = "'.$kuser->color.'";
	kide.retardo = '.(int)$kuser->retardo.';
	kide.last_time = '.KideHelper::getLastTime().';
	
	//ban user if post 4 messages in 10 seconds or less
	kide.ban_total = 4;
	kide.ban_time = 10;
	kide.ban = [];
	for (var i=0; i<this.ban_total+2; i++)
		kide.ban[i] = i == 1 ? 2 : 0;

	kide.msg = {
		espera_por_favor: \''.addslashes(JText::_("COM_KIDE_ESPERA_POR_FAVOR")).'\',
		mensaje_borra: \''.addslashes(JText::_("COM_KIDE_MENSAJE_BORRAR")).'\',
		retardo_frase: \''.addslashes(JText::_("COM_KIDE_RETARDO_FRASE")).'\',
		lang: [\''.addslashes(JText::_("COM_KIDE_MONTH")).'\', \''.addslashes(JText::_("COM_KIDE_MONTHS")).'\', \''.addslashes(JText::_("COM_KIDE_DAY")).'\', \''.addslashes(JText::_("COM_KIDE_DAYS")).'\', \''.addslashes(JText::_("COM_KIDE_HOUR")).'\', \''.addslashes(JText::_("COM_KIDE_HOURS")).'\', \''.addslashes(JText::_("COM_KIDE_MINUTE")).'\', \''.addslashes(JText::_("COM_KIDE_MINUTES")).'\', \''.addslashes(JText::_("COM_KIDE_SECOND")).'\', \''.addslashes(JText::_("COM_KIDE_SECONDS")).'\'],
		privados_usuario_cerrado: \''.addslashes(JText::_("COM_KIDE_PRIVADOS_USUARIO_CERRADO")).'\',
		privados_nuevos: \''.addslashes(str_replace("%url", JRoute::_(KIDE_URL."&view=kide"), JText::_("COM_KIDE_PRIVADOS_NUEVOS"))).'\',
		privados_need_login: \''.addslashes(JText::_('COM_KIDE_PRIVADOS_NEED_LOGIN')).'\'
	};
	kide.smilies = [
		'.kideHelper::smilies_js().'
	];
	/*]]>*/');
	  
		$doc->addStyleDeclaration('
	'.($kuser->color?'#KIDE_txt { color: #'.$kuser->color.'; }':'').'
	#KIDE_usuarios_td { vertical-align: '.$order.' }');
			
		//if ($user_config->load("gmt") === null)
			$doc->addScriptDeclaration('
	var tiempo = new Date();
	kide.save_config("gmt", (tiempo.getTimezoneOffset()/60)*-1);');
			
		if($user_config->load("retardo") === null)
			$doc->addScriptDeclaration('kide.ajax("retardo");');
	}
}
