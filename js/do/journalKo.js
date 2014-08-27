//  $(document).ready(function(){
// $(".options").click({a:12, b:"abc",assd: $(this)},
//       function(e){
//         debugger;
//         var id = $(this).attr('data-id');
//         console.log(id);
//               }
//   );
//  });
$(document).ready(function(){
    if(!window.tmpl.tmpl_notice_h) window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html());
    if(!window.tmpl.tmpl_notice_hb) window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html());
    if(!window.tmpl.tmpl_notices) window.tmpl.tmpl_notices = $("#notices").html();
    JournalKo.initWIdgetToPopupLearner();
    JournalKo.initWidgetToPopupResponsible();
});

var JournalKo = {
    qwerty: function(e){
      
    },

    initWIdgetToPopupLearner: function(){
      learners = $("div[id^=learner_]"); 
      learners.each(function(){
              // var idFriend = $(this).attr("id").split("_")[1];
              $(this).cPopup({
                  idWND: "learnerPopup_" + $(this).attr("id"),
                  // btns: window._OKCANCEL,
                  btns: window._NONE,
                  location : {
                      position: "default",
                      parentDependence: $("div.mainfield"),
                      offset: { left: 0, top: 0, right: 0, bottom: 0 },
                      // proportions: { width: 300, height: undefined },
                      quarters: [4,3,1,2]
                  }
              });
          });
    },

    initWidgetToPopupResponsible: function(){
      responsibles = $("div[id^=responsible_]"); 
      responsibles.each(function(){
              // var idFriend = $(this).attr("id").split("_")[1];
              $(this).cPopup({
                  idWND: "learnerPopup_" + $(this).attr("id"),
                  // btns: window._OKCANCEL,
                  btns: window._NONE,
                  location : {
                      position: "default",
                      parentDependence: $("div.mainfield"),
                      offset: { left: 0, top: 0, right: 0, bottom: 0 },
                      // proportions: { width: 300, height: undefined },
                      quarters: [4,3,1,2]
                  }
              });
          });
    },

  /////////////////// СПИСОК РОДИТЕЛЕЙ ///////////////////
  /**** |hideShowAddNewLearnerResponsible| **** Открытие/сокрытие формы поиска родителя/ученика **/
    hideShowAddNewLearnerResponsible: function(idSearch_1, idSearch_2, idSearch_3)
    {
      // Если противоположная форма для поиска отображена
        if ($("#"+idSearch_1+"").css('display') == 'block')
      {
        // Прячем форму противоположного поиска
          $("#"+idSearch_1+"").hide();
        // Очищаем противиположное поисковое поле
          $("#"+idSearch_2+"").val("");

        if(idSearch_1 == 'searchStud')
          $("#textSearchLearner").text("Добавить нового ученика").removeClass('actSubj');
          else
            $("#textSearchResponsible").text("Добавить нового родителя").removeClass('actSubj');
      }

      // Если уже была произведена операция поиска пользователей
      if($(".resultItems").length != 0)
      {  
        // Удаляем из DOM модели найденых пользователей
          $(".resultItems").remove(); 
        // Очищаем поисковое поле
          $("#"+idSearch_2+"").val(""); 
      }

      var el = $("#"+idSearch_3+"");
      if (el.css('display') == 'block')
      {
        el.hide();
        if(idSearch_3 == 'searchStud')
          $("#textSearchLearner").text("Добавить нового ученика").removeClass('actSubj');
          else
            $("#textSearchResponsible").text("Добавить нового родителя").removeClass('actSubj');
        // Очищаем поле для поиска при сворачивании
        $("#"+idSearch_2+"").val("");
      }
        else if(el.css('display') == 'none')
        {
          el.show();
          if(idSearch_3 == 'searchStud')
            $("#textSearchLearner").text("Скрыть добавление ученика").addClass("actSubj");
            else
              $("#textSearchResponsible").text("Скрыть добавление родителя").addClass("actSubj");
        }
    },

  	hideShowAddNewLearner: function()
  	{
      JournalKo.hideShowAddNewLearnerResponsible('searchResp', 'searchLearner', 'searchStud');
  	},

    hideShowAddNewResponsible: function(idLearner)
    {
      JournalKo.hideShowAddNewLearnerResponsible('searchStud', 'searchResponsible', 'searchResp');
      
      var popup = $("div[id^=popupLearner_]");
      if(popup) popup.remove();

      $("#learnersList [value='"+idLearner+"']").attr("selected", "selected");
    },

    hideShowAddNewResponsibleFromPopup: function(idLearner){
      if($('#searchResp').css('display') != 'block')
      { 
        JournalKo.hideShowAddNewResponsible(idLearner);
        $("#learner_"+idLearner).cPopup('close');
      }
      else
        $("#learnersList [value='"+idLearner+"']").attr("selected", "selected");
    },

    prepareEmptySearchResultHTML: function(_searchKey){
        var title;
        if(_searchKey == " Поиск контакта..."){
            title = MI_FRIENDSSEARCH_EMPTY_SEARCHKEY;
        } else {
            title = MI_FRIENDSSEARCH_EMPTY_REQUESTS.replace(new RegExp("%SEARCHKEY",""), _searchKey);
        }
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: title
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return insHTML;
    },

  /***** |searchLearnerOrResponsible| **** Поиск ученика или родителя ****/
  	searchLearnerOrResponsible: function(event, post)
  	{
      var idSchool = post.split("_")[1];
      var idClass = post.split("_")[3];
  		var typeSearch = event.parent().parent().attr('id');
      // var typeSearch = sps[4];
      if(typeSearch == 'searchStud')
        var searchKey = $('#searchLearner').val();
      else
        var searchKey = $('#searchResponsible').val();

      var massUsers = JournalKo.loadUsersBySearchKey(searchKey, 0);
      var countUsers = JournalKo.loadCountUsersBySearchKey(searchKey);
      var countSteps=0;

            $(".warningMessage").remove();

            insertHTML ="<div class='resultItems'>\
                                <ul>";
      if(massUsers != "Empty")
      {
            for(var i in massUsers)
            {
                user = {
                    id: massUsers[i]["id"],
                    fi: massUsers[i]["fi"],
                    pathAvatar: massUsers[i]["pathAvatar"]
                };

                insertHTML +="<li>\
                                  <div class='rsltItem'>\
                                      <a href=''>\
                                        <img src=\"../../" + user.pathAvatar + "\"/>\
                                        <span>"+user.fi+"</span>\
                                      </a>";
                insertHTML += typeSearch == "searchStud" ? "<span class='addToList' onclick='JournalKo.addToSchoolClass(\"user_"+user.id+"_"+idSchool+"_"+idClass+"\");'>Зачислить</span>" : "<span\
                                                           class='addToList' onclick='JournalKo.addResponsibleToLearner(\"user_"+user.id+"_"+idSchool+"_"+idClass+"\");'>Добавить</span>";
                insertHTML+=   "</div>\
                             </li>";
                countSteps++;
                if(countSteps == 6)
                {
                    var lastIdUser = user.id;
                }
            }
                insertHTML += " </ul></div>";
            
            $('.resultItems').remove();
            $("#"+typeSearch+"").append(insertHTML);

            $(".countSerchedUsersAll").attr("value",countUsers);
            var countLoadedUsers = $('.resultItems ul li').length;
            if(countLoadedUsers < countUsers)
            {
                var insertHTMLWarning = "";
                insertHTMLWarning += "<div class='warningMessage' id='lastIdUser_"+lastIdUser+"' onclick='JournalKo.loadMoreLearnerOrResponsible("+lastIdUser+",\""+post+"\",\""+typeSearch+"\")'>\
                                     <div class='centeredm'><span>Выгрузить еще...</span></div>\
                                </div>";
                $(".content").append(insertHTMLWarning);
            }
      }
        else
        {
            insertHTML += JournalKo.prepareEmptySearchResultHTML(searchKey);
            insertHTML += "</div>";
            // Удаляем найденные результаты, если в DOM имеются
            $(".resultItems").remove();

            // Добавляем информационное окно
            $(".content").append(insertHTML);
        }
  	},

    loadMoreLearnerOrResponsible: function (lastIdUser, post, typeSearch){
        var insertHTML = "";
        var sps = post.split("_");
        var idSchool = post.split("_")[1];
        var idClass = post.split("_")[3];
        if(typeSearch == 'searchStud')
          var searchKey = $('#searchLearner').val();
        else
          var searchKey = $('#searchResponsible').val();
        var massUsers = JournalKo.loadUsersBySearchKey(searchKey, lastIdUser);
        var countSteps = 0;
            
        if(massUsers != "Empty")
        {
          for(var i in massUsers)
          {
              user = {
                  id: massUsers[i]["id"],
                  fi: massUsers[i]["fi"],
                  pathAvatar: massUsers[i]["pathAvatar"]
              };

              insertHTML +="<li>\
                                <div class='rsltItem'>\
                                    <a href=''>\
                                      <img src=\"../../" + user.pathAvatar + "\"/>\
                                      <span>"+user.fi+"</span>\
                                    </a>";
              insertHTML += typeSearch == "searchStud" ? "<span class='addToList' onclick='JournalKo.addToSchoolClass(\"user_"+user.id+"_"+idSchool+"_"+idClass+"\");'>Зачислить</span>" : "<span\
                                                         class='addToList' onclick='JournalKo.addResponsibleToLearner(\"user_"+user.id+"_"+idSchool+"_"+idClass+"\");'>Добавить</span>";
              insertHTML+=   "</div>\
                           </li>";
              countSteps++;
              if(countSteps == 6)
              {
                  var lastIdUser = user.id;
              }
          }

          $(".resultItems ul").append(insertHTML);
          $(".warningMessage").attr('id', "lastIdUser_"+lastIdUser+"");
          $(".warningMessage").attr('onclick', "JournalKo.loadMoreLearnerOrResponsible("+lastIdUser+",\""+post+"\",\""+typeSearch+"\")");

          var countLoadedUsers = $('.resultItems ul li').length;
          var countSearchedUsers = $(".countSerchedUsersAll").attr("value");

          if(countLoadedUsers == countSearchedUsers)
            $(".warningMessage").remove();
        }
    },

    loadUsersBySearchKey: function (searchKey, startId){
        var massUsers;
        ajax.post_sync("do/journalKo/searchLearner.php", "searchKey="+searchKey+"&startId="+startId,
                function(response){
                    massUsers = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
                } 
            );
        return massUsers;
    },

    loadCountUsersBySearchKey: function (searchKey){
        var countUsers;
        ajax.post_sync(
            "friends/getCountUsersBySearch.php",
            "searchKey="+searchKey,
            function(response){
                countUsers = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
            } 
        );
        return countUsers;
    },

  /**** |addToSchoolClass| **** Добавление найденого пользователя в класс ***/
    addToSchoolClass: function(post){
      var sps = post.split("_");
      var idUser = sps[1];
      var idSchool = sps[2];
      var idClass = sps[3];

      ajax.post_sync("do/journalKo/addToSchoolClass.php","idUser=" + idUser + "&idSchool=" + idSchool + "&idClass=" + idClass,
        function(data){
              if(data)
              {
                JournalKo.repaintLearnerResponsibleTable(data, idClass, idSchool);
                JournalKo.repaintLearnerComboBox(data);
                JournalKo.hideShowAddNewLearner();
                
              }
              else
                alert("Пользователь уже является учеником другой школы/класса");
        }, 
        function(XMLHttpRequest, textStatus, errorThrown){ 
            var errorMessage = "";
        }
          );
    },

  /**** |addResponsibleToLearner| *** Добавление пользователя к ученику в качестве родителя **/
    addResponsibleToLearner: function(post){
      var sps = post.split("_");
      var idUser = sps[1];
      var idSchool = sps[2];
      var idClass = sps[3];
      var idLearner = $("select#learnersList").val();
      if(idLearner == 0)
        alert("Выберите ученика");
      else
      // var idClass = sps[3];
      ajax.post_sync("do/journalKo/addResponsibleToLearner.php","idUser=" + idUser + "&idSchool=" + idSchool + "&idLearner=" + idLearner + "&idClass=" + idClass,
              function(data){
                    if(data != 'Added yet')
                    {
                      if(data != 'Another role')
                      { 
                        JournalKo.repaintLearnerResponsibleTable(data, idClass, idSchool);
                        JournalKo.hideShowAddNewResponsible();
                      }
                      else
                        alert("Пользователь не может быть родителем выбранного ученика");
                    }
                    else
                      alert("Пользователь уже является родителем выбранного ученика");
              }, 
              // function(XMLHttpRequest, textStatus, errorThrown){ 
              //     var errorMessage = "";
              // }
              function(XMLHttpRequest, textStatus, errorThrown){
                alert('Возникла системная ошибка! Ваш запрос не выполнен!');
            }
          );
    },

    repaintLearnerResponsibleTable: function(data, idClass, idSchool){
      var insertHTML = '';
                /* Номер ученика по списку */
                var numberLearner = 1;
                insertHTML += "<table cellspacing='0' cellpadding='0' id='tableLeRe'>\
                                    <thead>\
                                      <tr>\
                                        <td><input id='checkLearnerAll' type='checkbox' onchange='JournalKo.changeLearnersAllCheckBox($(this));'>Ученики</td>\
                                        <td><input id='checkResponsibleAll' type='checkbox' onchange='JournalKo.changeResponsiblesAllCheckBox($(this));'>Родители</td>\
                                      </tr>\
                                    </thead>\
                                    <tbody>";
                for(var i in data)
                {  
                  learnerResponsible = {
                      idProfile: data[i]["idProfile"],
                      idLearner: data[i]["idLearner"],
                      fio: data[i]["fio"],
                      responsibles: data[i]["responsibles"]
                  };
                                        /* Количество родителей одного ученика */
                                        /* !! Cтрока ниже не актуальна в IE8 !! */
                                        var countResponsibles = Object.getOwnPropertyNames(learnerResponsible.responsibles).length;
                                        /* Счетчик для цикла родителей */
                                        var countFors = 1;
                                        /* Номер родителя по списку */
                                        var numberResponsible = 1;
                          insertHTML += "<tr>\
                                          <td rowspan='"+countResponsibles+"' class='leftside'>\
                                            <nobr>\
                                                          <input id='checkLearner_"+learnerResponsible.idLearner+"' type='checkbox' onchange='JournalKo.changeStateLearnerCheckBox( $(this) );'>\
                                                          <a href='../index.php?id="+learnerResponsible.idProfile+"'>"+numberLearner+". "+learnerResponsible.fio+"</a>\
                                                          <div id='learner_"+learnerResponsible.idLearner+"' class='options'\
                                                          onclick='JournalKo.createLearnerPopup($(this),"+idClass+", "+idSchool+");'>\
                                                          </div>\
                                            </nobr>\
                                          </td>";
                  for(var j in data[i]['responsibles'])
                  {                        
                    responsible = {
                      idResponsible: data[i]['responsibles'][j]['id'],
                      idProfile: data[i]['responsibles'][j]['idProfile'],
                      fio: data[i]['responsibles'][j]['fio'],
                      error: data[i]['responsibles']['error']
                    };
                                if (countFors < 2)
                                {
                          insertHTML += "<td>";
                                    if (responsible.error == undefined)
                                    {
                          insertHTML +=    "<nobr>\
                                                          <input id='checkResponsible_"+responsible.idResponsible+"_learner_"+learnerResponsible.idLearner+"' type='checkbox' onchange='JournalKo.changeStateResponsibleCheckBox( $(this) );'>\
                                                          <a href='../index.php?id="+responsible.idProfile+"'>1. "+responsible.fio+"</a>\
                                                          <div id='responsible_"+responsible.idResponsible+"_learner_"+ learnerResponsible.idLearner+"' class='options' onclick='JournalKo.createResponsiblePopup($(this),"+idClass+", "+idSchool+");'>\
                                                          </div>\
                                            </nobr>";
                                    }
                          insertHTML +=    "</td>";
                                }
                                else
                                {
                                    if(countResponsibles > 1)
                                    {
                          insertHTML +=    "<tr>\
                                                <td>\
                                                  <nobr>\
                                                          <input id='checkResponsible_"+responsible.idResponsible+"_learner_"+learnerResponsible.idLearner+"' type='checkbox' onchange='JournalKo.changeStateResponsibleCheckBox( $(this) );'>\
                                                          <a href='../index.php?id="+responsible.idProfile+"'>"+numberResponsible+". "+responsible.fio+"</a>\
                                                          <div id='responsible_"+responsible.idResponsible+"_learner_"+ learnerResponsible.idLearner+"' class='options' onclick='JournalKo.createResponsiblePopup($(this),"+idClass+", "+idSchool+");'>\
                                                          </div>\
                                                  </nobr>\
                                                </td>\
                                            </tr>";
                                    }
                                }
                          countFors++;
                          numberResponsible++;
                  }                
                          numberLearner++;
                          insertHTML +=   "</tr>";
                }              
                          insertHTML +=   "</tbody>\
                                              </table>";
                          $("#tableLeRe").replaceWith(insertHTML);


      JournalKo.initWIdgetToPopupLearner(/*$("#learner_"+data[Object.keys(data).length]['idLearner']+""*/);
      JournalKo.initWidgetToPopupResponsible();

    },

    repaintLearnerComboBox: function(data){
                var insertHTML = "";
                
                insertHTML += " <select id='learnersList'>\
                                        <option style='background-color: #bbb; color: #000' value='0'>\
                                              --Выберите ученика--\
                                        </option>";
                for(var i in data)
                {  
                  learnerResponsible = {
                      idLearner: data[i]["idLearner"],
                      fio: data[i]["fio"],
                      responsibles: data[i]["responsibles"]
                  };                        
                  insertHTML += "<option value="+learnerResponsible.idLearner+">"+learnerResponsible.fio+"</option>";
                }                        
                insertHTML +=  "</select>";

                $("#searchResp #learnersList").replaceWith(insertHTML);
    },

    createLearnerPopup: function(el, idClass, idSchool){
        var sps = el.attr("id").split("_");
        var idLearner = sps[1];
        var test_2 = el;//$(e.target);
        // if(!test_2.cPopup('isInit')) {
              test_2.cPopup('open', {
                  data: {},
                  body: JournalKo.prerareLearnerPopupUI(idLearner, idClass, idSchool),
              });
          // } else test_2.cPopup('open');
    },

    prerareLearnerPopupUI: function(idLearner, idClass, idSchool){
        var insertHTML = "";
        insertHTML += "<div class='displayMenu' style='display:block;position: inherit; width: 180px;'>\
                        <span class='menuitem' id='delete_"+idLearner+"' onclick='JournalKo.deleteLearnerFromClass("+idLearner+", "+idClass+", "+idSchool+")'>Удалить</span>\
                        <span class='menuitem' id='addResp_"+idLearner+"' onclick='JournalKo.hideShowAddNewResponsibleFromPopup("+idLearner+");'>Добавить родителя</span>\
                        <span class='menuitem' id='moveClass_"+idLearner+"' onclick=''>Перевести в другой класс</span>\
                    </div>";
        return insertHTML;
    },

    deleteLearnerFromClass: function(idLearner, idClass, idSchool){
      $("#popupLearner_learner_"+idLearner).remove();

      var elements = $("div [id $=_learner_"+idLearner+"][id^=responsible_]");
      var massResponsible = new Array();
      var sps;
      elements.each(function(indx, element){
              sps = $(element).attr("id").split("_");
              massResponsible.push(sps[1]);
          });
      var arrayString = JSON.stringify(massResponsible);


//        var n = 4, m = 4;
//        var mas = {};
//        for (var i = 0; i < m; i++){
//            mas[i] = {};
//            for (var j = 0; j < n; j++){
//                mas[i][j] = 0;
//            }}
//        console.log(mas);
//      var stringify = JSON.stringify(mas);
//        var arr = new Array();
//        arr['a']='The letter A';
//      var string2 = JSON.stringify(arr);
//        console.log(stringify);
//        console.log(string2);
//      return;


      ajax.post_sync("do/journalKo/deleteLearnerFromClass.php","idLearner=" + idLearner + "&idClass=" +
                      idClass + "&idSchool=" + idSchool + "&massResponsible=" + arrayString,
          function(data){
            JournalKo.repaintLearnerResponsibleTable(data, idClass, idSchool);
            JournalKo.repaintLearnerComboBox(data);
        }, 
        function(XMLHttpRequest, textStatus, errorThrown){ 
            var errorMessage = "";
        }
      );

    },

    createResponsiblePopup: function(el, idClass, idSchool){
        var sps = el.attr("id").split("_");
        var idResponsible = sps[1];
        var idLearner = sps[3];
        var test_2 = el;//$(e.target);

        // el.PopupShowInstantly({
        //     idWND: "popupResponsible_" + el.attr("id"),
        //     reload: true,
        //     location : { 
        //          position: "default",
        //         offset: { 
        //                 left: 0,
        //                 top: 0, 
        //                 right: 0, 
        //                 bottom: 0, 
        //         },
        //         quarters: [3] /* 1, 2, 3, 4, default = 4 */
        //     },
        //     create: {
        //         fn_getContent: JournalKo.prerareResponsiblePopupUI,
        //             data: {idLearner: idLearner, idClass: idClass, idResponsible: idResponsible, idSchool: idSchool}
        //     }
        // });
          test_2.cPopup('open', {
                  data: {},
                  body: JournalKo.prerareResponsiblePopupUI(idLearner, idClass, idResponsible, idSchool),
              });
    },

    prerareResponsiblePopupUI: function(idLearner, idClass, idResponsible, idSchool){
        var insertHTML = "";
        insertHTML += "<div class='displayMenu' style='display:block;position: inherit; width: 180px;'>\
                      <span class='menuitem' id='delete_"+idResponsible+"' onclick='JournalKo.deleteResponsibleFromLearner("+idLearner+", "+idClass+", "+idResponsible+", "+idSchool+")'>Удалить</span>\
                    </div>";
        return insertHTML;
    },

    deleteResponsibleFromLearner: function(idLearner, idClass, idResponsible, idSchool){
      $("#popupResponsible_responsible_"+idResponsible+"_learner_"+idLearner).remove();

      // var elements = $("div [id $=_learner_"+idLearner+"]");
      // var massIdResponsible = new Array();
      // var sps;
      // elements.each(function(indx, element){
      //         sps = $(element).attr("id").split("_");
      //         massIdResponsible.push(sps[1]);
      //     });
      // var arrayString = JSON.stringify(massIdResponsible);
      ajax.post_sync("do/journalKo/deleteResponsibleFromLearner.php","idLearner=" + idLearner + "&idClass=" + idClass + "&idResponsible=" + idResponsible + "&idSchool=" + idSchool,
          function(data){
            JournalKo.repaintLearnerResponsibleTable(data, idClass, idSchool);
            JournalKo.repaintLearnerComboBox(data);
        }, 
        function(XMLHttpRequest, textStatus, errorThrown){ 
            var errorMessage = "";
        }
      );

    },

    /********************** | БЛОК МЕТОДОВ УПРАВЛЕНИЯ ГАЛОЧКАМИ | *******************/
    changeLearnersAllCheckBox: function (el){
      $('[id^=checkLearner_]').prop( 'checked', el.is(':checked')); 
      $('[id^=checkResponsible_]').attr( 'disabled', el.is(':checked')); 
      $('[id^=checkResponsible_]').prop( 'checked', el.is(':checked'));
      $('[id^=checkResponsibleAll]').attr( 'disabled', el.is(':checked')); 
      $('[id^=checkResponsibleAll]').prop( 'checked', el.is(':checked'));
    },

    changeResponsiblesAllCheckBox: function(el){
      var state;
      if(el.is(':checked') == true)
        state = true;
      else state = false;
        $('[id^=checkResponsible_]:enabled').prop( 'checked', state);
    },

    changeStateLearnerResponsibleCheckBox: function(el, checkAll, check){
      if(el.is(':checked') == false)
      {
        state = false;
        $('[id='+checkAll+']').prop( 'checked', false); // Убираем галочку "Все ученики"
      }
      else 
      {
        var mass = $('[id^='+check+']');
        var oneElOfMass;
        var state = true;
        mass.each(function(){
              oneElOfMass = $(this).is(':checked');
              if(oneElOfMass != true)  
                state = false;
          });
        if(state != false)
        {
           $('[id^='+checkAll+']').prop( 'checked' , state);

        }
      }  
      return state;
    },

    changeStateLearnerCheckBox: function(el){
      var state = JournalKo.changeStateLearnerResponsibleCheckBox(el, 'checkLearnerAll', 'checkLearner_');
        $('[id^=checkResponsibleAll]').prop( 'checked' , state); // Устанавливаем состояние для галочки "Все родители"
        $('[id^=checkResponsibleAll]').prop( 'disabled' , state); // Устанавливаем состояние доступности для галочки "Все родители"
         
      var sps = el.attr("id").split("_");
      var checkBoxResp = $('[id^=checkResponsible_][id$=learner_'+sps[1]+']');
      if(checkBoxResp.length != 0)
      {
        checkBoxResp.attr("disabled", el.is(':checked'));
        checkBoxResp.prop( 'checked' , el.is(':checked'));
      }
    },

    changeStateResponsibleCheckBox: function(el){
      JournalKo.changeStateLearnerResponsibleCheckBox(el, 'checkResponsibleAll', 'checkResponsible_');
    },

    deleteCheckedLearnerResponsible: function(idSchool, idClass){
      var massForDeleteLearners = {};
      var massForDeleteResponsible = {};
      var spsL,spsR;
      var tempR = [], tempL = [];
      var oldLearner, newLearner;
      $('[id^=checkLearner_]:checked').each(function(){
              spsL = $(this).attr("id").split("_");
              var elements = $("div [id $=_learner_"+spsL[1]+"][id^=responsible_]");
              elements.each(function(){
                spsR = $(this).attr("id").split("_");
                newLearner = spsR[3];
                if(oldLearner != newLearner)
                {
                    oldLearner = newLearner;
                    tempL = [];
                    tempL.push(spsR[1]);
                }
                else
                    tempL.push(spsR[1]);   
              });
                massForDeleteLearners[spsL[1]] = tempL;
                tempL = [];
          });

      $('[id^=checkResponsible_]:checked:enabled').each(function(){
              spsR = $(this).attr("id").split("_");
              newLearner = spsR[3];
              if(oldLearner != newLearner)
              {
                  oldLearner = newLearner;
                  tempR = [];
                  tempR.push(spsR[1]);
              }
              else
                  tempR.push(spsR[1]);   

              massForDeleteResponsible[spsR[3]] = tempR;
          });
      var massForDeleteLearners = JSON.stringify(massForDeleteLearners);
      var massForDeleteResponsible = JSON.stringify(massForDeleteResponsible);

       ajax.post_sync("do/journalKo/deleteCheckedLearnerResponsible.php","idSchool="+ idSchool +"&idClass="+ idClass + "&massForDeleteLearners="+ massForDeleteLearners +"&massForDeleteResponsible=" + massForDeleteResponsible,
          function(data){
            JournalKo.repaintLearnerResponsibleTable(data, idClass, idSchool);
            JournalKo.repaintLearnerComboBox(data);
        }, 
        function(XMLHttpRequest, textStatus, errorThrown){ 
            var errorMessage = "";
        }
      );

    },






























  /////////////////// СПИСОК УЧЕНИКОВ ///////////////////
  /**** |sortTable| **** Функция подготовки отсортированных массивов ***/
    sortTable: function(number, typeSort){
      function compareNumeric(a, b) {
          if (a > b) return 1;
          if (a < b) return -1;
        }
        var finalMass = new Array();
        var massIdLearner = new Array();
        var massAverageMark = new Array();
        var massId = new Array();
        var test1 = new Array();
        var mark = $("td[id $=_"+number+"] ");
          mark.each(function(indx, element){
              massId.push($(element).attr("id"));
          });
          for(var i in massId)
          {
            var t_mass = massId[i].split("_");
            massIdLearner.push(t_mass[0]);
            massAverageMark.push(parseFloat($("#"+massId[i]+"").find("."+typeSort+"").text()));
          }
        var tempMassAVG;
        var tempMassIdL;
        for(var i in massAverageMark)
          for(var j in massAverageMark)
          if(massAverageMark[i] < massAverageMark[j])
          { 
            tempMassAVG = massAverageMark[i];
            massAverageMark[i] = massAverageMark[j];
            massAverageMark[j] = tempMassAVG;
            
            tempMassIdL = massIdLearner[i];
            massIdLearner[i] = massIdLearner[j];
            massIdLearner[j] = tempMassIdL;
          }

          return massIdLearner;
    },

  /**** |sortTableForward| ** Функция сортировки таблицы по убыванию **/
    sortTableForward: function(number, typeSort){
        var massIdLearner = JournalKo.sortTable(number, typeSort);

        for(var i in massIdLearner)
          {
            oneRowMarks = $("tr[id ^=marks-"+massIdLearner[i]+"-]");
            oneRowFio = $("tr[id ^=learner-"+massIdLearner[i]+"-]");
            
            oneRowMarks.insertAfter($('#helpRowRight'));
            oneRowFio.insertAfter($('#helpRowLeft'));
          }
        var avg = $("span[id = avg_"+number+"]");
        var count = $("span[id = count_"+number+"]");
        var RAbsent = $("span[id = RAbsent_"+number+"]");
        var NRAbsent = $("span[id = NRAbsent_"+number+"]");
        avg.attr('onclick',"JournalKo.sortByAverageBack(\""+number+"\");");
        count.attr('onclick',"JournalKo.sortByCountMarksBack(\""+number+"\");");
        RAbsent.attr('onclick',"JournalKo.sortByReasonAbsentBack(\""+number+"\");");
        NRAbsent.attr('onclick',"JournalKo.sortByNoReasonAbsentBack(\""+number+"\");");
    },

  /**** |sortTableBack| ** Функция сортировки таблицы по возрастанию **/
    sortTableBack: function(number, typeSort){
        var massIdLearner = JournalKo.sortTable(number, typeSort);
        for(var i in massIdLearner)
          {
            oneRowMarks = $("tr[id ^=marks-"+massIdLearner[i]+"-]");
            oneRowFio = $("tr[id ^=learner-"+massIdLearner[i]+"-]");
            
            oneRowMarks.appendTo($('.rightAll tbody'));
            oneRowFio.appendTo($('.leftAll tbody'));
          }

        var avg = $("span[id = avg_"+number+"]");
        var count = $("span[id = count_"+number+"]");
        var RAbsent = $("span[id = RAbsent_"+number+"]");
        var NRAbsent = $("span[id = NRAbsent_"+number+"]");
        avg.attr('onclick',"JournalKo.sortByAverageForward(\""+number+"\");");
        count.attr('onclick',"JournalKo.sortByCountMarksForward(\""+number+"\");");
        RAbsent.attr('onclick',"JournalKo.sortByReasonAbsentForward(\""+number+"\");");
        NRAbsent.attr('onclick',"JournalKo.sortByNoReasonAbsentForward(\""+number+"\");");
    },

  /**** Разные способы сортировок по результирующим цифрам ****/
    sortByAverageForward: function(number){
      JournalKo.sortTableForward(number,"first");
    },

    sortByAverageBack: function(number){
      JournalKo.sortTableBack(number,"first");
    },

    sortByCountMarksForward: function(number){
      JournalKo.sortTableForward(number,"second");
    },

    sortByCountMarksBack: function(number){
      JournalKo.sortTableBack(number,"second");
    },
    
    sortByReasonAbsentForward: function(number){
      JournalKo.sortTableForward(number,"third");
    },

    sortByReasonAbsentBack: function(number){
      JournalKo.sortTableBack(number,"third");
    },
    
    sortByNoReasonAbsentForward: function(number){
      JournalKo.sortTableForward(number,"fourth");
    },

    sortByNoReasonAbsentBack: function(number){
      JournalKo.sortTableBack(number,"fourth");
    },

  /**** |sortFios| *** Сортировка по ФИО ***/
    sortFios: function(){
      var massRowNumber = new Array();
      var massIdLearner = new Array();
      var splitMass;
      var learners = $("tr [id ^=learner-]");
          learners.each(function(indx, element){
              splitMass = $(element).attr("id").split("-");
              massRowNumber.push(parseInt(splitMass[2]));
              massIdLearner.push(splitMass[1]);
          });

      var tempMassRowNumber;
      var tempMassIdL;
      
      for(var i in massRowNumber)
          for(var j in massRowNumber)
          if(massRowNumber[i] < massRowNumber[j])
          { 
            tempMassRowNumber = massRowNumber[i];
            massRowNumber[i] = massRowNumber[j];
            massRowNumber[j] = tempMassRowNumber;
            
            tempMassIdL = massIdLearner[i];
            massIdLearner[i] = massIdLearner[j];
            massIdLearner[j] = tempMassIdL;
          }     

          return massIdLearner;
    },

  /**** |sortByFIOsForward| *** Сортировка ФИО по убыванию ***/
    sortByFIOsForward: function(){
      var massIdLearner = JournalKo.sortFios();
      for(var i in massIdLearner)
          {
            oneRowMarks = $("tr[id ^=marks-"+massIdLearner[i]+"]");
            oneRowFio = $("tr[id ^=learner-"+massIdLearner[i]+"]");
            
            oneRowMarks.appendTo($('.rightAll tbody'));
            oneRowFio.appendTo($('.leftAll tbody'));
          }

      var fio = $(".button");
      fio.attr('onclick',"JournalKo.sortByFIOsBack();");
    },

  /**** |sortByFIOsBack| *** Сортировка ФИО по возрастанию ***/
    sortByFIOsBack: function(){
      var massIdLearner = JournalKo.sortFios();
      for(var i in massIdLearner)
          {
            oneRowMarks = $("tr[id ^=marks-"+massIdLearner[i]+"]");
            oneRowFio = $("tr[id ^=learner-"+massIdLearner[i]+"]");
            
            oneRowMarks.insertAfter($('#helpRowRight'));
            oneRowFio.insertAfter($('#helpRowLeft'));
          }

      var fio = $(".button");
      fio.attr('onclick',"JournalKo.sortByFIOsForward();");
    }

}