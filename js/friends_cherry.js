var Friend = {
    //Добавить пользователя в друзья 
    addFriendDB: function(post) {
        var tmp = post.split("_");
        var oldClass = $("#friends_"+tmp[0]).attr("class");
        var newClass;
        var isOld;
        ajax.post_sync("friends/friend.php","idLoad=" + tmp[0] + "&idAuth=" + tmp[1] + "&idGroup=" + tmp[2],
            function(response) {
            //Сменить класс кнопки 
            	if($("#friends_"+tmp[0]).length) {
                    $("#friends_"+tmp[0]).removeAttr('class').addClass(response);
                }
                newClass = response;
            },
            function(msg){}
        );
        var isOld = $("#"+tmp[0]+"_77").length;
        switch(newClass){
            case "acceptFriendRequest":
                if(oldClass == "editFriendRequest"){
                    Friend.updateCountFriendSidebar("increment");
                } 
                break;
            case "editFriendRequest":
                if(oldClass == "acceptFriendRequest"){
                    Friend.updateCountFriendSidebar("decrement");
                } 
                break;
        }
    },
    //Удалить пользователя из друзей
    remFriendDB: function(post) {
        var tmp = post.split("_");
        ajax.post("friends/friend.php","idLoad=" + tmp[1] + "&idAuth=" + PM.idAuth + "&idGroup=0" ,
            function(data) {
                ge("contact_" + PM.idAuth + "_" +  tmp[1] ).remove();
//            	console.log(ge("group_"+tmp[0]+"_1").children().length);
                if(ge("group_"+tmp[0]+"_1").children().length==0) {
                    ins="";
                    ins += '<p id="norequests"> В группе <b>Исходящие</b> нет контактов. </p>';
                    ge("group_"+tmp[0]+"_1").append(ins);
            	}
                var countOutboxRequests = ge("countOutboxRequestsSpan").html();
                countOutboxRequests--;
                ge("countOutboxRequestsSpan").html(countOutboxRequests);
            	//Удалить пользователя из интерфейса, необходимо дописать вставку html шаблона, когда этот друг удаляется последним из списка
            },
            function(msg){}
        );
    },
    //Указать эту заявку в друзья как "просмотренная"
    oldRequest: function(post) {
        var tmp = post.split("_");
        ajax.post("friends/friend1.php","idLoad=" + post + "&idAuth=" + PM.idAuth,
            function(data) {
            //Удалить кнопку "Не сейчас"
            ge(post + "_77").remove();
            $("#friends_"+post).removeAttr("class").addClass("acceptFriendRequestOld");
            Friend.updateCountFriendSidebar("decrement");
            },
            function(msg){}
        );            
    },
        //Добавить новую группу
    addGroup: function(){
		var dat = $("[id^=newGrouup_]");
		tmp = dat.attr("id").split("_");
		if(dat.val()!="" && dat.val().trim(" ")!="")
		{
			ajax.post("friends/addGroup.php","idAuth=" + tmp[1]+ "&idLoad=" + tmp[2] + "&group=" + dat.val(),
            function(data) 
            {
            	//Запускается отоьбражение всех групп, необходимо всегда, т.к. если групп меньше чем 3, то новую группу мы увидим без лишних проблем,
            	//а если уже существующих групп больше чем 3, то новая добавится в конец списка, который, если свернут, её не отобразит...
            	var dat = $("[id^=newGrouup_]");
            	dat.val("");
            	Friend.allGroups(data)
            	// Friend.nameFriend(data)
            },
            function(msg){}
        	);
		}

	},

    //Показать все группы пользователя
    allGroups: function(post) {
            ajax.post("friends/allGroups.php","idLoad=" + post + "&idAuth=" + PM.idAuth,
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
    nameFriend: function(post) {
            var res = null;
    var ins = "";
    ajax.post_sync("friends/nameFriend.php","idLoad=" + post + "&idAuth=" + PM.idAuth,
        function(data)
        {
            var res = data.contact.firstname + " " + data.contact.lastname;
            var classdata = $("div[id^=friends_]").attr("class");

            //авторизированный пользователь и выгруженный никак не связаны                
            if(classdata==="sendAddRequest")
                ins += '<div class="groups">\
                            <span>Добавить<a id = "friendName">' + res + '</a> в контакты?</span>\
                       </div>';
            //Выгруженный пользователь подал заявку авторизированному
            else if(classdata==="acceptFriendRequest")
                ins += '<div class="groups">\
                           <span>Принять заявку <a id = "friendName">' + res + '</a>на добавление в контакты?</span>\
                        </div>\
                        <ul id = "spisokGroup">' + post.FIO + '</ul>';
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
                    ins += '<li id="friendGroup_'+ post +'_'+ data["groups"][i]["id"] +'" class="checkItem">\
                            <input type="checkbox" ' + checkedVal + ' value="'+post+'_'+PM.idAuth+'_'+data["groups"][i]["id"]+'"/>\
                            <span>'+ data["groups"][i]["name"] + ' ('+ data["groups"][i]["countuser"]  +')</span>\
                                                    </li>';
            };
            if ( data["count"] > 3)
            {
                ins += '<li onclick="Friend.allGroups('+ post +')" class="checkItem"><a>Показать все группы</a></li>';
            }
            ins += '</ul>';
            ins += '<div class="addGroup">\
                        <input type="text" id="newGrouup_' + PM.idAuth + '_' + post + '"/>\
                        <img src="../img/add.png" onclick="Friend.addGroup();" />\
                    </div>';
        }, 
        function(msg){}
    );
    return ins;
},
    createContent: function(data) {
            return '<div class="friendsCont" style="">' + data + '</div>';
     },
    createOk: function() { return '<a><div id="ok" class="toAdd"><span>Добавить</span></div></a>'; },
    createCancel: function() { return '<a><div id="cancel" class="notToAdd"><span>Не сейчас</span></div></a>'; },
	
    ok: function() { 
        var wnd = arguments[0];
        var checked = $(":checkbox:checked");
        //Если нажали добавить пользователя в контакты и при этом передали хотя бюы одну группу
        if(checked.length != 0) {
            var idS = "";
            var idString = "";
            var chck = "";
            var chck1 = "";
            var idFriend;
            var idOwner;
             var idGroup;
            //Выбрать все отмеченные чекбоксы
            $(checked).each(function(){
                    //взять значение отмеченного чекбокса
                idString = $(this).val(); 
                //получить id группы которой соответствует выбранный чекбокс
                idS = idString.split("_");
                idFriend = idS[0];
                idOwner = idS[1];
                idGroup = idS[2];
                //Если это первая группа, то записать в выходную строку id пользователя  и id группы
                chck1 = idFriend+"_"+idOwner+"_";
                if(chck != "") {
                        //Если это не первая выбранная группа, то дописать её в выходную строку
                        chck +="|";
                }
                chck += idGroup;
            });
            Friend.addFriendDB(chck1+chck);
            Friend.deleteContactFromRequestsUI(idFriend);
            for (var i = 0; i < 99999999; i++) {j=i*i; };
            console.log("busy end");
            // return 'ok end';
            // if (callback) {callback(); }
        }
        else { 		
            //Если нажали добавить пользователя в контакты и при этом не передали ни одной группы
            //получить id пользователя
            var tmp = $("li[id^=friendGroup_]").attr("id");
            if(tmp != null) {
                tmp = tmp.split("_");
                //Удалить пользователя из контактов
                Friend.addFriendDB(tmp[1]+"_"+PM.idAuth+"_"+0);
            }
        }
    },
    cancel: function(){

    },
    
    deleteContactFromRequestsUI: function(idFriend){
        $("#contact_"+PM.idAuth+"_"+idFriend).remove();
        var requestsContainer = $("#group_"+PM.idAuth+"_2");
        var countRequests = requestsContainer.children().length;
        if(!countRequests) {
            requestsContainer.html("<p id=\"norequests\">В группе <b>Входящие</b> нет контактов.</p>");
        }
        $("#countInboxRequestsSpan").html(countRequests);
    },
	
    requestAddFriend : function(e, post){
        var tmp = post.split("_");
        var idFriend = tmp[1];
        var el = $("div[id=" + post + "]");

        var test_2 = $("div[id^=friends_");
        test_2.bind("createPopup", Friend._fcl );
        
        test_2.bind("startInitializingPopup", Friend._fcl );
        test_2.bind("endInitializedPopup", Friend._fcl );
        
        test_2.bind("loadingPopup", Friend._fcl );
        test_2.bind("startPositionedPopup", Friend._fcl );
        test_2.bind("endPositioningPopup", Friend._fcl );
        test_2.bind("loadedPopup", Friend._fcl );
                
        test_2.bind("closingPopup", Friend._fcl );
        test_2.bind("closedPopup", Friend._fcl );

        test_2.bind("beforeShowPopup", Friend._fcl );
        test_2.bind("afterShowPopup", Friend._fcl );
        
        test_2.bind("pressOkPopup", Friend._fcl );
        test_2.bind("pressCancelPopup", Friend._fcl );


        console.log('_____________');
        if(!test_2.cDialog('isInit')) {
            test_2.cDialog('open', {
                data: { idProfileToAdd: idFriend},
                body: Friend.createContent(Friend.nameFriend(idFriend)),
                fn_ok: Friend.ok,
                fn_cl: Friend.cancel
            });
        } else test_2.cDialog('open');

        // e.stopPropagation();
    },
    _fcl: function (e) {
        console.log(new Date(), e.type);
        $(e.target).off(e.type);

    },
    requestAddFriendFromContact : function(post){
        var tmp = post.split("_");
        var idFriend = tmp[1];
        // var el = $("div[id=" + tmp[0] +"_"+ tmp[1] + "]");
        var el = $("p[id=" + post + "]");

        // el.PopupShowInstantly({
        //     idWND: "requestFriendWND",
        //     reload: true,
        //     location : {
        //         position: "default",
        //         parentDependence: $("div.mainfield"),
        //         quarters: [3,4]
        //     },
        //     create: {
        //         btns: window._OKCANCEL,
        //         fn_getContent: Friend.createContent, 
        //         // fn_getBtnOk: Friend.createOk,
        //         // fn_getBtnCancel: Friend.createCancel,
        //         data: { FIO: Friend.nameFriend(idFriend) }
        //     },
        //     events: {
        //         fn_ok: Friend.ok,
        //         fn_cancel: Friend.cancel
        //     },
        //     handler: {
        //         aftershow: function(args) { console.log("show " + args.id); },
        //         afterclose: function(args) { console.log("close " + args.id); }
        //     }
        // });
    },

    updateCountFriendSidebar: function(state){
        var oldCountFriendRequests = $("#countNewRequestsSpan").html();
        var newCountFriendRequests;
        if(oldCountFriendRequests == 1 && state == "decrement"){
            $("#countNewRequestsSpan").html("");
        } else if(oldCountFriendRequests == "" && state == "increment"){
            $("#countNewRequestsSpan").html("1");
        } else if(oldCountFriendRequests == 1 && state == "increment"){
            $("#countNewRequestsSpan").html("2");
        } else if (oldCountFriendRequests > 1) {
            if (state == "decrement") {
                oldCountFriendRequests--;
            } else if (state == "increment"){
                oldCountFriendRequests++;
            }
            newCountFriendRequests = oldCountFriendRequests;
            $("#countNewRequestsSpan").html(newCountFriendRequests);
        }
    },
    searchRequestsUI: function (){
        var insHTML = "";
        var idAuth = PM.idAuth;
        var searchKey = $("#searchRequestsInput").val();
        var result_array = Friend.loadRequestsBySearchKey(idAuth, searchKey);
        console.log(result_array);
        $("#contacts_"+idAuth).empty();
        
        /*FILL insHTML*/
        if(!result_array["outboxRequests"]){
        insHTML += '<div class="friendsnum" id="groupTitle_'+idAuth+'_1">\
                        <a href="#"><span>Исходящие</span></a>\
                        <a href="#"><img src="../img/secure.png"></a>\
                    </div>\
                    <p>Поиск не дал результатов</p>';
        }
        else {
            insHTML += '<div class="friendsnum" id="groupTitle_'+idAuth+'_1">\
                <a href="#"><span>Исходящие (<span class="countSpan" id="countOutboxRequestsSpan">'+result_array["outboxRequests"].length+'</span>)</span></a>\
                <a href="#"><img src="../img/secure.png"></a>\
            </div>\
            <div class="friend" id="group_'+idAuth+'_1">';
            for(var i in result_array["outboxRequests"]){
                var contact = {
                    id: result_array["outboxRequests"][i]["id"],
                    FI: result_array["outboxRequests"][i]["FI"],
                    avaPath: result_array["outboxRequests"][i]["avaPath"]
                };
                insHTML += "\
                <div class=\"reciverSmall\" id=\"contact_"+idAuth+"_"+contact.id+"\">\
                    <a href=\"index.php?id="+contact.id+"\">\
                        <div class=\"reciverImgsmall\">\
                            <img src=\".."+contact.avaPath+"\">\
                        </div>\
                    </a>\
                    <div class=\"reciverNamesmall\">\
                        <p><a href=\"index.php?id="+contact.id+"\">"+contact.FI+"</a></p>\
                        <p id=\"reciverClass\"><!-- --></p>";
            insHTML += '<p onclick="Friend.remFriendDB(\''+idAuth+'_'+contact.id+'\');" class="manageAdd">Отменить</p>';
        insHTML += "</div>\
                    <div class=\"reciverManage\">\
                        <!--<img src=\"../img/lamp.png\">-->\
                    </div>\
                </div>";
            }
            insHTML += "</div>";
        }
        if(!result_array["inboxRequestsNew"] && !result_array["inboxRequestsOld"]){
        insHTML += '<div class="friendsnum" id="groupTitle_'+idAuth+'_1">\
                        <a href="#"><span>\Входящие</span></a>\
                        <a href="#"><img src="../img/secure.png"></a>\
                    </div>\
                    <p>Поиск не дал результатов</p>';
        } else {
            if(result_array["inboxRequestsNew"]){
                var countInboxNew = result_array["inboxRequestsNew"].length;
            }
            else {countInboxNew = 0;}
            
            if(result_array["inboxRequestsOld"]){
                var countInboxOld = result_array["inboxRequestsOld"].length;
            }
            else {countInboxOld = 0;}
            var countInbox = countInboxOld + countInboxNew;
                
            insHTML += '<div class="friendsnum" id="groupTitle_'+idAuth+'_2">\
                <a href="#"><span>Входящие (<span class="countSpan" id="countInboxRequestsSpan">'+ countInbox +'</span>)</span></a>\
                <a href="#"><img src="../img/secure.png"></a>\
            </div>\
            <div class="friend" id="group_'+idAuth+'_2">';
            if(result_array["inboxRequestsNew"]){
                for(var i in result_array["inboxRequestsNew"]){
                    var contact = {
                        id: result_array["inboxRequestsNew"][i]["id"],
                        FI: result_array["inboxRequestsNew"][i]["FI"],
                        avaPath: result_array["inboxRequestsNew"][i]["avaPath"]
                    };
                    insHTML += "\
                    <div class=\"reciverSmall\" id=\"contact_"+idAuth+"_"+contact.id+"\">\
                        <a href=\"index.php?id="+contact.id+"\">\
                            <div class=\"reciverImgsmall\">\
                                <img src=\".."+contact.avaPath+"\">\
                            </div>\
                        </a>\
                        <div class=\"reciverNamesmall\">\
                            <p><a href=\"index.php?id="+contact.id+"\">"+contact.FI+"</a></p>\
                            <p id=\"reciverClass\"><!-- --></p>";
                insHTML += '<div id="friends_'+contact.id+'" class="acceptFriendRequest" onclick="Friend.requestAddFriend(\'friends'+'_'+contact.id+'\');">Добавить</div>\
                            <p></p>\
                            <p class="manageNoAdd" onclick="Friend.oldRequest(\''+contact.id+'\');" id="'+contact.id+'_77">Не сейчас</p>';
            insHTML += "</div>\
                        <div class=\"reciverManage\">\
                            <!--<img src=\"../img/lamp.png\">-->\
                        </div>\
                    </div>";
                }
            }
            if(result_array["inboxRequestsOld"]){
                for(var i in result_array["inboxRequestsOld"]){
                    var contact = {
                        id: result_array["inboxRequestsOld"][i]["id"],
                        FI: result_array["inboxRequestsOld"][i]["FI"],
                        avaPath: result_array["inboxRequestsOld"][i]["avaPath"]
                    };
                    insHTML += "\
                    <div class=\"reciverSmall\" id=\"contact_"+idAuth+"_"+contact.id+"\">\
                        <a href=\"index.php?id="+contact.id+"\">\
                            <div class=\"reciverImgsmall\">\
                                <img src=\".."+contact.avaPath+"\">\
                            </div>\
                        </a>\
                        <div class=\"reciverNamesmall\">\
                            <p><a href=\"index.php?id="+contact.id+"\">"+contact.FI+"</a></p>\
                            <p id=\"reciverClass\"><!-- --></p>";
                insHTML += '<div id="friends_'+contact.id+'" class="acceptFriendRequestOld" onclick="Friend.requestAddFriend(\'friends'+'_'+contact.id+'\');">Добавить</div>\n\
                            <p></p>';
            insHTML += "</div>\
                        <div class=\"reciverManage\">\
                            <!--<img src=\"../img/lamp.png\">-->\
                        </div>\
                    </div>";
                }
            }
            insHTML += "</div>";
        }
        $("#contacts_"+idAuth).append(insHTML);
    },
    loadRequestsBySearchKey: function (idAuth, searchKey){
        var response_object;
        ajax.post_sync(
                "friends/getRequestsBySearch.php",
                "idAuth="+idAuth+"&searchKey="+searchKey,
                function(response){
                    response_object = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
                } 
            );
        return response_object;
    },
    searchSubscribersUI: function (){
        var insHTML = "";
        var idLoad = PM.idLoad;
        var searchKey = $("#searchSubscribersInput").val();
        var result_array = Friend.loadSubscribersBySearchKey(idLoad, searchKey);
        console.log(result_array);
        $("#contacts_"+idLoad).empty();
        /*FILL insHTML*/
        $("#contacts_"+idLoad).append(insHTML);
    },
    loadSubscribersBySearchKey: function (idLoad, searchKey){
        var response_object;
        ajax.post_sync(
                "friends/getSubscribersBySearch.php",
                "idLoad="+idLoad+"&searchKey="+searchKey,
                function(response){
                    response_object = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
                } 
            );
        return response_object;
    },

    searchUsersUI: function (startId){
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
                            pathAvatar: massUsers[i]["pathAvatar"]
                        };
                insertHTML += " <div class='reciverSmall' id='idAuth_"+PM.idAuth+"_idLoad_"+user.id+">\
                                    <div>\
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
                                            insertHTML +="<p id='user_"+user.id+"' class='manageAdd acceptFriendRequestOld' onclick='Friend.requestAddFriendFromContact(\"user_"+user.id+"\");'>Добавить</p>";
                            insertHTML +="</div>\
                                        \
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
            insertHTML += "Поиск не дал результатов";
            insertHTML += "</div>";
            
            $("#countOutboxRequestsSpan").text(0);
            $(".content").append(insertHTML);
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
                            pathAvatar: massUsers[i]["pathAvatar"]
                        };
                insertHTML += " <div class='reciverSmall' id='idAuth_"+PM.idAuth+"_idLoad_"+user.id+">\
                                    <div>\
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
                                            insertHTML +="<p id='user_"+user.id+"' class='manageAdd acceptFriendRequestOld' onclick='Friend.requestAddFriendFromContact(\"user_"+user.id+"\");'>Добавить</p>";
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
        ajax.post_sync("friends/getCountUsersBySearch.php", "searchKey="+searchKey,
                function(response){
                    countUsers = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
                } 
            );
        return countUsers;
    },
};