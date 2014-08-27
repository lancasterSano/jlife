$(document).ready(function() {
  $(window).bind('resize', function(e){ console.log('resize out body'); });

    // var supplementProfileData = null;
    // ajax.post_sync("supplementProfile.php", "idOwner="+PM.idAuth,
    //     function(response){ supplementProfileData = response; },
    //     function(XMLHttpRequest){ }
    // );

    // // проверка на лицензию
    // if(supplementProfileData.profile.valid!="1" && supplementProfileData.profile.acceptlicense==null) { $('div.footer ul li a#jLifeUserAgreement').click(); }

    // // кол-во непрочитаных  сообщений и заявок в друзья
    // var countUnread = supplementProfileData['countUnread'], 
    //     countNewFriends = supplementProfileData['countFriendRequests'],
    //     countMessagesDiv = $("#comm2"),
    //     countFriendRequestsDiv = $("#countNewRequestsSpan");

    // if(countUnread > 0) { countMessagesDiv.html(countUnread); } else countMessagesDiv.html("");
    // if(countNewFriends > 0) {
    //     countFriendRequestsDiv.html(countNewFriends);
    //     // меняем ссылку на добавление контактов(contactsadd.php) в случае, если у нас есть входящие заявки
    //     countFriendRequestsDiv.parent().find("a").attr("href",PM.navigate+"/pages/contactsadd.php");
    // }
    // else countFriendRequestsDiv.html("");
});