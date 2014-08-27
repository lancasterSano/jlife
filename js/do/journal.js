$(document).ready(function(){
    $("td[id^=hometask_]").each(function(){
        $(this).cPopup({
            idWND: "createHometaskPopup_" + $(this).attr("id"),
            btns: window._NONE,
            location : {
                position: "default",
                parentDependence: $("div.mainfield"),
                offset: { left: 0, top: 0, right: 0, bottom: 0 },
                proportions: { width: 300, height: undefined },
                quarters: [4,3,1,2]
            }
        });
    });
});

var Journal = {
	getHomework: function(idL, idS){
        var idLesson = idL, insertHTML = "";
        ajax.post_sync("do/journal/getHomework.php","idLesson=" + idLesson,
            function(data){
                
                insertHTML ="   <div class='journalContItem'>\
                                    <div class='workType'>\
                                        <span>д / з</span>\
                                    </div>";
                if (data[0]["hometask"])
                    insertHTML +="      <div class='workDesc'>\
                                            <span>" + data[0]["hometask"]["hometask"] + "</span>\
                                        </div>\
                                    </div>";
                else
                    insertHTML +="</div>";
                if(data[0].idParagraph)
                for(var i in data)
                {
                    homework = {
                        id: data[i]["idParagraph"],
                        number: data[i]["number"],
                        name: data[i]["name"],
                        partparagraphs: data[i]["partparagraphs"]
                    };

                    insertHTML += "     <div class='journalContItem'>\
                                            <div class='workType'>\
                                                <a class='actionItem' href='paragraph.php?school=" + idS + "&paragraph=" + homework.id + "'>§ " + homework.number + "</a>\
                                            </div>\
                                            \
                                            <div class='workDesc'>\
                                                <a class='actionItem' href='paragraph.php?school=" + idS + "&paragraph=" + homework.id + "'>" + homework.name + "</a>";
                    for (var j in homework.partparagraphs)
                    {        
                        insertHTML +=" <a class='actionItem' href='paragraph.php?school=" + idS + "&amp;paragraph=" + homework.id + "#s_" + homework.partparagraphs[j].id + "'> Часть " + homework.partparagraphs[j].number + "</a>";
                    }
                        insertHTML +="   </div>\
                                            </div>"; 
                }
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                // console.log("error from ajax: " + XMLHttpRequest.responseText);
                var errorMessage = "";
                //Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        return insertHTML;
    },
    createHomeworkPopup : function(post){
        var sps = post.split("_");
        var el = $("td[id^=hometask_" + sps[0] + "_" + sps[1]+ "_" + sps[2] + "]");

        var test_2 = el;//$(e.target);
        test_2.bind("afterShowPopup", function (e){
                                      el = $(e.target);
                                      el.css("background-color","");
                                      el.addClass("isPressed");
                                      });             

        test_2.bind("closedPopup", function (e) {
                                   el = $(e.target);
                                   el.css("background", "#fff");
                                   el.removeClass("isPressed");
                                   if(el.hasClass("isHovered"))
                                        {
                                            el.css("background-color","#FEFFCC");
                                        }
                                     });
        if(!test_2.cPopup('isInit')) {
          test_2.cPopup('open', {
              data: {},
              body: Journal.getHomework(sps[1],sps[3]),
          });
        } else test_2.cPopup('open');

    },


    /*******************************/
    /*** Hovers для ячеек таблицы***/
    /*******************************/


    mouseOverOutMark: function(post, background, state){
        var sps = post.split("_");
        var setHometaskHorizontalIsHovered = $("td [id ^=hometask][id $=_"+sps[3]+"]");
        var hometask = $("td [id ^=hometask][id $=_"+sps[3]+"]:not(.isPressed)");
            hometask.css("background",background);
        var mark = $("td [id ^=mark_"+sps[1]+"_"+sps[2]+"_]").css("background",background);
        var marksUpDown = $("td[id $=_"+sps[3]+"][id ^=mark_]").css("background",background);
        var avg = $("td [id ^=avg_"+sps[1]+"_"+sps[2]+"_]").css("background",background);
        if(avg)
        {
            var avgUpDown = $("td[id $= "+sps[4]+"][id ^=avg_]").css("background",background);
            var result = ge("result_"+sps[4]).css("background",background);
        }
        var rowLearner = ge("learner_"+sps[1]+"_"+sps[2]).css("background",background);
        var columnType = ge("type_"+sps[3]).css("background",background);
        var columnDate = ge("date_"+sps[3]).css("background",background);

        if(state == true)
        {
            setHometaskHorizontalIsHovered.addClass('isHovered');
            // setHometaskUpDownVerticalIsHovered.addClass('isHovered');
        }
        else
        {
            setHometaskHorizontalIsHovered.removeClass('isHovered');
            // setHometaskUpDownVerticalIsHovered.removeClass('isHovered');
        }
    },
    mouseOverMark: function(post){
        Journal.mouseOverOutMark(post, '#FEFFCC', true);
    },
    mouseOutMark: function(post){
        Journal.mouseOverOutMark(post, '#FFFFFF', false);
    },


    mouseOverOutLearner: function(post, background){
        var sps = post.split("_");
        var mark = $("td [id ^=mark_"+sps[0]+"_"+sps[1]+"_]").css("background", background);
        var avg = $("td [id ^=avg_"+sps[0]+"_"+sps[1]+"_]").css("background", background);
        var rowLearner = ge("learner_"+post).css("background", background);
    },
    mouseOverLearner: function(post){
        Journal.mouseOverOutLearner(post, '#FEFFCC')
    },
    mouseOutLearner: function(post){
        Journal.mouseOverOutLearner(post, '#FFFFFF')
    },



    mouseOverOutType: function(post, background, state){
        var mark = $("td[id $=_"+post+"][id^=mark_]").css("background", background);
        var setHometaskHorizontalIsHovered = $("td [id ^=hometask][id $=_"+post+"]");
        var hometask = $("td [id ^=hometask][id $=_"+post+"]:not(.isPressed)");
            hometask.css("background", background);
        var columnType = ge("type_"+post).css("background", background);
        var columnDate = ge("date_"+post).css("background", background);

        if(state == true)
        {
            setHometaskHorizontalIsHovered.addClass('isHovered');
            // setHometaskUpDownVerticalIsHovered.addClass('isHovered');
        }
        else
        {
            setHometaskHorizontalIsHovered.removeClass('isHovered');
            // setHometaskUpDownVerticalIsHovered.removeClass('isHovered');
        }
    },
    mouseOverType: function(post){
        Journal.mouseOverOutType(post, '#FEFFCC', true);
    },
    mouseOutType: function(post){
        Journal.mouseOverOutType(post, '#FFFFFF', false);
    },

    mouseOverOutResult: function(post, background){
        var avg = $("td[id $= "+post+"][id^=avg_]").css("background", background);
        var result = ge("result_"+post).css("background", background);
    },
    mouseOverResult: function(post){
        Journal.mouseOverOutResult(post, '#FEFFCC');
    },
    mouseOutResult: function(post){
        Journal.mouseOverOutResult(post, '#FFFFFF');
    },


    mouseOverOutHometask: function(post, background, state){
        var sps = post.split("_");
        var marksUpDown = $("td [id ^=mark_][id $=_"+sps[2]+"]").css("background", background);

        var setHometaskHorizontalIsHovered = $("td [id ^=hometask_"+sps[1]+"]");
        var hometask = $("td [id ^=hometask_"+sps[1]+"]:not(.isPressed)");
            hometask.css("background", background);
        var setHometaskUpDownVerticalIsHovered = $("td [id ^=hometask][id $="+sps[2]+"]");
        var hometaskUpDown = $("td [id ^=hometask][id $="+sps[2]+"]:not(.isPressed)");
            hometaskUpDown.css("background", background);
        var textHometask = ge("group_"+sps[1]).css("background", background);
        
        var columnType = ge("type_"+sps[2]).css("background", background);
        var columnDate = ge("date_"+sps[2]).css("background", background);


        if(state == true)
        {
            setHometaskHorizontalIsHovered.addClass('isHovered');
            setHometaskUpDownVerticalIsHovered.addClass('isHovered');
        }
        else
        {
            setHometaskHorizontalIsHovered.removeClass('isHovered');
            setHometaskUpDownVerticalIsHovered.removeClass('isHovered');
        }
    },
    mouseOverHometask: function(post){
        Journal.mouseOverOutHometask(post, '#FEFFCC', true);
    },
    mouseOutHometask: function(post){
        Journal.mouseOverOutHometask(post, '#FFFFFF', false);
    },


    mouseOverOutTextHometask: function(post, background, state){
        var setHometaskHorizontalIsHovered = $("td [id ^=hometask_"+post+"]");
        var hometask = $("td [id ^=hometask_"+post+"]:not(.isPressed)");
            hometask.css("background", background);
        var textHometask = ge("group_"+post).css("background", background);

        if(state == true)
            setHometaskHorizontalIsHovered.addClass('isHovered');
        else
            setHometaskHorizontalIsHovered.removeClass('isHovered');
    },
    mouseOverTextHometask: function(post){
        Journal.mouseOverOutTextHometask(post, "#FEFFCC", true);
    },
    mouseOutTextHometask: function(post){
        Journal.mouseOverOutTextHometask(post, "#FFFFFF", false);
    }
};