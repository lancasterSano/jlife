$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();
});
var Messages = {
    addMessage: function (idRecepient){
        var text;
        if(document.getElementById("txtArea").innerText){
            text = document.getElementById("txtArea").innerText;
        } else {
            text = document.getElementById("txtArea").innerHTML.replace(/\&lt;br\&gt;/gi,"\n").replace(/(&lt;([^&gt;]+)&gt;)/gi, "");
        }
        console.log(text);
        if(text != "")
        {
            if(idRecepient)
            {
                ajax.post("messages/addMessage.php",
                        "me=" + PM.idAuth + "&recepient=" + idRecepient + "&text=" + text,
                        function(data){
                            if(data[0]=='unknown' && data[1] == null){}
                            else if(data[0] == 'insertedmsg' && data[1] == null) {}
                            else if(data[0] == 'insertedmsg' && data[1] != null) 
                            {
                                location = data[2];
                            }
                        },
                        function(XMLHttpRequest, textStatus, errorThrown){
                            alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                        }
                );
            } else {
                $("#informationMsg").empty().append(Messages.prepareNoRecepientHTML());
            }
        } else {
            $("#informationMsg").empty().append(Messages.prepareEmptyMessageHTML());
        }
    },
    loadFriends: function(){

        //$("#friendSelectBox").empty();
        var ins = "";
        var idCurrentRecepient = $("#friendSelectBox :selected").val();
        console.log(idCurrentRecepient);
        ajax.post_sync(
            "messages/loadFriends.php",
            "me=" + PM.idAuth + "&currentRecepient=" + $("#friendSelectBox :selected").val(),
            function(response){
                switch (response["status"]){
                    case "loadedFrAndRec":
                        ins+= '<option value=\"' + response["recepient"]["id"]+'\">' + response["recepient"]["fio"] + '</option>';
                        for(var i in response["friends"]){
                            friends = {
                                idFriend: response["friends"][i]["id"],
                                fiFriend: response["friends"][i]["fio"]
                            };
                            ins += '<option value=\"' + friends.idFriend+'\">'+friends.fiFriend + '</option>';
                        }
                        break;
                    case "loadedFrWithRec":
                        for(var i in response["friends"]){
                            friends = {
                                idFriend: response["friends"][i]["id"],
                                fiFriend: response["friends"][i]["fio"]
                            };
                            console.log(friends.fiFriend);
                            ins += '<option value=\"' + friends.idFriend+'\">'+friends.fiFriend + '</option>';
                        }
                        console.log(ins);
                        break;
                    case "loadedFr":
                        ins += '<option value=\"0\">Выберите друга...</option>';
                        for(var i in response["friends"]){
                            friends = {
                                idFriend: response["friends"][i]["id"],
                                fiFriend: response["friends"][i]["fio"]
                            };
                            ins += '<option value=\"' + friends.idFriend+'\">'+friends.fiFriend + '</option>';
                        }
                        
                        break;
                    case "loadedRec":
                        ins+= '<option value=\"' + response["recepient"]["id"]+'\">' + response["recepient"]["fio"] + '</option>';
                        break;
                    case "loadedNoFrAndRec":
                        ins += '<option value=\"0\">У вас нет друзей :(</option>';
                        break;
                        
                }
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
            }       
        );
        console.log(ins);
        $("#friendSelectBox").empty();
        $("#friendSelectBox").append(ins);
        
        //$("#friendSelectBox option:first").attr("selected", "selected");
        
        // var select = $('#friendSelectBox'); //.attr('options');
        //options[options.length] = new Option('Foo', 'foo', true, true);
        // $('#friendSelectBox').append("<option value='1'>Foo</option>");
        //console.log(options);
    },
    prepareSendMessage: function(idSender){
        var idRec = $("#friendSelectBox option:selected").val();
        $(".avatar:first").html('<a href=\"../pages/index.php?id='+idRec+'"><img src=\"../img/'+idRec+'.jpg\"></a>');
        if($(".messageText:first").length) $(".messageText:first").remove();
        if($(".sendDate:first").length) $(".sendDate:first").remove();
        $("#btnNewMsg").unbind();
        $("#btnNewMsg").attr("onclick", "");
        $("#btnNewMsg").bind('click',function(event){
            Messages.addMessage(idSender,idRec);
        });
    },
    prepareNoRecepientHTML: function(){
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_MESSAGES_NO_RECEPIENT_TEXT
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return insHTML;
    },
    prepareEmptyMessageHTML: function(){
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_MESSAGES_EMPTY_MESSAGE_TEXT
        }));
        var insHTML = tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        return insHTML;
    },
    newMessage: function(){
        location.href='../pages/messageSend.php?from=new';
    },
    changeRecepient: function(){
        var avaPath;
        $(".messageText:first").empty();
        var idRecepient = $("#friendSelectBox :selected").val();
        ajax.post_sync(
                "messages/loadAvatarPath.php",
                "id="+idRecepient,
                function(response){
                    avaPath = response;
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                }
        );
        $(".avatar:first").html("<a href='../pages/index.php?id="+idRecepient+"'><img src=\".."+avaPath+"\"></a>");
        $("#friendSelectBox").blur();
        $("#btnNewMsg").attr("onclick", "Messages.addMessage(\""+idRecepient+"\");");
        $("#txtArea").focus();
//        console.log(idRecepient);
        
    }
};
$(document).ready(function(){
    $("#txtArea").focus();
});
