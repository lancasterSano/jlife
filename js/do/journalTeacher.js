$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();

    var marks = $("td[id^=mark_]"); 
        marks.each(function(){
            // var idFriend = $(this).attr("id").split("_")[1];
            $(this).cPopup({
                idWND: "marksPopup_" + $(this).attr("id"),
                // btns: window._OKCANCEL,
                btns: window._NONE,
                location : {
                    position: "default",
                    parentDependence: $("div.mainfield"),
                    offset: { left: 0, top: 0, right: 0, bottom: 0 },
                    // proportions: { width: 300, height: undefined },
                    quarters: [4,3,1,2]
                }
            });
        });

    var types = $("td[id^=type_]"); 
        types.each(function(){
            // var idFriend = $(this).attr("id").split("_")[1];
            $(this).cPopup({
                idWND: "typesPopup_" + $(this).attr("id"),
                // btns: window._OKCANCEL,
                btns: window._NONE,
                location : {
                    position: "default",
                    parentDependence: $("div.mainfield"),
                    offset: { left: 0, top: 0, right: 0, bottom: 0 },
                    // proportions: { width: 300, height: undefined },
                    quarters: [4,3,1,2]
                }
            });
        });

});

var JournalTeacher = {
    getDateLesson: function(idL){
        var dateLesson;
        var idLesson = idL;
         ajax.post_sync("do/journalTeacher/getDateLesson.php","idLesson=" + idLesson,
            function(data){
                dateLesson = data;
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
         return dateLesson;
    },

    getSections: function(idS){
        var sections;
        var idSubject = idS;
         ajax.post_sync("do/journalTeacher/getSections.php","idSubject=" + idSubject,
            function(data){
                sections = data;
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
         return sections;
    },

    getParagraphs: function(idSubgroup){
        var paragraphs;
        var insertHTML = "";
        var idSection = $('#sections').val();
        ajax.post_sync("do/journalTeacher/getParagraphs.php","idSection=" + idSection + "&idSubgroup=" + idSubgroup,
            function(data){
                paragraphs = data;
                if(ge("paragraphs").children()) ge("paragraphs").children().remove();
                if(ge("partParagraphs").children()) ge("partParagraphs").children().remove();
                insertHTML = "<option style=\"background-color: #bbb; color: #000\" value='0'>--Выберите параграф--</option>"
                for(var i in paragraphs)
                {    
                    para = {
                                id: paragraphs[i]["id"],
                                name: paragraphs[i]["name"],
                                number: paragraphs[i]["number"],
                                notstudy: paragraphs[i]["notstudy"]
                    };
                    insertHTML += "<option ";
                    if(para.notstudy == 1)
                        insertHTML += "style=\"color: red\" ";
                    insertHTML += "value='" + para.id + "'>" + para.name + "</option>";
                }
                ge("paragraphs").append(insertHTML);
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
    },

    getPartparagraphs: function(){
        var partParagraphs;
        var insertHTML = "";
        var idParagraph = $('#paragraphs').val();
        ajax.post_sync("do/journalTeacher/getPartParagraphs.php","idParagraph=" + idParagraph,
            function(data){
                partParagraphs = data;
                if(ge("partParagraphs").children()) ge("partParagraphs").children().remove();
                insertHTML = "<option style=\"background-color: #bbb; color: #000\" value='0'>--Выберите раздел--</option>"
                for(var i in partParagraphs)
                {    
                    partPara = {
                                id: partParagraphs[i]["id"],
                                name: partParagraphs[i]["name"],
                                number: partParagraphs[i]["number"]
                    };
                    insertHTML += "<option value='" + partPara.id + "'>" + partPara.name + "</option>";
                }
                ge("partParagraphs").append(insertHTML);
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
    },

    getHomework: function(idL){
        var hometasks;
        var idLesson = idL, insertHTML;
        ajax.post_sync("do/journalTeacher/getHomework.php","idLesson=" + idLesson,
            function(data){
                hometasks = data;
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
        return hometasks;
    },

    countSymbolsInputed: function(){
        var countSymbols = ge("txtArea").val().length;
        var result = 256;
            result = result - countSymbols;
        var text1 = ge("text1"),
            text2 = ge("text2"),
            countSymbolsObj = ge("countSymbols"),
            textarea = ge("txtArea");
        if(result <= 0)
        {
            text1.css("color","#d28b8d");
            text2.css("color","#d28b8d");
            countSymbolsObj.css("color","#d28b8d");
            
            textarea.addClass('entererror');
            // textarea.css("outline","none");

            countSymbolsObj.text("0");
            var text = textarea.val();
            text = text.substring(0, 256);
            textarea.val(text);
        }
        else
        {
            text1.css("color","#b7b7b7");
            text2.css("color","#b7b7b7");
            countSymbolsObj.css("color","#b7b7b7");

            textarea.removeClass('entererror');
            // textarea.css("outline","");

            countSymbolsObj.text(result);
        }
    },

    creatingHomeworkUI: function(element, idSubject, idLesson, idSchool, idSubgroup){
        var styleRed = "";
        var hometaskIsPressed = $("td [id ^=hometask_].isPressed");
             
        if(!element.hasClass('isPressed'))
        {
            element.css("background-color","");
            element.addClass("isPressed");

             if(hometaskIsPressed.hasClass("isHovered"))
             {
                hometaskIsPressed.css("background-color","#FEFFCC");
                hometaskIsPressed.removeClass("isPressed");
             }
        }
        else return;
        var dateLesson = JournalTeacher.getDateLesson(idLesson);
        var sections = JournalTeacher.getSections(idSubject);
        var hometasks = JournalTeacher.getHomework(idLesson);
        var dz = ge("dz");
        var infoSaveDZ = $(".infoSaveDZ");
        if(dz) dz.remove();
        if(infoSaveDZ) infoSaveDZ.remove();

        var insertHTML = "";
            insertHTML = "  <div class='repDZ' id='dz'>\
                                <div class='repDZttl'>\
                                    <span> Домашнее задание на " + dateLesson + "</span>\
                                </div>\
                                \
                                <div class='chooseIt'>\
                                    <div class='takeIt'>\
                                        <div class='takeIt-select' >\
                                            <select id='sections' onchange='JournalTeacher.getParagraphs("+idSubgroup+");'>\
                                                <option style=\"background-color: #bbb; color: #000\" value='0'>--Выберите тему--</option>";
        for(var i in sections)
        {
            sect = {
                            id: sections[i]["id"],
                            name: sections[i]["name"],
                    };
        insertHTML +=                           "<option value='" + sect.id + "'>" + sect.name + "</option>";
        }
        insertHTML +=                       "</select>\
                                        </div>\
                                        \
                                        <span title='Добавить все параграфы' class='actionItem' onclick='JournalTeacher.addSection(\"lesson_"+ idLesson +"_school_"+ idSchool +"\");'>+ Секция</span>\
                                    </div>\
                                    \
                                    <div class='takeIt'>\
                                        <div class='takeIt-select'>\
                                            <select id='paragraphs'onchange='JournalTeacher.getPartparagraphs();'>\
                                            </select>\
                                        </div>\
                                        \
                                        <span title='Добавить параграф' class='actionItem' onclick='JournalTeacher.addParagraph(\"lesson_"+ idLesson +"_school_"+ idSchool +"\");'>+ Параграф</span>\
                                    </div>\
                                    \
                                    <div class='takeIt'>\
                                        <div class='takeIt-select'>\
                                            <select id='partParagraphs'>\
                                            </select>\
                                        </div>\
                                        <span title='Добавить часть параграфа' class='actionItem' onclick='JournalTeacher.addPartParagraph(\"lesson_"+ idLesson +"_school_"+ idSchool +"\");'>+ Часть</span>\
                                    </div>\
                                </div>\
                                \
                                <div class='paragr' id='spisokParagr'>";
        if(hometasks[0].idParagraph)
        for(var i in hometasks)
        {
            homework = {
                            id: hometasks[i]["idParagraph"],
                            number: hometasks[i]["number"],
                            name: hometasks[i]["name"],
                            notstudy: hometasks[i]["notstudy"],
                            hometask: hometasks[i]["hometask"],
                            partparagraphs: hometasks[i]["partparagraphs"]
                    };
        if(homework.notstudy == 1)
            styleRed = "style=\"color: red\"";
        else  
            styleRed = "";                  
        insertHTML +=               "<div class='paragrIt'>\
                                        <div title='Удалить параграф' class='delete' onclick='JournalTeacher.deleteParagraph(\"lesson_"+ idLesson +"_paragraph_"+ homework.id +"_school_" + idSchool + "\");'></div>\
                                        <a class='actionItem' " + styleRed + " href='paragraph.php?school=" + idSchool + "&paragraph=" + homework.id + "'>§ " + homework.number +" "+ homework.name + "</a>";
            for (var j in homework.partparagraphs)
            {
                insertHTML += " <a class='actionItem' " + styleRed + " href='paragraph.php?school=" + idSchool + "&amp;paragraph=" + homework.id + "#s_" + homework.partparagraphs[j].id + "'> Часть " + homework.partparagraphs[j].number + "</a>";
            }
        insertHTML +=               "</div>";
        }
        insertHTML +=          "</div>\
                                \
                                <div class='repairTool'>\
                                    <div class='commentToDZ'>\
                                        <span>Комментарий к домашнему заданию</span>\
                                    </div>\
                                    <textarea id='txtArea' class='textInput' contenteditable onkeyup='JournalTeacher.countSymbolsInputed();'>";
        if (hometasks[0]["hometask"])                            
        insertHTML +=       hometasks[0]["hometask"]["hometask"];
        else if (hometasks[0].hometasks)
        insertHTML +=       hometasks[0]["hometasks"];
        insertHTML +=              "</textarea>\
                                <div class='textInfo'>\
                                    <span id='text1'>Доступно</span>\
                                    <span id='countSymbols'></span>\
                                    <span id='text2'>символов</span>\
                                    <span title='Скрыть блок редактирования д/з' class='manageTools actionItem' onclick='JournalTeacher.hideHomeworkUI();'>Скрыть</span>\
                                    <span class='manageTools actionItem' onclick='JournalTeacher.addHometaskInLessonsTable(\"lesson_"+ idLesson +"_school_"+idSchool+"\");'>Сохранить</span>\
                                    <span title='Очистить комментарий' class='manageTools actionItem' onclick='JournalTeacher.abortChangesInTextArea(\"lesson_"+ idLesson +"_school_"+idSchool+"\");'>Очистить</span>\
                                \
                                </div>\
                            </div>";
        var a = ge("contentId");
        a.append(insertHTML);
        // Для отображения количества доступных символов для ввода
        JournalTeacher.countSymbolsInputed(); 
        return insertHTML;
    },

    hideHomeworkUI: function(){
        // $.when( ge("dz").slideUp(300) ).done(function( ) {
                          // this.remove(); // Alerts "123"
                        // });
        ge("dz").remove();
        $("td [id ^=hometask_].isPressed").removeClass('isPressed');
    },

    deleteInfoSaveDZMessage: function(){
        $(".infoSaveDZ").fadeOut(300);
    },
    
    errorMessageWindow: function(title, text, typeMessage){
        var notices = Array();
        var type; 
        notices.push(window.tmpl.tmpl_notice_hb({title: title, text: text}));
        type = typeMessage;
        var insertHTML = tmpl(window.tmpl.tmpl_notices, {type:type, notices: notices})
        HTML = "<div class='infoSaveDZ'>"+insertHTML+"</div>";
        setTimeout(JournalTeacher.deleteInfoSaveDZMessage, 5000);

        $(".infoSaveDZ").remove();
        ge("dz").before(HTML);
    },

    addSection: function(post){
        sps = post.split("_"); // sps[1] => idLesson | sps[3] => idSchool
        var idLesson = sps[1];
        var idSchool = sps[3];
        var idSection = ge('sections').val(); 
        if(idSection != null && idSection != 0)
        {
            ajax.post_sync("do/journalTeacher/addSection.php","idLesson=" + idLesson + "&idSection=" + idSection,
                function(addedSection){
                    if(addedSection != null)
                    {
                        data = JournalTeacher.getHomework(idLesson);
                        JournalTeacher.repaintHomework(data, idSchool, idLesson);
                        $("td [id ^=hometask][id *=_"+idLesson+"_]").text("V");
                    }
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );
        }
        else
            JournalTeacher.errorMessageWindow(ME_ADDING_NEW_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "секции"), MW_CHOOSE_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "секцию"), 3);
    },

    addParagraph: function(post){
        sps = post.split("_");
        var idLesson = sps[1];
        var idSchool = sps[3];
        var idParagraph = ge('paragraphs').val();
        
        if(idParagraph != null && idParagraph != 0)
        {
            ajax.post_sync("do/journalTeacher/addParagraph.php","idLesson=" + idLesson + "&idParagraph=" + idParagraph,
                function(addedParagraph){
                    if(addedParagraph != null)
                    {
                        data = JournalTeacher.getHomework(idLesson);
                        JournalTeacher.repaintHomework(data, idSchool, idLesson);
                        $("td [id ^=hometask][id *=_"+idLesson+"_]").text("V");
                    }
                    else
                        JournalTeacher.errorMessageWindow(ME_ADDING_NEW_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "параграфа"), MI_CHECK_ISSET_PARTPARAGRAPHS, 3);
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );
        }
        else
            JournalTeacher.errorMessageWindow(ME_ADDING_NEW_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "параграфа"), MW_CHOOSE_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "параграф"), 3);
    },

    addPartParagraph : function(post){
        var sps = post.split("_"); // sps[1] => idLesson | sps[3] => idSchool
        var idLesson = sps[1];
        var idSchool = sps[3];
        var idPartParagraph = ge('partParagraphs').val();
        if(idPartParagraph != null && idPartParagraph != 0)
        {
            ajax.post_sync("do/journalTeacher/addPartParagraph.php","idLesson=" + idLesson + "&idPartParagraph=" + idPartParagraph,
                function(data){
                    data = JournalTeacher.getHomework(idLesson);
                    JournalTeacher.repaintHomework(data, idSchool, idLesson);
                    $("td [id ^=hometask][id *=_"+idLesson+"_]").text("V");
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );
        }
        else
            JournalTeacher.errorMessageWindow(ME_ADDING_NEW_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "части параграфа"), MW_CHOOSE_PARTPARAGRAPH_PARAGRAPH_SECTION.replace(new RegExp("%PART",""), "часть параграфа"), 3);
    },

    addHometaskInLessonsTable: function(post){
        var sps = post.split("_");
        var newHometask = ge("txtArea").val();
        var idLesson = sps[1];
        var idSchool = sps[3];
        
        var HTML = "";
        ajax.post_sync("do/journalTeacher/addHometaskInLessonsTable.php","idLesson=" + idLesson + "&textHometask=" + newHometask,
                function(data){
                    var notices = Array();
                    var type;
                    if(data == true)
                    {
                        notices.push(window.tmpl.tmpl_notice_h({title: MS_SAVING_NEW_HOMETASK.replace(new RegExp("%DATE",""), $(".repDZttl span").text())}));
                        type = 4;
                    }
                    else
                    {
                        notices.push(window.tmpl.tmpl_notice_hb({title: ME_SAVING_NEW_HOMETASK, text: MI_CONTACT_THE_ADMINISTRATOR}));
                        type = 2;
                    }    
                        var insertHTML = tmpl(window.tmpl.tmpl_notices, {type:type, notices: notices})
                        HTML = "<div class='infoSaveDZ'>"+insertHTML+"</div>";
                    setTimeout(JournalTeacher.deleteInfoSaveDZMessage, 5000);

                    ge("dz").replaceWith(HTML);
                    data = JournalTeacher.getHomework(idLesson);
                    var currentCellHometask =  $("td [id ^=hometask][id *=_"+idLesson+"_]")
                    if(!data[0].name && data[0]['hometask'].hometask == "")
                       currentCellHometask.text("");
                    else
                       currentCellHometask.text("V");

                    $("td [id ^=hometask_].isPressed").removeClass('isPressed');
                },
                function(XMLHttpRequest, textStatus, errorThrown){}
            );
    },

    abortChangesInTextArea: function(post){
        var sps = post.split("_");
        var newHometask = ge("txtArea").val();
        var idLesson = sps[1];
        var idSchool = sps[3];

        var data = JournalTeacher.getHomework(idLesson);
        JournalTeacher.repaintHometask(data, idSchool);
    },

    deleteParagraph: function(post){
        var sps = post.split("_"); // sps[1] => idLesson | sps[3] => idParagraph | sps[5] => idSchool
        var idLesson = sps[1];
        var idParagraph = sps[3];
        var idSchool = sps[5];

            ajax.post_sync("do/journalTeacher/deleteParagraph.php","idLesson=" + idLesson + "&idParagraph=" + idParagraph,
                function(data){
                    data = JournalTeacher.getHomework(idLesson);
                    JournalTeacher.repaintHomework(data, idSchool, idLesson);
                    if(!data[0].name && data[0]['hometask'].hometask == "")
                    {
                       var a =  $("td [id ^=hometask][id *=_"+idLesson+"_]")
                       a.text("");
                    }
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );
    },

    repaintHomework: function(data,idSchool,idLesson){
        var styleRed = "";
        var hometasks = data;
        var insertHTML = "";

        insertHTML +=               "<div class='paragr' id='spisokParagr'>";
        if(hometasks[0].idParagraph)
        for(var i in hometasks)
        {
            homework = {
                            id: hometasks[i]["idParagraph"],
                            number: hometasks[i]["number"],
                            name: hometasks[i]["name"],
                            notstudy: hometasks[i]["notstudy"],
                            hometask: hometasks[i]["hometask"],
                            partparagraphs: hometasks[i]["partparagraphs"]
                    };
        if(homework.notstudy == 1)
            styleRed = "style=\"color: red\"";
        else  
            styleRed = "";                      
        insertHTML +=               "<div class='paragrIt'>\
                                        <div class='delete' onclick='JournalTeacher.deleteParagraph(\"lesson_"+ idLesson +"_paragraph_"+ homework.id +"_school_" + idSchool + "\");'></div>\
                                        <a " + styleRed + " href='paragraph.php?school=" + idSchool + "&paragraph=" + homework.id + "'>§ " + homework.number +" "+ homework.name + "</a>";
            for (var j in homework.partparagraphs)
            {
                insertHTML += " <a " + styleRed + " href='paragraph.php?school=" + idSchool + "&amp;paragraph=" + homework.id + "#s_" + homework.partparagraphs[j].id + "'> Часть " + homework.partparagraphs[j].number + "</a>";
            }
        insertHTML +=               "</div>";
        }
        insertHTML +=          "</div>";

        ge("spisokParagr").replaceWith(insertHTML);
    },

    repaintHometask: function(data, idSchool){
        var hometasks = data;
        var insertHTML = "";

        if (hometasks[0]["hometask"])                            
        insertHTML +=       hometasks[0]["hometask"]["hometask"];
        else if (hometasks[0].hometasks)
        insertHTML +=       hometasks[0]["hometasks"];
        ge("txtArea").val(insertHTML);
        JournalTeacher.countSymbolsInputed();
    },

    confirmMark: function(post){
        var sps = post.split("_");
        var idGroup = sps[0];
        var idRow = sps[1];
        var idNumberMark = sps[2];

        var idLearner = sps[3];
        var idLesson = sps[4];
        var idLessonType = sps[5];
        var idSubgroup = sps[6];
        var idSchool = sps[7];

        var selectedMark = $("span[id^='mark_'][class='selected']").attr("id");
        // Если переменная пустая
        if(!selectedMark) return;

        var mark = selectedMark.split("_");
        if(selectedMark != "")
            if(mark[1] != '0')
                ajax.post_sync("do/journalTeacher/confirmMark.php","idLearner=" + idLearner + "&idLesson=" + idLesson + "&idLessonType=" + idLessonType + "&mark=" + mark[1] + "&idSubgroup=" + idSubgroup + "&idSchool=" + idSchool,
                    function(data){
                        // insertHTML = "<td id='mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"' class='colItem'\
                        //                 onclick='JournalTeacher.createMarksPopup(\""+idGroup+"_"+idRow+"_"+idNumberMark+"_"+idLearner+"_"+idLesson+"_"+idLessonType+"_"+mark[1]+"_"+idSubgroup+"\")'\
                        //                 onmouseover='JournalTeacher.mouseOverMark(\"mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"\")'\
                        //                 onmouseout='JournalTeacher.mouseOutMark(\"mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"\")'>";

                        document.getElementById("mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"").setAttribute("onclick", "JournalTeacher.createMarksPopup(\""+idGroup+"_"+idRow+"_"+idNumberMark+"_"+idLearner+"_"+idLesson+"_"+idLessonType+"_"+mark[1]+"_"+idSubgroup+"\")");
                        // ge("markp").text(data["mark"]);
                        ge("mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"").text(data["mark"]);
                        var countMark = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=1]").text(data["results"][0]["countmark"]);
                        var averageMark = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=2]").text(data["results"][0]["averagemark"]);
                        var countAbsent = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=3]").text(data["results"][0]["countabsent"]);
                        // var rating = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=4]").text(data["results"][0]["rating"]);
                    }, 
                    function(XMLHttpRequest, textStatus, errorThrown){ 
                        var errorMessage = "";
                    }
                );
            else
                alert("К сожалению, выставить текущую отметку в данный момент невозможно");
    },

    deleteMark: function(post){
        var sps = post.split("_");
        var idGroup = sps[0];
        var idRow = sps[1];
        var idNumberMark = sps[2];

        var idLearner = sps[3];
        var idLesson = sps[4];
        var idLessonType = sps[5];
        var idSubgroup = sps[6];
        var idSchool = sps[7];

        var selectedMark = $("#mark_"+idGroup+"_"+idRow+"_"+idNumberMark).text();
        if(selectedMark.length == 0) return;

        if(selectedMark > 0 && selectedMark < 13)
            var mark = parseInt(selectedMark);
        else
            var mark = 13;
        if(selectedMark != "")
            ajax.post_sync("do/journalTeacher/deleteMark.php","idLearner=" + idLearner + "&idLesson=" + idLesson + "&idLessonType=" + idLessonType + "&mark=" + mark + "&idSubgroup=" + idSubgroup + "&idSchool=" + idSchool,
                function(data){
                    // insertHTML = "<td id='mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"' class='colItem'\
                    //                 onclick='JournalTeacher.createMarksPopup(\""+idGroup+"_"+idRow+"_"+idNumberMark+"_"+idLearner+"_"+idLesson+"_"+idLessonType+"_"+""+"_"+idSubgroup+"\")'\
                    //                 onmouseover='JournalTeacher.mouseOverMark(\"mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"\")'\
                    //                 onmouseout='JournalTeacher.mouseOutMark(\"mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"\")'>";
                    document.getElementById("mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"").setAttribute("onclick", "JournalTeacher.createMarksPopup(\""+idGroup+"_"+idRow+"_"+idNumberMark+"_"+idLearner+"_"+idLesson+"_"+idLessonType+"_"+""+"_"+idSubgroup+"\")");
                    // ge("markp").text("");
                    ge("mark_"+idGroup+"_"+idRow+"_"+idNumberMark+"").text("");
                    var countMark = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=1]").text(data["results"][0]["countmark"]);
                    var averageMark = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=2]").text(data["results"][0]["averagemark"]);
                    var countAbsent = $("td [id^=avg_"+idGroup+"_"+idRow+"_][id$=3]").text(data["results"][0]["countabsent"]);
                    ge("mark_"+mark).removeClass("selected");
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );
    },

    setSelectedClass: function(e, i){
        var curTarget = $(e.target);
        if(curTarget.hasClass('selected')) {
            $(e.target).removeClass('selected');
        }
        else {
            $("span[id^='mark_'][class='selected']").removeClass('selected');
            $(e.target).addClass('selected');   
        }
    },

    getMarksUI: function(idGroup, idRow, idNumberMark, idLearner, idLesson, idLessonType, mark, idSubgroup, idSchool){
        var insertHTML = "";
        var idSchool
        insertHTML = "<div class='addMarkCont'>\
                                    <div class='marks' id='allMarks'>";
        for(var i = 1; i < 7; i++)
        {    
            // Условие для определения, совпадает ли поставленная оценка с той, которая должна отрисоваться, если да, то присваиваем класс selected
            if(mark == i)
                insertHTML +="<span class='selected' id='mark_"+i+"' onclick='JournalTeacher.setSelectedClass(event, \""+i+"\");'>"+i+"</span>";
            else
                insertHTML +="<span id='mark_"+i+"' onclick='JournalTeacher.setSelectedClass(event, \""+i+"\");'>"+i+"</span>";
        }
        if(mark == '0')
            insertHTML +="<span class='selected' id='mark_0' onclick='JournalTeacher.setSelectedClass(event, \""+0+"\");'>П</span>";
        else
            insertHTML +="<span id='mark_0' onclick='JournalTeacher.setSelectedClass(event, \""+0+"\");'>П</span>";
        
        for(var i = 7; i < 13; i++)
        {
            if(mark == i)
                insertHTML +="<span class='selected' id='mark_"+i+"' onclick='JournalTeacher.setSelectedClass(event, \""+i+"\");'>"+i+"</span>";
            else
                insertHTML +="<span id='mark_"+i+"' onclick='JournalTeacher.setSelectedClass(event, \""+i+"\");'>"+i+"</span>";
        }
        if(mark == '13')
            insertHTML +="<span class='selected' id='mark_13' onclick='JournalTeacher.setSelectedClass(event, \""+ 13 +"\");'>H</span>";  
        else
            insertHTML +="<span id='mark_13' onclick='JournalTeacher.setSelectedClass(event, \""+ 13 +"\");'>H</span>";

        insertHTML +="</div>\
                            <div class='manage'>\
                                <span onclick='JournalTeacher.deleteMark(\""+idGroup+"_"+idRow+"_"+idNumberMark+"_"+idLearner+"_"+idLesson+"_"+idLessonType+"_"+idSubgroup+"_"+idSchool+"\")'>Очистить</span>\
                                <span onclick='JournalTeacher.confirmMark(\""+idGroup+"_"+idRow+"_"+idNumberMark+"_"+idLearner+"_"+idLesson+"_"+idLessonType+"_"+idSubgroup+"_"+idSchool+"\");'>Выставить</span>\
                            </div>\
                        </div>";
        return insertHTML;
    },
    

    // _fcl: function (e) {
    //     console.log(new Date(),e.target.id, e.type);
    //     $(e.target).off(e.type);
    // },


    createMarksPopup : function(post){
        var sps = post.split("_");
        var idGroup = sps[0], 
            idRow = sps[1],
            idNumberMark = sps[2],
            idLearner = sps[3],
            idLesson = sps[4],
            idLessonType = sps[5],
            mark = sps[6],
            idSubgroup = sps[7]; 

        var el = $("#mark_" + sps[0] + "_" + sps[1]+ "_" + sps[2]);
        var idSchool = $(el).attr("data-id");

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

          // if(!test_2.cPopup('isInit')) {
              test_2.cPopup('open', {
                  data: {},
                  body: JournalTeacher.getMarksUI(idGroup, idRow, idNumberMark, idLearner, idLesson, idLessonType, mark, idSubgroup,idSchool),
              });
          // } else test_2.cPopup('open');
    },
    
    getAllLessonTypes: function(idSpisokLessonType){
        var lessonTypes;
        
            ajax.post_sync("do/journalTeacher/getAllLessonTypes.php","idSpisokLessonType=" + idSpisokLessonType,
                function(data){
                    lessonTypes = data;
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );
        return lessonTypes;
    },

    reloadJournalAfterAdding: function(){
        alert("as");
        location.reload();
    },

    addNewLessonType: function(idLesson){
        var idLessonType = $('#lessonTypes-add').val();
        ajax.post_sync("do/journalTeacher/addNewLessonType.php","idLesson=" + idLesson + "&idLessonType=" + idLessonType,
            function(data){
                alert("Добавлено новое мероприятие!");
                location.reload();
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
                var errorMessage = "";
            }
        );
    },

    changeLessonType: function(idSpisokLessonType){
        var idLessonType = $('#lessonTypes-change').val();

            ajax.post_sync("do/journalTeacher/changeLessonType.php","idLessonType=" + idLessonType + "&idSpisokLessonType=" + idSpisokLessonType,
                function(data){
                    alert("Мероприятие изменено!");
                    location.reload();
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            );  
    },

    deleteLessonType: function(idLesson, idSpisokLessonType){
        if(confirm("Вы точно хотите удалить мероприятие !"))
            ajax.post_sync("do/journalTeacher/deleteLessonType.php","idLesson=" + idLesson + "&idSpisokLessonType=" + idSpisokLessonType,
                    function(result){
                        if(result == 'Error')
                            alert('Удалить мероприятие невозможно, оно является единственным на этом уроке!');
                        else
                            location.reload();
                    }, 
                    function(XMLHttpRequest, textStatus, errorThrown){ 
                        var errorMessage = "";
                    }
                ); 
    },

    /*typeTabs - переменная, которая обозначает вкладку, из которой мы кликаем на комбобокс*/
    changeLetter: function(typeTabs){
        if(typeTabs == 'add')
            var idLessonType = $('#lessonTypes-add').val();
        else if(typeTabs == 'change')
            var idLessonType = $('#lessonTypes-change').val();

        ajax.post_sync("do/journalTeacher/getAllLessonTypes.php","idLessonType=" + idLessonType,
                function(data){
                    var name = data[0]['name'];
                    if(typeTabs == 'add')
                        ge('letter-add').replaceWith("<span id='letter-add'>"+name+"</span>");
                    else if(typeTabs == 'change')
                        ge('letter-change').replaceWith("<span id='letter-change'>"+name+"</span>");
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            ); 
    },

    checkCountLessonTypesInLesson: function(idLesson){
        ajax.post_sync("do/journalTeacher/checkCountLessonTypesInLesson.php","idLesson=" + idLesson,
                function(data){
                    countLessonTypes =  data;
                }, 
                function(XMLHttpRequest, textStatus, errorThrown){ 
                    var errorMessage = "";
                }
            ); 
        // return result;
    },

    getLessonTypeUI: function(number, idLesson, idSpisokLessonType){
        var insertHTML = "", optionsForInsertHTML = "";
        var lessonTypes = JournalTeacher.getAllLessonTypes(idSpisokLessonType);

        JournalTeacher.checkCountLessonTypesInLesson(idLesson);
        curLT = {
                    id: lessonTypes.currentLessonType[0]['id'],
                    name: lessonTypes.currentLessonType[0]['name'],
                    description: lessonTypes.currentLessonType[0]['description']
                }   

        insertHTML += "<div class='addLesTNew'>\
                        <div class='addLesTNewCont'>\
                            <div id='tabs'>\
                                <div id='tabs-1'>\
                                    <div class='chooseWork'>\
                                        <span>Тип деятельности на уроке</span>\
                                        <div class='NewSelect'>\
                                            <span id='letter-add'>"+curLT.name+"</span>\
                                            <select id='lessonTypes-add' onchange='JournalTeacher.changeLetter(\"add\");'>\
                                                <option disabled='' selected='' style=\"background-color: #bbb; color: #000\" value='0'>--Выберите тип--</option>";
        for(var i in lessonTypes.allLessonTypes)
        {
            lessonType = { id: lessonTypes.allLessonTypes[i]["id"], 
                           name: lessonTypes.allLessonTypes[i]["name"], 
                           description: lessonTypes.allLessonTypes[i]['description'] 
                        };
        optionsForInsertHTML +=                           "<option value='" + lessonType.id + "'>" + lessonType.description + "</option>";
        }
        insertHTML += optionsForInsertHTML;
        insertHTML +=                       "</select>\
                                        </div>\
                                        <div class='addW'> <span class='actionItem' onclick='JournalTeacher.addNewLessonType(\""+idLesson+"\");'>Добавить мероприятие</span></div>\
                                    </div>\
                                </div>\
                                <div id='tabs-2'>\
                                    <div class='chooseWork'>\
                                        <span>Тип деятельности на уроке</span>\
                                        <div class='NewSelect'>\
                                            <span id='letter-change'>"+curLT.name+"</span>\
                                            <select id='lessonTypes-change' onchange='JournalTeacher.changeLetter(\"change\");'>\
                                                <option disabled='' selected='' style=\"background-color: #bbb; color: #000\" value='0'>--Выберите тип--</option>";
        insertHTML += optionsForInsertHTML;
        insertHTML +=                      "</select>\
                                        </div>\
                                        <div class='addW'> <span class='actionItem' onclick='JournalTeacher.changeLessonType(\""+idSpisokLessonType+"\");'>Изменить мероприятие</span></div>\
                                    </div>\
                                </div>\
                                <ul>\
                                    <li><a href='#tabs-1'>Добавить</a></li>\
                                    <li><a href='#tabs-2'>Изменить</a></li>";
        insertHTML += "             <li><a href='' ";
        
        if(countLessonTypes > 1)
            insertHTML += "onclick='JournalTeacher.deleteLessonType("+idLesson+","+idSpisokLessonType+")'";

        insertHTML += ">Удалить</a></li>\
                                </ul>\
                            </div>\
                        </div>\
                    </div>";
        return insertHTML;
    },

    createLessonTypePopup : function(post){

        var sps = post.split("_");
        var number = sps[1];
        var idLesson = sps[3];
        var idSpisokLessonType = sps[5];

        var el = $("#type_" + sps[1]);
        var test_2 = el;

        test_2.bind("afterShowPopup", function (e){
                                    el = $(e.target);
                                    el.css("background-color","");
                                    el.addClass("isPressed");
                                    });             
      
        test_2.bind("closedPopup", function (e) {
                                    el = $(e.target);
                                    // el.css("background", "#FEFFCC");
                                    el.removeClass("isPressed");
                                    if(el.hasClass("isHovered")){
                                       el.css("background-color","#FEFFCC");
                                    }
                                    });

        test_2.cPopup('open', {
                  data: {},
                  body: JournalTeacher.getLessonTypeUI(number, idLesson, idSpisokLessonType),
              });

        $( "#tabs" ).tabs();
        if(countLessonTypes <= 1)
            $( "#tabs" ).tabs( "disable", 2 );
        // Hover states on the static widgets
        $( "#dialog-link, #icons li" ).hover(
            function() {
                $( this ).addClass( "ui-state-hover" );
            },
            function() {
                $( this ).removeClass( "ui-state-hover" );
            }
        );
    },


















    /*******************************/
    /*** Hovers для ячеек таблицы***/
    /*******************************/

    mouseOverOutMark: function(post, background, state){
        var sps = post.split("_");
        var currentMark = ge(post);
        var hometask = $("td [id ^=hometask][id $=_"+sps[3]+"]:not(.isPressed)").css("background", background);
        
        /***************************************************/
        var setMarksHorizontalIsHovered = $("td [id ^=mark_"+sps[1]+"_"+sps[2]+"_]");
        var mark = $("td [id ^=mark_"+sps[1]+"_"+sps[2]+"_]:not(.isPressed)");
            mark.css("background", background)

        /***************************************************/
        var setMarksVerticalIsHovered = $("td [id $=_"+sps[3]+"][id ^=mark_]");
        var marksUpDown = $("td [id $=_"+sps[3]+"][id ^=mark_]:not(.isPressed)");
            marksUpDown.css("background", background)
        
        /***************************************************/
        var avg = $("td [id ^=avg_"+sps[1]+"_"+sps[2]+"_]").css("background", background);
        if(avg)
        {
            var avgUpDown = $("td[id $= "+sps[4]+"][id ^=avg_]").css("background", background);
            var result = ge("result_"+sps[4]).css("background", background);
        }
        var rowLearner = $("td [id^=learner_"+sps[1]+"_"+sps[2]+"_]").css("background",background);
        var setTypeIsHovered = ge("type_"+sps[3]);
        var columnType = ge("type_"+sps[3]+":not(.isPressed)");
            columnType.css("background", background);
        var columnDate = ge("date_"+sps[3]+":not(.isPressed)").css("background", background);

        if(state == true)
        {
            setMarksHorizontalIsHovered.addClass('isHovered'); 
            setMarksVerticalIsHovered.addClass('isHovered');
            setTypeIsHovered.addClass('isHovered');
        }
        else
        {
            setMarksHorizontalIsHovered.removeClass('isHovered');
            setMarksVerticalIsHovered.removeClass('isHovered');
            setTypeIsHovered.removeClass('isHovered');
        }
    },
    mouseOverMark: function(post){
        JournalTeacher.mouseOverOutMark(post, '#FEFFCC', true);
    },
    mouseOutMark: function(post){
        JournalTeacher.mouseOverOutMark(post, '#FFFFFF', false);
    },


    mouseOverOutLearner: function(post, background, state){
        var sps = post.split("_");
        
        /***************************************************/
        var mark = $("td [id ^=mark_"+sps[0]+"_"+sps[1]+"_]:not(.isPressed)").css("background", background);
        var setMarksHorizontalIsHovered = $("td [id ^=mark_"+sps[0]+"_"+sps[1]+"_]");
            mark.css("background", background);
        if(state == true)
            setMarksHorizontalIsHovered.addClass('isHovered');
        else
            setMarksHorizontalIsHovered.removeClass('isHovered');

        var avg = $("td [id ^=avg_"+sps[0]+"_"+sps[1]+"_]").css("background", background);
        var rowLearner = ge("learner_"+post).css("background", background);
    },
    mouseOverLearner: function(post){
        JournalTeacher.mouseOverOutLearner(post, "#FEFFCC", true);
    },
    mouseOutLearner: function(post){
        JournalTeacher.mouseOverOutLearner(post, "#FFFFFF", false);
    },


    mouseOverOutType: function(post, background, state){
        var setMarksHorizontalIsHovered = $("td[id $=_"+post+"][id^=mark_]");
        var mark = $("td[id $=_"+post+"][id^=mark_]:not(.isPressed)").css("background", background);
            mark.css("background", background);
        /******************************************/

        var hometask = $("td [id ^=hometask][id $=_"+post+"]:not(.isPressed)").css("background", background);
        var setTypeIsHovered1 = ge("type_"+post);
        var columnType = ge("type_"+post+":not(.isPressed)");
            columnType.css("background", background);
        var setTypeIsHovered2 = ge("date_"+post);
        var columnDate = ge("date_"+post+":not(.isPressed)");
            columnDate.css("background", background);
        if(state == true)
        {
            setMarksHorizontalIsHovered.addClass('isHovered');
            setTypeIsHovered1.addClass('isHovered');
            setTypeIsHovered2.addClass('isHovered');
        }
        else
        {
            setMarksHorizontalIsHovered.removeClass('isHovered');
            setTypeIsHovered1.removeClass('isHovered');
            setTypeIsHovered2.removeClass('isHovered');
        }
    },
    mouseOverType: function(post){
        JournalTeacher.mouseOverOutType(post, "#FEFFCC", true);
    },
    mouseOutType: function(post){
        JournalTeacher.mouseOverOutType(post, "#FFFFFF", false);
    },


    mouseOverOutResult: function(post, background){
        var avg = $("td[id $= "+post+"][id^=avg_]").css("background", background);
        var result = ge("result_"+post).css("background", background);
    },
    mouseOverResult: function(post){
        JournalTeacher.mouseOverOutResult(post, "#FEFFCC");
    },
    mouseOutResult: function(post){
        JournalTeacher.mouseOverOutResult(post, "#FFFFFF");
    },


    mouseOverOutHometask: function(post, background, state){
        var sps = post.split("_");
        var setMarksVerticalIsHovered = $("td [id ^=mark_][id $=_"+sps[2]+"]");
        var marksUpDown = $("td [id ^=mark_][id $=_"+sps[2]+"]:not(.isPressed)");
            marksUpDown.css("background", background)

        var setHometaskHorizontalHovered = $("td [id ^=hometask_"+sps[1]+"]");
        var hometask = $("td [id ^=hometask_"+sps[1]+"]:not(.isPressed)");
            hometask.css("background", background);
            
        var setHometaskUpDownHovered = $("td [id ^=hometask][id $=_"+sps[2]+"]");
        var hometaskUpDown = $("td [id ^=hometask][id $=_"+sps[2]+"]:not(.isPressed)");
            hometaskUpDown.css("background", background);
        var textHometask = ge("group_"+sps[1]).css("background", background);

        var setTypeIsHovered1 = ge("type_"+sps[2]);
        var columnType = ge("type_"+sps[2]+":not(.isPressed)");
            columnType.css("background", background);
        var setTypeIsHovered2 = ge("date_"+sps[2]);
        var columnDate = ge("date_"+sps[2]+":not(.isPressed)");
            columnDate.css("background", background);
        
        var columnType = ge("type_"+sps[2]+":not(.isPressed)").css("background", background);
        var columnDate = ge("date_"+sps[2]+":not(.isPressed)").css("background", background);
        

        if(state == true)
        {
            setMarksVerticalIsHovered.addClass('isHovered');
            setHometaskHorizontalHovered.addClass('isHovered');
            // setHometaskUpDownHovered.addClass('isHovered');
            setTypeIsHovered1.addClass('isHovered');
            setTypeIsHovered2.addClass('isHovered');
        }
        else
        {
            setMarksVerticalIsHovered.removeClass('isHovered');
            setHometaskHorizontalHovered.removeClass('isHovered');
            // setHometaskUpDownHovered.removeClass('isHovered');
            setTypeIsHovered1.removeClass('isHovered');
            setTypeIsHovered2.removeClass('isHovered');
        }
    },
    mouseOverHometask: function(post){
        JournalTeacher.mouseOverOutHometask(post, "#FEFFCC", true);
    },
    mouseOutHometask: function(post){
        JournalTeacher.mouseOverOutHometask(post, "#FFFFFF", false);  
    },


    mouseOverOutTextHometask: function(post, background){
        var hometask = $("td [id ^=hometask_"+post+"]:not(.isPressed)").css("background", background);
        var textHometask = ge("group_"+post).css("background", background);
    },
    mouseOverTextHometask: function(post){
        JournalTeacher.mouseOverOutTextHometask(post, "#FEFFCC");
    },
    mouseOutTextHometask: function(post){
        JournalTeacher.mouseOverOutTextHometask(post, "#FFFFFF");
    },


};