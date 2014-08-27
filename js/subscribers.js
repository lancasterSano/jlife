$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();
});
var searchResultSubscribers ={ 
    /**
     * Хранит в себе результаты поиска по подписчикам
     */
    result: Array(),
    /**
     * Возвращает всех подписчиков заданного типа
     * @param {String} type тип (1 - на кого я подписан, 2 - кто на меня подписан)
     * @returns {object} массив подписчиков, который можно передавать в другие функции этой страницы
     * (id, FI, avaPath)
     */
    getAllContacts: function(_type){
        if(_type == 1) {
            return searchResultSubscribers.result["subscriptions"];
        } else if(_type == 2){
            return searchResultSubscribers.result["subscribers"];
        }
    },
    /**
     * Возвращает X найденных подписчиков заданного типа
     * @param {String} type тип (1 - на кого я подписан, 2 - кто на меня подписан)
     * @returns {object} массив подписчиков, который можно передавать в другие функции этой страницы
     */
    getPartContacts:function(_type){
        var resultArray;
        if(_type == 1) {
            resultArray = searchResultSubscribers.result["subscriptions"];
        } else if(_type == 2){
            resultArray = searchResultSubscribers.result["subscribers"];
        }
        if(resultArray){
            return resultArray.slice(0, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
        } else return null;
    },
    deleteSubscriber: function(_type, _idSubscriber){
        var resultArray;
        if(_type == 1) {
            resultArray = searchResultSubscribers.result["subscriptions"];
        } else if(_type == 2){
            resultArray = searchResultSubscribers.result["subscribers"];
        }
        for(var i in resultArray){
            if(resultArray[i]["id"] == _idSubscriber){
                var index = resultArray.indexOf(resultArray[i]);
                resultArray.splice(index, 1);
            }
        }
    },
    addSubscriber: function(_type, _subscriber){
        var resultArray;
        if(_type == 1) {
            if(searchResultSubscribers.result["subscriptions"] == null)
                searchResultSubscribers.result["subscriptions"] = Array();
            resultArray = searchResultSubscribers.result["subscriptions"];
                
        } else if(_type == 2){
            if(searchResultSubscribers.result["subscribers"] == null)
                searchResultSubscribers.result["subscribers"] = Array();
            resultArray = searchResultSubscribers.result["subscribers"];
        }
        resultArray.push(_subscriber);
    },
    changeSubscriberState: function(_idSubscriber, newstate){
        for(var i in searchResultSubscribers.result["subscribers"]){
            if(searchResultSubscribers.result["subscribers"][i]["id"] == _idSubscriber){
                searchResultSubscribers.result["subscribers"][i]["type"] = newstate;
            }
        }
    }
};
var Subscriber = {
    addSubscriberFromSubscribersUI: function(_idSubscriber) {
        var responseArray = Subscriber.setSubscriberState(_idSubscriber, "subscribe");
        $("#contact_" + PM.idAuth + "_" + _idSubscriber + "_2").attr("id", "contact_" + PM.idAuth + "_" + _idSubscriber + "_3");
        $("#manageSubscriber_" + _idSubscriber+"_2").attr("id", "manageSubscriber_" + _idSubscriber+"_3");
        $("#manageSubscriber_" + _idSubscriber+"_3").hide();
        var subscriber = {
            id: responseArray.oldsubscriber["id"],
            FI: responseArray.oldsubscriber["FI"],
            avaPath: responseArray.oldsubscriber["avaPath"],
            type: 1
        };
        var subscriptionsDiv = $("div [id ^= subscriptions]");
        var locationmode = subscriptionsDiv.attr("id").split("_")[1];
        var showmode = subscriptionsDiv.attr("id").split("_")[2];
        if(showmode == "empty"){
            showmode = "part";
        }
        var newcountsubscriptions = intval($("#mySubscriptionsSpan").html()) + 1;
        $("#mySubscriptionsSpan").html(newcountsubscriptions);
        if(newcountsubscriptions > SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS){
            $("#showAll_Subscriptions").show();
        }
        if(locationmode == "search"){
            searchResultSubscribers.addSubscriber(1, subscriber);
            searchResultSubscribers.changeSubscriberState(subscriber.id, 3);
        }
        var subscribers = Subscriber.getCurrentSubscribers(1, locationmode, showmode);
        var insHTML = Subscriber.prepareGroupSubscribersHTML(1, subscribers, locationmode, showmode);
        subscriptionsDiv.remove();
        $("#headerSubscriptions").after(insHTML);
        
    },
    expandMinimizeSubsribersUI: function(_type){
        var resultDiv, typeString, typeStringHeader, insHTML = '';
        if(_type == 1){
            typeString = "subscriptions";
            typeStringHeader = "Subscriptions";
            resultDiv = $("div [id ^= subscriptions]");
        } else if (_type == 2){
            typeString = "subscribers";
            typeStringHeader = "Subscribers";
            resultDiv = $("div [id ^= subscribers]");
        }
        var locationmode = resultDiv.attr("id").split("_")[1];
        var oldshowmode = resultDiv.attr("id").split("_")[2], newshowmode;
        if(oldshowmode === "part"){
            newshowmode = "full";
            $("#showAll_"+typeStringHeader).text("Cвернуть");
        }
        else if(oldshowmode === "full"){
            newshowmode = "part";
            $("#showAll_"+typeStringHeader).text("Показать всех");
        }
        var subscribers = Subscriber.getCurrentSubscribers(_type, locationmode, newshowmode);
        insHTML = Subscriber.prepareGroupSubscribersHTML(_type, subscribers, locationmode, newshowmode);
        resultDiv.remove();
        $('#header'+typeStringHeader+'').after(insHTML);
    },
    searchSubscribersUI: function(event){
        if(event != undefined)
            if(event.keyCode !== 13) return;
        $("#searchSubscribersInput").blur();
        var insHTML = "";
        var searchKey = $("#searchSubscribersInput").val();
        Subscriber.loadSubscribersBySearchKey(searchKey);
        var subscriptionsPart = searchResultSubscribers.getPartContacts(1);
        var subscriptionsFull = searchResultSubscribers.getAllContacts(1);
        var subscribersPart = searchResultSubscribers.getPartContacts(2);
        var subscribersFull = searchResultSubscribers.getAllContacts(2);
        if(!subscriptionsPart){
            insHTML += Subscriber.prepareHeaderSubscribersHTML(1, 0, 0);
            insHTML += Subscriber.prepareEmptySearchSubscriptionsHTML(true, searchKey);
        }
        else {
            var subscribercount;
            if(!subscribersPart)
                subscribercount = 0;
            else
                subscribercount = subscribersFull.length;
            insHTML += Subscriber.prepareHeaderSubscribersHTML(1, subscriptionsFull.length, subscribercount);
            insHTML += Subscriber.prepareGroupSubscribersHTML(1, subscriptionsPart, "search", "part");
        }
        if(!subscribersPart){
            insHTML += Subscriber.prepareHeaderSubscribersHTML(2, 0, 0);
            insHTML += Subscriber.prepareEmptySearchSubscribersHTML(true, searchKey);
        } else {
            var subscriptioncount;
            if(!subscriptionsPart)
                subscriptioncount = 0;
            else
                subscriptioncount = subscriptionsFull.length;
                
            insHTML += Subscriber.prepareHeaderSubscribersHTML(2, subscriptioncount, subscribersFull.length);
            insHTML += Subscriber.prepareGroupSubscribersHTML(2, subscribersPart, "search", "part");
        }
        $("#contacts_"+PM.idLoad).empty().append(insHTML);
    },
    addSubscriberFromIndexUI: function(_idSubscriber) {
        var action = $("#subscriber").attr("class");
        var responseArray = Subscriber.setSubscriberState(_idSubscriber, action);
        if(action == "subscribe") {
            $("#subscriber").removeClass("subscribe").addClass("unsubscribe"); 
        }
        else {
            $("#subscriber").removeClass("unsubscribe").addClass("subscribe"); 
        }
    },
    /**
     * Метод, который удаляет подписчика из интерфейса
     * @param {type} _idSubscriber ИД подписчика
     */
    delSubscriberUI: function(_idSubscriber) {
        var responseArray = Subscriber.setSubscriberState(_idSubscriber, "unsubscribe");
        var insHTML = "";
        $("#contact_" + PM.idAuth + "_" + _idSubscriber + "_1").remove();
        var subscriptionsDiv = $("div [id ^= subscriptions]");
        var locationmode = subscriptionsDiv.attr("id").split("_")[1];
        var showmode = subscriptionsDiv.attr("id").split("_")[2];
        if(locationmode == "search"){
            searchResultSubscribers.deleteSubscriber(1, _idSubscriber);
            searchResultSubscribers.changeSubscriberState(_idSubscriber, 2);
        }
        var subscriptions = Subscriber.getCurrentSubscribers(1, locationmode, showmode);
        if(subscriptions){
            insHTML += Subscriber.prepareGroupSubscribersHTML(1, subscriptions, locationmode, showmode);
        } else {
            insHTML += Subscriber.prepareEmptySubscriptionsHTML(true);
        }
        subscriptionsDiv.remove();
        $("#headerSubscriptions").after(insHTML);
        
        $("#contact_" + PM.idAuth + "_" + _idSubscriber + "_3").attr("id", "contact_" + PM.idAuth + "_" + _idSubscriber + "_2");
        $("#manageSubscriber_" + _idSubscriber + "_3").attr("id", "manageSubscriber_" + _idSubscriber+"_2");
        $("#manageSubscriber_" + _idSubscriber+"_2").show();
        var newcountsubscriptions = intval($("#mySubscriptionsSpan").html()) - 1;
        $("#mySubscriptionsSpan").html(newcountsubscriptions);
        if(newcountsubscriptions <= SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS){
            $("#showAll_Subscriptions").hide();
            $("#showAll_Subscriptions").text("Показать всех");
            $("div [id ^= subscriptions]").attr("id", "subscriptions_"+locationmode+"_part");
        }
    },
    setSubscriberState: function(_idSubscriber, _action){
        var responseArray;
        ajax.post_sync(
            "subscriber/setSubscriberState.php",
            "idLoad=" + _idSubscriber + "&action=" + _action,
            function(response){
                responseArray = {
                    oldrelation: response["oldrelation"],
                    newrelation: response["newrelation"],
                    oldsubscriber: response["oldProfile"]
                };
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('function: setSubscriberState<br>\
                        Error textstatus: ' + textStatus + 
                        '.<br>Error thrown: '+ errorThrown + 
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return responseArray;
    },
    /**
     * Метод, который возвращает HTML, когда нет подписок
     * @returns {String} HTML-строка
     */
    prepareEmptySubscriptionsHTML: function(_isParent){
        var insHTML = '';
        if(_isParent){
            insHTML += '<div class="friend" id="subscriptions_std_empty">';
        }
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_SUBSCRIBERS_NO_SUBSCRIPTIONS
        }));
        insHTML += tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        if(_isParent){
            insHTML += '</div>';
        }
        return insHTML;
    },
    prepareEmptySearchSubscriptionsHTML: function(_isParent, _searchKey){
        var insHTML = '';
        if(_isParent){
            insHTML += '<div class="friend" id="subscriptions_search_empty">';
        }
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_SUBSCRIBERS_NO_SEARCH_SUBSCRIPTIONS.replace(new RegExp("%SEARCHKEY",""), _searchKey)
        }));
        insHTML += tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        if(_isParent){
            insHTML += '</div>';
        }
        return insHTML;
    },
    /**
     * Метод, который возвращает HTML, когда нет подписчиков
     * @returns {String} HTML-строка
     */
    prepareEmptySubscribersHTML: function(){
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_SUBSCRIBERS_NO_SUBSCRIBERS
        }));
        return tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
    },
    prepareEmptySearchSubscribersHTML: function(_isParent, _searchKey){
        var insHTML = '';
        if(_isParent){
            insHTML += '<div class="friend" id="subscribers_search_empty">';
        }
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_SUBSCRIBERS_NO_SEARCH_SUBSCRIBERS.replace(new RegExp("%SEARCHKEY",""), _searchKey)
        }));
        insHTML += tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        if(_isParent){
            insHTML += '</div>';
        }
        return insHTML;
    },
    /**
     * Метод, подготавливающий HTML группы подписчиков
     * @param {int} _type тип подписчиков (1 - те, на кого я подписан, 2 - те, кто на меня подписан)
     * @param {object} _subscribers массив подписчиков
     * @param {String} _locationmode режим локации (std - стандартный, search - поиск)
     * @param {String} _showmode режим показа (part - показываем часть, full - показываем всем)
     * @returns {String} HTML-строка группы контактов
     */
    prepareGroupSubscribersHTML: function(_type, _subscribers, _locationmode, _showmode){
        var typeString;
        if(_type == 1) {
            typeString = "subscriptions";
        } else if(_type == 2){
            typeString = "subscribers";
        }
        var insHTML = '<div class="friend" id="'+typeString+'_'+_locationmode+'_'+_showmode+'">';
        for(var i in _subscribers){
            var subscriber = {
                id: _subscribers[i]["id"],
                FI: _subscribers[i]["FI"],
                avaPath: _subscribers[i]["avaPath"],
                type: _subscribers[i]["type"]
            };
            insHTML += Subscriber.prepareSubscriberHTML(subscriber);
        }
        insHTML += "</div>";
        return insHTML;
    },
    /**
     * Подготавливает HTML подписчика
     * @param {object} объект со следующими полями: 
     * _id идентификатор подписчика
     * _FI ФИО подписчика
     * _avaPath путь к аватарке
     * _type тип контакта (1 - я на него подписан, 2 - он на меня подписан, 3 - взаимная подписка)
     * @returns {String} HTML-строка подписчика
     */
    prepareSubscriberHTML: function(subscriber){
        return tmpl($("#subscriber").html(), {contact: subscriber});
    },
    prepareHeaderSubscribersHTML: function(_type, _countsubscriptions, _countsubscribers){
        return tmpl($("#subscriberHeader").html(), 
            {
                headerType: _type,
                countsubscribers: _countsubscribers,
                countsubscriptions: _countsubscriptions
            }
        );
    },
    /**
     * Метод, который возвращает всех подписчиков определенного пользователя определенного типа
     * @param {type} _type тип. 1 - подписки, 2 - подписчики
     * @param {type} idLoad ИД профиля, для которого ищем подписки
     * @returns {object} объект подписчиков, который содержит элементы вида [id, FI, avaPath, type]
     */
    getSubscribersFull:function(_type, idLoad){
        var subscribers;
        ajax.post_sync(
            "friends/getSubscribersFull.php",
            "t="+_type + "&l=" + idLoad,
            function(response) {
                subscribers = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getSubscribersFull<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return subscribers;
    },
    /**
     * Метод, который возвращает первых Х подписчиков определенного пользователя определенного типа
     * @param {type} _type тип. 1 - подписки, 2 - подписчики
     * @param {type} idLoad ИД профиля, для которого ищем подписки
     * @returns {object} объект подписчиков, который содержит элементы вида [id, FI, avaPath, type]
     */
    getSubscribersPart:function(_type, idLoad){
        var subscribers;
        ajax.post_sync(
            "friends/getSubscribersPart.php",
            "t="+_type + "&l=" + idLoad,
            function(response) {
                subscribers = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                    alert('function: getSubscribersPart<br>\
                            Error textstatus: ' + textStatus + 
                            '.<br>Error thrown: '+ errorThrown + 
                            '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return subscribers;
    },
    /**
     * Метод возвращает подписчиков в зависимости, в каком состоянии сейчас находится страница
     * свёрнута/несвёрнута, поиск/дефолтное состояние
     * @param {int} _type 1 - хотим получить, на кого мы подписаны, 2 - хотим наших подписчиков
     * @param {String} _locationmode режим нахождения (поиск - "search"/дефолтное состояние - "std")
     * @param {String} _showmode режим показа (свернут/не свёрнут)
     * @returns {object} массив подписчиков, null, если подписчиков нет
     */    
    getCurrentSubscribers: function(_type, _locationmode, _showmode){
        var subscribers;
        if(_locationmode === "std"){
            if(_showmode == "full"){
                subscribers = Subscriber.getSubscribersFull(_type, PM.idLoad);
            } else if(_showmode == "part"){
                subscribers = Subscriber.getSubscribersPart(_type, PM.idLoad);
            }
        } else if(_locationmode === "search"){
            if(_showmode === "full"){
                subscribers = searchResultSubscribers.getAllContacts(_type);
            } else if(_showmode === "part"){
                subscribers = searchResultSubscribers.getPartContacts(_type);
            }
            if(!subscribers.length){
                subscribers = null;
            }
        }
        return subscribers;
    },
    loadSubscribersBySearchKey: function(searchKey){
        searchResultSubscribers.result = [];
        var response_object;
        ajax.post_sync(
            "friends/getSubscribersBySearch.php",
            "idLoad=" + PM.idLoad + "&searchKey="+searchKey,
            function(response){
                response_object = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
            } 
        );
        if(response_object){
            searchResultSubscribers.result = response_object;
        }
    }
};