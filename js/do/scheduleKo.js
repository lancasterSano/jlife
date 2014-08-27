/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var scheduleKo = {
    loadLessons: function(date, idSelected, type, idKo, nameSelected){// type - тип разреза расписания
        var ret = null;
        ajax.post_sync(
            "/do/scheduleKo/getLessonsByDay.php",
            "id="+idSelected+"&dateL="+date+"&type="+type+"&idK="+idKo+"&n="+nameSelected,
            function(response) {
                ret = response;
            },
            function() {
        
            }
        );
        return ret;
    },
    loadLessonsUI: function(date, idSelected, type, idKo, nameSelected, isToday, isTomorrow) {
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
            var lessons = scheduleKo.loadLessons(date, idSelected, type, idKo, nameSelected);
            console.log(lessons);
            insHTML = scheduleKo.prepareLessonsUI(date, lessons);
            $("#day_"+date).append(insHTML);
            $("#timetable_"+date).fadeIn("slow");
        }
    },
    prepareLessonsUI: function(date, lessons) {
        var insHTML = "";
        var tddzclass = "";
        var tdaudclass = "";
        insHTML += '<table id="timetable_'+date+'" class="dayCont" cellspacing="0" cellpadding="0" style="display: none;"><tbody>';
        if(lessons) {
            for (var number in lessons){
                for(var idsubgroup in lessons[number]){
                    var hometask, cabinet, fioteacher;
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
                    if(lessons[number][idsubgroup]["fioteacher"] == null) {
                        fioteacher = "&nbsp";
                    } else {
                        fioteacher = lessons[number][idsubgroup]["fioteacher"];
                    }
                    lesson = {
                        id : lessons[number][idsubgroup]["id"],
                        number: number,
                        color: lessons[number][idsubgroup]["color"],
                        hometask: hometask,
                        cabinet: cabinet,
                        idsubgroup: lessons[number][idsubgroup]["idsubgroup"],
                        idsubject: lessons[number][idsubgroup]["idsubject"],
                        subjectname: lessons[number][idsubgroup]["subjectname"],
                        islast: lessons[number][idsubgroup]["islast"],
                        lessontypes: lessons[number][idsubgroup]["lessontypes"],
                        idteacher: lessons[number][idsubgroup]["idteacher"],
                        nameclassgroup: lessons[number][idsubgroup]["nameclassgroup"],
                        fioteacher: fioteacher
                    };
                    insHTML += '<tr>\
                                    <td class="number" style="background: '+lesson.color+'; border-bottom: 3px solid '+lesson.color+';">\
                                        <span>'+lesson.number+'</span>\
                                    </td>';
                    if(lesson.idsubgroup != 0){
                        insHTML += '<td class="lesName" style="border-bottom:3px solid '+lesson.color+';">\
                                        <a href="">\
                                            <span>'+lesson.subjectname+'</span>\
                                        </a>\
                                        <span class="descript">'+lesson.nameclassgroup+'</span>\
                                    </td>';
                    } else {
                        insHTML += '<td class="lesName" style="border-bottom:3px solid '+lesson.color+';">\
                                        <span>'+lesson.subjectname+'</span>\
                                    </td>';  
                    }
                    if(lesson.islast) {tddzclass = "dzL";} else {tddzclass = "dz";}
                    insHTML +=  '<td class="'+tddzclass+'">\
                                    <div class="first">\
                                        <span>'+lesson.fioteacher+'</span>\
                                    </div>';

                    insHTML += '    <div class="last">';
                    if(lesson.lessontypes !== undefined) {
                        for(var i in lesson.lessontypes){
                            lessontype = {
                                id : lesson.lessontypes[i]["id"],
                                name : lesson.lessontypes[i]["name"],
                                description : lesson.lessontypes[i]["description"],
                                islastlessontype : lesson.lessontypes[i]["islastlessontype"]
                            };
                            if(lessontype.islastlessontype) {
                                insHTML += '<span>'+lessontype.description+'</span>';
                            } else {
                                insHTML += '<span>'+lessontype.description+'</span>,';
                            }
                        }
                    } else {
                        insHTML += '<span>&nbsp;</span>';
                    }
                    insHTML +=      '</div>\
                                </td>';
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