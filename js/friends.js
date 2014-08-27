$(document).ready(function(){
    if(!window.tmpl.tmpl_notice_h) window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html());
    if(!window.tmpl.tmpl_notice_hb) window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html());
    if(!window.tmpl.tmpl_notices) window.tmpl.tmpl_notices = $("#notices").html();
    var index = $("div[id^=friends_]");
    index.cPopup({
        confirm: false,
        idWND: "rf_wnd_indexFriendPopup_"+PM.idLoad,
        btns: window._OKCANCEL,
        location : {
            position: "default",
            offset: { left: 1, top: 0, right: 0, bottom: 0 },
         proportions: { width: 300, height: undefined },
            quarters: [3,4]
        }
    });
    
});
var Friend = {
    assignPopupToAllUserSearch: function(){
        var contacts = $("p[id^=user_]"); 
        contacts.each(function(){
            var idFriend = $(this).attr("id").split("_")[1];
            $(this).cPopup({
                idWND: "rf_wnd_inboxRequestPopup_" + idFriend,
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
    /**
     * Метод добавления друга/изменения его статуса
     * @param {int} _idFriend идентификатор друга
     * @param {int} _idGroup идентификаторы групп, в которые добавляю/удаляю
     * @returns {String} новое состояние друга. 
     * editAddRequest - я подал заявку на добавление к нему,
     * sendAddRequest - мы не состоим ни в каких отношениях, 
     * editFriendRequest - мы друзья, 
     * acceptFriendRequest - он подал заявку ко мне, я её не смотрел, 
     * acceptFriendRequestOld - он подал заявку ко мне, я её посмотрел
     */
    addFriendDB: function(_idFriend, _idGroups) {
        var newclass;
        ajax.post_sync(
            "friends/friend.php",
            "f="+_idFriend + "&g="+_idGroups,
            function(response) {
                newclass = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('function: addFriendDB<br>\
                        Error textstatus: ' + textStatus + 
                        '.<br>Error thrown: '+ errorThrown + 
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return newclass;
    },
    //Добавить новую группу
    addGroup: function(){
        var dat = $("[id^=newGrouup_]");
        tmp = dat.attr("id").split("_");
        if(dat.val()!="" && dat.val().trim(" ")!="") {
            ajax.post(
                "friends/addGroup.php","idAuth=" + tmp[1]+ "&idLoad=" + tmp[2] + "&group=" + dat.val(),
                function(data) 
                {
                    //Запускается отоьбражение всех групп, необходимо всегда, т.к. если групп меньше чем 3, то новую группу мы увидим без лишних проблем,
                    //а если уже существующих групп больше чем 3, то новая добавится в конец списка, который, если свернут, её не отобразит...
                    var dat = $("[id^=newGrouup_]");
                    dat.val("");
                    Friend.allGroups(data);
                    // Friend.nameFriend(data)
                },
                function(msg){}
            );
        }
    },
    //Показать все группы пользователя
    allGroups: function(post) {
        ajax.post(
            "friends/allGroups.php","idLoad=" + post + "&idAuth=" + PM.idAuth,
            function(data)
            {
                var ins = "";
                //цикл по всем группам авторизированного пользователя
                for(var i in data["groups"])
                {
                        ins += '<li name = "'+post+'" id="friendGroup_'+ post +'_'+ data["groups"][i]["id"] +'" class="checkItem">\
                                                        <input type="checkbox" value="'+post+'_'+PM.idAuth+'_'+data["groups"][i]["id"]+'" />\
                                                        <span>'+ data["groups"][i]["name"] + ' ('+ data["groups"][i]["countuser"]  +')</span>\
                                                        </li>';
                };
                ge("spisokGroup").empty().prepend(ins);
                //Цикл по группам авторизированного пользователя, в которые уже добавлен выгруженный,
                //Для того чтобы проставить чекбоксы
                for(var i in data["pick"])
                {
                        $("[value="+post+'_' + PM.idAuth + '_' + data["pick"][i]["idgroup"] + "]").attr('checked', 'checked'); 
                }
            }, 
            function(msg){}
        );
    },
    //Вставить во всплывающие окно имя и фамилию выгруженного пользователя
    //группы авторизированного пользователя
    //проставить чекбоксы выбранными те в которых группах уже есть выгруженный пользователь
    prepareAddFriendPopupHTML: function(_idFriend) {
        var ins = "";
        ajax.post_sync(
            "friends/nameFriend.php",
            "idLoad=" + _idFriend + "&idAuth=" + PM.idAuth,
            function(data)
            {
                var res = data.contact.firstname + " " + data.contact.lastname;
                var index = $("div[id^=friends_]");
                var request = $("p[id^=add_"+_idFriend+"]");
                var allusersearch = $("p[id^=user_"+_idFriend+"]");
                var classdata;
                if(index.length){
                    classdata = index.attr("class");
                }
                if(request.length){
                    classdata = request.attr("class").split(" ")[1];
                }
                if(allusersearch.length){
                    classdata = allusersearch.attr("class").split(" ")[1];
                }

                //авторизированный пользователь и выгруженный никак не связаны                
                if(classdata==="sendAddRequest")
                    ins += '<div class="groups">\
                                <span>Добавить<a id = "friendName">' + res + '</a> в контакты?</span>\
                           </div>';
                //Выгруженный пользователь подал заявку авторизированному
                else if(classdata==="acceptFriendRequest")
                    ins += '<div class="groups">\
                               <span>Принять заявку <a id = "friendName">' + res + '</a>на добавление в контакты?</span>\
                            </div>';
                else if(classdata==="acceptFriendRequestOld")
                    ins += '<div class="groups">\
                                <span>Принять заявку <a id = "friendName">' + res + '</a>на добавление в контакты?</span>\
                            </div>';
                else if(classdata==="editAddRequest")
                    ins += '<div class="groups">\
                                <span>Изменить контакт <a id = "friendName">' + res + '</a>?</span>\
                            </div>';
                else if (classdata==="editFriendRequest")
                    ins += '<div class="groups">\
                                <span>Изменить заявку <a id = "friendName">' + res + '</a>на добавление в контакты?</span>\
                            </div>';
                ins += '<ul id = "spisokGroup">';

                var checkedVal = '';
                for(var i in data["groups"])
                {
                    checkedVal = '';
                    for(var y in data["pick"])
                    {
                        if(data["groups"][i]["id"] == data["pick"][y]["idgroup"] ) 
                        { checkedVal = 'checked="checked"'; break;}
                    }
                        ins += '<li id="friendGroup_'+ _idFriend +'_'+ data["groups"][i]["id"] +'" class="checkItem">\
                                <input type="checkbox" ' + checkedVal + ' value="'+_idFriend+'_'+PM.idAuth+'_'+data["groups"][i]["id"]+'"/>\
                                <span>'+ data["groups"][i]["name"] + ' ('+ data["groups"][i]["countuser"]  +')</span>\
                                                        </li>';
                };
                if ( data["count"] > 3)
                {
                    ins += '<li onclick="Friend.allGroups('+ _idFriend +')" class="checkItem"><a>Показать все группы</a></li>';
                }
                ins += '</ul>';
                ins += '<div class="addGroup">\
                            <input type="text" id="newGrouup_' + PM.idAuth + '_' + _idFriend + '"/>\
                            <img src="../img/add.png" onclick="Friend.addGroup();" />\
                        </div>';
            }, 
            function(msg){}
        );
        return ins;
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
    createContent: function(insHTML) {
        return '<div class="friendsCont" style="">' + insHTML + '</div>';
    },
    createOk: function() {
        return '<a><div id="ok" class="toAdd"><span>Добавить</span></div></a>'; 
    },
    createCancel: function() {
        return '<a><div id="cancel" class="notToAdd"><span>Не сейчас</span></div></a>'; 
    },
    /**
     * Добавляет контакт в друзья или изменяет существующий контакт
     */
    addFriendUI: function() {
        var oldclass;
        var indexAddFriendDiv = $("div [id ^= friends]");
        var idFriend = arguments[0].idProfileToAdd; //$(".popup :first").attr("id").split("_")[1];
        var requestsAddFriendDiv = $("#add_"+idFriend);
        var allcontactssearchDiv = $("#user_"+idFriend);
        var checked = $(":checkbox:checked:not(:hidden)");
        if(checked.length != 0) {
            //Если нажали добавить пользователя в контакты и при этом передали хотя бюы одну группу
            var groupString = "", idGroup;
            $(checked).each(function(){
                idGroup = $(this).val().split("_")[2]; 
                //Если это первая группа, то записать в выходную строку id пользователя  и id группы
                if(groupString != "") {
                    //Если это не первая выбранная группа, то дописать её в выходную строку
                    groupString += "|";
                }
                groupString += idGroup;
            });
        }
        else {
            if(requestsAddFriendDiv.length){
                return;
            }
            //Если нажали добавить пользователя в контакты и при этом не передали ни одной группы
            groupString = "0";
        }
        var newclass = Friend.addFriendDB(idFriend, groupString);
        if(indexAddFriendDiv.length){
            oldclass = indexAddFriendDiv.attr("class");
            if(oldclass != newclass){
                indexAddFriendDiv.addClass(newclass).removeClass(oldclass);
            }
        } else if(requestsAddFriendDiv.length){
            oldclass = requestsAddFriendDiv.attr("class").split(" ")[1];
        } else if(allcontactssearchDiv.length){
            oldclass = allcontactssearchDiv.attr("class").split(" ")[1];
            if(oldclass != newclass){
                allcontactssearchDiv.addClass(newclass).removeClass(oldclass);
            }
        }
        Friend.updateCountFriendSidebar(oldclass, newclass);
        
        if(requestsAddFriendDiv.length) {
            requestsAddFriendDiv.cPopup("close");
            var insHTML = '';
            var inboxRequestsDiv = $("div [id ^= inboxRequests]");
            var locationmode = inboxRequestsDiv.attr("id").split("_")[1];
            if(locationmode == "search"){
                searchResultRequests.deleteRequest(2, idFriend);
            }
            var showmode = inboxRequestsDiv.attr("id").split("_")[2];
            var requests = Requests.getCurrentRequests(2, locationmode, showmode);
            if(requests){
                insHTML += Requests.prepareGroupRequestsHTML(2, requests, locationmode, showmode);
            } else {
                insHTML += Requests.prepareEmptyRequestsHTML("inbox");
            }
            inboxRequestsDiv.remove();
            $("#headerInboxRequests").after(insHTML);
            var newcountrequest = intval($("#countInboxRequestsSpan").html()) - 1;
            $("#rf_wnd_inboxRequestPopup_"+idFriend).remove();
            Requests.assignPopupToInboxRequests();
            $("#countInboxRequestsSpan").html(newcountrequest);
            if(newcountrequest <= SETTING_COUNT_FIRST_LOAD_REQUESTS){
                $("#showAll_inbox").hide();
            }
        } else if(indexAddFriendDiv.length){
            indexAddFriendDiv.cPopup("close");
        }
    },
    cancel: function(){},
    
    deleteContactFromRequestsUI: function(idFriend){
        $("#contact_"+PM.idAuth+"_"+idFriend).remove();
        var requestsContainer = $("div [id ^= inboxRequests]");
        var countRequestsNew = intval($("#countInboxRequestsSpan").html()) - 1;
        if(countRequestsNew === 0) {
            requestsContainer.html("<p id=\"norequests\">В группе <b>Входящие</b> нет контактов.</p>");
        }
        $("#countInboxRequestsSpan").html(countRequestsNew);
    },
	
    requestAddFriend : function(el, idFriend){
        el.cPopup('open', {
            data: { idProfileToAdd: idFriend},
            body: Friend.createContent(Friend.prepareAddFriendPopupHTML(idFriend)),
            fn_ok: Friend.addFriendUI,
            fn_cl: Friend.cancel
        });
    },
    /**
     * editAddRequest - я подал заявку на добавление к нему,
     * sendAddRequest - мы не состоим ни в каких отношениях, 
     * editFriendRequest - мы друзья, 
     * acceptFriendRequest - он подал заявку ко мне, я её не смотрел, 
     * acceptFriendRequestOld - он подал заявку ко мне, я её посмотрел
     */
    updateCountFriendSidebar: function(oldclass, newclass){
        var countDiv = $("#countNewRequestsSpan");
        var oldcountrequest = intval(countDiv.html());
        switch(oldclass){
            case "sendAddRequest":
            case "editAddRequest":
            case "acceptFriendRequestOld":
                break;
            case "acceptFriendRequest":
                if(newclass === "editFriendRequest" || newclass === "acceptFriendRequestOld"){
                    if(oldcountrequest === 1){
                        countDiv.html("");
                    } else {
                        countDiv.html( oldcountrequest - 1);
                    }
                }
                break;
                break;
            case "editFriendRequest":
                if(newclass === "acceptFriendRequest"){
                    countDiv.html(oldcountrequest + 1);
                }
                break;
        }
    },
    searchUsersUI: function (event, startId){
        if(event != undefined)
            if(event.keyCode !== 13) return;
        $("#searchInput").blur();
        var insertHTML = "";
        var searchKey = $("#searchInput").val();
        var massUsers = Friend.loadUsersBySearchKey(searchKey, startId);
        var countUsers = Friend.loadCountUsersBySearchKey(searchKey);
        var countSteps = 0;
        
        $(".friend").remove();
        $(".warningMessage").remove();

        insertHTML += "<div class='friend' id=''>";

        if(massUsers != "Empty")
        {
                for(var i in massUsers)
                {
                        user = {
                            id: massUsers[i]["id"],
                            fi: massUsers[i]["fi"],
                            pathAvatar: massUsers[i]["pathAvatar"],
                            classMod: massUsers[i]["classMod"]
                        };
                    insertHTML += "\
                    <div class='reciverSmall' id='idAuth_"+PM.idAuth+"_idLoad_"+user.id+"'>\
                      <a href='index.php?id="+user.id+"'>\
                        <div class='reciverImgsmall'><img src='.."+user.pathAvatar+"'/></div>\
                      </a>\
                      <div class='reciverNamesmall'>\
                        <p><a href='index.php?id="+user.id+"'>"+user.fi+"</a></p>\
                        <p id='reciverClass'><!-- --></p>";
                    if(user.id != PM.idAuth)
                        insertHTML +="\
                        <p id='user_"+user.id+"' class='manageAdd "+user.classMod+"' onclick='Friend.requestAddFriend($(this),"+user.id+");'>Добавить</p>";
                    insertHTML +="\
                      </div>\
                    </div>";
                    countSteps++;
                    if(countSteps == 6)
                    {
                        var lastIdUser = user.id;
                    }
                }
            insertHTML += "</div>";
            $("#countOutboxRequestsSpan").text(countUsers);
            $(".content").append(insertHTML);
            Friend.assignPopupToAllUserSearch();
            var countLoadedUsers = $(".reciverSmall").length;
            if(countLoadedUsers < countUsers)
            {
                var insertHTMLWarning = "";
                insertHTMLWarning += "<div class='warningMessage' id='lastIdUser_"+lastIdUser+"' onclick='Friend.loadMoreUsersUI("+lastIdUser+")'>\
                                    <div class='centeredm'><span>Выгрузить еще...</span></div>\
                                </div>";
                $(".content").append(insertHTMLWarning);
            }
        }
        else
        {
            insertHTML += Friend.prepareEmptySearchResultHTML(searchKey);
            insertHTML += "</div>";
            
            $("#countOutboxRequestsSpan").text(0);
            $(".content").append(insertHTML);
            Friend.assignPopupToAllUserSearch();
        }
    },
    loadMoreUsersUI: function (lastIdUser){
        var insertHTML = "";
        var searchKey = $("#searchInput").val();
        var massUsers = Friend.loadUsersBySearchKey(searchKey, lastIdUser);
        var countSteps = 0;
            
        if(massUsers != "Empty")
        {
            countUsers = Object.getOwnPropertyNames(massUsers).length;
                for(var i in massUsers)
                {
                        user = {
                            id: massUsers[i]["id"],
                            fi: massUsers[i]["fi"],
                            pathAvatar: massUsers[i]["pathAvatar"],
                            classMod: massUsers[i]["classMod"]
                        };
                insertHTML += "<div class='reciverSmall' id='idAuth_"+PM.idAuth+"_idLoad_"+user.id+"'>\
                                        <a href='index.php?id="+user.id+"'>\
                                            <div class='reciverImgsmall'>\
                                                <img src='.."+user.pathAvatar+"'/>\
                                            </div>\
                                        </a>\
                                        \
                                        <div class='reciverNamesmall'>\
                                            <p><a href='index.php?id="+user.id+"'>"+user.fi+"</a></p>\
                                            <p id='reciverClass'><!-- --></p>";
                                            if(user.id != PM.idAuth)
                                                insertHTML +="<p id='user_"+user.id+"' class='manageAdd "+user.classMod+"' onclick='Friend.requestAddFriend($(this), "+user.id+");'>Добавить</p>";
                            insertHTML +="</div>\
                                        \
                                    </div>";
                    countSteps++;
                    if(countSteps == 6)
                    {
                        var lastIdUser = user.id;
                        break;
                    }
                }

            $(".friend").append(insertHTML);
            Friend.assignPopupToAllUserSearch();
            $(".warningMessage").attr('id', "lastIdUser_"+lastIdUser+"");
            $(".warningMessage").attr('onclick', "Friend.loadMoreUsersUI("+lastIdUser+")");
            var countLoadedUsers = $(".reciverSmall").length;
            var countSearchedUsers = $("#countOutboxRequestsSpan").text();
            
            if(countLoadedUsers == countSearchedUsers)
                $(".warningMessage").remove();
        }
    },
    loadUsersBySearchKey: function (searchKey, startId){
        var massUsers;
        ajax.post_sync("friends/getUsersBySearch.php", "searchKey="+searchKey+"&startId="+startId,
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
    }
};