$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();
    Contact.assignPopupToGroups();
    Contact.assignPopupToFriends();
});
/**
 * Объект для хранения результатов поиска
 */
var searchResult ={ 
    /**
     * Хранит в себе результаты поиска по группам
     */
    result: Array(),
    /**
     * Возвращает все найденные результаты в заданной группе
     * @param {int} _idGroup идентификатор группы
     * @returns {object} массив найденных друзей, который можно передавать в другие функции этой страницы
     * (id, FI, avaPath)
     */
    getAllContacts: function(_idGroup){
        for(var i in searchResult.result){
            if(searchResult.result[i]["id"] == _idGroup)
                return searchResult.result[i]["friends"];
        }
    },
    /**
     * Возвращает X найденных результаты в заданной группе
     * @param {int} _idGroup идентификатор группы
     * @returns {object} массив первых Х друзей, который можно передавать в другие функции этой страницы
     * (id, FI, avaPath)
     */
    getPartContacts:function(_idGroup){
        for(var i in searchResult.result){
            if(searchResult.result[i]["id"] == _idGroup)
                return searchResult.result[i]["friends"].slice(0, SETTING_COUNT_FIRST_LOAD_FRIENDS);
        }
    },
    /**
     * Удаляет из определенной группы массива найденных друзей определенного друга
     * @param {int} _idGroup
     * @param {int} _idFriend
     */
    deleteFriendFromGroup: function(_idGroup, _idFriend){
        for(var i in searchResult.result){
            if(searchResult.result[i]["id"] == _idGroup){
                var friends = searchResult.result[i]["friends"];
                for(var j in friends){
                    if(friends[j]["id"] == _idFriend){
                        var index = friends.indexOf(friends[j]);
                        friends.splice(index, 1);
                    }
                }
            }
        }
    }
};
var Contact = {
    /**
     * Функция, которая назначает виджеты кнопкам групп
     */
    assignPopupToGroups: function(){
        var groups = $("span.groupPopup"); 
        groups.each(function(){
            var idGroup = $(this).parent().attr("id").split("_")[2];
            $(this).cPopup({
                idWND: "rf_wnd_groupPopup" + idGroup,
                btns: window._NONE,
                location : {
                    position: "default",
                    offset: { left: 0, top: 0, right: 0, bottom: 50 },
                 proportions: { width: undefined, height: undefined },
                    quarters: [4]
                }
            });
        }); 
    },
    assignPopupToFriends: function(){
        var friendsInGroups = $(".reciverManage");
        friendsInGroups.each(function(){
            var idFriend = $(this).parent().attr("id").split("_")[2];
            var idGroup = $(this).parents(".friend").attr("id").split("_")[2];
            $(this).cPopup({
                idWND: "rf_wnd_friendPopup" + idGroup+"_"+idFriend,
                btns: window._NONE,
                location : {
                    position: "default",
                    offset: { left: 0, top: 0, right: 0, bottom: 50 },
                 proportions: { width: undefined, height: undefined },
                    quarters: [4,3]
                }
            });
        }); 
    },
    /**
     * Метод, который загружает всех друзей (кнопка "Показать всех") в зависимости от того, где мы сейчас находимся
     * (результаты поиска или нормальнй режим просмотра друзей)
     * или загрузить первые X контактов ("Свернуть"), где X - константа в конфигурационном файле
     * @param {type} _idGroup идентификатор группы
     */
    expandMinimizeGroupUI: function(_idGroup) {
        var idArray = $("div[id^=group_"+PM.idLoad+"_"+_idGroup+"_]").attr("id"), insHTML = '';
        var locationmode = idArray.split("_")[3];
        var oldshowmode = idArray.split("_")[4], newshowmode;
        if(oldshowmode === "part"){
            newshowmode = "full";
            $("#groupButton_"+_idGroup).text("Cвернуть");
        }
        else if(oldshowmode === "full"){
            newshowmode = "part";
            $("#groupButton_"+_idGroup).text("Показать всех");
        }
        if(locationmode === "std"){
            var friends = Contact.getContactsGroup(_idGroup, newshowmode);
            insHTML = Contact.prepareGroupContactsHTML(_idGroup, friends, true, locationmode, newshowmode);
        } else if(locationmode === "search"){
            var friends;
            if(newshowmode === "full"){
                friends = searchResult.getAllContacts(_idGroup);
            } else if(newshowmode === "part"){
                friends = searchResult.getPartContacts(_idGroup);
            }
            insHTML = Contact.prepareGroupContactsHTML(_idGroup, friends, true, locationmode, newshowmode);
        }
        $('#group_' + PM.idLoad + '_' + _idGroup + '_' + locationmode +'_'+ oldshowmode).remove();
        $("#groupButton_"+_idGroup).parent(".friendsnum").after(insHTML);
        Contact.assignPopupToFriends();
    },
    /**
     * Удаляет выбранную группу (в интерфейсе)
     * @param {int} _idGroup идентификатор группы
     */
    deleteGroupUI: function(_idGroup){
        $('#groupPopupButton_'+_idGroup).cPopup("close");
        var requests = Contact.getUniqueRequests(_idGroup);
        if(requests){
            var result = confirm("В этой группе есть неподтвержденные исходящие заявки. Вы уверены, что хотите удалить группу?");
            if(result){
                var isDeleted = Contact.deleteGroup(_idGroup);
                if(isDeleted){
                    location.href = PM.navigate+'/pages/contacts.php';
                }
            }
        } else {
            var isDeleted = Contact.deleteGroup(_idGroup);
            if(isDeleted){
                location.href = PM.navigate+'/pages/contacts.php';
            }
        }
    },
    /**
     * Удаляет друга из всех групп, отображает в UI
     * @param {int} _idGroup идентификатор группы друга
     * @param {int} _idFriend идентификатор друга
     */
    deleteFriendFromAllGroupsUI: function(_idGroup, _idFriend){
        $("#friendButton_"+_idGroup+"_"+_idFriend).cPopup("close");
        var groups = Contact.getGroupsOfFriend(_idFriend);
        var newclass = Friend.addFriendDB(_idFriend, "0");
        Friend.updateCountFriendSidebar("editFriendRequest", newclass);
        var deletemode = intval($("#groupSelect").val());
        if(deletemode) { //удаляем в конкретной группе
            var idArray = $("div[id^=group_"+PM.idLoad+"_"+_idGroup+"_]").attr("id"), insHTML;
            var locationmode = idArray.split("_")[3];
            var showmode = idArray.split("_")[4];
            if(locationmode == "search"){
                searchResult.deleteFriendFromGroup(_idGroup, _idFriend);
            }
            var friends = Contact.getCurrentFriends(_idGroup, locationmode, showmode);

            var infogroup = Contact.getInfoGroup(_idGroup);
            if(locationmode == "search"){
                if(friends.length)
                    isFriends = true;
                else
                    isFriends = false;
            } else if(locationmode == "std"){
                if(friends)
                    isFriends = true;
                else
                    isFriends = false;
            }
            if(isFriends)
                insHTML = Contact.prepareGroupContactsHTML(_idGroup, friends, false, locationmode, showmode);
            else
                insHTML = Contact.prepareEmptyGroupHTML(_idGroup, infogroup["name"]);
            $('#group_' + PM.idLoad + '_' + _idGroup + '_' + locationmode +'_'+ showmode).empty().append(insHTML);
            $('#groupcountuser_' + _idGroup).html(intval($('#groupcountuser_' + _idGroup).html())-1);
        } else { // удаляем во всех группах
            for (var i in groups){
                var idArray = $("div[id^=group_"+PM.idLoad+"_"+groups[i]+"_]").attr("id"), insHTML;
                var locationmode = idArray.split("_")[3];
                var showmode = idArray.split("_")[4];
                if(locationmode == "search"){
                    searchResult.deleteFriendFromGroup(groups[i], _idFriend);
                }
                var friends = Contact.getCurrentFriends(groups[i], locationmode, showmode);
                var infogroup = Contact.getInfoGroup(groups[i]);
                var isFriends;
                if(locationmode == "search"){
                    if(friends.length)
                        isFriends = true;
                    else
                        isFriends = false;
                } else if(locationmode == "std"){
                    if(friends)
                        isFriends = true;
                    else
                        isFriends = false;
                }
                if(isFriends){
                    insHTML = Contact.prepareGroupContactsHTML(groups[i], friends, false, locationmode, showmode);

                }
                else
                    insHTML = Contact.prepareEmptyGroupHTML(groups[i], infogroup["name"]);

                $('#group_' + PM.idLoad + '_' + groups[i] + '_' + locationmode +'_'+ showmode).empty().append(insHTML);
                $('#groupcountuser_' + groups[i]).html(intval($('#groupcountuser_' + groups[i]).html())-1);
                Contact.assignPopupToFriends();
                if(intval($('#groupcountuser_' + groups[i]).html()) <= SETTING_COUNT_FIRST_LOAD_FRIENDS){
                    $("#groupButton_"+groups[i]).hide();
                }
            }
        }
    },
    /**
     * Метод возвращает друзей группы в зависимости, в каком состоянии сейчас находится страница
     * свёрнута/несвёрнута, поиск/дефолтное состояние
     * @param {int} _idGroup идентификатор группы
     * @param {String} _locationmode режим нахождения (поиск - "search"/дефолтное состояние - "std")
     * @param {String} _showmode режим показа (свернут/не свёрнут)
     * @returns {object} массив друзей
     */
    getCurrentFriends: function(_idGroup, _locationmode, _showmode){
        var friends;
        if(_locationmode === "std"){
            friends = Contact.getContactsGroup(_idGroup, _showmode);
        } else if(_locationmode === "search"){
            if(_showmode === "full"){
                friends = searchResult.getAllContacts(_idGroup);
            } else if(_showmode === "part"){
                friends = searchResult.getPartContacts(_idGroup);
            }
        }
        return friends;
    },
    /**
     * Удаляет друга из группы, отображает в UI
     * @param {int} _idGroup идентификатор группы друга
     * @param {int} _idFriend идентификатор друга
     */
    deleteFriendFromGroupUI: function(_idGroup, _idFriend){
        $("#friendButton_"+_idGroup+"_"+_idFriend).cPopup("close");
        // получаем текущие группы друга
        var firstgroup = true;
        var groups = Contact.getGroupsOfFriend(_idFriend);
        
        // формируем новую строку групп (без учета удаленной группы)
        var groupString = "";
        if(groups.length == 1){
            groupString = "0";
        } else {
            for (var i in groups){
                if(groups[i] == _idGroup){
                    var index = groups.indexOf(groups[i]);
                    groups.splice(index, 1);
                }
            }
            for(var i in groups){
                if(firstgroup){
                    groupString = groups[i];
                    firstgroup = false;
                } else {
                    groupString += "|";
                    groupString += groups[i];
                }
            }
        }
        
        // сохраняем изменения в БД
        var newclass = Friend.addFriendDB(_idFriend, groupString);
        
        // обновляем счетчик в сайдбаре
        if(newclass == "acceptFriendRequest"){
            Friend.updateCountFriendSidebar("editFriendRequest", newclass);
        }
        
        var idArray = $("div[id^=group_"+PM.idLoad+"_"+_idGroup+"_]").attr("id"), insHTML;
        var locationmode = idArray.split("_")[3];
        var showmode = idArray.split("_")[4];
        
        // удаляем друга из массива найденных контактов
        if(locationmode == "search"){
            searchResult.deleteFriendFromGroup(_idGroup, _idFriend);
        }
        
        // получаем друзей в текущей группе по полученным параметрам и проверяем результат на пустоту
        var friends = Contact.getCurrentFriends(_idGroup, locationmode, showmode);
        var isFriends;
        var infogroup = Contact.getInfoGroup(_idGroup);
        if(locationmode == "search"){
            if(friends.length)
                isFriends = true;
            else
                isFriends = false;
        } else if(locationmode == "std"){
            if(friends)
                isFriends = true;
            else
                isFriends = false;
        }
        
        // готовим HTML группы
        if(isFriends)
            insHTML = Contact.prepareGroupContactsHTML(_idGroup, friends, false, locationmode, showmode);
        else
            insHTML = Contact.prepareEmptyGroupHTML(_idGroup, infogroup["name"]);
        
        // вставляем, уменьшаем счетчик друзей группы на 1, скрываем кнопку, если мы находимся в режиме "Все контакты"
        $('#group_' + PM.idLoad + '_' + _idGroup + '_' + locationmode +'_'+ showmode).empty().append(insHTML);
        $('#groupcountuser_' + _idGroup).html(intval($('#groupcountuser_' + _idGroup).html())-1);
        Contact.assignPopupToFriends();
        var deletemode = intval($("#groupSelect").val());
        if(!deletemode){
            if(intval($('#groupcountuser_' + _idGroup).html()) <= SETTING_COUNT_FIRST_LOAD_FRIENDS){
                $("#groupButton_"+_idGroup).hide();
            }
        }
    },
    /**
     * Переходит на страницу отправки сообщения указанному другу
     * @param {int} _idFriend
     */      
    gotoSendMessage: function(_idFriend){
        location.href = PM.navigate + "/pages/messageSend.php?rec="+_idFriend+"&from=new";
    },
    /**
     * Метод перерисовывает группу по нажатию на кнопку "Сохранить" при переименовании группы
     * @param {int} _idGroup идентификатор выбранной группы (на которую щёлкнули)
     * @param {int} _idGroupCombobox идентификатор группы в комбобоксе
     */
    confirmRenameGroupUI: function(_idGroup, _idGroupCombobox){
        var idArray = $("div[id^=group_"+PM.idLoad+"_"+_idGroup+"_]").attr("id");
        var showmode = idArray.split("_")[4];
        var name = $('#groupEditingInput_'+_idGroup).val();
        var countuser = $('#oldcountuser_'+_idGroup).html();
        var isSaved = Contact.setNameGroup(_idGroup, name);
        if(isSaved){
            var isExpandable = false;
            if(_idGroupCombobox == 0)
                isExpandable = true;
            var isPart = true;
            if(showmode == "full"){
                isPart = false;
            }
            var infogroup = Contact.getInfoGroup(_idGroup);
            var insHTML = Contact.prepareHeaderGroupHTML(_idGroup, infogroup["name"], countuser, isExpandable, isPart);
                
            var oldheader = $('#groupTitle_'+PM.idLoad+'_'+_idGroup);
            var nextdiv = oldheader.next();
            oldheader.remove();
            nextdiv.before(insHTML);
            Contact.assignPopupToGroups();
            Contact.refreshComboBoxFriends(_idGroupCombobox);
            if(infogroup["countuser"] == 0){
                nextdiv.before(Contact.prepareEmptyGroupHTML(_idGroup, infogroup["name"]));
                nextdiv.remove();
            }
        }
    },
    /**
     * Метод перерисовывает комбобокс, беря новые данные из базы
     * @param {int} _idGroup идентификатор группы, которая была активна до рефреша
     */
    refreshComboBoxFriends: function(_idGroup){
        var groups = Contact.getGroups();
        if(_idGroup == "0")
            var insHTML = '<option value="0" selected="">Все группы</option>';
        else
            var insHTML = '<option value="0">Все группы</option>';
        for (var i in groups){
            if(groups[i]["id"] == _idGroup)
                insHTML += '<option value="'+groups[i]["id"]+'" selected="">'+groups[i]["name"]+'</option>';
            else
                insHTML += '<option value="'+groups[i]["id"]+'">'+groups[i]["name"]+'</option>';
        }
        $('#groupSelect').empty().append(insHTML);
    },
    /**
     * Метод возвращает группу в исх. состояние по нажатию на кнопку "Отменить" при переименовании группы
     * @param {type} _idGroup идентификатор выбранной группы (на которую щёлкнули)
     * @param {type} idGroupCombobox идентификатор группы в комбобоксе
     */
    cancelRenameGroupUI: function(_idGroup, idGroupCombobox){
        var idArray = $("div[id^=group_"+PM.idLoad+"_"+_idGroup+"_]").attr("id");
        var showmode = idArray.split("_")[4];
        var oldheader = $('#groupTitle_'+PM.idLoad+'_'+_idGroup);
        var nextdiv = oldheader.next();
        var countuser = $('#oldcountuser_'+_idGroup).html();
        oldheader.remove();
        var infogroup = Contact.getInfoGroup(_idGroup);
        var isExpandable = false;
        if(idGroupCombobox == 0)
            isExpandable = true;
        var isPart = true;
        if(showmode == "full"){
            isPart = false;
        }
        nextdiv.before(Contact.prepareHeaderGroupHTML(_idGroup, infogroup["name"], countuser, isExpandable, isPart));
        Contact.assignPopupToGroups();
    },
    /**
     * Метод, который показывает попап управления группой
     * @param {object} obj объект jquery (тот объект, внутри которого вызывается функция)
     * @param {int} _idGroup идентификатор группы
     */
    showGroupPopup: function(obj, _idGroup){
//        obj.PopupShowInstantly(
//        {
//            idWND: "popupGroup_"+_idGroup,
//            reload: true,
//            location : { 
//                position: "default",
//                offset: { left: 0, top: 0, right: 0, bottom: 0 }
//            },
//            create: {
//                btns: window._NONE,
//                fn_getContent: Contact.prepareGroupPopupHTML,
//                data: { idgroup: _idGroup}
//            }
//        });
        
        if(!obj.cPopup('isInit')) {
            obj.cPopup('open', {
                data: { idGroup: _idGroup},
                body: Contact.prepareGroupPopupHTML(_idGroup)
            });
        } else obj.cPopup('open');
    },
    /**
     * Метод, который показывает попап управления другом
     * @param {object} obj объект jquery (тот объект, внутри которого вызывается функция)
     * @param {int} _idGroup идентификатор группы
     * @param {int} _idFriend идентификатор друга
     */
    showContactPopup: function(obj, _idGroup, _idFriend){
//        obj.PopupShowInstantly(
//        {
//            idWND: "popupFriend_"+_idGroup+"_"+_idFriend,
//            reload: true,
//            location : { 
//                position: "default",
//                offset: { left: 0, top: 0, right: 0, bottom: 0 }
//            },
//            create: {
//                btns: window._NONE,
//                fn_getContent: Contact.prepareContactPopupHTML,
//                data: { idgroup: _idGroup, idfriend: _idFriend}
//            }
//        });
        if(!obj.cPopup('isInit')) {
            obj.cPopup('open', {
                data: { idGroup: _idGroup, idFriend: _idFriend},
                body: Contact.prepareContactPopupHTML(_idGroup, _idFriend)
            });
        } else obj.cPopup('open');
    },
    /**
     * Метод, который отрисовывает переименование группы
     * @param {int} _idGroup идентификатор группы
     */
    renameGroupUI: function(_idGroup){
        var infogroup = Contact.getInfoGroup(_idGroup);
        var insHTML = Contact.prepareEditingHeader(_idGroup, infogroup["name"]);
        var countuser = $('#groupcountuser_'+_idGroup).html();
        $('#groupPopupButton_'+_idGroup).cPopup('close');
        var groupTitleDiv = $('#groupTitle_'+PM.idLoad+'_'+_idGroup);
        groupTitleDiv.empty();
        groupTitleDiv.append('<span id="oldcountuser_'+_idGroup+'" style="display:none;">'+countuser+'</span>')
                     .append(insHTML);
        $('#groupEditingInput_'+_idGroup).focus();
    },
    /**
     * Метод, который подготавливает HTML редактирования группы
     * @param {int} _idGroup идентификатор группы
     * @param {String} _name название группы
     * @returns {String} HTML-строка
     */
    prepareEditingHeader: function(_idGroup, _name){
        var idGroupCombobox =  $('#groupSelect').val();
        var insHTML = '<input type="text" id="groupEditingInput_'+_idGroup+'" class="groupEditingInput" value="'+_name+'">\
                         <span class="groupEditing" onclick="Contact.confirmRenameGroupUI('+_idGroup+','+idGroupCombobox+');">Сохранить</span>\
                         <span class="groupEditing" onclick="Contact.cancelRenameGroupUI('+_idGroup+','+idGroupCombobox+');">Отменить</span>';
        return insHTML;
    },
    /**
     * Метод, который формирует HTML попапа управления группой
     * @param {object} объект, который хранит: _idGroup - идентификатор группы
     * @returns {String} HTML-строка
     */
    prepareGroupPopupHTML: function(idGroup){
        var insHTML = '<div class="displayMenu">\
                        <span onclick="Contact.renameGroupUI('+idGroup+');">Переименовать</span>\
                        <span onclick="Contact.deleteGroupUI('+idGroup+');">Удалить</span>\
                    </div>';
        return insHTML;
    },
    /**
     * Метод, который формирует HTML попапа управления другом
     * @param {type} obj объект, который хранит: _idGroup - идентификатор группы, _idFriend - идентификатор друга
     * @returns {String} HTML-строка попапа друга
     */
    prepareContactPopupHTML: function(_idGroup, _idFriend){
        var insHTML = '<div class="menuPopupFriend">\
                        <span onclick="Contact.deleteFriendFromAllGroupsUI('+_idGroup+','+_idFriend+')">Удалить из всех групп</span>\
                        <span onclick="Contact.deleteFriendFromGroupUI('+_idGroup+','+_idFriend+')">Удалить из группы</span>\
                        <span onclick="Contact.gotoSendMessage('+_idFriend+')">Написать сообщение</span>\
                    </div>';
        return insHTML;
    },
    /**
     * Метод, формирующий HTML-группы
     * @param {int} _idGroup идентификатор группы
     * @param {object} _friends массив друзей
     * @param {boolean} _isParentDiv нужно ли друзей завернуть в div
     * @param {String} _locationmode показывает режим отображения. 
     * "std" - если отображаем контакты в обычном режиме, 
     * "search" - если отображаем результаты поиска
     * @param {String} _mode показывает режим отображения. 
     * "part" - если группа или результат поиска из первых Х человек, 
     * "full" - если группа или результат поиска отображается полностью
     * @returns {String} HTML-строка группы и друзей в группе
     */
    prepareGroupContactsHTML: function(_idGroup, _friends, _isParentDiv, _locationmode, _showmode){
        var insHTMLContacts = '';
        if(_isParentDiv)
            insHTMLContacts += '<div class="friend" id="group_'+PM.idLoad+'_'+_idGroup+'_'+_locationmode+'_'+_showmode+'">';
        for (var i in _friends){
            insHTMLContacts += Contact.prepareContactHTML(
                    _friends[i]["id"],
                    _friends[i]["FI"],
                    _friends[i]["avaPath"],
                    _idGroup);
        }
        if(_isParentDiv)
            insHTMLContacts += '</div>';
        return insHTMLContacts;
    },
    /**
     * Формирует HTML пустой группы
     * @param {int} _idGroup идентификатор группы
     * @param {String} _name имя группы
     * @returns {String} HTML-строка
     */
    prepareEmptyGroupHTML: function(_idGroup, _name){
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP_TEXT.replace(new RegExp("%GROUPNAME",""), _name)
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return '<div class="emptyFriends" id="group_'+PM.idLoad+'_'+_idGroup+'_std_empty">'+insHTML+'</div>';
    },
    /**
     * Формирует HTML, когда у пользователя нет групп
     * @returns {String} HTML-строка
     */
    prepareNoGroupsHTML: function(){
        return '<div class="emptyFriends"><p>У Вас нет групп.</p></div>';
    },
    /**
     * Метод, возвращающий HTML контакта (чтобы избежать дублирования кода)
     * @param {int} _id идентификатор контакта
     * @param {String} _friendFI фио контакта
     * @param {String} _avatarPath путь к фото контакта
     * @param {int} _idGroup идентификатор группы контакта
     * @returns {String} HTML-строка контакта
     */
    prepareContactHTML: function(_id, _friendFI, _avatarPath, _idGroup){
        var typeString;
        if(PM.idAuth == PM.idLoad)
            typeString = "reciverSmall";
        else
            typeString = "reciverSmallS";
        var insHTML = '<div class="'+typeString+'" id="contact_'+ PM.idLoad +'_'+ _id + '">\
                        <div class="reciverImgsmall" onclick="Contact.gotoContact('+_id+')">\
                          <img src="..'+ _avatarPath +'"/>\
                        </div>\
                        <div class="reciverNamesmall" onclick="Contact.gotoContact('+_id+')">\
                          <p>' +_friendFI+ '</p>\
                          <p id="reciverClass"></p>\
                        </div>';
        if(PM.idAuth == PM.idLoad)
            insHTML += '<div id="friendButton_'+_idGroup+'_'+_id+'" class="reciverManage" onclick="Contact.showContactPopup($(this),'+ _idGroup+','+_id+');">\
                        </div>';
        else
            insHTML += '<div id="friendButton_'+_idGroup+'_'+_id+'" class="reciverManage"></div>';
                insHTML += '</div>';
        return insHTML;
    },
    gotoContact: function(_id){
        location.href = PM.navigate+"/pages/index.php?id="+_id;
    },
    /**
     * Метод, возвращающий HTML заголовка группы (чтобы избежать дублирования кода)
     * @param {int} _idGroup идентификатор группы
     * @param {String} _name имя группы
     * @param {String} _countfriends количество друзей в группе
     * @param {boolean} _isExpandable режим отображения (если _isExpandable = true, 
     * то кнопка "Показать все" рядом с группой показывается, если контактов много), иначе не показывается ни в каком случае
     * @param {boolean} _isPart является ли хэдер хэдером для части контактов или для всех
     * нужно для корректного отображения кнопок "Показать все" и "Свернуть" 
     * @returns {String} HTML-строка заголовка группы
     */
    prepareHeaderGroupHTML: function(_idGroup, _name, _countfriends, _isExpandable, _isPart){
        var insHTML = '<div class="friendsnum" id="groupTitle_'+PM.idLoad+'_'+_idGroup+'">\
                         <span id="groupName_'+_idGroup+'" class="groupHeader">'+_name+'</span>\
                         <span class="groupHeader">(</span>\
                         <span id="groupcountuser_'+_idGroup+'" class="groupHeader" style="margin: 0">'+_countfriends+'</span>\
                         <span class="groupHeader" style="margin: 0">)</span>';
        if(PM.idAuth == PM.idLoad){
            insHTML += ' <span id="groupPopupButton_'+_idGroup+'" class="groupPopup" onclick="Contact.showGroupPopup($(this), '+_idGroup+');"><img src="'+PM.navigate+'/img/out_pict.png" /></span>';
            if(_isExpandable){
                insHTML += '   <span class="groupExpand" id="groupButton_'+_idGroup+'" ';
                if(_countfriends <= SETTING_COUNT_FIRST_LOAD_FRIENDS)
                    insHTML += '  style="display:none;" ';
                insHTML += '      onclick="Contact.expandMinimizeGroupUI('+_idGroup+');">';
                if(_isPart)
                    insHTML+= '       Показать всех';
                else
                    insHTML+= '       Свернуть';
                insHTML += '</a>';
            }
        }
        insHTML += '</div>';
        return insHTML;
    },
    /**
     * Метод, который возвращает вёрстку, когда поиск не дал результатов
     * @param {int} _type тип ошибки (1 - поиск не дал результатов во всех друзьях, 2 - в одной конкретной группе)
     * @param {String} _nameGroup имя группы (если идет поиск во всех группах, то передавать undefined)
     * @param {String} _searchKey ключ поиска
     * @returns {String} HTML-строка
     */
    prepareEmptySearchResultsHTML: function(_type, _nameGroup, _searchKey){
        var title;
        switch(_type){
            case 1:
                title = MI_CONTACTS_EMPTY_SEARCH_FRIENDS_ALL_GROUPS.replace(new RegExp("%SEARCHKEY",""), _searchKey);
                break;
            case 2:
                title = MI_CONTACTS_EMPTY_SEARCH_FRIENDS_ONE_GROUP.replace(new RegExp("%SEARCHKEY",""), _searchKey).replace(new RegExp("%GROUPNAME",""), _nameGroup);
                break;
        }
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: title
        }));
        return tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
    },
    /**
     * Метод получения массива друзей пользователя в группе
     * @param {int} _idGroup идентификатор группы
     * @param {String} _mode режим отображения. Принимает значение part - если хотим получить 
     * часть контактов в группе
     * full - если хотим получить всех контактов в группе
     * @returns {object} массив друзей, где каждый элемент имеет структуру
     * id, FI, avaPath
     */      
    getContactsGroup: function(_idGroup, _mode){
        var friends;
        ajax.post_sync(
                "friends/getFriendsInGroup.php",
                "l=" + PM.idLoad + "&g=" + _idGroup + "&m=" + _mode,
                function(response){
                    friends = response;
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getContactsGroup<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
                }        
        );
        return friends;
    },
    /**
     * Метод, который возвращает уникальные заявки в указанной группе
     * @param {int} _idGroup
     * @returns {array} requests IDs
     */
    getUniqueRequests: function(_idGroup){
        var friends;
        ajax.post_sync(
                "friends/getUniqueRequests.php",
                "g=" + _idGroup,
                function(response){
                    friends = response;
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getUniqueRequests<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
                }        
        );
        return friends;
    },
    /**
     * Метод, который получает имя группы и количество человек в группе
     * @param {type} _idGroup идентификатор группы
     * @returns {array} resp доступные поля: resp["name"] resp["countuser"]
     */
    getInfoGroup: function(_idGroup){
        var resp;
        ajax.post_sync(
            "friends/selectHeaderGroup.php",
            "l=" + PM.idLoad + "&g=" + _idGroup,
            function(response){
                resp = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('function: getInfoGroup<br>\
                       Error textstatus: ' + textStatus + 
                      '.<br>Error thrown: '+ errorThrown + 
                      '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return resp;
    },
    /**
     * Получает массив всех групп пользователя с его друзьями в виде id, name, friends, countuser. 
     * friends подходит для использования в других функциях
     * @returns {object} массив групп
     */
    getGroups: function(){
        var groups = null;
        ajax.post_sync(
                "friends/selectAllGroups.php",
                "l=" + PM.idLoad,
                function(response){
                    groups = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getGroups<br>\
                       Error textstatus: ' + textStatus + 
                      '.<br>Error thrown: '+ errorThrown + 
                      '<br>Xml: ' + XMLHttpRequest.responseText);
                }
        );
        return groups;
    },
    /**
     * Устанавливает группе новое имя
     * @param {int} _idGroup идентификатор группы
     * @param {int} _name имя группы
     * @returns {boolean} isSaved сохранено ли новое имя группы
     */
    setNameGroup: function(_idGroup, _name){
        var isSaved;
        ajax.post_sync(
            "friends/changeNameGroup.php",
            "g=" + _idGroup + "&n=" + _name,
            function(response){
                isSaved = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: setNameGroup<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return isSaved;
    },
    /**
     * Удаляет группу 
     * @param {int} _idGroup идентификатор группы
     * @returns {boolean} удалена ли группа
     */
    deleteGroup: function(_idGroup){
        var isDeleted;
        ajax.post_sync(
            "friends/deleteGroup.php",
            "g=" + _idGroup,
            function(response){
                isDeleted = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: deleteGroup<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return isDeleted;
    },
    /**
     * Получает идентификаторы групп друга
     * @param {int} _idFriend идентификатор друга
     * @returns {object} массив групп друга
     */
    getGroupsOfFriend: function(_idFriend){
        var groups;
        ajax.post_sync(
            "friends/getGroupsOfFriend.php",
            "f=" + _idFriend,
            function(response){
                groups = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getGroupsOfFriend<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return groups;
    },
    /**
     * Обработчик события изменения значения в комбобоксе групп пользователя (вызывается, 
     * только если мы смотрим своих друзей)
     * @param {int} _idGroup идентификатор группы
     */
    selectGroup: function(_idGroup){ 
        if(_idGroup != 0) {//Выгрузить одну группу по id
            var infogroup = Contact.getInfoGroup(_idGroup);
            var insHTML = Contact.prepareHeaderGroupHTML(_idGroup, infogroup["name"], infogroup["countuser"], false, false);
            var friends = Contact.getContactsGroup(_idGroup, "full");
            if(friends != null)
                insHTML += Contact.prepareGroupContactsHTML(_idGroup, friends, true, "std", "full");
            else
                insHTML += Contact.prepareEmptyGroupHTML(_idGroup, infogroup["name"]);
        } else {
            var insHTML = '';
            var groups = Contact.getGroups();
            if(groups == null){
                insHTML += Contact.prepareNoGroupsHTML();
            } else {
                for(var i in groups){
                    var group = {
                        id: groups[i]["id"],
                        name: groups[i]["name"],
                        countuser: groups[i]["countuser"],
                        friends: groups[i]["friends"]
                    };
                    insHTML += Contact.prepareHeaderGroupHTML(group.id, group.name, group.countuser, true, true);
                    if(group.friends != null)
                        insHTML += Contact.prepareGroupContactsHTML(group.id, group.friends, true, "std", "part");
                    else
                        insHTML += Contact.prepareEmptyGroupHTML(group.id, group.name);
                }
            }
        }
        $("#contacts_"+PM.idLoad).empty().append(insHTML);
        Contact.assignPopupToGroups();
        Contact.assignPopupToFriends();
    },
    /**
     * Функция отображения найденных контактов
     */
    searchFriendsUI: function(event){
        if(event != undefined)
            if(event.keyCode !== 13) return;
        $("#searchInput").blur();
        var insHTML = "";
        var idLoad = PM.idLoad;
        var idGroup;
        if(PM.idAuth === PM.idLoad) { var idGroup = $("#groupSelect :selected").val(); }
        else { idGroup = 0; }
        
        var searchKey = $("#searchInput").val();
        var result_array = Contact.loadFriendsBySearchKey(idLoad, idGroup, searchKey);
        switch(result_array["status"]) {
            case "nofrWithoutGroups":
            case "nofrInAllGroups":
                insHTML += Contact.prepareEmptySearchResultsHTML(1, undefined, searchKey);
                break;
            case "nofrInOneGroup":
                var group = Contact.getInfoGroup(idGroup);
                insHTML += Contact.prepareEmptySearchResultsHTML(2, group["name"], searchKey);
                break;
            case "frInOneGroup":
                insHTML += Contact.prepareHeaderGroupHTML(idGroup, result_array["res_array"][0]["name"], 
                                                            result_array["res_array"][0]["friends"].length, false, false);
                insHTML += Contact.prepareGroupContactsHTML(idGroup, result_array["res_array"][0]["friends"], true, "search", "full");
                break;
            case "frInAllGroups":
                for(var i in result_array["res_array"]) {
                    group = {
                        id: result_array["res_array"][i]["id"],
                        name: result_array["res_array"][i]["name"],
                        countuser: result_array["res_array"][i]["countuser"],
                        friends: result_array["res_array"][i]["friends"]
                    };
                    insHTML += Contact.prepareHeaderGroupHTML(group.id, group.name, group.countuser, true, true);
                    var friends = searchResult.getPartContacts(group.id);
                    insHTML += Contact.prepareGroupContactsHTML(group.id, friends, true, "search", "part");
                }
                break;
            case "frWithoutGroups":
                insHTML += Contact.prepareHeaderGroupHTML(0, "Все друзья", result_array["res_array"].length, false, false);
                insHTML += Contact.prepareGroupContactsHTML(0, result_array["res_array"], true, "search", "full");
                break;
        }
        $("#contacts_"+idLoad).empty();
        $("#contacts_"+idLoad).append(insHTML);
        Contact.assignPopupToGroups();
        Contact.assignPopupToFriends();
    },
    /**
     * Функция загрузки друзей по ключу
     * @param {int} idLoad идентификатор пользователя, на странице которого ищем
     * @param {int} idGroup идентификатор группы в которой ищем (0 - если во всех группах)
     * @param {int} searchKey ключ поиска
     * @returns {object} массив вида status, res_array. status = nofrInAllGroups - 
     * не найдено друзей во всех группах по заданному ключу, по аналогии nofrInOneGroup - в одной группе, 
     * nofrWithoutGroups - не найдено друзей по ключу у другого пользователя. frInAllGroups - найдены друзья во всех группах, 
     * frInOneGroup - в одной группе, frWithoutGroups - у другого пользователя
     */
    loadFriendsBySearchKey: function(idLoad, idGroup, searchKey){
        searchResult.result = [];
        var response_object;
        ajax.post_sync(
                "friends/getFriendsBySearch.php",
                "idLoad="+idLoad+"&idGroup="+idGroup+"&searchKey="+searchKey,
                function(response){
                    response_object = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                        alert('function: loadFriendsBySearchKey<br>\
                                Error textstatus: ' + textStatus + 
                                '.<br>Error thrown: '+ errorThrown + 
                                '<br>Xml: ' + XMLHttpRequest.responseText);
                } 
            );
        if(response_object["res_array"]){
            for(var i in response_object["res_array"]){
                console.log(response_object["res_array"][i]);
                searchResult.result.push(response_object["res_array"][i]);
            }
        }
        return response_object;
    }
};
