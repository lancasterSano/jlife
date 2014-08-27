if(getCookie("loadLessonsUI") != undefined)
    console.log("Cookies:" + getCookie("loadLessonsUI"));

var ScheduleTeacher = {
    loadLessons: function(date, idteacher){
        var ret = null;
        ajax.post_sync(
            "/do/scheduleTeacher/getLessonsByDay.php",
            "id="+idteacher+"&dateL="+date,
            function(response) {
                ret = response;
            },
            function() {
        
            }
        );
        return ret;
    },
    loadLessonsUI: function(date, idteacher, idschool, isToday, isTomorrow) {
//        console.log("date - " + date);
//        console.log("ID Learner - " + idlearner);
        var opened;
        $("#daydate_"+date).toggleClass("opened");
        if($("#daydate_"+date).hasClass("opened")) {
            var arr = new Array();
            if(getCookie("loadLessonsUI") != undefined)
                arr.push(getCookie("loadLessonsUI"));
            arr.length > 0 ? arr.push(date) : arr[0] = date;
            setCookie("loadLessonsUI", arr, null);
            opened = true;
        } else {
            var arr;
            var curCookies = getCookie("loadLessonsUI");
            // изменить регулярное выражение!
            arr = curCookies.replace(date,'');
            deleteCookie("loadLessonsUI");
            setCookie("loadLessonsUI", arr, null);
            opened = false; 
        }
        console.log("Cookies:" + getCookie("loadLessonsUI"));

       if(($("#daydate_"+date).hasClass("dayDateR")) || 
           ($("#daydate_"+date).hasClass("dayDateRTd")) || 
           ($("#daydate_"+date).hasClass("dayDateRTm")) && opened) {
            if(isToday) {
                $("#daydate_"+date).removeClass("dayDateRTd").addClass("dayDate");    
            } else if(isTomorrow) {
                $("#daydate_"+date).removeClass("dayDateRTm").addClass("dayDate");
            } else {
                $("#daydate_"+date).removeClass("dayDateR").addClass("dayDate");
            }
        }
        if(($("#daydate_"+date).hasClass("dayDate")) && !opened) {
            if(isToday) {
                $("#daydate_"+date).removeClass("dayDate").addClass("dayDateRTd");
            } else if(isTomorrow) {
                $("#daydate_"+date).removeClass("dayDate").addClass("dayDateRTm");
            } else {
                $("#daydate_"+date).removeClass("dayDate").addClass("dayDateR");
            }
        }
       
        if($("#timetable_"+date).length) {
            if($("#daydate_"+date).hasClass("opened")) {
                $("#timetable_"+date).fadeIn("slow");
            } else {
               $("#timetable_"+date).fadeOut("slow");
            }
        } else {
            var insHTML = "";
            var lessons = ScheduleTeacher.loadLessons(date, idteacher);
            insHTML = ScheduleTeacher.prepareLessonsUI(lessons, idschool, date);
            $("#day_"+date).append(insHTML);
            $("#timetable_"+date).fadeIn("slow");
        }
    },
    prepareLessonsUI: function(lessons, idschool, date) {
        var insHTML = "";
        var tddzclass = "";
        var tdaudclass = "";
        var tdclclass = "";
        insHTML += '<table id="timetable_'+date+'" class="dayCont" cellspacing="0" cellpadding="0" style="display: none;"><tbody>';
        if(lessons) {
            for (var number in lessons){
                for (var idsubgroup in lessons[number]){
                    var hometask, cabinet;
                    if(lessons[number][idsubgroup]["hometask"] == null) {
                        hometask = "";
                    } else {
                        hometask = lessons[number][idsubgroup]["hometask"];
                    }
                    if(lessons[number][idsubgroup]["cabinet"] == null) {
                        cabinet = "";
                    } else {
                        cabinet = lessons[number][idsubgroup]["cabinet"];
                    }
                    lesson = {
                        id : lessons[number][idsubgroup]["id"],
                        color: lessons[number][idsubgroup]["color"],
                        number: lessons[number][idsubgroup]["number"],
                        idsubject: lessons[number][idsubgroup]["idsubject"],
                        subjectname: lessons[number][idsubgroup]["subjectname"],
                        islast: lessons[number][idsubgroup]["islast"],
                        idclass: lessons[number][idsubgroup]["idclass"],
                        nameclass: lessons[number][idsubgroup]["nameclass"],
                        cabinet: cabinet,
                        paragraphs: lessons[number][idsubgroup]["paragraphs"],
                        idsubgroup: lessons[number][idsubgroup]["idsubgroup"],
                        idmaterial: lessons[number][idsubgroup]["idmaterial"],
                        hometask: hometask
                    };
                    insHTML += '<tr>\
                                    <td class="number" style="background: '+lesson.color+'; border-bottom: 3px solid '+lesson.color+';">\
                                        <span>'+number+'</span>\
                                    </td>';
                    if(lesson.idsubgroup != 0){
                        insHTML += '<td class="lesName" style="border-bottom:3px solid '+lesson.color+';">';
                        if(lesson.idmaterial!=null){
                            insHTML += '<a href="sections.php?material='+lesson.idmaterial+'">';
                        } else {
                            insHTML += '<a href="sections.php">';
                        }
                                insHTML += '<span>'+lesson.subjectname+'</span>\
                                        </a>\
                                    </td>';
                    } else {
                        insHTML += '<td class="lesName" style="border-bottom:3px solid '+lesson.color+';">\
                                      <span>'+lesson.subjectname+'</span>\
                                    </td>';
                    }
                    if(lesson.islast) {tddzclass = "dzL";} else {tddzclass = "dz";}
                    insHTML +=      '<td class="'+tddzclass+'">\
                                        <div>';
                    for (var j in lesson.paragraphs){
                        var paragraph = {
                            id: lesson.paragraphs[j]["id"],
                            name: lesson.paragraphs[j]["name"],
                            number: lesson.paragraphs[j]["number"],
                            notstudy: lesson.paragraphs[j]["notstudy"],
                            partparagraphs: lesson.paragraphs[j]["partparagraphs"]
                        };
                        console.log(paragraph);
                        insHTML +=      '<span class="paragraphspan">';
                        if(paragraph.notstudy == "1"){
                            insHTML += '<a class="paragraphhref" style="color: red;" href="addpar.php?school='+idschool+'&paragraph='+paragraph.id+'">';
                        } else {
                            insHTML += '<a class="paragraphhref" href="addpar.php?school='+idschool+'&paragraph='+paragraph.id+'">';
                        }
                                            insHTML += '§'+paragraph.number+'. '+paragraph.name+'\
                                            </a>';
                        for (var z in paragraph.partparagraphs){
                            var partparagraph = {
                                id: paragraph.partparagraphs[z]["id"],
                                number: paragraph.partparagraphs[z]["number"]
                            };
                            if(paragraph.notstudy == "1"){
                                insHTML +=  '<a class="partparagraphhref" style="color:red" href="addpar.php?school='+idschool+'&paragraph='+paragraph.id+'#s_'+partparagraph.id+'">Часть '+partparagraph.number+' </a>';
                            } else {
                                insHTML +=  '<a class="partparagraphhref" href="addpar.php?school='+idschool+'&paragraph='+paragraph.id+'#s_'+partparagraph.id+'">Часть '+partparagraph.number+' </a>';
                            }
                        }
                        insHTML += '</span>';
                    }
                    insHTML += '</div>\
                                    <div>\
                                        <span>'+lesson.hometask+'</span>\
                                    </div>\
                                </td>';


                    if(lesson.islast) { tdclclass = "classL"; } else { tdclclass = "class"; }
                    if(lesson.idsubgroup!=0){
                    insHTML += '<td class="'+tdclclass+'">\
                                    <span>\
                                        <a href="cabinet.php?subject='+lesson.idsubject+'&class='+lesson.idclass+'&subgroup='+lesson.idsubgroup+'&s=3">'+lesson.nameclass+'</a>\
                                    </span>\
                                </td>';
                    } else {
                    insHTML += '<td class="'+tdclclass+'">\
                                </td>';
                    }
                    if(lesson.islast) { tdaudclass = "auditorL"; } else { tdaudclass = "auditor"; }
                    insHTML += '<td class="'+tdaudclass+'">\
                                    <span>'+lesson.cabinet+'</span>\
                                </td>\
                            </tr>';
                }
            }
        } else {
            insHTML += '<tr><td style="height: 20px;"></td></tr><tr><td style="text-align: center; font-weight: bold;">В этот день у Вас выходной!</td></tr><tr><td style="height: 20px;"> </td></tr>';;
        }
        insHTML += '</tbody></table>';
        return insHTML;
    }
};