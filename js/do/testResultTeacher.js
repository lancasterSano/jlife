$(document).ready(function(){
    testResultTeacher.assignPopupToTests();
});
var testResultTeacher = {
    assignPopupToTests: function(){
        var marks = $("td [id ^= mark_]");
        marks.each(function(){
            var idsubject = $(this).attr("id").split("_")[1];
            var idday = $(this).attr("id").split("_")[2];
            if(intval(idday)>0){
                $(this).cPopup({
                    idWND: "rf_wnd_markPopup_" + idsubject + "_" +idday,
                    btns: window._NONE,
                    location : {
                        position: "default",
                        offset: { left: 0, top: 0, right: 0, bottom: 50 },
                     proportions: { width: undefined, height: undefined },
                        quarters: [3, 4, 2, 1]
                    }
                });
            }
        });
    },
    onMouseOverLearner: function(learner, learnerid){
        learner.css("background", "#FEFFCC");
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_"+learnerid+"_]").addClass("isHovered");
    },
    onMouseOutLearner: function(learner, learnerid){
        learner.css("background", "#FFFFFF");
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_"+learnerid+"_]").removeClass("isHovered");
    },
    onMouseOverMark: function(mark){
        var tmp = mark.attr('id');
        tmp = tmp.split("_");
        var learnerid = tmp[1];
        var idcolumn = tmp[2];
        $("td [id ^= learner][id$=_"+learnerid+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= learner][id$=_"+learnerid+"]").addClass("isHovered");
        
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_"+learnerid+"_]").addClass("isHovered");
        
        $("td [id ^= date][id $= _"+idcolumn+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= date][id $= _"+idcolumn+"]").addClass("isHovered");
        
        $("td [id ^= mark_][id $= _"+idcolumn+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_][id $= _"+idcolumn+"]").addClass("isHovered");
    },
    onMouseOutMark: function(mark){
        var tmp = mark.attr('id');
        tmp = tmp.split("_");
        var learnerid = tmp[1];
        var idcolumn = tmp[2];
        $("td [id ^= learner][id$=_"+learnerid+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= learner][id$=_"+learnerid+"]").removeClass("isHovered");
        
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_"+learnerid+"_]").removeClass("isHovered");
        
        $("td [id ^= date][id $= _"+idcolumn+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= date][id $= _"+idcolumn+"]").removeClass("isHovered");
        
        $("td [id ^= mark_][id $= _"+idcolumn+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_][id $= _"+idcolumn+"]").removeClass("isHovered");
    },
    onMouseOverDate: function(date){
        var tmp = date.attr('id');
        tmp = tmp.split("_");
        var idcolumn = tmp[1];
        $("td [id ^= date][id $=_"+idcolumn+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= date][id $= _"+idcolumn+"]").addClass("isHovered");
        
        $("td [id ^= mark_][id $= _"+idcolumn+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_][id $= _"+idcolumn+"]").addClass("isHovered");
    },
    onMouseOutDate: function(date){
        var tmp = date.attr('id');
        tmp = tmp.split("_");
        var idcolumn = tmp[1];
        $("td [id ^= date][id $=_"+idcolumn+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= date][id $= _"+idcolumn+"]").removeClass("isHovered");
        
        $("td [id ^= mark_][id $= _"+idcolumn+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_][id $= _"+idcolumn+"]").removeClass("isHovered");
    },
    onMouseOverResMark: function(resMark){
        var tmp = resMark.attr('id');
        tmp = tmp.split("_");
        var learnerid = tmp[1];
        $("td [id ^= learner][id$=_"+learnerid+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= learner][id$=_"+learnerid+"]").addClass("isHovered");
        
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_"+learnerid+"_]").addClass("isHovered");
        
        $("td [id ^= mark_][id $= _resMark]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_][id $= _resMark]").addClass("isHovered");
        
        $("td [id = resMarkTd]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id = resMarkTd]").addClass("isHovered");
    },
    onMouseOutResMark: function(resMark){
        var tmp = resMark.attr('id');
        tmp = tmp.split("_");
        var learnerid = tmp[1];
        $("td [id ^= learner][id$=_"+learnerid+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= learner][id$=_"+learnerid+"]").removeClass("isHovered");
        
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_"+learnerid+"_]").removeClass("isHovered");
        
        $("td [id ^= mark_][id $= _resMark]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_][id $= _resMark]").removeClass("isHovered");
        
        $("td [id = resMarkTd]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id = resMarkTd]").removeClass("isHovered");
    },
    onMouseOverResMarkColumn: function(){
        $("td [id = resMarkTd]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id = resMarkTd]").addClass("isHovered");
        
        $("td [id $= _resMark]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id $= _resMark]").addClass("isHovered");
    },
    onMouseOutResMarkColumn: function(){
        $("td [id = resMarkTd]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id = resMarkTd]").removeClass("isHovered");
        
        $("td [id $= _resMark]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id $= _resMark]").removeClass("isHovered");
    },
    onMouseOverRatingColumn: function(){
        $("td [id = ratingTd]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id = ratingTd]").addClass("isHovered");
        
        $("td [id $= _rating]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id $= _rating]").addClass("isHovered");
    },
    onMouseOutRatingColumn: function(){
        $("td [id = ratingTd]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id = ratingTd]").removeClass("isHovered");
        
        $("td [id $= _rating]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id $= _rating]").removeClass("isHovered");
    },
    onMouseOverRating: function(rating){
        var tmp = rating.attr('id');
        tmp = tmp.split("_");
        var learnerid = tmp[1];
        $("td [id ^= learner][id$=_"+learnerid+"]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= learner][id$=_"+learnerid+"]").addClass("isHovered");
        
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_"+learnerid+"_]").addClass("isHovered");
        
        $("td [id ^= mark_][id $= _rating]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id ^= mark_][id $= _rating]").addClass("isHovered");
        
        $("td [id = ratingTd]:not(.isPressed)").css("background", "#FEFFCC");
        $("td [id = ratingTd]").addClass("isHovered");
    },
    onMouseOutRating: function(rating){
        var tmp = rating.attr('id');
        tmp = tmp.split("_");
        var learnerid = tmp[1];
        $("td [id ^= learner][id$=_"+learnerid+"]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= learner][id$=_"+learnerid+"]").removeClass("isHovered");
        
        $("td [id ^= mark_"+learnerid+"_]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_"+learnerid+"_]").removeClass("isHovered");
        
        $("td [id ^= mark_][id $= _rating]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id ^= mark_][id $= _rating]").removeClass("isHovered");
        
        $("td [id = ratingTd]:not(.isPressed)").css("background", "#FFFFFF");
        $("td [id = ratingTd]").removeClass("isHovered");
    },
    onClickMark: function(obj, idsubject, ddate, idlearner, idmaterial, idschool){
        var paragraphs = testResultTeacher.loadParagraphs(idlearner, ddate, idsubject, idmaterial);
        obj.bind("afterShowPopup", function(){
            obj.css("background-color","");
            obj.addClass("isPressed");
        });
        obj.bind("closedPopup", function(){
            obj.css("background-color","");
            obj.removeClass("isPressed");
            if(obj.hasClass("isHovered")){
                obj.css("background-color","#FEFFCC");
            }
        });
        if(paragraphs) {
            if(!obj.cPopup('isInit')) {
                obj.cPopup('open', {
                    data: { paragraphs: paragraphs, idschool: idschool},
                    body: testResultTeacher.prepareParagraphsUI(paragraphs,idschool)
                });
            } else obj.cPopup('open');
        }
//        create: {
//            btns: window._NONE,
//            fn_getContent: testResultTeacher.prepareParagraphsUI,
//            data: {
//                paragraphs: paragraphs,
//                idschool: idschool
//            }
    },
    loadParagraphs: function(idlearner, ddate, idsubject, idmaterial){
        var ret = null;
        console.log(idlearner);
        console.log(ddate);
        console.log(idsubject);
        console.log(idmaterial);
        ajax.post_sync(
                "/do/testResultTeacher/loadParagraphsTestsToTeacher.php",
                "ids="+idsubject+"&d="+ddate+"&idl="+idlearner+"&idm=" + idmaterial,
                function(response){
                    ret = response;
                },
                function(){
            
                }
        );
            
        return ret;
    },
    prepareParagraphsUI: function(paragraphs, idschool) {
        var insHTML = '';
        insHTML += '<div class="journalCont">';
        for(var i in paragraphs) {
            paragraph = {
                id: paragraphs[i]["id"],
                name: paragraphs[i]["name"],
                number: paragraphs[i]["number"],
                mark: paragraphs[i]["mark"]
            };
            var str = null;
            if(i == paragraphs.length - 1) str = "journalContItem";
            else str = "journalContItemT";
            var namePar;
            if(paragraph.name.length > 28)
                namePar = paragraph.name.substring(0,25)+"...";
            else
                namePar = paragraph.name;
            insHTML += '<div class="'+str+'">\
                            <div class="workType">\
                                <a href="addpar.php?school='+idschool+'&paragraph='+paragraph.id+'">§ '+paragraph.number+'</a>\
                            </div>\
                            <div class="workDescT">\
                                <a href="addpar.php?school='+idschool+'&paragraph='+paragraph.id+'">'+namePar+'</a>\
                            </div>\
                            <div class="testRes">\
                                <span>Результат тестирования - '+paragraph.mark+' б.</span>\n\
                            </div>\
                        </div>';
        }
        insHTML += '</div>';
        return insHTML;
    },

    printText: function(){
        // <link href='../../css/do/style_print.css' rel='stylesheet' media='print'>
        var txt = $("#test").html();
        debugger;
        window.print(txt);
    }
};

    function printDiv(divName) {
     var originalContents = document.body.innerHTML;
     var subjectName = $(".actSubj").text();
     $(".paragraph:first-child").text(subjectName);
     var printContents = document.getElementById(divName).innerHTML;

     document.body.innerHTML = printContents;
     // $("#test").append("<link href='../../css/do/style_print.css' rel='stylesheet' media='print'>");
     window.print();

     document.body.innerHTML = originalContents;
}