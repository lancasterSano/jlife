var Test = {
  //Добавить пользователя в друзья 
    SelectAnswer: function(post) {
        var tmp = post.split("_");
        var SelectedQuestion = tmp[1];
        var SelectedAnswer = tmp[2];
        // $("a[id^=Answer_"+SelectedQuestion+"]").removeAttr('class');
        var oldClass = $("#Answer_"+SelectedQuestion+"_"+SelectedAnswer).attr("class");
        if (oldClass != "selected")
        {
            $("#Answer_"+SelectedQuestion+"_"+SelectedAnswer).addClass("selected");
        }
        else
        {
            $("#Answer_"+SelectedQuestion+"_"+SelectedAnswer).removeAttr('class');   
        }
    }, 
    finishTest: function(post) {
        var paragSchool = post.split("_");
        var paragraph = paragSchool[0];
        var school = paragSchool[1];
        var subject = paragSchool[2];
        var material = paragSchool[3];
        var userAnswersArr = {};
        var userAnswersArrNew="";
        $(".selected").each(function(){
            var tmp = $(this).attr('id').split("_");
            var questionId = tmp[1];
            var answerId = tmp[2];
        //     if (userAnswersArr[questionId] == undefined ) {
        //         userAnswersArr[questionId]  = {};
        // }
            // userAnswersArr[questionId][answerId]= 5;
            if(userAnswersArrNew != "")
            {
                userAnswersArrNew +="-"+questionId+"."+answerId;
            }
            else
            {
                userAnswersArrNew +=questionId+"."+answerId;
            }
        });
        // console.log(userAnswersArrNew);
        // console.log(userAnswersArr);
        // userAnswersArr = JSON.stringify(userAnswersArr);
        // document.write(paragraph);
        ajax.post_sync("do/test/finishTest.php","idLoad=" +PM.idLoad + 
                                            "&idAuth=" + PM.idAuth +
                                            "&paragraph=" + paragraph + 
                                            "&school=" + school + 
                                            "&subject=" + subject + 
                                            "&material=" + material + 
                                            // "&userAnswers=" + userAnswersArr,
                                            "&userAnswers=" + userAnswersArrNew,

            function(response) {
                var mark = 0 ;
                $("div[id^=Question_]").each(function()
               {
                    var idQuest = $(this).attr('id').split("_");
                    if (response[idQuest[1]] && response[idQuest[1]]["mark"] > 0)
                    {
                        $(this).css("background-color","#deffe8");
                        mark = mark+parseFloat(response[idQuest[1]]["mark"]);
                    }
                    else
                    {
                        $(this).css("background-color","#ffe8e8");
                    }
                     
                });
                $("#result").text("Результат тестирования "+Math.round(mark)+"б.");
                $("#result3").remove();
                $("#result4").remove();
                $("#result1").show();
                $("#result2").show();   
            },
            function(msg){}
        );
    }
        // var oldClass = $("#friends_"+tmp[0]).attr("class");
        // var newClass;
        // var isOld;
        // ajax.post_sync("friends/friend.php","idLoad=" + tmp[0] + "&idAuth=" + tmp[1] + "&idGroup=" + tmp[2],
        //     function(response) {
        //     //Сменить класс кнопки 
        //     	if($("#friends_"+tmp[0]).length) {
        //             $("#friends_"+tmp[0]).removeAttr('class').addClass(response);
        //         }
        //         newClass = response;
        //     },
        //     function(msg){}
        // );
    
};