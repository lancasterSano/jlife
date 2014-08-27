$(document).ready(function() {
	window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
	window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
	window.tmpl.tmpl_notices = $("#notices").html();

	$("#create_email #btn_form").on("click", { form: "create" }, crch_email_handler);
	_reinit_repeat_request("create");
	
	$("#ch_email #btn_form").on("click", { form: "ch" }, crch_email_handler);
	_reinit_repeat_request("ch");

	_reinit_verify_form("create");
	_reinit_verify_form("ch");
	
	$("#ch_pswd #btn_form").on("click", { form: "ch" }, ch_pswd_handler);
}); // ready

function _reinit_repeat_request (form) {
	var el = $("#"+form+"_email .confirmationMSG span.title span");
	el.on("click", { form: form, hex: el.attr("data") }, repeat_re_handler);
}
function _reinit_verify_form (form) {
	var el = $("#verify_"+form+"_email #btn_form");
	el.on("click", { form: form, hex: el.attr("data") }, verify_email_handler);
}
function repeat_re_handler (e) {
	if(e.data.form == undefined || e.data.hex == undefined ) return;
	var conformation_after = $("div#"+e.data.form+"_email div.confirmation"),
		confirmation_before = $("div#"+e.data.form+"_email div.preConfirmation");
	var notices = [],
		loader_place = $("div#"+e.data.form+"_email div.load-layer");
		if(e.data.hex == undefined || e.data.hex == '') return _unknownError(conformation_after);

	ajax.post_bs("ssecurity/crchem.php","hex="+e.data.hex,
		function(data){ loader_place.addClass("display"); },
		function(data){ 
			if(data.errors == undefined && data.rrchem != undefined && data.rrchem.o) {
				var alerts = [ window.tmpl.tmpl_notice_hb(
					{
						title: ((e.data.form=="ch"?MI_REQUEST_CHANGE_MAIL_TITLE:MI_REQUEST_CREATE_MAIL_TITLE).replace(new RegExp("%MAIL",""), hiddenEmail(data.rrchem.nem))).replace(new RegExp("%HEX",""), data.rrchem.hex), 
						text: (e.data.form=="ch"?MI_REQUEST_CHANGE_MAIL_TEXT:MI_REQUEST_CREATE_MAIL_TEXT).replace(new RegExp("%DATE_SEND",""), data.rrchem.dsr)
					}) ];
				confirmation_before.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:1, notices: alerts}));
				_reinit_repeat_request(e.data.form);
			}
			else _unknownError(conformation_after);
			loader_place.removeClass("display");
		},
		function(XMLHttpRequest, textStatus, errorThrown){
			_unknownError(conformation_after);
			loader_place.removeClass("display");
		}
	);
}


// email
function validateFormRCHEM(form, fields) {
	var notices = {},
		vee = validateTitle(fields.em.val()), vev, vpe, vpl, vpchar;

	if(!vee) {notices.valid_email_empty=1;}
	else {
		vev = validateEmail(fields.em.val());
		if(!vev) {notices.valid_email_fail=1;}
		
	}
	vpe = validateTitle(fields.pswd.val());
	if(!vpe) {notices.valid_pswd_empty=1;}
	else {
		vpl = validateTitleLength(fields.pswd.val(), 6, 32);
		if(!vpl) {notices.valid_pswd_length=1;}
		else {
			vpchar = validatePSWD(fields.pswd.val());
			if(!vpchar) {notices.valid_pswd_characters=1;}
		}
	}
	return validateFormView(form, fields, notices);
}
function crch_email_handler(e) {
	if(e.data.form == undefined) return;
	var em = $("#"+e.data.form+"_email #f_email"),
		pswd = $("#"+e.data.form+"_email #f_pswd"),
		conformation_after = $("div#"+e.data.form+"_email div.confirmation"),
		confirmation_before = $("div#"+e.data.form+"_email div.preConfirmation");
	var notices = validateFormRCHEM(e.data.form, {em: em, pswd: pswd});
	// notices = [];

	// Validate before send
	if(notices.length) { pswd.val(''); conformation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices })); }
	else { // valid true. Cleare all errors.
		conformation_after.empty();
		var loader_place = $("div#"+e.data.form+"_email div.load-layer");
		ajax.post_bs("ssecurity/crchem.php","em="+em.val().toLowerCase()+"&pswd="+pswd.val(),
		    function(data){ loader_place.addClass("display"); },
			function(data){
				if(data.errors == undefined) { // успех
					if(data.chem != undefined && data.chem.o) {
						var alerts = [ window.tmpl.tmpl_notice_hb(
							{
								title: ((e.data.form=="ch"?MI_REQUEST_CHANGE_MAIL_TITLE:MI_REQUEST_CREATE_MAIL_TITLE).replace(new RegExp("%MAIL",""), hiddenEmail(data.chem.nem))).replace(new RegExp("%HEX",""), data.chem.hex), 
								text: (e.data.form=="ch"?MI_REQUEST_CHANGE_MAIL_TEXT:MI_REQUEST_CREATE_MAIL_TEXT).replace(new RegExp("%DATE_SEND",""), data.chem.dsr)
							}) ];
						confirmation_before.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:1, notices: alerts}));
						_reinit_repeat_request(e.data.form);
						em.val(""); pswd.val("");
					}
					else _unknownError(conformation_after);
				}
				else {
					if(data.errors.indn != undefined) {notices.push(window.tmpl.tmpl_notice_h({title: ME_ERROR_SEND_DATA })); } 
					else if(data.errors.indv != undefined) {
						notices = validateFormView(e.data.form, {em: em, pswd: pswd}, data.errors.indv);
						pswd.val('');
					}
					conformation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices }));
				}
				loader_place.removeClass("display");
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				_unknownError(conformation_after);
				loader_place.removeClass("display");
			}
		);
	}
}

// verify email
function validateFormVerifyRCHEM(form, fields) {
	var notices = [], vpe, vpl, vpchar;
		// vpe = validateTitle(fields.pswd.val());
		// vpl = validateTitleLength(fields.pswd.val(), 6, 32);
		// vpchar = validatePSWD(fields.pswd.val());

	// if(!vpe) notices[0] = 'valid_pswd_empty';
	// if(!vpl) notices[1] = 'valid_pswd_length';
	// if(!vpchar) notices[2] = 'valid_pswd_characters';

	vpe = validateTitle(fields.pswd.val());
	if(!vpe) {notices.valid_pswd_empty=1;}
	else {
		vpl = validateTitleLength(fields.pswd.val(), 6, 32);
		if(!vpl) {notices.valid_pswd_length=1;}
		else {
			vpchar = validatePSWD(fields.pswd.val());
			if(!vpchar) {notices.valid_pswd_characters=1;}
		}
	}
	return validateFormView(form, fields, notices);

	// return notices;
}
function verify_email_handler(e) {
	if(e.data.form == undefined || e.data.hex == undefined ) return;
	var pswd = $("#verify_"+e.data.form+"_email #f_pswd"),
		conformation_after = $("div#verify_"+e.data.form+"_email div.confirmation");
		confirmation_before = $("div#verify_"+e.data.form+"_email div.preConfirmation");
	var notices = [];
	// var notices = validateFormVerifyRCHEM(e.data.form, {pswd: pswd});

	// Validate before send
	if(notices.length) { conformation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices })); }
	else { // valid true. Cleare all errors.
		conformation_after.empty();
		var loader_place = $("div#verify_"+e.data.form+"_email div.load-layer");
		ajax.post_bs("ssecurity/vcrchem.php","hex="+e.data.hex+"&pswd="+pswd.val(),
		    function(data){ loader_place.addClass("display"); },
			function(data){
				if(data.errors == undefined) { // успех
					if(data.chem.o) {					
						// window.location.replace("http://stackoverflow.com");
						window.location.href="ssecurity.php?msc=1";
					}
					else _unknownError(conformation_after);
				}
				else {
					if(data.errors.indn != undefined) { notices.push(window.tmpl.tmpl_notice_h({title: ME_ERROR_SEND_DATA })); }
					else if(data.errors.indv != undefined) {
						notices = validateFormView(e.data.form, {pswd: pswd}, data.errors.indv);
						pswd.val('');
					}
					conformation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices }));
				}
				loader_place.removeClass("display");
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				_unknownError(conformation_after);
				loader_place.removeClass("display");
			}
		);
	}
}

// pswd
function validateFormCHPSWD(form, fields) {
	var notices = {},
		vpoe = validateTitle(fields.pswdo.val()),
		vpol, vpochar, vpne, vpnl, vpnchar, vpnse, vpnsl, vpnschar, vpnnseequal;

	if(!vpoe) {notices.valid_pswdo_empty=1;}
	else {
		vpol = validateTitleLength(fields.pswdo.val(), 6, 32);
		if(!vpol) {notices.valid_pswdo_length=1;}
		else {
			vpochar = validatePSWD(fields.pswdo.val());
			if(!vpochar) {notices.valid_pswdo_characters=1;}
		}
	}

	vpne = validateTitle(fields.pswdn.val());
	if(!vpne) {notices.valid_pswdn_empty=1;}
	else {
		vpnl = validateTitleLength(fields.pswdn.val(), 6, 32);
		if(!vpnl) {notices.valid_pswdn_length=1;}
		else {
			vpnchar = validatePSWD(fields.pswdn.val());
			if(!vpnchar) {notices.valid_pswdn_characters=1;}
		}
	}

	if(!Object.keys(notices).length) {
		vpnse = validateTitle(fields.pswdns.val());
		if(!vpnse) {notices.valid_pswdns_empty=1;}
		else {
			vpnsl = validateTitleLength(fields.pswdns.val(), 6, 32);
			if(!vpnsl) {notices.valid_pswdns_length=1;}
			else {
				vpnschar = validatePSWD(fields.pswdns.val());
				if(!vpnschar) {notices.valid_pswdns_characters=1;}
			}
		}
		if(!Object.keys(notices).length) {
			vpnnseequal = fields.pswdn.val() === fields.pswdns.val() ? true : false;
			if(!vpnnseequal) {notices.valid_pswdn_pswdns_ne=1;}
		}
	}
	return validateFormView(form, {pswdo: fields.pswdo, pswdn: fields.pswdn, pswdns: fields.pswdns}, notices);
}
function ch_pswd_handler(e) {
	if(e.data.form == undefined) return;
	var em = $("#"+e.data.form+"_pswd #f_pswd"),
		pswdo = $("#"+e.data.form+"_pswd #f_pswdo"),
		pswdn = $("#"+e.data.form+"_pswd #f_pswdn"),
		pswdns = $("#"+e.data.form+"_pswd #f_pswdns"),
		conformation_after = $("div#"+e.data.form+"_pswd div.confirmation"),
		confirmation_before = $("div#"+e.data.form+"_pswd div.preConfirmation");

		confirmation_before.empty();
	var notices = validateFormCHPSWD(e.data.form, {pswdo: pswdo, pswdn: pswdn, pswdns: pswdns});
	// notices = [];

	// Validate before send
	if(notices.length) { conformation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices })); }
	else { // valid true. Cleare all errors.
		conformation_after.empty();
		var loader_place = $("div#"+e.data.form+"_pswd div.load-layer");
		ajax.post_bs("ssecurity/chpswd.php","pswdo="+pswdo.val()+"&pswdn="+pswdn.val()+"&pswdns="+pswdns.val(),
		    function(data){ loader_place.addClass("display"); },
			function(data){
				if(data.errors == undefined) { // успех
					if(data.chem != undefined && data.chem.o) {
						var alerts = [ window.tmpl.tmpl_notice_h({ title: MS_CHANGE_PSWD_SUCCESS_TITLE }) ];
						confirmation_before.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:1, notices: alerts}));
						_reinit_repeat_request(e.data.form);
						pswdo.val(""); pswdn.val(""); pswdns.val("");
					}
					else _unknownError(conformation_after);
				}
				else {
					if(data.errors.indn != undefined) { notices.push(window.tmpl.tmpl_notice_h({title: ME_ERROR_SEND_DATA })); }
					else if(data.errors.indv != undefined) {
						notices = validateFormView(e.data.form, {pswdo: pswdo, pswdn: pswdn, pswdns: pswdns}, data.errors.indv);
					}
					conformation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices }));
				}
				loader_place.removeClass("display");
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				_unknownError(conformation_after);
				loader_place.removeClass("display");
			}
		);
	}
}


// GLOBAL
function _unknownError (conformation) {
	notices = [ window.tmpl.tmpl_notice_h({title: ME_ERROR_SEND_DATA }) ];
	conformation.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:2, notices: notices }));	
}
function validateFormView (form, fields, errors) {
	var notices = [];
	// fields.pswdold.removeClass('err'); fields.pswdnew.removeClass('err'); fields.pswdnewsecond.removeClass('err');
	// if(errors.valid_pswdo_empty || errors.valid_pswdo_length || errors.valid_pswdo_characters) { fields.pswdold.addClass('err'); }
	// if(errors.valid_pswdn_empty || errors.valid_pswdn_length || errors.valid_pswdn_characters || errors.valid_pswdn_pswdns_ne) { fields.pswdnew.addClass('err'); }
	
	// if(form == 'ch') { fields.pswdo.addClass('err'); }
	// if(form == 'ch') { fields.pswdn.addClass('err'); }
	// if(form == 'ch') { fields.pswdns.addClass('err'); }

	
	for(var er in errors) {
		console.log(errors[er]? 'on client js - '+er : 'on server php - '+er);
		switch(er){
		// email
			case 'valid_email_fail':
				notices.push(window.tmpl.tmpl_notice_hb({title: ME_VALID_EMAIL_FAIL_TITLE, text: ME_VALID_EMAIL_FAIL_TEXT }));
				break
			case 'emcopy':
				notices.push(window.tmpl.tmpl_notice_h({title: ME_VALID_EMAIL_COPY_TITLE }));
				break
			case 'embusy':
				notices.push(window.tmpl.tmpl_notice_h({title: ME_VALID_EMAIL_EXIST_TITLE }));
				break
			case 'valid_email_empty':
				notices.push(window.tmpl.tmpl_notice_h({title: ME_VALID_EMAIL_EMPTY_TITLE}));
				break
		// pswd
			case 'valid_pswd_fail':
				notices.push(window.tmpl.tmpl_notice_h({title: (ME_VALID_PSWD_FAIL_TITLE  || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[0])}));
				break
			case 'valid_pswd_length':MEARR_I_VARIABLES_PASSWORD
				notices.push(window.tmpl.tmpl_notice_hb({
					title: (ME_VALID_PSWD_LENGTH_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[0]),
					text: ME_VALID_PSWD_LENGTH_TEXT }));
				break
			case 'valid_pswd_characters':
				notices.push(window.tmpl.tmpl_notice_hb({
				title: (ME_VALID_PSWD_CHAR_TITLE || '%s').replace('%s', MEARR_In_VARIABLES_PASSWORD[0]), 
				text: ME_VALID_PSWD_CHAR_TEXT }));
				break
			case 'valid_pswd_empty':
				notices.push(window.tmpl.tmpl_notice_h({
					title: (ME_VALID_PSWD_O_N_EMPTY_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[0])}));
				break
		// pswd old
			case 'valid_pswdo_empty':
				notices.push(window.tmpl.tmpl_notice_h({
					title: (ME_VALID_PSWD_O_N_EMPTY_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[0])}));
				break
			case 'valid_pswdo_length':
				notices.push(window.tmpl.tmpl_notice_hb({
					title: (ME_VALID_PSWD_LENGTH_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[0]),
					text: ME_VALID_PSWD_LENGTH_TEXT }));
				break
			case 'valid_pswdo_characters':
				notices.push(window.tmpl.tmpl_notice_hb({
					title: (ME_VALID_PSWD_CHAR_TITLE || '%s').replace('%s', MEARR_In_VARIABLES_PASSWORD[0]), 
					text: ME_VALID_PSWD_CHAR_TEXT }));
				break
		// pswd new
			case 'valid_pswdn_empty':
				notices.push(window.tmpl.tmpl_notice_h({
					title: (ME_VALID_PSWD_O_N_EMPTY_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[1])}));
				break
			case 'valid_pswdn_length':
				notices.push(window.tmpl.tmpl_notice_hb({
					title: (ME_VALID_PSWD_LENGTH_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[1]), 
					text: ME_VALID_PSWD_LENGTH_TEXT }));
				break
			case 'valid_pswdn_characters':
				notices.push(window.tmpl.tmpl_notice_hb({
					title: (ME_VALID_PSWD_CHAR_TITLE || '%s').replace('%s', MEARR_In_VARIABLES_PASSWORD[1]), 
					text: ME_VALID_PSWD_CHAR_TEXT }));
				break
			case 'valid_pswdcopy':
				notices.push(window.tmpl.tmpl_notice_h({
					title: (ME_VALID_PSWD_COPY_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[1])}));
				break
			case 'valid_pswd_usedearlier':
				notices.push(window.tmpl.tmpl_notice_hb({
					title: ME_VALID_PSWD_USEDEARLIER_TITLE,
					text: (ME_VALID_PSWD_USEDEARLIER_TEXT).replace('%DATE_CHPSWDD', errors[er].ochpswdd)}));
				break
				
		// pswd new second
		
			case 'valid_pswdns_empty':
				notices.push(window.tmpl.tmpl_notice_h({
					title: (ME_VALID_PSWD_NS_EMPTY_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[2])}));
				break
			case 'valid_pswdns_length':
				notices.push(window.tmpl.tmpl_notice_hb({
					title: (ME_VALID_PSWD_LENGTH_TITLE || '%s').replace('%s', MEARR_I_VARIABLES_PASSWORD[2]), 
					text: ME_VALID_PSWD_LENGTH_TEXT }));
				break
			case 'valid_pswdns_characters':
			 	notices.push(window.tmpl.tmpl_notice_hb({
				 	title: (ME_VALID_PSWD_CHAR_TITLE || '%s').replace('%s', MEARR_In_VARIABLES_PASSWORD[2]), 
				 	text: ME_VALID_PSWD_CHAR_TEXT }));
				break
		// pswd new != pswd new sevond
			case 'valid_pswdn_pswdns_ne':
			 	notices.push(window.tmpl.tmpl_notice_h({
				 	title: (ME_VALID_PSWDN_PSWDNS_NE || '%s').replace('%s', MEARR_In_VARIABLES_PASSWORD[2])}));
				break
		}
	}
	if(fields.pswdo !=undefined && fields.pswdn !=undefined && fields.pswdns !=undefined) {
		fields.pswdo.removeClass('err'); fields.pswdn.removeClass('err'); fields.pswdns.removeClass('err');
		if(errors.valid_pswdo_empty || errors.valid_pswdo_length || errors.valid_pswdo_characters || errors.valid_pswdo_pswdns_ne || errors.valid_pswd_fail)  { fields.pswdo.addClass('err'); }
		if(errors.valid_pswdn_empty || errors.valid_pswdn_length || errors.valid_pswdn_characters || errors.valid_pswdn_pswdns_ne || errors.valid_pswdcopy ) { fields.pswdn.addClass('err'); }
		if(errors.valid_pswdns_empty || errors.valid_pswdns_length || errors.valid_pswdns_characters || errors.valid_pswdn_pswdns_ne ) { fields.pswdns.addClass('err'); }
	}
	if(fields.pswd !=undefined && fields.em !=undefined) {
		fields.em.removeClass('err'); fields.pswd.removeClass('err');
		if(errors.valid_pswd_empty || errors.valid_pswd_length || errors.valid_pswd_characters || errors.valid_pswd_fail) { fields.pswd.addClass('err'); }
		if(errors.valid_email_empty || errors.embusy || errors.emcopy || errors.valid_email_fail) { fields.em.addClass('err'); }
	}
	return notices;
}
