// GLOBAL
  function _unknownError (confirmation) {
    notices = [ window.tmpl.tmpl_notice_h({title: ME_ERROR_SEND_DATA }) ];
    confirmation.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:MT_ERROR, notices: notices }));  
  }
  function validateFormView (fields, errors) {
    /*
      valid_login_empty     valid_login_length    valid_email_empty     valid_email_length

      valid_firstname_empty valid_lastname_empty  valid_middlename_empty
      valid_email_busy      valid_login_busy
      valid_pswd_empty      valid_pswd_length     valid_pswd_characters      
    */
    var notices = [];  
    for(var er in errors) {
      console.log(errors[er]? 'on client js - '+er : 'on server php - '+er);
      var MEARR_I_VARIABLES_FIO = ["имя", "фамилия", "отчество", "пароль", "логин"];
      switch(er){
        case 'valid_pswd_empty':
        case 'valid_pswd_length':
        case 'valid_pswd_characters':
        case 'valid_login_empty':
        case 'valid_login_length':
        case 'valid_login_characters':
        case 'valid_email_empty':
        case 'valid_email_length':
          notices[99] = window.tmpl.tmpl_notice_hb({title: "Ошибка создания профиля", text: "Попробуйте повторить позже"});
          break
        case 'valid_email_busy':
          notices.push(window.tmpl.tmpl_notice_h({title: ME_VALID_EMAIL_EXIST_TITLE }));
          break

        case 'valid_login_busy':
          notices.push(window.tmpl.tmpl_notice_h({title: "логин уже занят" }));
          break

          // notices.push(window.tmpl.tmpl_notice_hb({title: (ME_VALID_PSWD_LENGTH_TITLE || '%s').replace('%s', "пароль"), text: ME_VALID_PSWD_LENGTH_TEXT }));
          // break
          // notices.push(window.tmpl.tmpl_notice_hb({title: (ME_VALID_PSWD_CHAR_TITLE || '%s').replace('%s', "пароле"), text: ME_VALID_PSWD_CHAR_TEXT }));
          // break
          // notices.push(window.tmpl.tmpl_notice_h({title: ("Поле '%s' не указано" || '%s').replace('%s', MEARR_I_VARIABLES_FIO[3])}));
          // break

        case 'valid_firstname_empty':
          notices.push(window.tmpl.tmpl_notice_h({title: ("Поле '%s' не указано"  || '%s').replace('%s', MEARR_I_VARIABLES_FIO[0])}));
          break
        case 'valid_lastname_empty':
          notices.push(window.tmpl.tmpl_notice_h({title: ("Поле '%s' не указано"  || '%s').replace('%s', MEARR_I_VARIABLES_FIO[1])}));
          break
        case 'valid_middlename_empty':
          notices.push(window.tmpl.tmpl_notice_h({title: ("Поле '%s' не указано"  || '%s').replace('%s', MEARR_I_VARIABLES_FIO[2])}));
          break
      }
    }
    if(fields.firstname !=undefined && fields.lastname !=undefined && fields.middlename !=undefined) {
      fields.firstname.removeClass('err'); fields.lastname.removeClass('err'); fields.middlename.removeClass('err');

      // if(errors.valid_login_empty || errors.valid_login_length || errors.valid_login_busy) { fields.login.addClass('err'); }
      // if(errors.valid_pswd_empty || errors.valid_pswd_length || errors.valid_pswd_characters) { fields.pswd.addClass('err'); }
      // if(errors.valid_email_empty || errors.valid_email_busy || errors.valid_email_length) { fields.email.addClass('err'); }

      if(errors.valid_firstname_empty) { fields.firstname.addClass('err'); }
      if(errors.valid_lastname_empty) { fields.lastname.addClass('err'); }
      if(errors.valid_middlename_empty) { fields.middlename.addClass('err'); }
    }
    return notices;
  }

$(document).ready(function() {
  window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
  window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
  window.tmpl.tmpl_notices = $("#notices").html();
  window.formsrl = {}; 
  window.formsrl.loader_place = $("div#register_responsible div.load-layer");
  window.formsrl.confirmation_before = $("div#register_responsible div.preConfirmation");
  window.formsrl.confirmation_after = $("div#register_responsible div.confirmation");

  $("#register_responsible #btn_form").on("click", regResponsible.regResponsible);

  regResponsible.genLogin();
  regResponsible.genPswd();
    
});
var regResponsible = {
  genLogin: function (e) { $('div#register_responsible span#login').text(makeRandomStr()); },
  genPswd: function (e) { $('div#register_responsible span#pswd').text(makeRandomStr()); },
  validateFormSRL: function(fields) {
    /*
      valid_login_empty     valid_login_length    valid_email_empty     valid_email_length

      valid_firstname_empty valid_lastname_empty  valid_middlename_empty
      valid_email_busy      valid_login_busy
      valid_pswd_empty      valid_pswd_length     valid_pswd_characters   
    */
    var notices = {},
      vfe = validateTitle(fields.firstname.val()),
      vle = validateTitle(fields.lastname.val()),
      vme = validateTitle(fields.middlename.val());

    if(!vfe) {notices.valid_firstname_empty=1;}
    if(!vle) {notices.valid_lastname_empty=1;}
    if(!vme) {notices.valid_middlename_empty=1;}
    if(vfe && vle && vme) {
      var vge = validateTitle(fields.login.text()),
        vpe = validateTitle(fields.pswd.text()),
        vee = validateTitle(fields.email.text()),
        vll, vpl, vel;

      if(!vge) {notices.valid_login_empty=1;}
      else {
        vgl = validateTitleLength(fields.login.text(), 6, 6);
        if(!vgl) {notices.valid_login_length=1;}
        else {
          vgch = validatePSWD(fields.login.text());
          if(!vgch) {notices.valid_login_characters=1;}
        }
      }

      if(!vpe) {notices.valid_pswd_empty=1;}
      else {
        vpl = validateTitleLength(fields.pswd.text(), 6, 6);
        if(!vpl) {notices.valid_pswd_length=1;}
        else {
          vpch = validatePSWD(fields.pswd.text());
          if(!vpch) {notices.valid_pswd_characters=1;}
        }
      }

      if(!vee) {notices.valid_email_empty=1;}
      else {
        vel = validateTitleLength(fields.email.text(), 6, 6);
        if(!vel) {notices.valid_email_length=1;}
      }
    }
    return validateFormView(fields, notices);
  },
/**** |regResponsible| *** Добавление ученику нового родителя **/
  regResponsible: function(){
    var _formReg = { },
      selectItem = $("#groupSelect option:selected").val();
    if(selectItem) _formReg.idrelation=selectItem;
    $('div.changeFields form input').each(function(name, node){
      if(node.value) {
        _formReg[node.name]=node.value;
      }
    });

    // 2
      var idLearner = $('span a[id^=learner]').attr('id').split("_")[1];
      var idSchool = $('span a[id^=school]').attr('id').split("_")[1];
      var idClass = $('span a[id^=class]').attr('id').split("_")[1];
      _formReg.PSCL = {
        idRelation: _formReg.idrelation,
        idUser: _formReg.idprofile,
        idSchool: idSchool,
        idLearner: idLearner,
        idClass: idClass,
      }

    regResponsible.registerProfile(_formReg);
  },
/**** |registerProfile| *** Регистрация ноаого профиля **/
  registerProfile: function(_formReg){
    var firstname = $("#register_responsible #f_firstname"),
      lastname = $("#register_responsible #f_lastname"),
      middlename = $("#register_responsible #f_middlename"),
      login = $("#register_responsible span#login"),
      pswd = $("#register_responsible span#pswd"),
      email = pswd;

      notices = regResponsible.validateFormSRL(
        {
          firstname: firstname,
          lastname: lastname,
          middlename: middlename,
          login: login,
          pswd: pswd,
          email: email
        }
      );
    if(_formReg.login === undefined) _formReg.login = login.text();
    if(_formReg.pswd === undefined) _formReg.pswd = pswd.text();
    if(_formReg.email === undefined) _formReg.email = _formReg.login;

    window.formsrl.confirmation_after.empty();
    // Validate before send
    if(notices.length) { window.formsrl.confirmation_after.prepend(tmpl(window.tmpl.tmpl_notices, {type:MT_ERROR, notices: notices })); }
    else { // valid true. Cleare all errors.
      ajax.post_bs("autoreg.php",_formReg,
        function(data){ window.formsrl.loader_place.addClass("display"); },
        function(data){
          if(data.errors === undefined) { // успех
            if(data.successreg.idprofile !== undefined && data.successreg.idprofile) {
              _formReg.PSCL.idUser = data.successreg.idprofile;
              console.log("SUCCESS: creates profile");
              regResponsible.addResponsibleToLearner(_formReg);
            }
            else _unknownError(window.formsrl.confirmation_after);
          }
          else {
            console.log("FAIL: Profile not created");
            // if(data.errors.indn != undefined) { notices.push(window.tmpl.tmpl_notice_h({title: ME_ERROR_SEND_DATA })); }
            // else if(data.errors.indv != undefined) {
            //   notices = validateFormView(e.data.form, {}, data.errors.indv);
            // }
            window.formsrl.confirmation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:MT_ERROR, notices: notices }));
            window.formsrl.loader_place.removeClass("display");
          }
        },
        function(XMLHttpRequest, textStatus, errorThrown){
          _unknownError(window.formsrl.confirmation_after);
          window.formsrl.loader_place.removeClass("display");
        }
      );
    }
  },
/**** |addResponsibleToLearner| *** Добавление пользователя к ученика в качестве родителя **/
  addResponsibleToLearner: function(_formReg){
    ajax.post_bs("do/journalKo/addResponsibleToLearner.php", _formReg.PSCL,
      function(data){ },
      function(data){
        if(data != 'Added yet' && data != 'Another role') { // успех
          window.location.reload();
          // console.log("responsible created to learner");
        }
        else {
          console.log("FAIL: Responsible not created");
          $('div.changeFields form input').each(function(name, node){ node.value = ''; });
          notices.push(window.tmpl.tmpl_notice_h({title: "Ошибка: Регистрация ученику родителя" }));
          window.formsrl.confirmation_after.empty().prepend(tmpl(window.tmpl.tmpl_notices, {type:MT_ERROR, notices: notices }));
        }
        window.formsrl.loader_place.removeClass("display");
      },
      function(XMLHttpRequest, textStatus, errorThrown){
        _unknownError(window.formsrl.confirmation_after);
        window.formsrl.loader_place.removeClass("display");
      }
    );
  },
}