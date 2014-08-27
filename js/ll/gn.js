window.onload = function () {
  if (jltm.tm_init() && jltm.tmisset()) {
    positionPage();
    if( fn_userAgreement()) {
      fn_loadSupplimentData();
    }
  }
  else jltm.tmclredir();
}
// global obj
  var jltm = {
    tup : 60000, // как часто оьновлять
    texp : 120000, // время жизни
    tm_init : function() {
      if(jltm.tmisset()) {
        if(typeof tmp_data !== "undefined") {
          jltm.tup = tmp_data.tup ? tmp_data.tup : jltm.tup;
          jltm.texp = tmp_data.texp ? tmp_data.texp : jltm.texp;
        }
        tmp_data = undefined;
        return window.setInterval(jltm.tm, jltm.tup);
      }
      return false;
    },
    tm : function() {
      if(!jltm.tmisset()) {
        // out page
        jltm.tmclredir();
      }
      var expires = 0; // session lifetime live while session live (debug_js_ignore)
      if(!debug_js_ignore.value){
        var now = new Date();
        var time = now.getTime();
        var expireTime = time + jltm.texp;
        now.setTime(expireTime);
        expires = now.toGMTString();
      }
      setCookie('jlinyii',  getCookie('jlinyii'), { expires: expires, path: '/' }); 
      if(debug.value) jltm.debug();
    },
    tmisset : function(){ return (getCookie('jlinyii')==="undefined" || getCookie('jlinyii')===undefined) ? false : true; },
    tmcl : function() {
      setCookie('jlinyii',  getCookie('jlinyii'), { expires: -1, path: '/' });
    },
    tmclredir : function() {
      jltm.tmcl();
      window.location = "/auth/auth/logout";
    },
    get_tm : function() { return getCookie('jlinyii'); },
    debug: function () {
      var myDate = new Date();
      var now = myDate.getHours() + ":" + myDate.getMinutes() + ":" + myDate.getSeconds() + ":" + myDate.getMilliseconds();
      console.log('[' + now + '] [ ' +  getCookie('jlinyii') + '] (' + jltm.tup / 1000 + ' | ' + jltm.texp + ') upd lifetime ...'); 
    }
  },
  DUA = {
    createHeader_UserAgreement : function(){return "<h3 class='headerDoc'>Для продолжения  работы  Вам  необходимо принять условия пользовательского соглашения:</h3>"; },
    createBtns_UserAgreement : function () { return $("#userAgreement-btns").html(); },
    createBody_UserAgreement : function () { return $("#userAgreement").html(); },
    openDialogUserAgreement : function(e){
      var test_2 = $(e.target);

      if(!test_2.cPopup('isInit')) {
          test_2.cPopup('open', {
              bg: true,
              btnesc: (!PM.pua) ? false : true,
              closeoutside: (!PM.pua) ? false : true,
              useHead: true,
              head: DUA.createHeader_UserAgreement(),
              body: DUA.createBody_UserAgreement(),
              distinctBtns: $("#userAgreement-btns").html(),
              fn_ok: function() {
               if(!PM.pua) DUA.actlic(); },
              fn_cl: function() { if(!PM.pua) jltm.tmclredir(); },
              // distinctBtns: $("#userAgreement").html(),
              data: {},
          });
      } else test_2.cPopup('open');
    },
    actlic: function() {
        ajax.post_sync("actlic.php", "idOwner="+PM.idAuth,
            function(response){ if(response) PM.pua = (new Date()).yyyymmdd('-'); },
            function(XMLHttpRequest){ }
        );
    }
  }

  // function
    function onBodyResize(force) {
      var dwidth = Math.max(intval(window.innerWidth), intval(document.documentElement.offsetWidth));
        if (window.lastWindowWidth != dwidth) {
            window.lastWindowWidth = dwidth;
            // if (browser.msie6) { return; }
            var pl = $(force).offsetWidth, sbw = sbWidth();
            if (document.body.offsetWidth < pl) {
                // document.body.style.overflowX = "auto";
                dwidth = pl + sbw + 2;
            } else {
                // document.body.style.overflowX = "hidden";
            }
            if (dwidth) {
                geO("scrollFix").style.width = dwidth - sbw - 2 + "px";
            }
        }
    }
    function positionPage () {
      var siteBlock = geO("site_block");
      if(siteBlock) {
        window._bodyNode = document.getElementsByTagName("body")[0];
        $(window._bodyNode).css('margin','-1000px 0 0 0');
        onBodyResize(siteBlock); // upd size page
        $(window._bodyNode).css('margin','0px');    
      }
    }
    function fn_loadSupplimentData () {
      // upd data sidebar
      var supplementProfileData = null;
      ajax.post_sync("supplementProfile.php", "idOwner="+PM.idAuth,
          function(response){ supplementProfileData = response; },
          function(XMLHttpRequest, textStatus, errorThrown){
            console.log("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
            // jltm.tmclredir();
          }
      );
      // кол-во непрочитаных  сообщений и заявок в друзья
      var countUnread = supplementProfileData['countUnread'], 
          countNewFriends = supplementProfileData['countFriendRequests'],
          countMessagesDiv = $("#comm2"),
          countFriendRequestsDiv = $("#countNewRequestsSpan");

      if(countUnread > 0) { countMessagesDiv.html(countUnread); } else countMessagesDiv.html("");
      if(countNewFriends > 0) {
          countFriendRequestsDiv.html(countNewFriends);
          // меняем ссылку на добавление контактов(contactsadd.php) в случае, если у нас есть входящие заявки
          countFriendRequestsDiv.parent().find("a").attr("href",PM.navigate+"/pages/contactsadd.php");
      }
      else countFriendRequestsDiv.html("");
    }
    function fn_userAgreement () {
      var useragreement = $('div.footer ul li a#jLifeUserAgreement');
      if(useragreement.length) {
        useragreement.cPopup({
          confirm: false,
          idWND: "UserAgreement",
          // btns: window._DISTINCT,
          btns: (!PM.pua) ? window._DISTINCT : window._OK, //  _OK | _OKCANCEL | _DISTINCT      
          location : {
              position: "relative",
              parentDependence: $("div#container"),
              сonsiderVisibleAreaPD: false,
              offset: { left: 50, top: 10 }, 
              proportions: { width: 930, height: (!PM.pua) ? 550 : 535 },
              quarters: [3,4],

          },          
        });
        useragreement.on('click', DUA.openDialogUserAgreement);
        // проверка на лицензию
        if(!PM.pua) { useragreement.click(); return false; }
        else return true;
      }
      return false;
    }
