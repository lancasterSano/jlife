$(document).ready(function(){
    window.tmpl.tmpl_notice_h = tmpl($("#notice-h").html()),
    window.tmpl.tmpl_notice_hb = tmpl($("#notice-hb").html()),
    window.tmpl.tmpl_notices = $("#notices").html();
    var manageClassBtns = $("div [id ^= help]"); 
    var manageTeacherBtns = $("div [id ^= teacherManage]"); 
    manageClassBtns.each(function(){
        groupsKo.initializeManageClassPopup($(this));
    }); 
    manageTeacherBtns.each(function(){
        groupsKo.initializeManageTeacherPopup($(this));
    }); 
    
});
var groupsKo = {
    initializeManageClassPopup: function(obj){
        var idClass = obj.attr("id").split("_")[1];
        obj.cPopup({
            idWND: "rf_wnd_popupClass" + idClass,
            btns: window._NONE,
            location : {
                position: "default",
                offset: { left: 0, top: 0, right: 0, bottom: 50 },
             proportions: { width: undefined, height: undefined },
                quarters: [4, 3, 1, 2]
            }
        });
    },
    initializeManageTeacherPopup: function(obj){
        var idTeacher = obj.attr("id").split("_")[1];
        obj.cPopup({
            idWND: "rf_wnd_popupManageTeacher" + idTeacher,
            btns: window._NONE,
            location : {
                position: "default",
                offset: { left: 0, top: 0, right: 0, bottom: 50 },
             proportions: { width: undefined, height: undefined },
                quarters: [3, 1, 4, 2]
            }
        });
    },
    initializeManageClassSubjectPopup: function(obj){
        var idClass = obj.attr("id").split("_")[1];
        var idSubject = obj.attr("id").split("_")[2];
        var idGroup = obj.attr("id").split("_")[3];
        obj.cPopup({
            idWND: "rf_wnd_popupClassSubject"+idClass+"_"+idSubject+"_"+idGroup,
            btns: window._NONE,
            location : {
                position: "default",
                offset: { left: 0, top: 0, right: 0, bottom: 50 },
             proportions: { width: undefined, height: undefined },
                quarters: [4, 3, 1, 2]
            }
        });
    },
    drawAddClass: function(level, idschool){
        // DECLARE VARIABLES AND ASSIGN VALUES
        var teachers = null;
        var nextclass = null;
        var letter = null;
        var insHTML = '';
        
        // GET NEXT CLASS ACCORDING TO CURRENT LEVEL
        ajax.post_sync(
            'do/groupsKo/getNextClass.php',
            's=' + idschool + '&l=' + level,
            function(response){
               nextclass = response['name'];
               letter = response['letter'];
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus + '.<br>Error thrown: '+ errorThrown + '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
        console.log(nextclass);
        
        // GET TEACHERS FROM DB
        ajax.post_sync(
            'do/groupsKo/loadTeachers.php',
            's=' + idschool,
            function(response){
                teachers = response;
            },
            function(error){
                console.log(error);
            }
        );
        console.log(teachers);
        
        // FORM HTML TO INSERT IN TR
        if(nextclass) {
            insHTML += '<td>\
                          <div class="roll"></div>\
                          <div class="group"><span id="addedClass_'+level+'">'+nextclass+'</span></div>\
                          <div class="stNumb"><span>0</span></div>\
                          <div class="classLead classLeadSelect">\
                            <select id="addselect_'+level+'">';
                      insHTML += '<option selected="" value="0">Не назначен</option>';
                    for (var i in teachers){
                        teacher = {
                            id: teachers[i].id,
                            FI: teachers[i].FI
                        };
                        // тут нужно загрузить тичеров в опшны
                        insHTML += '<option value="'+teacher.id+'">'+teacher.FI+'</option>';
                    }
                    insHTML += '</select>\
                          </div>\
                          <div class="manage">\
                            <span onclick="groupsKo.addClass('+level+','+idschool+')">Добавить</span>\
                            <span onclick="groupsKo.cancelAdding('+level+','+idschool+');">Отменить</span>\
                          </div>\
                        </td>';
        } else {
            insHTML += '<td><span style="color:red}">Превышен лимит на количество классов</span>\
                        <div class="manage">\
                            <span onclick="groupsKo.cancelAdding('+level+','+idschool+');">Ок</span>\
                            </div>\
                        </td>';
        }
        
        // GET ADDING CLASS TR
        var addClassTd = $('#addClass_' + level);
        
        // EMPTY ADDING CLASS TR AND HIDE IT
        addClassTd.empty().css('display', 'none');
        
        // SET CLASS TO ELEMENT (TR HAS ADD CLASS CONTENT)
        if(nextclass) {
            addClassTd.addClass('addClass'); 
        }
        
        // ADD HTML TO DOM AND SHOW IT
        addClassTd.append(insHTML).show(500);
        
    },
    cancelAdding: function(level, idschool){
        // GET ADDING CLASS TR
        var addClassTd = $('#addClass_' + level);
         
        // EMPTY ADDING CLASS TR AND HIDE IT
        addClassTd.empty().css('display', 'none');
        
        // FORM HTML OF TR AND APPEND IT
        var insHTML = '<td>\
                        <span class="addNewClass" \
                              onclick="groupsKo.drawAddClass('+level+','+idschool+');">\
                          Добавить новый класс\
                        </span>\
                      </td>';
        addClassTd.append(insHTML);
        
        // SET CLASS TO ADDING CLASS TR (CHANGES CSS ATTRIBUTES FOR CORRECT SHOW) AND SHOW IT
        addClassTd.removeClass('addClass').show(500);
    },
    addClass: function(level, idschool) {
        //DECLARE V-BLES
        var status = null;
        var insHTML = "";
        var idYodaProfile = null;
        var FIOYoda = null;
        var idClass = null;
        var idYoda = null;

        // GET DATA FROM DOM
        var nameclass = $("#addedClass_"+level).text();
        var letter = nameclass.substr(nameclass.length - 1); 
        var teachersID = $("#addselect_"+level).val();
        
        // 1.ADD CLASS TO DATABASE
        ajax.post_sync(
            "do/groupsKo/createClass.php",
            "s=" + idschool + "&let=" + letter + "&lvl="+ level + "&t=" + teachersID,
            function(response){
                status = response["status"];
                idYodaProfile = response["idYodaProfile"];
                FIOYoda = response["FIOYoda"];
                idYoda = response["idYoda"];
                idClass = response["idClass"];
            },
            function(error){
                console.log(error);
            }
        );
        if(status == "addclasswithyoda" || status == "addclasswithoutyoda") {
            var isYoda;
            if(status == "addclasswithoutyoda") {
                isYoda = false;
            } else if(status = "addclasswithyoda") {
                isYoda = true;
            }
        // 2.REFRESH PAGE
            $("#addClass_" + level).empty();
            $("#addClass_" + level).removeClass("addClass");
            $("#addClass_" + level).append('<td>\
                <span class="addNewClass" \
                      onclick="groupsKo.drawAddClass('+level+','+idschool+');">\
                        Добавить новый класс\
                </span>\
            </td>');
            if(!idYoda){
                idYoda = "0";
            }
            insHTML += '<tr class="default" id="class_'+idClass+'">\
            <td>\
              <div class="roll" onclick="groupsKo.loadClassSubjectsUI('+idClass+','+idschool+')"></div>\
              <div class="group"><span>'+nameclass+'</span></div>\
              <div class="stNumb"><span>0</span></div>\
              <div class="classLead" id="yoda_'+idClass+'">\
                <span>';
                    if(isYoda){
                        insHTML += '<a href="/pages/index.php?id='+idYodaProfile+'">'+FIOYoda+'</a>';
                    } else {
                        insHTML += 'Не назначен';
                    }
                  
            insHTML += '</span>\
                    </div>\
                    <div class="manage" id="manage_'+idClass+'">\
                      <span><a href=""><img src="../../img/doc.png">Журнал</a></span>\
                      <span><a href=""><img src="../../img/phot.png">Расписание</a></span>\
                    </div>\
                    <div id="help_'+idClass+'" class="help" \
                         onclick="groupsKo.drawPopupClass($(this), '+idClass+', '+idschool+', '+idYoda+','+idYodaProfile+');">\
                    </div>\
                  </td>\
                </tr>';
            $("#addClass_"+level).before(insHTML);
            $("#addClass_"+level).removeClass("hiddenParralel");
            $("#headClass_"+level).removeClass("hiddenParralel");
            groupsKo.initializeManageClassPopup($("#help_"+idClass));
        }
    }, 
    loadClassSubjectsUI: function(idClass, idschool){
        // DECLARE VARIABLES AND ASSIGN VALUES
        var insHTML = "";
        var subgroups = null;
        
        if($("#info_class_"+idClass).length){
            $("#info_class_"+idClass).toggle(500);
        } else {
            // GET SUBJECTS FROM DB WITH TEACHERS WHO TEACH THAT SUBJECT AND GROUPS OF THIS SUBJECT
            subgroups = groupsKo.loadSubjectsDB(idClass);

            // PRERARE HTML_CODE
            insHTML = groupsKo.prepareSubjectsUI(subgroups, idClass, idschool);

            // APPEND GENERATED HTML TO MODEL
            $("#class_"+idClass).after(insHTML);
            $("#info_class_"+idClass).show(500);
            var popupManageClassSubjectButtons = $("div [id^=helpTd_"+idClass+"]");
            popupManageClassSubjectButtons.each(function(){
                groupsKo.initializeManageClassSubjectPopup($(this));
            }); 
        }
    },
    loadSubjectsDB: function(idClass){
        var subgroups = null;
        ajax.post_sync(
            "do/groupsKo/getSubjectsOfClass.php",
            "c=" + idClass,
            function (response){
                subgroups = response;
            },
            function (error){
                alert(error);
            }
        );
        return subgroups;
    }, 
    prepareSubjectsUI: function(subgroups, idClass, idschool){
        var insHTML = "";
        if(subgroups) {
            insHTML += '<tr style="display:none;" id="info_class_'+idClass+'" class="info">\n\
                          <td>\
                            <table cellspacing="0" cellpadding="0"><tbody>\
                              <tr class="headB">\
                                <td colspan="4"></td>\
                              </tr>';
            for (var i in subgroups[idClass]) {
                var subject = {
                    id: subgroups[idClass][i]["idsubject"],
                    name: subgroups[idClass][i]["namesubject"],
                    groups: subgroups[idClass][i]["groups"]
                };
                    insHTML += '';
                var idteacherforfunc;
                if(subject.groups.length == 1) {
                    if(!subject.groups[0]["TeacherID"]) {
                        idteacherforfunc = "0";
                    } else {
                        idteacherforfunc = subject.groups[0]["TeacherID"];
                    }
                  insHTML += '<tr class="info" id="classsubjectgroup_'+idClass+'_'+subject.id+'_'+subject.groups[0]["idgroup"]+'">\
                                <td class="discName"><span>'+subject.name+'</span></td>\
                                <td class="leader" style="border-right:none;" id="leader_'+idClass+'_'+subject.id+'_'+subject.groups[0]["idgroup"]+'"><span>'+subject.groups[0]["TeacherFIO"]+'</span></td>\
                                <td class="infoGr" id="infogr_'+idClass+'_'+subject.id+'_'+subject.groups[0]["idgroup"]+'"><span></span></td>\
                                <td class="helpTd" id="helpTd_'+idClass+'_'+subject.id+'_'+subject.groups[0]["idgroup"]+'"\n\
                                 onclick="groupsKo.drawPopupClassSubject($(this), '+idClass+','+subject.id+','+subject.groups[0]["idgroup"]+','+idteacherforfunc+','+idschool+');"></td>\
                              </tr>';
                                  
                } else if(subject.groups.length >= 2){
                    for(var j in subject.groups){
                        var group = {
                            id: subject.groups[j]["idgroup"],
                            name: subject.groups[j]["namegroup"],
                            teacherFIO: subject.groups[j]["TeacherFIO"],
                            teacherID: subject.groups[j]["TeacherID"]
                        };
                        if(!group.teacherID) {
                            idteacherforfunc = "0";
                        } else {
                            idteacherforfunc = group.teacherID;
                        }
                        insHTML += '<tr class="info" id="classsubjectgroup_'+idClass+'_'+subject.id+'_'+group.id+'">';
                        if(j == 0) {
                            insHTML += '<td rowspan="'+subject.groups.length+'" class="discName"><span>'+subject.name+'</span></td>';
                        }
                        insHTML += '<td class="leader" id="leader_'+idClass+'_'+subject.id+'_'+group.id+'"><span>'+group.teacherFIO+'</span></td>\
                                      <td class="infoGr" id="infogr_'+idClass+'_'+subject.id+'_'+group.id+'"><span>'+group.name+'</span></td>\
                                      <td class="helpTd" id="helpTd_'+idClass+'_'+subject.id+'_'+group.id+'" \n\
                                        onclick="groupsKo.drawPopupClassSubject($(this), ' + idClass + ',' + subject.id + ',' + group.id + ',' + idteacherforfunc +','+idschool+');"></td>\
                                    </tr>';
                    }
                }
                
            }
            insHTML += '<tr class="addNewSubj" id="addNewSubj_'+idClass+'">\
                          <td colspan="4"><span onclick="groupsKo.drawAddSubject('+idClass+','+idschool+');">Добавить предмет для класса</span></td>\
                        </tr>\
                      </tbody>\
                    </table>\
                  </td>\
                </tr>';
        } else {
            insHTML += '<tr style="display:none;" id="info_class_'+idClass+'" class="info">\
                          <td>\
                            <table cellspacing="0" cellpadding="0"><tbody>\
                              <tr class="headB">\
                                <td colspan="4"></td>\
                              </tr>';
                    insHTML += '<tr class="addNewSubj" id="addNewSubj_'+idClass+'">\
                                  <td colspan="4"><span onclick="groupsKo.drawAddSubject('+idClass+','+idschool+');">Добавить предмет для класса</span></td>\
                                </tr>\
                              </tbody>\
                            </table>\
                          </td>\
                        </tr>';
        }
        return insHTML;
    },
    drawAddSubject: function(idclass, idschool){
        // DECLARE VARIABLES AND ASSIGN VALUES
        var subjects = null;
        var subgroups = null;
        var subjectsUnassigned = Array();
        var insHTML = "";
        
        // GET ARRAY OF SUBJECTS FROM DB AND ARRAY OF SUBGROUPS
        ajax.post_sync(
            "do/groupsKo/getSubjectsAndSubgroups.php",
            "c=" + idclass,
            function(response){
                subjects = response["subjects"];
                subgroups = response["subgroups"];
            },
            function(error){
                alert(error);
            }
        );
        console.log(subjects);
        console.log(subgroups);
        // FORM ARRAY OF UNASSIGNED SUBJECTS FROM 2 ARRAYS
        for(var i in subjects){
            var idsubject_subjects = subjects[i]["id"];
            var isSubjectAssigned = false;
            for(var j in subgroups){
                var idsubject_subgroups = subgroups[j]["subjectS_id"];
                if(idsubject_subjects == idsubject_subgroups){
                    isSubjectAssigned = true;
                }
            }
            if(!isSubjectAssigned) {
                var subjectUnassigned = {
                    id: subjects[i]["id"],
                    name: subjects[i]["name"],
                    complexity: subjects[i]["complexity"]
                };
                subjectsUnassigned.push(subjectUnassigned);
            }
        }
        
        // FORM HTML
        insHTML += '  <td class="selectSubj">\
                        <div class="selectCSS">\
                          <select id="addSubj_selectSubj_'+idclass+'" onchange="groupsKo.loadTeachers('+idclass+','+idschool+');">\
                            <option selected="" value="0">---</option>';
            for(var i in subjectsUnassigned) {
                insHTML += '<option value="'+subjectsUnassigned[i]["id"]+'">'+subjectsUnassigned[i]["name"]+' '+subjectsUnassigned[i]["complexity"]+'</option>';
            }
              insHTML += '</select>\
                        </div>\
                      </td>\
                      <td class="selectTeach">\
                        <div class="selectCSS">\
                          <select id="addSubj_selectTeach_'+idclass+'">\
                            <option selected="" value="0">---</option>';
              insHTML += '</select>\
                        </div>\
                      </td>\
                      <td class="ready" colspan="2"><span id="addSubjSpan_'+idclass+'">Добавить</span>\
                        <span onclick="groupsKo.cancelAddingSubject('+idclass+')">Отменить</span>\
                      </td>';
            $("#addNewSubj_"+idclass).empty();
            $("#addNewSubj_"+idclass).removeClass("addNewSubj").addClass("addSubjSec");
            $("#addNewSubj_"+idclass).append(insHTML);
            $("#addSubjSpan_"+idclass).css("color", "#ccc");
    },
    cancelAddingSubject: function(idclass, idschool){
         // EMPTY ADDING CLASS TR
        $("#addNewSubj_" + idclass).empty();
        
        // SET CLASS TO ELEMENT (TR HAS ADD CLASS BUTTON)
        $("#addNewSubj_" + idclass).removeClass("addSubjSec").addClass("addNewSubj");
        
        // FORM HTML OF TR WITH ADD CLASS BUTTON
        $("#addNewSubj_" + idclass).append('<td colspan="4"><span onclick="groupsKo.drawAddSubject('+idclass+','+idschool+');">Добавить предмет для класса</span>');
    },
    loadTeachers: function(idclass, idschool){
        // DECLARE VARIAVLES NEEDED LATER
        var teachers = null;
        var insHTML = "";
        var isError = null;
        
        // GET IDSUBJECT FROM DOM
        var idsubject = $("#addSubj_selectSubj_"+idclass).val();
        if(idsubject != "0") {
        // GET TEACHERS FROM DB WHO CAN TEACH THIS SUBJECT
            ajax.post_sync(
                "do/groupsKo/getTeachers.php",
                "s=" + idsubject + "&sch=" + idschool,
                function(response){
                    teachers = response;
                },
                function(error){
                    isError = true;
                    alert(error);
                }
            );
            if(!isError) {
                if(teachers) {
                    console.log(teachers);
                    insHTML += '<option selected="" value="0">Не назначен</option>';
                    for(var i in teachers) {
                        var teacher = {
                            id: teachers[i]["id"],
                            name: teachers[i]["name"]
                        };

                        insHTML += '<option value="'+teacher.id+'">'+teacher.name+'</option>';
                    }
                } else {
                    insHTML += '<option selected="" value="0">Не назначен</option>';
                    console.log("Нет учителей по этому предмету");
                }
            }
        } else {
            insHTML += '<option selected="" value="-1">---</option>';
            console.log("Ничего не выбрано");
        }
        $("#addSubj_selectTeach_"+idclass).empty();
        $("#addSubj_selectTeach_"+idclass).append(insHTML);
        if(idsubject != "0") {
            $("#addSubjSpan_"+idclass).css("color", "#8eb4e3");
            $("#addSubjSpan_"+idclass).bind("click", function(){
                groupsKo.addSubjectUI(idclass, idschool);
            });
            $("#addSubjSpan_"+idclass).bind("mouseenter", function(){
                $("#addSubjSpan_"+idclass).css("color", "#7093bf");
            });
            $("#addSubjSpan_"+idclass).bind("mouseleave", function(){
                $("#addSubjSpan_"+idclass).css("color", "#8eb4e3");
            });
        } else {
            $("#addSubjSpan_"+idclass).css("color", "#cccccc");
            $("#addSubjSpan_"+idclass).unbind("click");
            $("#addSubjSpan_"+idclass).unbind("mouseenter");
            $("#addSubjSpan_"+idclass).unbind("mouseleave");
        }
    },
    addSubjectUI: function(idclass, idschool){
        // DECLARE VARIABLES NEEDED LATER
        var insHTML = "";
        var idsubject = null;
        var namesubject = null;
        var idteacher = null;
        var nameteacher = null;
        var idgroup = null;
        
        // GET DATA FROM DOM
        idsubject = $("#addSubj_selectSubj_"+idclass+" option:selected").val();
        namesubject = $("#addSubj_selectSubj_"+idclass+" option:selected").text();
        idteacher = $("#addSubj_selectTeach_"+idclass+" option:selected").val();
        nameteacher = $("#addSubj_selectTeach_"+idclass+" option:selected").text();
        
        if(idteacher == "0") {
            idteacher = null;
            nameteacher = "Не назначен";
        }
//        console.log(idsubject);
//        console.log(idteacher);
        
        
        // ADD TO DB
        ajax.post_sync(
            "do/groupsKo/setSubjectClass.php",
            "c=" + idclass + "&t=" + idteacher + "&s=" + idsubject,
            function(response){
                idgroup = response["idgroup"];
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus + '.<br>Error thrown: '+
                        errorThrown + '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
        if(idteacher == null){
            idteacher = "0";
        }
        // FORM HTML
        insHTML += '<tr class="info" id="classsubjectgroup_'+idclass+'_'+idsubject+'_'+idgroup+'" style="display:none;">\
                      <td class="discName"><span>'+namesubject+'</span></td>\
                      <td class="leader" id="leader_'+idclass+'_'+idsubject+'_'+idgroup+'"><span>'+nameteacher+'</span></td>\
                      <td class="infoGr" id="infogr_'+idclass+'_'+idsubject+'_'+idgroup+'"><span>Group 1</span></td>\n\
                      <td class="helpTd" id="helpTd_'+idclass+'_'+idsubject+'_'+idgroup+'" \n\
                                         onclick="groupsKo.drawPopupClassSubject($(this), '+idclass+','+idsubject+','+idgroup+','+idteacher+','+idschool+')"></td>\n\
                    </tr>';
        
        // REFRESH DOM
        $("#addNewSubj_"+idclass).before(insHTML);
        $('#classsubjectgroup_'+idclass+'_'+idsubject+'_'+idgroup).show(500);
        groupsKo.cancelAddingSubject(idclass, idschool);
        groupsKo.initializeManageClassSubjectPopup($("#helpTd_"+idclass+"_"+idsubject+"_"+idgroup));
    },
    drawPopupClass: function(obj, idclass, idschool, idyoda, idprofile, level){
        var status = "";
        if($("#info_class_"+idclass).length) {
            if($("#info_class_"+idclass).is(":visible")) {
                status = "visible";
            } else {
                status = "hidden";
            }
        } else {
            status = "noel";
        }
//      idWND: "popupClass_" + obj.attr("id"),
//      quarters: [4,3,1,2] /* 1, 2, 3, 4, default = 4 */

        obj.cPopup('open', {
            data: { idschool: idschool, idclass: idclass, status: status, idyoda: idyoda, idprofile: idprofile, level: level},
            body: groupsKo.preparePopupClassUI(idschool, idclass, status, idyoda, idprofile, level)
        });
    },
    
    preparePopupClassUI: function(_idschool, _idclass, _status, _idyoda, _idprofile, _level){
        var insHTML = "";
        var spanText = "";
        if(_status == "visible") {
            spanText = "Свернуть";
        } else if(_status == "hidden" || _status == "noel"){
            spanText = "Развернуть";
        }
        insHTML += '<div class="displayMenu" style="display:block; width: 180px;">\
                      <span class="manageClassSpan" id="expandMinimizeClassSpan_'+_idclass+'" onclick="groupsKo.showHideClassSubjects('+_idclass+','+_idschool+');">'+spanText+'</span>\
                      <span class="manageClassSpan" id="addSubjClassSpan_'+_idclass+'" onclick="groupsKo.addNewSubjectFromPopup('+_idclass+','+_idschool+');">Добавить предмет</span>\
                      <span class="manageClassSpan" id="editClassSpan_'+_idclass+'" onclick="groupsKo.editClass('+_idclass+','+_idschool+','+_idyoda+','+_idprofile+','+_level+')">Редактировать</span>\
                      <span class="manageClassSpan" id="delClassSpan_'+_idclass+'" onclick="groupsKo.deleteClass('+_idclass+','+_level+');">Удалить</span>\
                    </div>';
        return insHTML;
    },

    showHideClassSubjects: function(idclass, idschool){
        var infoclass = $("#info_class_"+idclass);
//        var infospan = $("#expandMinimizeClassSpan_"+idclass);
        if(infoclass.length) {
            if(infoclass.is(":visible")){
                infoclass.hide(500);
            } else {
                infoclass.show(500);
            }
        } else {
            groupsKo.loadClassSubjectsUI(idclass, idschool);
        }
        $("#help_"+idclass).cPopup("close");
    },
    addNewSubjectFromPopup: function(idclass, idschool){
        var infoclass = $("#info_class_"+idclass);
        if(infoclass.length) {
             if(!infoclass.is(":visible")){
                 infoclass.show(500);
             }
        } else {
            groupsKo.loadClassSubjectsUI(idclass, idschool);
        }
        groupsKo.drawAddSubject(idclass, idschool);
         $("#help_"+idclass).cPopup("close");
    },
    editClass: function(idclass, idschool, idyoda, idprofile, level){
        var insHTML = '';
        var teachers = null;
        var idteacher = null;

        // GET TEACHERS FROM DB
        ajax.post_sync(
            "do/groupsKo/loadTeachers.php",
            "s=" + idschool,
            function(response){
                teachers = response;
            },
            function(error){
                console.log(error);
            }
        );
            
        console.log("ID Yoda - "+idyoda);
            
        // FORM HTML FOR TEACHERS
        insHTML += '<select id="editClassSelect_'+idclass+'">';
        insHTML += '<option selected="" value="-1">Не назначен</option>';
        for(var i in teachers){
            teacher = {
                id: teachers[i].id,
                FI: teachers[i].FI
            };
            // тут нужно загрузить тичеров в опшны
            insHTML += '<option value="'+teacher.id+'">'+teacher.FI+'</option>';
        }
        insHTML += '</select>';
        
        var tdYoda = $("#yoda_"+idclass);
        tdYoda.empty().append(insHTML).addClass("classLeadSelect");
        
        // FORM HTML FOR MANAGE TD
        insHTML = '';
        insHTML += '<span onclick="groupsKo.saveEditedClass('+idclass+','+idschool+','+level+');">Добавить</span>\
                    <span onclick="groupsKo.cancelEditingClass('+idclass+','+idyoda+','+idprofile+','+idschool+');">Отменить</span></div>';
        var tdManage = $("#manage_"+idclass);
        tdManage.empty().append(insHTML);
        
        // DELETE POPUP BUTTON
        $("#help_"+idclass).remove();
        
        // GET ID TEACHER BY ID YODA
        ajax.post_sync(
            "do/groupsKo/getTeacherByYoda.php",
            "y=" + idyoda,
            function(response){
                idteacher = response["idteacher"];
            },
            function(XMLHttpRequest){
        
            }
        );
        
        // CHOOSE TEACHER IN COMBOBOX
        if(idyoda) {
            $('#editClassSelect_'+idclass).val(idteacher);
        } else {
            $('#editClassSelect_'+idclass).val("-1");
        }
        
        // REMOVE POPUP
        $("#popupClass_help_"+idclass).remove();
        
    },
    cancelEditingClass: function(idclass, idyoda, idprofile, idschool){
        // DECLARE VARIABLES NEEDED LATER
        var FIOYoda = null;
        var FIOInitialsYoda = null;
        var tdYoda = $("#yoda_"+idclass);
        var tdManage = $("#manage_"+idclass);
        var insHTML = '';
         
        // GET FIO YODA BY ID YODA
        ajax.post_sync(
        "do/groupsKo/getYoda.php",
            "y=" + idyoda,
            function(response){
                FIOYoda = response["FIOYoda"];
                FIOInitialsYoda = response["FIOInitialsYoda"];
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus + '.<br>Error thrown: '+ errorThrown + '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
        if(idyoda) {
            insHTML += '<span><a href="../../pages/index.php?id='+idprofile+'">'+FIOInitialsYoda+'</a></span>';
        } else {
            insHTML += '<span>Не назначен</span>';
        }
        tdYoda.empty().append(insHTML).removeClass("classLeadSelect");
        insHTML = '<span><a href=""><img src="../../img/doc.png">Журнал</a></span>\
                <span style="padding-right:0;"><a href=""><img src="../../img/phot.png">Расписание</a></span>';
        tdManage.empty().append(insHTML).after('<div id="help_'+idclass+'" class="help" onclick="groupsKo.drawPopupClass($(this), '+idclass+', '+idschool+','+idyoda+','+idprofile+');">\
              </div>');
        groupsKo.initializeManageClassPopup($("#help_"+idclass));
         
         
    },
    saveEditedClass: function(idClass, idSchool, level){
        // GET DATA FROM DOM
        var ajaxResponse = null;
        var insHTML = "";
        var idnewyoda = null;
        var idprofile = null;
        var idTeacher = $("#editClassSelect_"+idClass+" option:selected").val();
        var fioTeacher = $("#editClassSelect_"+idClass+" option:selected").text();
        console.log("ID Teacher - "+idTeacher+"\nFIO Teacher - "+fioTeacher);
        
        // SEND AJAX REQUEST
        ajax.post_sync(
            "do/groupsKo/setYodaClass.php",
            "c="+idClass+"&s="+idSchool+"&nt="+idTeacher,
            function(response){
                ajaxResponse = response;
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
            }
        );
            
        // RE-DRAW DOM
        switch(ajaxResponse["status"]){
            case "nothing":
            case "unsetYoda":
                insHTML ='<span>Не назначен</span>';
                idnewyoda = 0;
                idprofile = 0;
                break;
            case "changeYoda":
            case "setYoda":
                insHTML = '<span><a href="../../pages/index.php?id='+ajaxResponse["idprofile"]+'">'+ajaxResponse["fioInitials"]+'</a></span>';
                idnewyoda = ajaxResponse["idnewyoda"];
                idprofile = ajaxResponse["idprofile"];
                break;
        }
        $("#yoda_"+idClass).empty().append(insHTML).removeClass("classLeadSelect");
        insHTML = '<span><a href=""><img src="../../img/doc.png">Журнал</a></span>\
                <span style="padding-right:0;"><a href=""><img src="../../img/phot.png">Расписание</a></span>';
        $("#manage_"+idClass).empty().append(insHTML).after('<div id="help_'+idClass+'" class="help" onclick="groupsKo.drawPopupClass($(this), '+idClass+', '+idSchool+','+idnewyoda+','+idprofile+','+level+');">\
              </div>');
        groupsKo.initializeManageClassPopup($("#help_"+idClass));
        
    },
    deleteClass: function(idclass, level){
        // REMOVE POPUP
        $("#help_"+idclass).cPopup("close");
        
        var res = confirm("Вы действительно хотите удалить класс? Вы потеряете данные обо всех предметах, которые изучает класс, расписание для этого класса и учеников");
        if(res) {
            // SEND AJAX
            ajax.post_sync(
                "do/groupsKo/deleteClass.php",
                "c="+idclass,
                function(response){

                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert('Error textstatus: ' + textStatus + '.<br>Error thrown: '+
                            errorThrown + '<br>Xml: ' + XMLHttpRequest.responseText);
                }
            );

            // REFRESH DOM
            $("#class_"+idclass).hide(500, function(){
                $("#class_"+idclass).remove();
                // MAKE PARALLEL EMPTY
                if($("#headClass_"+level).next() == $("#addClass_"+level)) {
                    $("#headClass_"+level).addClass("hiddenParralel");
                    $("#addClass_"+level).addClass("hiddenParralel");
                }
            });
            $("#info_class_"+idclass).hide(500, function(){
                $("#info_class"+idclass).remove();
            });

        }
        
        
    },
    drawPopupClassSubject: function(obj, idclass, idsubject, idgroup, idteacher, idschool){
        if(!obj.cPopup('isInit')) {
            obj.cPopup('open', {
                data: { idTeacher: idteacher, idClass: idclass, idSubject: idsubject, idGroup: idgroup},
                body: groupsKo.preparePopupClassSubjectUI(idclass, idsubject, idgroup, idteacher, idschool)
            });
        } else obj.cPopup('open');
    },
    preparePopupClassSubjectUI: function(_idclass, _idsubject, _idgroup, _idteacher, _idschool){
        return tmpl($("#popupManageClassSubject").html(), 
                    {
                        idTeacher: _idteacher,
                        idSubject: _idsubject,
                        idGroup: _idgroup,
                        idClass: _idclass,
                        idSchool: _idschool
                    }
        );
    },
    drawAddNewGroup: function(idclass, idsubject, idschool){
        $("div [id ^= helpTd_"+idclass+"_"+idsubject+"]").each(function(){
            if($(this).cPopup("isShow")){
                $(this).cPopup("close");
            }
        });
        // DECLARE VARIABLES NEEDED LATER
        var minIDSubgroup = null;
        var maxIDSubgroup = null;
        var newrowspan = null;
        var insHTML = '';
        var teachers = null;
        
        // GET ID OF FIRST ROW IN SUBJECT AND ID OF LAST ROW OF SUBJECT
        ajax.post_sync(
            "do/groupsKo/getFirstLastSubgroupSubject.php",
            "c=" + idclass + "&s=" + idsubject,
            function(response){
                minIDSubgroup = response["minID"];
                maxIDSubgroup = response["maxID"];
                
            },
            function(XMLHttpRequest){
                alert(XMLHttpRequest);
            }
        );
        
        if($("#addclasssubjectgroup_"+idclass+"_"+idsubject).length){
//            $('div [id^=popup]').remove();
            var result = confirm("Вы точно хотите закончить добавление текущей группы? Группа добавлена не будет.");
            if(result) {
                groupsKo.cancelAddNewGroup(idclass, idsubject, minIDSubgroup);
//                groupsKo.drawAddNewGroup(idclass, idsubject);
            }
            
            return;
        }
            
        // GET TEACHERS FROM DB WHO CAN TEACH THIS SUBJECT
            ajax.post_sync(
                "do/groupsKo/getTeachers.php",
                "s=" + idsubject + "&sch=" + idschool,
                function(response){
                    teachers = response;
                },
                function(error){
                    alert(error);
                }
            );
        
        // GET LEFT SUBJECT TD
        var subjectTd = $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+minIDSubgroup+" td:first-child");
        
        // GET LAST TR TO INSERT AFTER
        var lastTR = $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+maxIDSubgroup);
        
        // SET NEW ROWSPAN TO SUBJECT TD
        var oldrowspan = subjectTd.attr("rowspan");
        if(oldrowspan == undefined) {
            countsubjectgroups = "1";
        } else {
            var countsubjectgroups = oldrowspan;
        }
        if(oldrowspan == undefined) {
            newrowspan = "2";
        } else {
            oldrowspan++;
            newrowspan = oldrowspan;
        }
        subjectTd.attr("rowspan", newrowspan);
        
        // FORM AND APPEND HTML
        insHTML += '<tr style="display: none;" class="info" id="addclasssubjectgroup_'+idclass+'_'+idsubject+'">\
                        <td class="leader selectTeach" id="addGroupLeader_'+idclass+'_'+idsubject+'">\
                          <div class="selectCSS">\
                            <select id="addGroup_selectTeach_'+idclass+'_'+idsubject+'">';
                               if(teachers) {
                                    insHTML += '<option selected="" value="0">Не назначен</option>';
                                    for (var i in teachers) {
                                         var teacher = {
                                             id: teachers[i]["id"],
                                             name: teachers[i]["name"],
                                             fullname: teachers[i]["fullname"]
                                         };
                                         insHTML += '<option value="'+teacher.id+'">'+teacher.fullname+'</option>';
                                    }
                                } else {
                                    insHTML += '<option selected="" value="0">Не назначен</option>';
                                }
                              
                            insHTML += '</select>\
                          </div>\
                        </td>\
                        <td class="infoGr readyEdit" id="addGroupInfogr_'+idclass+'_'+idsubject+'" colspan="2">\
                          <span id="addSubjSpan_2" onclick="groupsKo.addNewGroup('+idclass+','+idsubject+','+maxIDSubgroup+','+countsubjectgroups+','+idschool+');">Добавить</span>\
                          <span onclick="groupsKo.cancelAddNewGroup('+idclass+','+idsubject+','+minIDSubgroup+');">Отменить</span>\
                        </td>\
                      </tr>';
        lastTR.after(insHTML);
        
        
        
        // SHOW ADDING GROUP TR
        $('#addclasssubjectgroup_'+idclass+'_'+idsubject).show(500);
    },
    addNewGroup: function(idclass, idsubject, maxIDGroup, countsubjectgroups, idschool){
        // DECLARE VARIABLES NEEDED LATER
        var idgroup = null;
        var namegroup = null;
        var IDteacher = null;
        var FIOteacher = null;
        var insHTML = '';
        
        
        // GET DATA FOR AJAX REQUEST
        var idteacher = $('#addGroup_selectTeach_'+idclass+'_'+idsubject).val();
        
        // SEND AJAX AND RECEIVE IDGROUP
        ajax.post_sync(
            "do/groupsKo/addNewGroup.php",
            "c=" + idclass + "&s=" + idsubject + "&t=" + idteacher+ "&cos=" + countsubjectgroups,
            function(response){
                idgroup = response["idgroup"];
                namegroup = response["namegroup"];
                IDteacher = response["IDteacher"];
                FIOteacher = response["FIOteacher"];
            },
            function(XMLHttpRequest){
                alert(XMLHttpRequest);
            }
        );
        
        // FORM HTML
        insHTML += '<tr class="info" id="classsubjectgroup_'+idclass+'_'+idsubject+'_'+idgroup+'">\
                      <td class="leader" id="leader_'+idclass+'_'+idsubject+'_'+idgroup+'">\
                        <span>'+FIOteacher+'</span>\
                      </td>\
                      <td class="infoGr" id="infogr_'+idclass+'_'+idsubject+'_'+idgroup+'">\
                        <span>'+namegroup+'</span>\
                      </td>\
                      <td class="helpTd" id="helpTd_'+idclass+'_'+idsubject+'_'+idgroup+'" \
                          onclick="groupsKo.drawPopupClassSubject($(this), '+idclass+', '+idsubject+', '+idgroup+', '+IDteacher+','+idschool+');"></td>\
                    </tr>';
        $('#addclasssubjectgroup_'+idclass+'_'+idsubject).remove();
        $('#classsubjectgroup_'+idclass+'_'+idsubject+'_'+maxIDGroup).after(insHTML);
        groupsKo.initializeManageClassSubjectPopup($('#helpTd_'+idclass+'_'+idsubject+'_'+idgroup));
            
    },
    cancelAddNewGroup: function(idclass, idsubject, minIDGroup){
        var newrowspan = null;
        
        // HIDE AND DELETE ELEMENT, ROLLBACK ROWSPAN
        $('#addclasssubjectgroup_'+idclass+'_'+idsubject).hide(500, function(){
            $('#addclasssubjectgroup_'+idclass+'_'+idsubject).remove();
            
            // SET NEW ROWSPAN TO SUBJECT TD
            var subjectTd = $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+minIDGroup+" td:first-child");
            var oldrowspan = subjectTd.attr("rowspan");
            oldrowspan--;
            newrowspan = oldrowspan;
            subjectTd.attr("rowspan", newrowspan);
        });
    },
    editClassSubject: function(idclass, idsubject, idgroup, idteacher, idschool){
        $('#helpTd_'+idclass+'_'+idsubject+'_'+idgroup).cPopup("close");
        // DECLARE VARIABLES NEEDED LATER
        var insHTML = "";
        var teachers = null;
        var isError = false;
        
        // LOG TO CONSOLE WHAT WE ARE DOING
        console.log("Редактируем предмет класса " + idclass + " и предмета " + idsubject);
        
        
        // GET OTHER TEACHERS FROM DB
        ajax.post_sync(
            "do/groupsKo/getTeachers.php",
            "s=" + idsubject + "&sch=" + idschool,
            function(response){
                teachers = response;
            },
            function(error){
                isError = true;
                alert(error);
            }
        );
        // FORM HTML FOR COMBOLIST
        insHTML += '<div class="selectCSS">\
                      <select id="addSubj_selectTeach_'+idclass+'_'+idsubject+'_'+idgroup+'">';
        if(teachers) {
            insHTML += '<option selected="" value="0">Не назначен</option>';
            for (var i in teachers) {
                var teacher = {
                    id: teachers[i]["id"],
                    name: teachers[i]["name"],
                    fullname: teachers[i]["fullname"]
                };
                insHTML += '<option value="'+teacher.id+'">'+teacher.fullname+'</option>';
            }
        } else {
            insHTML += '<option selected="" value="0">Не назначен</option>';
        }
        insHTML += '</select>\
                </div>';
        
        // FORM HTML FOR BUTTONS
        var insHTMLBtn = '<span id="addSubjSpan_2" onclick="groupsKo.saveEditedSubject('+idclass+','+idsubject+','+idteacher+','+idgroup+','+idschool+')">Добавить</span>\
                            <span onclick="groupsKo.cancelSaveEditedSubject('+idclass+','+idsubject+','+idteacher+','+idgroup+','+idschool+')">Отменить</span>';
        
        // REFRESH DOM
          // CHANGE HTML IN TEACHERS SECTION
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).addClass("selectTeach");
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).empty();
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTML);
          // CHANGE HTML IN GROUP SECTION
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).addClass("readyEdit");
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).empty();
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTMLBtn);
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).attr("colspan", 2);
          // SET SELECTED TEACHER
        $('#addSubj_selectTeach_'+idclass+'_'+idsubject+'_'+idgroup).val(idteacher);
          // REMOVE POPUP
        $('#popupClass_helpTd_'+idclass+'_'+idsubject+'_'+idgroup).remove();
          // REMOVE MENU FOR POPUP
        $('#helpTd_'+idclass+'_'+idsubject+'_'+idgroup).remove();
    },
    deleteClassSubject: function(idclass, idsubject, idgroup){
        // REMOVE POPUP
        $("#helpTd_"+idclass+"_"+idsubject+"_"+idgroup).cPopup("close");
        
        var res = confirm("Вы действительно хотите удалить группу? Вы потеряете данные обо всех уроках, которые изучает группа и учениках, которые учатся в группе");
        if(res) {
            // GET DATA FROM DOM
            var minIDSubgroup = null;
            var maxIDSubgroup = null;
            var newrowspan = null;
            var newcountgroups = null;


            // GET ID OF FIRST ROW IN SUBJECT AND ID OF LAST ROW OF SUBJECT
            ajax.post_sync(
                "do/groupsKo/getFirstLastSubgroupSubject.php",
                "c=" + idclass + "&s=" + idsubject,
                function(response){
                    minIDSubgroup = response["minID"];
                    maxIDSubgroup = response["maxID"];

                },
                function(XMLHttpRequest){
                    alert(XMLHttpRequest);
                }
            );

            var oldcountgroups = $('div [id^=classsubjectgroup_'+idclass+'_'+idsubject+'_]').length;


            // SEND DATA TO SERVER
            ajax.post_sync(
                "do/groupsKo/deleteSubgroup.php",
                "g="+idgroup,
                function(response){

                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert('Error textstatus: ' + textStatus + '.<br>Error thrown: '+
                            errorThrown + '<br>Xml: ' + XMLHttpRequest.responseText);
                }
            );


            if((idgroup == minIDSubgroup) && (oldcountgroups > 1) ){
                var subjectname = $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+minIDSubgroup+" td:first-child").text();
                oldcountgroups--;
                newcountgroups = oldcountgroups;
                $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).hide(500, function(){
                    var nextelement = $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).next();
                    nextelement.prepend('<td class="discname" rowspan="'+newcountgroups+'"><span>'+subjectname+'</span></td>');
                    $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).remove();}
                );
            } else if((idgroup == minIDSubgroup) && (oldcountgroups == 1)){
                $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).hide(500, function(){
                    $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).remove();}
                );
            } else {
                // REFRESH DOM
                $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).hide(500, function(){
                    // SET NEW ROWSPAN TO SUBJECT TD
                    var subjectTd = $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+minIDSubgroup+" td:first-child");
                    var oldrowspan = subjectTd.attr("rowspan");
                    oldrowspan--;
                    newrowspan = oldrowspan;
                    subjectTd.attr("rowspan", newrowspan);

                    $("#classsubjectgroup_"+idclass+"_"+idsubject+"_"+idgroup).remove();}
                );
            }
        }
        console.log("Удаляем предмет класса " + idclass + " и предмета " + idsubject);
    },
    cancelSaveEditedSubject: function(idclass, idsubject, idteacher, idgroup, idschool){
        // DECLARE VARIABLES NEEDED LATER
        var insHTMLforHelpTd = '';
        var insHTMLforTeacher = '';
        var groupname = null;
        var teacherFIO = null;
        
        // GET DATA FROM DOM
        // no data needed
        
        // GET INFO ABOUT SUBJECT FROM DB
        ajax.post_sync(
            "do/groupsKo/getSubgroupInfo.php",
            "g=" + idgroup,
            function(response){
                groupname = response["namegroup"];
                teacherFIO = response["FIOteacher"];
            },
            function(XMLHttpRequest){
                alert(XMLHttpRequest);
            }
        );
        
        // FORM TEACHER'S HTML
        insHTMLforTeacher += '<span>'+teacherFIO+'</span>';
        
        // CHANGE HTML IN TEACHERS SECTION
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).removeClass("selectTeach");
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).empty();
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTMLforTeacher);
        
        // CHANGE HTML IN GROUP SECTION
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).empty();
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).removeClass("readyEdit");
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).append('<span>'+groupname+'</span>');
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).attr("colspan", 1);
        
        // ADD MENU FOR POPUP
        insHTMLforHelpTd += '<td class="helpTd" id="helpTd_'+idclass+'_'+idsubject+'_'+idgroup+'" \
                            onclick="groupsKo.drawPopupClassSubject($(this), '+idclass+','+idsubject+','+idgroup+','+idteacher+','+idschool+');"></td>';
        $('#classsubjectgroup_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTMLforHelpTd);
        groupsKo.initializeManageClassSubjectPopup($("#helpTd_"+idclass+"_"+idsubject+"_"+idgroup));
    },
    saveEditedSubject: function(idclass, idsubject, idoldteacher, idgroup, idschool){
        // DECLARE VARIABLES NEEDED LATER
        var insHTMLTeacher = '';
        var insHTMLGroup = '';
        var insHTMLforHelpTd = '';
        var teacherFIO = null;
        var idnewteacher = null;
        var groupname = null;
        
        // GET DATA FROM DOM
        idnewteacher = $('#addSubj_selectTeach_'+idclass+'_'+idsubject+'_'+idgroup).val();
        console.log(idnewteacher);
        
        // UPDATE SUBGROUPS TEACHER
        ajax.post_sync(
            "do/groupsKo/updateSubgroupInfo.php",
            "g=" + idgroup + "&to=" + idoldteacher + "&tn=" + idnewteacher + "&s=" + idsubject,
            function(response){
                groupname = response["namegroup"];
                teacherFIO = response["teacherFIO"];
            },
            function(XMLHttpRequest){
                alert(XMLHttpRequest);
            }
        );
        console.log(groupname);
        console.log(teacherFIO);
        
        // REFRESH DOM
          // FORM TEACHERS HTML
        insHTMLTeacher += '<span>'+teacherFIO+'</span>';
          
          // CHANGE HTML IN TEACHERS SECTION
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).removeClass("selectTeach");
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).empty();
        $('#leader_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTMLTeacher);
        
          // FORM GROUPS HTML
        insHTMLGroup += '<span>'+groupname+'</span>';
        
          // CHANGE HTML IN GROUP SECTION
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).empty();
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).removeClass("readyEdit");
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTMLGroup);
        $('#infogr_'+idclass+'_'+idsubject+'_'+idgroup).attr("colspan", 1);
        
          // ADD MENU FOR POPUP
        insHTMLforHelpTd += '<td class="helpTd" id="helpTd_'+idclass+'_'+idsubject+'_'+idgroup+'" \
                            onclick="groupsKo.drawPopupClassSubject($(this), '+idclass+','+idsubject+','+idgroup+','+idnewteacher+','+idschool+');"></td>';
        $('#classsubjectgroup_'+idclass+'_'+idsubject+'_'+idgroup).append(insHTMLforHelpTd);
        groupsKo.initializeManageClassSubjectPopup($("#helpTd_"+idclass+"_"+idsubject+"_"+idgroup));
    },
    loadTeacherSubjectsUI: function(idteacher, idschool){
        // DECLARE VARIABLES AND ASSIGN VALUES
        var insHTML = "";
        var subjects = null;
        
        if($("#info_teacher_"+idteacher).length){
            $("#info_teacher_"+idteacher).toggle(500);
        } else {
            // GET SUBJECTS FROM DB WITH TEACHERS WHO TEACH THAT SUBJECT AND GROUPS OF THIS SUBJECT
            subjects = groupsKo.loadTeacherSubjectsDB(idteacher);

            // PREPARE HTML_CODE
            insHTML = groupsKo.prepareTeacherSubjectsUI(subjects, idteacher, idschool);

            // APPEND GENERATED HTML TO MODEL
            $("#teacher_"+idteacher).after(insHTML);
            $("#info_teacher_"+idteacher).show(500);
        }
    },
    loadTeacherSubjectsDB: function(idteacher){
        var subjects = null;
        ajax.post_sync(
            "do/groupsKo/getSubjectsOfTeacher.php",
            "t=" + idteacher,
            function (response){
                subjects = response;
            },
            function (error){
                alert(error);
            }
        );
        return subjects;
    },
    prepareTeacherSubjectsUI: function(subjects, idteacher, idschool){
        var insHTML = "";
        insHTML += '<tr style="display:none;" id="info_teacher_'+idteacher+'" class="info">\n\
                      <td>\
                        <table cellspacing="0" cellpadding="0"><tbody>\
                          <tr class="headB">\
                            <td colspan="4">\
                              <span style="color:#fff;width:309px;">Название предмета</span>\
                              <span style="color:#fff;width:63px;">Уровень</span>\
                            </td>\
                          </tr>';
        if(subjects){
            for (var i in subjects) {
                var subject = {
                    id: subjects[i]["id"],
                    name: subjects[i]["name"],
                    level: subjects[i]["level"],
                    complexity: subjects[i]["complexity"]
                };
                insHTML += '';
                insHTML += '<tr class="info" id="teachersubject_'+idteacher+'_'+subject.id+'">\
                                <td class="discName"><span>'+subject.name+'</span></td>\
                                <td class="leader" style="border-right:none;"><span>'+subject.level+'-е классы (сложность '+subject.complexity+')</span></td>\
                                <td class="helpTd" id=""\
                                 onclick=""></td>\
                              </tr>';
            }
        }
        insHTML += '<tr class="addNewSubj" id="addNewTeacherSubject_'+idteacher+'">\
                      <td colspan="4"><span onclick="groupsKo.addSubjectTeacher('+idschool+','+idteacher+')">Добавить предмет для преподавателя</span></td>\
                    </tr>\
                  </tbody>\
                </table>\
              </td>\
            </tr>';
        return insHTML;
    },
    addSubjectTeacher: function(idschool, idteacher){
         // DECLARE VARIABLES AND ASSIGN VALUES
        var subjects = null;
        var insHTML = "";
        
        // GET ARRAY OF SUBJECTS FROM DB AND ARRAY OF SUBGROUPS
        ajax.post_sync(
            "do/groupsKo/getSubjectsSchool.php",
            "s=" + idschool,
            function(response){
                subjects = response;
            },
            function(error){
                alert(error);
            }
        );
        console.log(subjects);
        
        // FORM HTML
        // IN SELECT SUBJECTS id="addSubj_selectSubj_'+idclass+'" onchange="groupsKo.loadTeachers('+idclass+');"
        // IN SELECT TEACHERS id="addSubj_selectTeach_'+idclass+'"
        insHTML += '  <td class="selectSubj">\
                        <div class="selectCSS">\
                          <select id="addTeacherSubject_SelectSubject_'+idteacher+'" onchange="groupsKo.loadLevelsUI('+idschool+','+idteacher+');">\
                            <option selected="" value="0">---</option>';
            for(var i in subjects) {
                insHTML += '<option>'+subjects[i]["name"]+'</option>';
            }
              insHTML += '</select>\
                        </div>\
                      </td>\
                      <td class="selectTeach">\
                        <div class="selectCSS">\
                          <select id="addTeacherSubject_SelectLevel_'+idteacher+'" onchange="groupsKo.checkValidLevel('+idschool+','+idteacher+');">\
                            <option selected="" value="0">---</option>';
              insHTML += '</select>\
                        </div>\
                      </td>\
                      <td class="ready" colspan="2"><span style="color: #ccc;" id="addTeacherSubjectSpan_'+idteacher+'">Добавить</span>\
                        <span onclick="groupsKo.cancelAddingSubjectTeacher('+idschool+','+idteacher+')">Отменить</span>\
                      </td>';
            $("#addNewTeacherSubject_"+idteacher).empty();
            $("#addNewTeacherSubject_"+idteacher).removeClass("addNewSubj").addClass("addSubjSec");
            $("#addNewTeacherSubject_"+idteacher).append(insHTML);
    },
    cancelAddingSubjectTeacher: function(idschool, idteacher){
        // EMPTY ADDING CLASS TR
        $("#addNewTeacherSubject_" + idteacher).empty();
        
        // SET CLASS TO ELEMENT (TR HAS ADD CLASS BUTTON)
        $("#addNewTeacherSubject_" + idteacher).removeClass("addSubjSec").addClass("addNewSubj");
        
        // FORM HTML OF TR WITH ADD CLASS BUTTON
        $("#addNewTeacherSubject_" + idteacher).append('<td colspan="4"><span id="addTeacherSubjectSpan_'+idteacher+'" \
                                                    onclick="groupsKo.addSubjectTeacher('+idschool+','+idteacher+');">\
            Добавить предмет для класса</span>');
    },
    checkValidLevel: function(idschool, idteacher){
        var level = $("#addTeacherSubject_SelectLevel_"+idteacher+" option:selected").text();
        if(level != "Не выбран") {
            $("#addTeacherSubjectSpan_"+idteacher).css("color", "#8eb4e3");
            $("#addTeacherSubjectSpan_"+idteacher).bind("click", function(){
                groupsKo.addSubjectTeacherDB(idschool, idteacher);
            });
            $("#addTeacherSubjectSpan_"+idteacher).bind("mouseenter", function(){
                $("#addTeacherSubjectSpan_"+idteacher).css("color", "#7093bf");
            });
            $("#addTeacherSubjectSpan_"+idteacher).bind("mouseleave", function(){
                $("#addTeacherSubjectSpan_"+idteacher).css("color", "#8eb4e3");
            });
        } else {
            $("#addTeacherSubjectSpan_"+idteacher).css("color", "#cccccc");
            $("#addTeacherSubjectSpan_"+idteacher).unbind("click");
            $("#addTeacherSubjectSpan_"+idteacher).unbind("mouseenter");
            $("#addTeacherSubjectSpan_"+idteacher).unbind("mouseleave");
        }
    },
    loadLevelsUI: function(idschool, idteacher){
        // DECLARE VARIAVLES NEEDED LATER
        var levels = null;
        var insHTML = "";
        var isError = null;
        
        // GET NAMESUBJECT FROM DOM
        var namesubject = $("#addTeacherSubject_SelectSubject_"+idteacher+" option:selected").text();
        console.log(namesubject);
        
        if(namesubject != "---") {
        // GET TEACHERS FROM DB WHO CAN TEACH THIS SUBJECT
            ajax.post_sync(
                "do/groupsKo/loadLevels.php",
                "n=" + namesubject+ "&s=" + idschool,
                function(response){
                    levels = response;
                },
                function(error){
                    isError = true;
                    alert(error);
                }
            );
            if(!isError) {
                if(levels) {
                    console.log(levels);
                    insHTML += '<option selected="" value="0">Не выбран</option>';
                    for(var i in levels) {
                        insHTML += '<option value="'+levels[i]["level"]+'" data-complexity="'+levels[i]["complexity"]+'">'+levels[i]["level"]+'-е классы '+levels[i]["complexity"]+'</option>';
                    }
                } else {
                    insHTML += '<option selected="" value="0">Не выбран</option>';
                }
            }
        } else {
            insHTML += '<option selected="" value="-1">---</option>';
            console.log("Ничего не выбрано");
        }
        $("#addTeacherSubject_SelectLevel_"+idteacher).empty();
        $("#addTeacherSubject_SelectLevel_"+idteacher).append(insHTML);
    },
    addSubjectTeacherDB: function(idschool, idteacher){
         // DECLARE VARIABLES NEEDED LATER
        var insHTML = "";
        var idsubject = null;
        
        // GET DATA FROM DOM
        var level = $("#addTeacherSubject_SelectLevel_"+idteacher+" option:selected").val();
        var namesubject = $("#addTeacherSubject_SelectSubject_"+idteacher+" option:selected").text();
        var complexity = $("#addTeacherSubject_SelectLevel_"+idteacher+" option:selected").data("complexity");
        
        ajax.post_sync(
            "do/groupsKo/setTeacherSubject.php",
            "t=" + idteacher + "&n=" + namesubject + "&l=" + level + "&c=" + complexity,
            function(response){
                idsubject = response;//... - нужно получить ID Subject
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus + '.<br>Error thrown: '+
                        errorThrown + '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
        
        // FORM HTML
        insHTML += '<tr class="info" id="teachersubject_'+idteacher+'_'+idsubject+'" style="display:none;">\
                      <td class="discName"><span>'+namesubject+'</span></td>\
                      <td class="leader" style="border-right:none;"><span>'+level+'-е классы (сложность '+complexity+')</span></td>\
                      <td class="helpTd" id="" \
                                         onclick=""></td>\
                    </tr>';
        
        // REFRESH DOM
        $("#addNewTeacherSubject_"+idteacher).before(insHTML);
        $("#teachersubject_"+idteacher+'_'+idsubject).show(500);
        groupsKo.cancelAddingSubjectTeacher(idschool, idteacher);
    },
    prepareSearchTeacherHTML: function(_idSchool){
        return tmpl($("#searchTeacher").html(), {idSchool: _idSchool});
    },
    showHideTeacherSearch: function(_idSchool){
        var searchTeacherDiv = $(".searchTeacher");
        if(!searchTeacherDiv.length){
            $("#addNewTeacher").parent().append(groupsKo.prepareSearchTeacherHTML(_idSchool));
            $("#addNewTeacher").text("Скрыть добавление преподавателя");
        } else {
            if(searchTeacherDiv.hasClass("hidden")){
                if($(".resultItems").length){
                    $(".resultItems").show();
                }
                searchTeacherDiv.removeClass("hidden");
                searchTeacherDiv.show();
                $("#addNewTeacher").text("Скрыть добавление преподавателя");
            } else {
                if($(".resultItems").length){
                    $(".resultItems").hide();
                }
                searchTeacherDiv.addClass("hidden");
                searchTeacherDiv.hide();
                $("#addNewTeacher").text("Добавить нового преподавателя");
            }
        }
    },
    searchTeacherUI: function(_idSchool){
        var insHTML = "";
        var searchKey = $("#searchTeacherInput").val();
        var results = groupsKo.getSearchedTeacherResults(searchKey, 0);
        console.log(results);
        if($(".resultItems").length){
            $(".resultItems").remove();
        }
        if($(".warningMessage").length){
            $(".warningMessage").remove();
        }
        if(results.isUsers){
            insHTML = groupsKo.prepareSearchResultsHTML(results.users, _idSchool);
        } else {
            insHTML = groupsKo.prepareEmptySearchResultsHTML(searchKey);
        }
        if(results.isMoreUsers){
            insHTML += groupsKo.prepareLoadMoreUsersHTML(searchKey, _idSchool, results.users[results.users.length-1].id);
        }
        $(".searchTeacher").parents(".content").append(insHTML);
    },
    searchMoreTeacherUI: function(_searchKey, _idSchool, _startID){
        var insHTML = "";
//        var startID = 
        var results = groupsKo.getSearchedTeacherResults(_searchKey, _startID);
        if($(".warningMessage").length){
            $(".warningMessage").remove();
        }
        insHTML = groupsKo.prepareSearchResultsHTML(results.users, _idSchool);
        $(".resultItems").children("ul").append(insHTML);
        if(results.isMoreUsers){
            insHTML += groupsKo.prepareLoadMoreUsersHTML();
        }
        
    },
    prepareLoadMoreUsersHTML: function(_searchKey, _idSchool, _startID){
        return tmpl($("#loadMoreTeacherResScript").html(),{searchkey: _searchKey, idschool: _idSchool, startID: _startID});
    },
    prepareSearchResultsHTML: function(_users, _idSchool){
        var insHTML = "";
        insHTML += "<div class=\"resultItems\">";
        insHTML += "<ul>";
        for (var i in _users){
            insHTML += tmpl($("#searchedTeacher").html(), {user:_users[i], idSchool: _idSchool});
        }
        insHTML += "</ul>";
        insHTML += "</div>";
        return insHTML;
    },
    prepareMoreSearchResultsHTML: function(_users, _idSchool){
        var insHTML = "";
        for (var i in _users){
            insHTML += tmpl($("#searchedTeacher").html(), {user:_users[i], idSchool: _idSchool});
        }
        return insHTML;
    },
    prepareEmptySearchResultsHTML: function(_searchKey){
        var insHTML = "";
        insHTML += "<div class=\"resultItems\">";
        var notices = Array();
        notices.push(window.tmpl.tmpl_notice_h({
            title: MI_CONTACTS_EMPTY_SEARCH_TEACHERS.replace(new RegExp("%SEARCHKEY",""), _searchKey)
        }));
        insHTML += tmpl(window.tmpl.tmpl_notices, {type: 1, notices: notices});
        insHTML += "</div>";
        return insHTML;
    },
    prepareNewTeacherHTML: function(_teacher, _idSchool){
        return tmpl($("#teacherScript").html(), {teacher: _teacher, idSchool: _idSchool});
    },
    addTeacherSchoolUI: function(_idSocial, _idSchool){
        var teacherinfo = groupsKo.setTeacherFromSocial(_idSocial, _idSchool);
        console.log(teacherinfo);
        console.log("ИД школы: "+_idSchool);
        var category;
        switch(teacherinfo["category"]){
            case "1":
                category = "Первая";
                break;
            case "2":
                category = "Вторая";
                break;
            case "3":
                category = "Третья";
                break;
        } 
        var number = $("table.sammary").children().first().children().length-2;
        var teacher = {
            id: teacherinfo["id"],
            number: number,
            firstname: teacherinfo["firstname"],
            lastname: teacherinfo["lastname"],
            middlename: teacherinfo["middlename"],
            category: category,
            isTeacherInSchool: teacherinfo["isTeacherInSchool"]
        };
        console.log(teacher);
        if(intval(teacher.isTeacherInSchool)){
            alert("Пользователь уже является преподавателем данной школы");
        } else {
            var elementAddBefore = $("table.sammary").children().first().children().last();
            elementAddBefore.before(groupsKo.prepareNewTeacherHTML(teacher, _idSchool));
            elementAddBefore.prev().show(500);
            groupsKo.initializeManageTeacherPopup($("#teacherManage_"+teacher.id));
        }
    },
    setTeacherFromSocial: function(_idSocial, _idSchool){
        var teacherinfo;
        ajax.post_sync(
            "do/groupsKo/addTeacherSchool.php",
            "idSoc="+_idSocial+"&idSch="+_idSchool,
            function(response){
                teacherinfo = response;
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('function: setTeacherFromSocial<br>\
                        Error textstatus: ' + textStatus + 
                        '.<br>Error thrown: '+ errorThrown + 
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return teacherinfo;
    },
    /**
     * Метод возвращает результаты поиска по пользователям
     * @param {String} _searchKey ключевое слово поиска
     * @param {int} _startID стартовый ИД пользователя для дальнейшего поиска
     * @returns {Array} доступные поля - ["users"]["isUsers"]["type"]
     */
    getSearchedTeacherResults: function(_searchKey, _startID){
        var results;
        ajax.post_sync(
            "do/groupsKo/getSearchedTeachers.php",
            "s="+_searchKey+"&id="+_startID,
            function(response){
                results = response;
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('function: getSearchedTeacherResults<br>\
                        Error textstatus: ' + textStatus + 
                        '.<br>Error thrown: '+ errorThrown + 
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return results;
    },
    showManageTeacherPopup: function(obj, _idteacher){
        if(!obj.cPopup('isInit')) {
            obj.cPopup('open', {
                data: { idteacher: _idteacher},
                body: groupsKo.prepareManageTeacherPopup(_idteacher)
            });
        } else obj.cPopup('open');
    },
    prepareManageTeacherPopup: function(_idteacher){
        return tmpl($("#popupManageTeacherScript").html(), 
                    {
                        idteacher: _idteacher
                    }
        );
    },
    deleteTeacherUI: function(_idteacher){
        var isDeleted = groupsKo.deleteTeacherDB(_idteacher);
        if(isDeleted){
            $("#teacher_"+_idteacher).hide(
                500,
                function(){
                    $("#teacher_"+_idteacher).remove();
                }
            );
        } else {
            alert("Произошла ошибка при удалении");
        }
    },
    deleteTeacherDB: function(_idteacher){
        var isDeleted = false;
        ajax.post_sync(
            "do/groupsKo/deleteTeacher.php",
            "idT="+_idteacher,
            function(response){
                isDeleted = response["isdeleted"];
            }, 
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('function: deleteTeacherDB<br>\
                        Error textstatus: ' + textStatus + 
                        '.<br>Error thrown: '+ errorThrown + 
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            } 
        );
        return isDeleted;
    }
};
$(document).ready(function(){
    $("#showhideallbut").click(
        function(){
            if($(this).hasClass("shown")){
                $(".hiddenParralel").hide(500);
                $(this).html("<span>Показать все</span>");
            } else if(!$(this).hasClass("shown")){
                $(this).html("<span>Скрыть пустые параллели</span>");
                $(".hiddenParralel").show(500);
            }
            $(this).toggleClass("shown");
        }
    );
});