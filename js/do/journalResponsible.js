$(document).ready(function(){
    var marks = $("td[id^=mark_]"); 
        marks.each(function(){
            // var idFriend = $(this).attr("id").split("_")[1];
            $(this).cPopup({
                idWND: "rf_wnd_inboxRequestPopup_" + $(this).attr("id"),
                // btns: window._OKCANCEL,
                btns: window._NONE,
                location : {
                    position: "default",
                    parentDependence: $("div.mainfield"),
                    offset: { left: 0, top: 0, right: 0, bottom: 0 },
                    // proportions: { width: 300, height: undefined },
                    quarters: [4,3]
                }
            });
        });
});

var JournalResponsible = 
{
	getMarks: function(idLearner, idLesson){ // idLoad , count_note (default: SETTING_COUNT_CONTINUATION_LOAD_ENTITY )
        
        var insertHTML = "";
        ajax.post_sync("do/journalResponsible/getMarksAndTypesLessons.php","idLearner=" + idLearner + "&idLesson=" + idLesson,
            function(data){
                insertHTML ="   <div class='journalContItem' style='width:200px;'>\
                                    <div class='item'>\
                                        <div class='left'>\
                                            <span class='header'>Оценки</span>\
                                        </div>\
                                        <div class='right'>\
                                           <span class='header' >Тип урока</span>\
                                        </div>\
                                    </div>";
                    insertHTML += "     <div class='journalContItem'>\
                                            <div class='item'>";
                for(var i in data)
                {
                    marks = {
                        mark: data[i]["value"],
                        sltId: data[i]["spisoklessontypeS_id1"],
                        lessonType: data[i]["lessonType"]
                    };
                    						
                    insertHTML +=              "<div class='left'>\
                                                    <span>"+marks.mark+"</span>\
                                                </div>\
                                                <div class='right'>\
                                                    <span>"+marks.lessonType+"</span>\
                                                </div>";
                }
                    insertHTML += "         </div>\
                                            \
                                        </div>";
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
        return insertHTML;
    },
    createMarksPopup : function(idSubject, idLearner, idLesson){
        var el = $("td[id^=mark_" + idSubject + "_" + idLearner + "_" + idLesson + "_]");
        var test_2 = el;//$(e.target);

        test_2.bind("afterShowPopup", function (e){
                                        el = $(e.target);
                                        el.css("background-color","");
                                        el.addClass("isPressed");
                                        });             
          
        test_2.bind("closedPopup", function (e) {
                                     el = $(e.target);
                                     el.removeClass("isPressed");
                                     if(el.hasClass("isHovered")){
                                        el.css("background-color","#FEFFCC");
                                     }
                                     });

        if(!test_2.cPopup('isInit')) {
          test_2.cPopup('open', {
              data: {},
              body: JournalResponsible.getMarks(idLearner,idLesson),
          });
        } else test_2.cPopup('open');
    },
   
    
    mouseOverOutMark: function(post, background, state){
        var sps = post.split("_");

        /***********************************/
        var setMarkHorizontalIsHovered = $("td [id ^=mark_"+sps[1]+"_]:not(.noLesson)");
        $("td [id ^=mark_"+sps[1]+"_]:not(.noLesson, .isPressed)").css("background", background); // mark

        /***********************************/
        var setMarksUpDownVerticalIsHovered = $("td[id $=_"+sps[3]+"][id ^=mark_]:not(.noLesson)");
        $("td[id $=_"+sps[3]+"][id ^=mark_]:not(.noLesson, .isPressed)").css("background", background); // marksUpDown
        
        ge("result_"+sps[3]).css("background", background); // result
        $("td [id =subject_"+sps[1]+"]").css("background", background); // rowSubject
        $("td [id ^=date_][id $=_"+sps[3]+"]").css("background", background); // columnDate
        
        if(state == true)
        {
            setMarkHorizontalIsHovered.addClass('isHovered'); 
            setMarksUpDownVerticalIsHovered.addClass('isHovered');
        }
        else
        {
            setMarkHorizontalIsHovered.removeClass('isHovered');
            setMarksUpDownVerticalIsHovered.removeClass('isHovered');
        }
    },
    mouseOverMark: function(post){
        JournalResponsible.mouseOverOutMark(post, '#FEFFCC', true);
    },
    mouseOutMark: function(post){
        JournalResponsible.mouseOverOutMark(post, '#FFFFFF', false);
    },
    
    mouseOverOutSubject: function(post, background, state){
        var sps = post.split("_");

        /***********************************/
        var setMarkHorizontalIsHovered = $("td [id ^=mark_"+sps[1]+"_]:not(.noLesson)");
        $("td [id ^=mark_"+sps[1]+"_]:not(.noLesson, .isPressed)").css("background", background); // mark

        $("td [id =subject_"+sps[1]+"]").css("background", background); // rowSubject

        if(state == true)
            setMarkHorizontalIsHovered.addClass('isHovered'); 
        else
            setMarkHorizontalIsHovered.removeClass('isHovered');
    },
    mouseOverSubject: function(post){
        JournalResponsible.mouseOverOutSubject(post, '#FEFFCC', true);
    },
    mouseOutSubject: function(post){
        JournalResponsible.mouseOverOutSubject(post, '#FFFFFF', false);
    },


    mouseOverOutType: function(post, background, state){
        var setMarksUpDownVerticalIsHovered = $("td [id $=_"+post+"][id ^=mark_]:not(.noLesson)");
        $("td [id $=_"+post+"][id ^=mark_]:not(.noLesson, .isPressed)").css("background", background); // marksUpDown

        $("td [id ^=date_][id $=_"+post+"]").css("background", background); // columnDate

        if(state == true)
            setMarksUpDownVerticalIsHovered.addClass('isHovered'); 
        else
            setMarksUpDownVerticalIsHovered.removeClass('isHovered');
    },
    mouseOverType: function(post){
        JournalResponsible.mouseOverOutType(post, '#FEFFCC', true);
    },
    mouseOutType: function(post){
        JournalResponsible.mouseOverOutType(post, '#FFFFFF', false);
    },


    mouseOverOutResult: function(post, background, state){
        $("td [id $=_"+post+"][id ^=mark_]:not(.noLesson, .isPressed)").css("background", background); // marksUpDown
        ge("result_"+post).css("background", background); // result
    },
    mouseOverResult: function(post){
        JournalResponsible.mouseOverOutResult(post, '#FEFFCC', true);
    },
    mouseOutResult: function(post){
        JournalResponsible.mouseOverOutResult(post, '#FFFFFF', false);
    },
};