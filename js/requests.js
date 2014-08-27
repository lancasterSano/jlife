$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();
    Requests.assignPopupToInboxRequests();
});
/**
 * Объект для хранения результатов поиска
 */
var searchResultRequests ={ 
    /**
     * Хранит в себе результаты поиска по группам
     */
    result: Array(),
    /**
     * Возвращает все найденные результаты в заданной группе
     * @param {type} _idGroup идентификатор группы
     * @returns {object} массив найденных друзей, который можно передавать в другие функции этой страницы
     * (id, FI, avaPath)
     */
    getAllContacts: function(_type){
        if(_type == 1) {
            return searchResultRequests.result["outboxRequests"];
        } else if(_type == 2){
            return searchResultRequests.result["inboxRequests"];
        }
    },
    /**
     * Возвращает X найденных результаты в заданной группе
     * @param {type} _idGroup идентификатор группы
     * @returns {object} массив первых Х друзей, который можно передавать в другие функции этой страницы
     * (id, FI, avaPath)
     */
    getPartContacts:function(_type){
        var resultArray;
        if(_type == 1) {
            resultArray = searchResultRequests.result["outboxRequests"];
        } else if(_type == 2){
            resultArray = searchResultRequests.result["inboxRequests"];
        }
        if(resultArray){
            return resultArray.slice(0, SETTING_COUNT_FIRST_LOAD_REQUESTS);
        } else return null;
    },
    deleteRequest: function(_type, _idRequest){
        var resultArray;
        if(_type == 1) {
            resultArray = searchResultRequests.result["outboxRequests"];
        } else if(_type == 2){
            resultArray = searchResultRequests.result["inboxRequests"];
        }
        for(var i in resultArray){
            if(resultArray[i]["id"] == _idRequest){
                var index = resultArray.indexOf(resultArray[i]);
                resultArray.splice(index, 1);
            }
        }
    }
};
var Requests = {
    assignPopupToInboxRequests: function(){
        var contacts = $("p[id^=add_]"); 
        contacts.each(function(){
            tmp = $(this).attr("id").split("_");
            $(this).cPopup({
                idWND: "rf_wnd_inboxRequestPopup_" + tmp[1],
                btns: window._OKCANCEL,
                location : {
                    position: "default",
                    offset: { left: 0, top: 0, right: 0, bottom: 50 },
                 proportions: { width: 300, height: undefined },
                    quarters: [3,4]
                }
            });
        });
    },
    expandMinimizeRequestsUI: function(_type){
        var resultDiv, typeString, typeStringHeader, insHTML = '';
        if(_type == 1){
            typeString = "outbox";
            typeStringHeader = "Outbox";
            resultDiv = $("div [id ^= outboxRequests]");
        } else if (_type == 2){
            typeString = "inbox";
            typeStringHeader = "Inbox";
            resultDiv = $("div [id ^= inboxRequests]");
        }
        var locationmode = resultDiv.attr("id").split("_")[1];
        var oldshowmode = resultDiv.attr("id").split("_")[2], newshowmode;
        if(oldshowmode === "part"){
            newshowmode = "full";
            $("#showAll_"+typeString).text("Cвернуть");
        }
        else if(oldshowmode === "full"){
            newshowmode = "part";
            $("#showAll_"+typeString).text("Показать всех");
        }
        var requests = Requests.getCurrentRequests(_type, locationmode, newshowmode);
        insHTML = Requests.prepareGroupRequestsHTML(_type, requests, locationmode, newshowmode);
        $('#'+typeString+'Requests_' + locationmode +'_'+ oldshowmode).remove();
        $('#header'+typeStringHeader+'Requests').after(insHTML);
        Requests.assignPopupToInboxRequests();
    },
    searchRequestsUI: function (event){
        if(event != undefined)
            if(event.keyCode !== 13) return;
        $("#searchRequestsInput").blur();
        var insHTML = "";
        var searchKey = $("#searchRequestsInput").val();
        Requests.loadRequestsBySearchKey(searchKey);
        var foundRequestsOutboxPart = searchResultRequests.getPartContacts(1);
        var foundRequestsOutboxFull = searchResultRequests.getAllContacts(1);
        var foundRequestsInboxPart = searchResultRequests.getPartContacts(2);
        var foundRequestsInboxFull = searchResultRequests.getAllContacts(2);
        if(!foundRequestsOutboxPart){
            insHTML += Requests.prepareHeaderRequestsHTML(1, 0);
            insHTML += Requests.prepareEmptySearchRequestsHTML("outbox", searchKey);;
        }
        else {
            insHTML += Requests.prepareHeaderRequestsHTML(1, foundRequestsOutboxFull.length);
            insHTML += Requests.prepareGroupRequestsHTML(1, foundRequestsOutboxPart, "search", "part");
        }
        if(!foundRequestsInboxPart){
            insHTML += Requests.prepareHeaderRequestsHTML(2, 0);
            insHTML += Requests.prepareEmptySearchRequestsHTML("inbox", searchKey);
        } else {
            insHTML += Requests.prepareHeaderRequestsHTML(2, foundRequestsInboxFull.length);
            insHTML += Requests.prepareGroupRequestsHTML(2, foundRequestsInboxPart, "search", "part");
        }
        $("#contacts_"+PM.idAuth).empty().append(insHTML);
        Requests.assignPopupToInboxRequests();
    },
    loadRequestsBySearchKey: function(searchKey){
        searchResultRequests.result = [];
        var response_object;
        ajax.post_sync(
            "friends/getRequestsBySearch.php",
            "searchKey="+searchKey,
            function(response){
                response_object = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
            } 
        );
        if(response_object){
            searchResultRequests.result = response_object;
        }
    },
    getRequestsFull:function(_type){
        var requests;
        ajax.post_sync(
            "friends/getRequestsFull.php",
            "t="+_type,
            function(response) {
                requests = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getRequestsFull<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return requests;
    },
    getRequestsPart:function(_type){
        var requests;
        ajax.post_sync(
            "friends/getRequestsPart.php",
            "t="+_type,
            function(response) {
                requests = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getRequestsPart<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return requests;
    },
    /**
     * Метод возвращает заявки в зависимости, в каком состоянии сейчас находится страница
     * свёрнута/несвёрнута, поиск/дефолтное состояние
     * @param {int} _type 1 - хотим исходящие заявки, 2 - хотим входящие заявки
     * @param {String} _locationmode режим нахождения (поиск - "search"/дефолтное состояние - "std")
     * @param {String} _showmode режим показа (свернут/не свёрнут)
     * @returns {object} массив заявок, null, если заявок нет
     */    
    getCurrentRequests: function(_type, _locationmode, _showmode){
        var requests;
        if(_locationmode === "std"){
            if(_showmode == "full"){
                requests = Requests.getRequestsFull(_type);
            } else if(_showmode == "part"){
                requests = Requests.getRequestsPart(_type);
            }
        } else if(_locationmode === "search"){
            if(_showmode === "full"){
                requests = searchResultRequests.getAllContacts(_type);
            } else if(_showmode === "part"){
                requests = searchResultRequests.getPartContacts(_type);
            }
            if(!requests.length){
                requests = null;
            }
        }
        return requests;
    },
    /**
     * Метод, подготавливающий HTML группы заявок
     * @param {int} _type тип заявок
     * @param {object} _requests
     * @param {String} _locationmode
     * @param {String} _showmode
     * @returns {String}
     */
    prepareGroupRequestsHTML: function(_type, _requests, _locationmode, _showmode){
        var typeString;
        if(_type == 1) {
            typeString = "outbox";
        } else if(_type == 2){
            typeString = "inbox";
        }
        var insHTML = '<div class="friend" id="'+typeString+'Requests_'+_locationmode+'_'+_showmode+'">';
        for(var i in _requests){
            var request = {
                id: _requests[i]["id"],
                FI: _requests[i]["FI"],
                avaPath: _requests[i]["avaPath"],
                type: _requests[i]["type"]
            };
            insHTML += Requests.prepareRequestHTML(request.id, request.FI, request.avaPath, request.type);
        }
        insHTML += "</div>";
        return insHTML;
    },
    /**
     * Подготавливает HTML хэдера заявок
     * @param {int} _headerType тип хэдера (1 - хэдер исходящих заявок, 2 - входящих)
     * @param {int} _countRequests количество заявок
     * @returns {String} HTML-строка
     */
    prepareHeaderRequestsHTML: function(_headerType, _countRequests){
        var idHeader = '', typeString, idSpan, typeShowAll;
        if(_headerType == 1){
            idHeader = "headerOutboxRequests";
            typeString = "Исходящие";
            idSpan = "countOutboxRequestsSpan";
            typeShowAll = "outbox";
        } else if (_headerType == 2){
            idHeader = "headerInboxRequests";
            typeString = "Входящие";
            idSpan = "countInboxRequestsSpan";
            typeShowAll = "inbox";
        }
        var insHTML =
       '<div class="friendsnum" \
            id="'+idHeader+'"> \
          <span style="color: #8eb4e3">'+typeString+' (<span class="countSpan" id="'+idSpan+'">'+_countRequests+'</span>)\
          </span>\
          <span class="groupExpand" id="showAll_'+typeShowAll+'"';
        if(_countRequests <= SETTING_COUNT_FIRST_LOAD_REQUESTS)
            insHTML += 'style="display:none;"';
        insHTML += 
                'onclick="Requests.expandMinimizeRequestsUI('+_headerType+');">\
            Показать всех\
          </span>\
       </div>';
        return insHTML;
    },
    /**
     * Подготавливает HTML заявки
     * @param {int} _id идентификатор контакта заявки
     * @param {String} _friendFI ФИО контакта
     * @param {String} _avatarPath путь к аватарке
     * @param {int} _type тип контакта (1 - исходящая заявка, 2 - новая входящая, 3 - старая входящая)
     * @returns {String} HTML-строка заявки
     */
    prepareRequestHTML: function(_id, _friendFI, _avatarPath, _type){
        var insHTML = 
       '<div class="reciverSmallReq" id="contact_'+PM.idAuth+'_'+_id+'">\
          <div class="reciverImgsmall"><a href="index.php?id='+_id+'"><img src="..'+_avatarPath+'" /></a></div>\
          <div class="reciverNamesmall">\
            <a href="index.php?id='+_id+'"><p>'+_friendFI+'</p></a>\
            <p id="reciverClass"></p>';
            if (_type == 1){
                insHTML +=
              '<p onclick="Requests.cancelOutboxRequestUI('+_id+');" class="manageAdd">\
                Отменить\
              </p>';
            }
            if (_type == 2){
                insHTML += 
              '<p id="add_'+_id+'" \
                class="manageAdd acceptFriendRequest" \
                onclick="Friend.requestAddFriend($(this), '+_id+');">\
                Добавить\
              </p>\
              <p id="notNow_'+_id+'" onclick="Requests.watchRequestUI('+_id+');" class="manageNoAdd">\
                Не сейчас\
              </p>';
            }
            if (_type == 3){
                insHTML += 
              '<p id="add_'+_id+'" \
                class="manageAdd acceptFriendRequestOld" \
                onclick="Friend.requestAddFriend($(this), '+_id+');">\
                Добавить\
              </p>';
            }
            insHTML += 
          '</div>\
          <div class="reciverManage"></div>\
        </div>';
        return insHTML;
    },
    prepareEmptyRequestsHTML: function(_type){
        var typeString;
        if(_type === "outbox")
            typeString = "Исходящие";
        if(_type === "inbox")
            typeString = "Входящие";
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_REQUESTS_EMPTY_REQUESTS.replace(new RegExp("%GROUPNAME",""), typeString)
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return '<div class="friend" id="'+_type+'Requests_empty">'+insHTML+'</div>';
    },
    prepareEmptySearchRequestsHTML: function(_type, _searchKey){
        var typeString;
        if(_type === "outbox")
            typeString = "Исходящие";
        if(_type === "inbox")
            typeString = "Входящие";
         var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_REQUESTS_EMPTY_SEARCH_REQUESTS.replace(new RegExp("%GROUPNAME",""), typeString).replace(new RegExp("%SEARCHKEY",""), _searchKey)
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return '<div class="friend" id="'+_type+'Requests_empty">'+insHTML+'</div>';
    },
    /**
     * Отменить исходящую заявку в друзья
     * @param {int} _idRequest id контакта заявки
     */
    cancelOutboxRequestUI: function(_idRequest) {
        var insHTML = '';
        if(Requests.deleteOutboxRequest(_idRequest)){
            var outboxRequestsDiv = $("div [id ^= outboxRequests]");
            var locationmode = outboxRequestsDiv.attr("id").split("_")[1];
            if(locationmode == "search"){
                searchResultRequests.deleteRequest(1, _idRequest);
            }
            var showmode = outboxRequestsDiv.attr("id").split("_")[2];
            var requests = Requests.getCurrentRequests(1, locationmode, showmode);
            if(requests){
                insHTML += Requests.prepareGroupRequestsHTML(1, requests, locationmode, showmode);
            } else {
                insHTML += Requests.prepareEmptyRequestsHTML("outbox");
            }
            outboxRequestsDiv.remove();
            $("#headerOutboxRequests").after(insHTML);
            var newcountrequest = intval($("#countOutboxRequestsSpan").html()) - 1;
            $("#countOutboxRequestsSpan").html(newcountrequest);
            if(newcountrequest <= SETTING_COUNT_FIRST_LOAD_REQUESTS){
                $("#showAll_outbox").hide();
            }
        }
    },
    /**
     * Помечает заявку как просмотренная и удаляет кнопку "Не сейчас"
     * @param {int} _idRequest идентификатор контакта заявки
     */
    watchRequestUI: function(_idRequest) {
        if(Requests.setRequestOld(_idRequest)){
            $("#notNow_"+_idRequest).remove();
            $("#add_"+_idRequest).removeClass("acceptFriendRequest").addClass("acceptFriendRequestOld");
            Friend.updateCountFriendSidebar("acceptFriendRequest", "acceptFriendRequestOld");
        }
    },
    /**
     * Помечает заявку от контакта по идентификатору, как просмотренную
     * @param {int} _idRequest идентификатор контакта заявки
     * @returns {boolean} помечена ли заявка, как просмотренная
     */
    setRequestOld:function(_idRequest){
        var isSet = false;
        ajax.post_sync(
            "friends/makeRequestOld.php",
            "idLoad="+_idRequest,
            function(response) {
                isSet = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: setRequestOld<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return isSet;
    },
    /**
     * Удалить исходящую заявку из БД
     * @param {type} _idRequest идентификатор контакта заявки
     * @returns {boolean} удалена ли исходящая заявка
     */
    deleteOutboxRequest: function(_idRequest){
        var isDeleted = false;
        ajax.post_sync(
            "friends/friend.php",
            "f=" + _idRequest + "&g=0",
            function(response) {
                isDeleted = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: setRequestOld<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return isDeleted;
    }
};