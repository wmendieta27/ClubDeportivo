/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

// general

kide.mensaje = function(name, uid, id, url, ti, sesion, rango, img) {
	this.html('KIDE_mensaje_username', name);
	this.attr('KIDE_mensaje_username', 'className', "KIDE_"+kide.rangos[rango]);
	this.html('KIDE_tiempo_msg', ti);
	this.attr('KIDE_mensaje_img', 'src', img ? img : this.img_blank);
	if (url) {
		this.attr('KIDE_mensaje_perfil', 'href', url);
		this.show("KIDE_mensaje_perfil_span", true);
		this.attr('KIDE_mensaje_img_enlace', 'href', url);
		this.attr('KIDE_mensaje_img_enlace', 'target', '_blank');
		this.css('KIDE_mensaje_img', 'cursos', 'pointer');
	}
	else {
		this.show("KIDE_mensaje_perfil_span", false);
		this.attr('KIDE_mensaje_img_enlace', 'href', 'javascript:void(0)');
		this.attr('KIDE_mensaje_img_enlace', 'target', '');
		this.css('KIDE_mensaje_img', 'cursor', 'default');
	}
	if ((this.rango == 1 || sesion == this.sesion) && id > 0) {
		this.show('KIDE_mensaje_borrar_span', true);
		this.attr('KIDE_mensaje_borrar', 'href', 'javascript:kide.borrar('+id+')');
	}
	else
		this.show('KIDE_mensaje_borrar_span', false);
	this.attr('KIDE_mensaje_ocultar', 'href', 'javascript:kide.show("KIDE_id_'+id+'")');
	if (this.rango == 1) {
		this.show('KIDE_mensaje_banear_span', false);
		if (sesion != '0' && rango != 1) {
			this.show('KIDE_mensaje_banear_span1', true);
			this.attr('KIDE_mensaje_banear', 'onclick', function() { kide.banear(sesion, 'mensaje'); });
		}
		else
			this.show('KIDE_mensaje_banear_span1', false);
	}
	this.show("KIDE_mensaje", true);
};
kide.mostrar_usuario = function(uid, name, rango, sesion, url, img) {
	this.html('KIDE_usuario_name', name);
	this.attr('KIDE_usuario_name', 'className', "KIDE_"+this.rangos[rango]);
	this.attr('KIDE_usuario_img', 'src', img ? img : this.img_blank);
	if (url) {
		this.attr('KIDE_usuario_perfil', 'href', url);
		this.show("KIDE_usuario_perfil_mostrar", true);
		this.attr('KIDE_usuario_img_enlace', 'href', url);
		this.attr('KIDE_usuario_img_enlace', 'target', '_blank');
		this.css('KIDE_usuario_img', 'cursor', 'pointer');
	}
	else {
		this.show("KIDE_usuario_perfil_mostrar", false);
		this.attr('KIDE_usuario_img_enlace', 'href', 'javascript:void(0)');
		this.attr('KIDE_usuario_img_enlace', 'target', '');
		this.css('KIDE_mensaje_img', 'cursor', 'default');
	}
	if (this.rango == 1) {
		this.show('KIDE_usuario_banear_span', false);
		if (sesion != '0' && rango != 1) {
			this.show('KIDE_usuario_banear_span1', true);
			this.attr('KIDE_usuario_banear', 'onclick', function() { kide.banear(sesion, 'usuario'); }); 
		}
		else
			this.show('KIDE_usuario_banear_span1', false);
	}
	this.show("KIDE_usuario", true);
};
kide.insertNewContent = function(uid,name,text,url,ti,color,rango,id,sesion,yo,hora,img) {
	if (text.replace(/ /g, "") != "") {
		var c = color.length>0 ? 'style="color:#'+color+'" class="KIDE_msg"' : 'class="KIDE_dc_'+this.rangos[rango]+' KIDE_msg"';
		var div = this.$('KIDE_msgs');
		var nodo = document.createElement('div');
		var insertO = this.$("KIDE_output");
		var s_hora;
		nodo.setAttribute('id', 'KIDE_id_'+id);
		nodo.setAttribute('class', 'KIDE_msg_top');
		if (this.show_hour)
			s_hora = '<span title="'+ti+'" class="KIDE_msg_hour">'+hora+'</span> ';
		else
			s_hora = '';
		var tmp = '';
		if (img && kide.show_avatar) {
			var style = kide.avatar_maxheight ? 'style="max-height:'+kide.avatar_maxheight+'" ' : '';
			tmp = '<img '+style+'src="'+img+'" class="KIDE_icono" alt="" /> ';
		}
		nodo.innerHTML = s_hora+tmp+'<span style="cursor: pointer" class="KIDE_'+this.rangos[rango]+'" onclick="kide.mensaje(\''+name+'\', '+uid+', '+id+', \''+url+'\', \''+ti+'\', \''+sesion+'\', '+rango+', \''+img+'\')">'+name+'</span>: <span '+c+'>'+this.filter_smilies(text)+'</span>';

		if (this.order == 'bottom') {
			this.insertAfter(nodo, insertO.lastChild);
		}
		else
			insertO.insertBefore(nodo, insertO.firstChild);
		if (!yo && this.sound == 1) 
			this.play_msg_sound();
		this.ajustar_scroll();
	}
};
kide.insert_sesion = function(user) {
	var div = document.createElement('div');
	div.setAttribute('style', 'cursor:pointer');
	div.setAttribute('class', user._class);
	div.onclick = function() { kide.mostrar_usuario(user.id, user.name, user.rango, user.sesion, user.profile, user.img) };
	div.innerHTML = user.name;
	this.$('KIDE_usuarios').insertBefore(div, this.$('KIDE_usuarios').firstChild);
};
kide.change_name_keyup = function(e, t) {
	if (this.isEnter(e)) {
		this.change_name(t);
		this.foco('KIDE_txt');
		return false;
	}
	return true;
};
kide.show_colors = function() {
	if (!kide.html('KIDE_opciones_colores')) {
		var colors = ['000000','000033','000066','000099','0000CC','0000FF','003300','003333','003366','003399','0033CC','0033FF','006600','006633','006666','006699','0066CC','0066FF','009900','009933','009966','009999','0099CC','0099FF','00CC00','00CC33','00CC66','00CC99','00CCCC','00CCFF','00FF00','00FF33','00FF66','00FF99','00FFCC','00FFFF','330000','330033','330066','330099','3300CC','3300FF','333300','333333','333366','333399','3333CC','3333FF','336600','336633','336666','336699','3366CC','3366FF','339900','339933','339966','339999','3399CC','3399FF','33CC00','33CC33','33CC66','33CC99','33CCCC','33CCFF','33FF00','33FF33','33FF66','33FF99','33FFCC','33FFFF','660000','660033','660066','660099','6600CC','6600FF','663300','663333','663366','663399','6633CC','6633FF','666600','666633','666666','666699','6666CC','6666FF','669900','669933','669966','669999','6699CC','6699FF','66CC00','66CC33','66CC66','66CC99','66CCCC','66CCFF','66FF00','66FF33','66FF66','66FF99','66FFCC','66FFFF','990000','990033','990066','990099','9900CC','9900FF','993300','993333','993366','993399','9933CC','9933FF','996600','996633','996666','996699','9966CC','9966FF','999900','999933','999966','999999','9999CC','9999FF','99CC00','99CC33','99CC66','99CC99','99CCCC','99CCFF','99FF00','99FF33','99FF66','99FF99','99FFCC','99FFFF','CC0000','CC0033','CC0066','CC0099','CC00CC','CC00FF','CC3300','CC3333','CC3366','CC3399','CC33CC','CC33FF','CC6600','CC6633','CC6666','CC6699','CC66CC','CC66FF','CC9900','CC9933','CC9966','CC9999','CC99CC','CC99FF','CCCC00','CCCC33','CCCC66','CCCC99','CCCCCC','CCCCFF','CCFF00','CCFF33','CCFF66','CCFF99','CCFFCC','CCFFFF','FF0000','FF0033','FF0066','FF0099','FF00CC','FF00FF','FF3300','FF3333','FF3366','FF3399','FF33CC','FF33FF','FF6600','FF6633','FF6666','FF6699','FF66CC','FF66FF','FF9900','FF9933','FF9966','FF9999','FF99CC','FF99FF','FFCC00','FFCC33','FFCC66','FFCC99','FFCCCC','FFCCFF','FFFF00','FFFF33','FFFF66','FFFF99','FFFFCC','FFFFFF'];
		var out = '';
		var c;
		for (var i=0; i<colors.length;i++) {
			c = colors[i];
			out += '<a href="javascript:kide.set_color(\''+c+'\')"><img class="KIDE_r" src="'+this.img_blank+'" style="background-color:#'+c+'" /></a>';
		}
		this.html('KIDE_opciones_colores', out)
	}
};
kide.ajustar_scroll = function() {
	if (kide.scrolling) return;
	if (kide.order == 'bottom')
		kide.attr('KIDE_msgs', 'scrollTop', kide.attr('KIDE_msgs', 'scrollHeight'));
	else
		kide.attr('KIDE_msgs', 'scrollTop', 0);
};