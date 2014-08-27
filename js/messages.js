$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();
});
var Messages = {
    //FUNCTION 1. DELETE MESSAGE
    deleteMessage: function (id){
        var key;
        if($("#inboxMsg").hasClass("active")){
            key = "inbox";
        }
        else if($("#outboxMsg").hasClass("active")){
            key = "outbox";
        }
        var messageContainer = $("#messages_"+PM.idAuth);
        var idOwner = PM.idAuth;
        var isDeleted = false;
        //STEP 1. delete ONE message by id from DB
        ajax.post_sync(
                "messages/deleteMessage.php",
                "id=" + id + "&idOwner=" + idOwner, 
                function(response){
                    if(response === "success") { isDeleted = true; }
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                }
        );
        //END STEP 1
        if(isDeleted) {
            //STEP 2. Decrement number of messages in active tab
            var countMessages = $("#count"+key+"MessagesSpan").html();
            countMessages--;
            $("#count"+key+"MessagesSpan").html(countMessages);
            //END STEP 2
            if(key == "inbox"){
                //STEP 3. Update count of unread messages in sidebar
                if($("#message_"+PM.idAuth+"_"+id).hasClass("messageUnR")){
                    var countUnread = $("#comm2").html();
                    countUnread--;
                    if(countUnread > 0 )
                        $("#comm2").html(countUnread);
                    else
                        $("#comm2").html("");
                }
                //END STEP 3
            }
            //STEP 4. Remove message from DOM
            $("#message_"+idOwner+"_"+id).remove();
            //END STEP 4
            //STEP 5. If count all messages in active tab > 0 then load next message else load no messages p
            if(countMessages > 0) {
                Messages.loadMessagesUI(1);
            } else{
                messageContainer.append(Messages.prepareNoMessagesHTML());
            }
            //END STEP 5
        }
    },
    deleteMarked: function(){
        var key;
        if($("#inboxMsg").hasClass("active")){
            key = "inbox";
        }
        else if($("#outboxMsg").hasClass("active")){
            key = "outbox";
        }
        var messageContainer = $("#messages_"+PM.idAuth);
        var delIDs = Messages.getMarkedMessages();
        console.log(delIDs);
        var isDeleted;
        var jsonString = JSON.stringify(delIDs);
        ajax.post_sync("messages/deleteMessages.php",
                       "ids=" + jsonString + "&idOwner=" + PM.idAuth, 
                       function(response){
                           if(response[0] === "success") {
                               isDeleted = true;
                           }
                           if(response[0] === "fail") {
                               isDeleted = false;
                           }
                       },
                       function(XMLHttpRequest, textStatus, errorThrown){
                           alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                       }
        );
        if(isDeleted){
            var countDeleted = delIDs.length;
            var oldCountMessages = $("#count"+key+"MessagesSpan").html();
            var newCountMessages = oldCountMessages - countDeleted;
            $("#count"+key+"MessagesSpan").html(newCountMessages);
            Messages.updateCountUnread(delIDs);
            for(var i in delIDs) {
                    $("#message_" + PM.idAuth + "_"+delIDs[i]).remove();
                }
            if(newCountMessages > 0) {
                Messages.loadMessagesUI(countDeleted);
            }
            else{
                messageContainer.append(Messages.prepareNoMessagesHTML());
            }
        }
    },
    loadMessagesUI: function(countCont){
        var key;
        var messageContainer = $("#messages_"+PM.idAuth);
        if($("#inboxMsg").hasClass("active")){
            key = "inbox";
        }
        else if($("#outboxMsg").hasClass("active")){
            key = "outbox";
        }
        var countMessages = $("#count"+key+"MessagesSpan").html();
        var msg_last = $("div[id^=message_]:last");
        var idMsgLast;
        if(msg_last.length >= 1){
            idMsgLast = msg_last.attr('id').split("_")[2];
        } else { idMsgLast = null; }
        var messages = Messages.loadMessages(idMsgLast, key, countCont);
        var insertHTML = Messages.prepareMessagesUI(messages);
        messageContainer.append(insertHTML);
        var countLoadMessages = messageContainer.children().length;
        if(countLoadMessages == countMessages){ Messages.makeHiddenLoadMoreButton(); }
    },
    prepareMessagesUI: function (messages){
        insertHTML = "";
        var classCSS;
        for (var i in messages){
            if(messages[i].state > 0) classCSS = "messageUnR";
            else classCSS = "message";
            insertHTML += '<div id=\"message_'+PM.idAuth+'_'+messages[i].id+'\" class=\"'+classCSS+'\">\
                               <div class=\"avatar\">\
                                   <a href=\"..'+messages[i].partnerProfileLink+'\"><img src=\"..'+messages[i].partnerAvatarPath+'\"></a>\
                               </div>\
                               <div class=\"messageContent\">\
                                   <div class=\"senderName\">\
                                       <a href=\"..'+messages[i].partnerProfileLink+'\">\
                                           <span>'+messages[i].partnerFI+'</span>\
                                       </a>\
                                       <span class=\"deleteMes\" onclick=\"Messages.deleteMessage(\''+messages[i].id+'\');\">Удалить</span>\
                                   </div>\
                                   <div class=\"messageText\">\
                                       <p><a href=\"..'+messages[i].expandLink+'\">'+messages[i].text+'</a></p>\
                                   </div>\n\
                                   <div class=\"forAct\">\n\
                                       <input type=\"checkBox\" name=\"'+messages[i].id+'\">\
                                   </div>\n\
                                   <div class=\"dateSent\">\n\
                                       <span>'+messages[i].date+'</span>\
                                   </div>\
                               </div>\
                           </div>';
        }
        return insertHTML;
    },
    prepareNoMessagesHTML: function(){
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_MESSAGES_NO_MESSAGES_TEXT
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return insHTML;
    },
    newMessage: function(){
        location.href='../pages/messageSend.php?from=new';
    },
    makeHiddenLoadMoreButton: function (){
        $("#loadMessages_"+PM.idAuth).css("display", "none");
    },
    loadMessages: function(idMsgLast, key , countMessagesToLoad){
        
        var msgJSON;
        var messages;
        ajax.post_sync("messages/loadMessages.php",
                  "idOwner=" + PM.idAuth + "&countCont=" + countMessagesToLoad + "&idMessageLast=" + idMsgLast + "&key=" + key,
                  function(response){
                      console.log(response[1]);
                      messages = response[1];
                      for(var i in response[1]){
                          msgJSON = [{
                              id: response[1][i]["id"],
                              text: response[1][i]["text"],
                              date: response[1][i]["date"],
                              state: response[1][i]["state"],
                              partnerProfileLink: response[1][i]["partnerProfileLink"],
                              partnerFI: response[1][i]["partnerFI"],
                              partnerAvatarPath: response[1][i]["partnerAvatarPath"],
                              expandLink: response[1][i]["expandLink"]
                          }];
                      }
                  },
                  function(XMLHttpRequest, textStatus, errorThrown){
                      
                  }
            );
            return messages;
    },
    getCountMessages: function(){
        var key;
        var count;
        var idOwner = PM.idAuth;
        if($(".active:first").val() === "Входящие"){
            key = "inbox";
        }
        else if($(".active:first").val() === "Исходящие"){
            key = "outbox";
        }
        ajax.post_sync("messages/getCountMessages.php",
                       "key=" + key + "&idOwner" + idOwner,
                       function(response){
                           count = response;
                       },
                       function(XMLHttpRequest, textStatus, errorThrown){
                           
                       }
        );
        return count;
    },
    markAll: function(){
        var messageContainer = $("#messages_"+PM.idAuth);
        var countCurrentMessages = messageContainer.children().length;
        var countChecked = Messages.getCountChecked();
        if(countChecked >= 0 && countChecked != countCurrentMessages) {
            $("input").prop("checked", true);
        }
        else if(countChecked >= 0 && countChecked == countCurrentMessages) {
            $("input").prop("checked", false);
        }
    },
    getCountChecked: function(){
        var countChecked = $("input:checked").length;
        return countChecked;
    },
    getMarkedMessages: function(){
        var messageIDs = new Array();
        var messages = $("input:checked");
        if(Messages.getCountChecked()){
            $.each(messages, function(){
                var checkBox = $(this);
                var id = parseInt(checkBox.attr("name"), 10);
                messageIDs.push(id);
            });
            return messageIDs;
        }
    },
    updateCountUnread: function(delIDs){
        var key;
        if($("#inboxMsg").hasClass("active")){
            key = "inbox";
        }
        else if($("#outboxMsg").hasClass("active")){
            key = "outbox";
        }
        if(key == "inbox"){
            var countNewDeleted = 0;
            for(var i in delIDs){
                if($("#message_"+PM.idAuth+"_"+delIDs[i]).hasClass("messageUnR")){
                    countNewDeleted++;
                }
            }
            var countOldUnread = $("#comm2").html();
            var countNewUnread = countOldUnread - countNewDeleted;
            if(countNewUnread >0 )$("#comm2").html(countNewUnread);
            else $("#comm2").html("");
        }
    }
};