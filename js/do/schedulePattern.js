var SchedulePattern = {
    /*
     * Функция для изменения текущего дня в GUI (появляются комбобоксы вместо спанов и т.д.)
     */
    loadEditDayUI: function(idClass, dayNumber){ //onclick "Edit Class Day"
                                                  // для этого мне нужен ид класса и номер дня недели
//        alert("Выполняется функция для класса "+idClass+" и номера дня "+dayNumber);
        var lessons, shift, classname, dayname, isLinked, status, number, idsubgroup, idclassroom, countshift;
        var insHTML = "";
        // получить информацию по этому дню из БД
        ajax.post_sync(
            "do/schedulePattern/loadTimetableClassDay.php",
            "c="+idClass+"&dn="+dayNumber,
            function (response){
                lessons = response["lessons"];
                classname = response["classname"];
                shift = response["shift"];
                countshift = response["countshift"];
                dayname = response["dayname"];
                console.log(response);
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus +
                        '.<br>Error thrown: '+ errorThrown +
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
        // сгенерировать новую вёрстку для этого дня
        insHTML += '<table>\
                      <thead>\
                        <tr>\
                          <td class="instrument">\
                          <td class="group">'+classname+'</td>\
                          <td class="smena">\
                            <span class="yesBorder">Смена</span>\
                            <div class="styled-select">\
                              <select>';
        for(var i = 1; i <= intval(countshift); i++){
            if(i == intval(shift)){
                     insHTML += '<option value="'+i+'" selected="">'+i+'</option>';
            } else {
                     insHTML += '<option value="'+i+'">'+i+'</option>';
            }
        }
        insHTML +=           '</select>\
                            </div>\
                          <td class="helpCh">\
                        </tr>\
                      </thead>\
                      <tbody>';
        var countlessons = 0;
        for(var i in lessons){
            var lessonsnumber = lessons[i];
            for(var j in lessonsnumber){
                countlessons++;
            }
        }
        var issideday = true; // переменная для отображения 
        for(var i in lessons){
            var lessonsnumber = lessons[i];
            for(var j in lessonsnumber){
                var subjectName, teacherFIO, cabinet, counthours;
                switch(lessonsnumber[j]["status"]){
                    case "noLessonsDay":
                    case "window":
                        subjectName = "Не выбран";
                        teacherFIO = "";
                        idclassroom = lessonsnumber[j]["idclassroom"];
                        cabinet = "---";
                        idsubgroup = "0";
                        number = lessonsnumber[j]["number"];
                        counthours = "";
                        isLinked = lessonsnumber[j]["isLinked"];
                        status = lessonsnumber[j]["status"];
                        break;
                    case "lesson":
                        var tempSubjectName = lessonsnumber[j]["subjectname"];
                        if(tempSubjectName.length > 19){
                            subjectName = tempSubjectName.substring(0, 16)+"...";
                        } else {
                            subjectName = tempSubjectName;
                        }
                        teacherFIO = lessonsnumber[j]["TeacherFIO"];
                        idclassroom = lessonsnumber[j]["idclassroom"];
                        if(idclassroom == 0){
                            cabinet = "---";
                        } else {
                            cabinet = lessonsnumber[j]["cabinet"];
                        }
                        idsubgroup = lessonsnumber[j]["idsubgroup"];
                        number = lessonsnumber[j]["number"];
                        counthours = lessonsnumber[j]["subjectCountHours"]+"ч";
                        isLinked = lessonsnumber[j]["isLinked"];
                        status = lessonsnumber[j]["status"];
                        break;
                }

                var lesson = {
                    number: number,
                    idSubgroup: idsubgroup,
                    isLinked: isLinked,
                    status: status,
                    teacherFIO: teacherFIO,
                    subjectName: subjectName,
                    idclassroom: idclassroom,
                    cabinet: cabinet,
                    counthours: counthours
                };
                insHTML += 
                   '<tr class="lessonTR">';
            if(issideday){
                insHTML += 
                     '<td class="dayName" id="dayName_'+idClass+'_'+dayNumber+'" rowspan="'+countlessons+'">\
                        <p class="mon">\
                          <span>'+dayname+'</span>\
                        </p>\
                      </td>';
            }
            insHTML += 
                     '<td class="lesson" colspan="3">\
                        <table cellspacing="0" cellpadding="0">\
                          <tbody>\
                            <tr class="rowOne">\
                              <td class="lesName nopaddingName" id="lessonName_'+idClass+'_'+dayNumber+'_'+lesson.number+'_'+lesson.idSubgroup+'_'+lesson.isLinked+'_'+lesson.status+'">\
                                '+lesson.number+'.\
                                <div class="styled-select">\
                                  <select id="comboEditLessonName_'+idClass+'_'+dayNumber+'_'+lesson.number+'_'+lesson.idSubgroup+'_'+lesson.isLinked+'_'+lesson.status+'"\
                                          class="comboEditLessonName_'+idClass+'_'+dayNumber+'_'+lesson.number+'"\
                                          onfocus="SchedulePattern.loadLessonsInListUI($(this));"\
                                          onchange="SchedulePattern.refreshLessonUI($(this));">\
                                    <option>'+lesson.subjectName+'</option>\
                                  </select>\
                                </div>\
                              </td>\
                              <td class="nopadding">\
                                <div class="styled-select">\
                                  <select id="comboEditLessonClassroom_'
                                              +idClass+'_'
                                              +dayNumber+'_'
                                              +lesson.number+'_'
                                              +lesson.idSubgroup+'_'
                                              +lesson.isLinked+'_'
                                              +lesson.status+'_'
                                              +lesson.idclassroom+'"\
                                          onfocus="SchedulePattern.loadClassroomsInListUI($(this));"\
                                          onchange="SchedulePattern.refreshClassroomUI($(this));">\
                                    <option value="'+lesson.idclassroom+'">'+lesson.cabinet+'</option>\
                                  </select>\
                                </div>\
                              </td>\
                            </tr>\
                            <tr class="rowTwo">\
                              <td class="lesMaster">'+lesson.teacherFIO+'</td>\
                              <td class="lesHours">'+lesson.counthours+'</td>\
                            </tr>\
                          </tbody>\
                        </table>\
                      </td>\
                    </tr>';
            issideday = false;
            }
        }
        insHTML += '<tr class="saveYESNO">\
                      <td colspan="4">\
                      Сохранить\
                        <span onclick="SchedulePattern.saveClassDayLessons($(this));">Да</span>\
                        <span onclick="SchedulePattern.cancelSaveClassDayLessons($(this));">Нет</span>\
                      </td>\
                    </tr>\
                    <tr class="addLesson">\
                      <td colspan="4">\
                        <span onclick="SchedulePattern.addLessonUI($(this));">Добавить новый урок</span>\
                      </td>\
                    </tr>\
                  </tbody>\
                </table>';
        // очистить контейнер этого дня
        $("#dayLessons_"+idClass+"_"+dayNumber).empty();
        // вставить в контейнер вёрстку
        $("#dayLessons_"+idClass+"_"+dayNumber).append(insHTML);
    },
    /*
     * Функция для изменения списка возможных уроков при нажатии на комбобокс (onfocus)
     */    
    loadLessonsInListUI: function(comboBoxObj){
//        console.log(comboBoxObj);
        var idCombobox, insHTML = "", subgroups, idCurrentSubgroup;
        // получаем ид комбобокса
        idCombobox = comboBoxObj.attr("id");
//        console.log(idCombobox);
        var tempArray = idCombobox.split("_");
        var idClass = tempArray[1];
        var lesson = {
            dayLesson: tempArray[2],
            number: tempArray[3],
            idsubgroup: tempArray[4],
            isLinked: tempArray[5],
            status: tempArray[6]
        };
        
        var subgroupsIDs = Array();
        if(lesson.isLinked != "0"){
        // получаем связанные сабгруппы, которые выбраны в вёрстке
            $(".comboEditLessonName_"+idClass+"_"+lesson.dayLesson+"_"+lesson.number)
            .each(
                function(){
                    var idcombobox = $(this).attr("id");
                    var tempArray = idcombobox.split("_");
                    var idsubgroup = tempArray[4];
                    if((idsubgroup != "0") && (idsubgroup != lesson.idsubgroup)){
                        subgroupsIDs.push(idsubgroup);
                    }
                }
            );
        }
            
        var lessonString = JSON.stringify(lesson);
        var subgroupsString = JSON.stringify(subgroupsIDs);
        ajax.post_sync(
            "do/schedulePattern/subgroupsForComboboxLoader.php",
            "l="+lessonString+"&c="+idClass+"&g="+subgroupsString,
            function (response){
                subgroups = response["subgroups"];
                idCurrentSubgroup = response["currentSubgroup"];
//                console.log(response);
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus +
                        '.<br>Error thrown: '+ errorThrown +
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
            
        // формируем HTML для вставки
        if(idCurrentSubgroup == 0){
            insHTML += '<option value="0" selected="">Не выбран</option>';
        } else {
            insHTML += '<option value="0">Не выбран</option>';
        }
        for(var i in subgroups){
            if(subgroups[i]["id"] == idCurrentSubgroup){
                insHTML += '<option value="'+subgroups[i]["id"]+'" selected="">'+subgroups[i]["subjectname"]+'</option>';
            } else {
                insHTML += '<option value="'+subgroups[i]["id"]+'">'+subgroups[i]["subjectname"]+'</option>';
            }
        }
        
        // обновляем наш комбобокс
        comboBoxObj.empty().append(insHTML);
    }, 
    /*
     * Функция для изменения текущего урока и обновления преподавателя и количества часов (onchange)
     */
    refreshLessonUI: function(comboBoxObj){
//        console.log(comboBoxObj);
        var idCombobox, newlessonFromPHP, subgroupsIDs = Array(), insHTML = "";
        idCombobox = comboBoxObj.attr("id");
//        console.log("ID Combobox - " + idCombobox);
        var tempArray = idCombobox.split("_");
        var oldlesson = {
            idClass: tempArray[1],
            dayNumber: tempArray[2],
            number: tempArray[3],
            idsubgroup: tempArray[4],
            isLinked: tempArray[5],
            status: tempArray[6]
        };
        
        // сериализуемый старый урок для отправки на сервер
        var oldlessonString = JSON.stringify(oldlesson);
        
        // получаем из комбобокса ID новой группы
        var idNewSubgroup = comboBoxObj.val();
        console.log("ID new subgroup - " + idNewSubgroup);
        
        // находим все связанные группы, которые есть в вёрстке и формируем массив ID групп
         $(".comboEditLessonName_"+oldlesson.idClass+"_"+oldlesson.dayNumber+"_"+oldlesson.number)
        .each(
            function(){
                var idcombobox = $(this).attr("id");
                var tempArray = idcombobox.split("_");
                var idsubgroup = tempArray[4];
                if(idsubgroup != "0"){
                    subgroupsIDs.push(idsubgroup);
                }
            }
        );
            
        // сериализуем найденные группы для отправки на сервер
        var subgroupsString = JSON.stringify(subgroupsIDs);
        
        // шлем запрос на сервер
        ajax.post_sync(
            "do/schedulePattern/loadLessonInfoBySubgroup.php",
            "l="+oldlessonString+"&s="+idNewSubgroup+"&g="+subgroupsString,
            function (response){
                newlessonFromPHP = response;
                console.log(response);
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus +
                        '.<br>Error thrown: '+ errorThrown +
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            }
        );
            
        // формируем новый урок, полученный от сервера по ID новой группы
        var newlesson = {
            idClass : newlessonFromPHP["idClass"],
            dayNumber : newlessonFromPHP["dayNumber"],
            number : newlessonFromPHP["number"],
            idsubgroup: newlessonFromPHP["idsubgroup"],
            isLinked : newlessonFromPHP["isLinked"], 
            status : newlessonFromPHP["status"],
            action : newlessonFromPHP["action"]
        };
        
        // формируем новый ID комбобокса и обновляем ID в вёрстке
        var newid = "comboEditLessonName_"+
                        newlesson.idClass+"_"+
                        newlesson.dayNumber+"_"+
                        newlesson.number+"_"+
                        newlesson.idsubgroup+"_"+
                        newlesson.isLinked+"_"+
                        newlesson.status;
        comboBoxObj.attr("id", newid);
        
        
        switch(newlesson.action){
            case "unsetLinkedLast":
               // снятие последнего урока из связанных уроков
               // удаляем все ячейки связанных уроков, а эту ячейку делаем несвязанным окном
                
                // переменная примет значение true, только если мы удалили TR, которая содержала dayName(боковая TD с названием дня недели)
                var hasDayNameDeleted = false;
                
                // переменная, чтоб сохранить в нее rowspan из того самого удаленного TR, где есть TD dayName
                var oldRowSpanDeletedTR;
                
                var newrowspan;
                
                // установка ID для TD lesName
                comboBoxObj.parents(".lesName").attr("id", newid);
                
                // для каждого TD из связанных уроков
                $(".comboEditLessonName_"+oldlesson.idClass+"_"+oldlesson.dayNumber+"_"+oldlesson.number)
                    .each(
                        function(){
                            // находим его isLinked
                            var idcombobox = $(this).attr("id");
                            var tempArray = idcombobox.split("_");
                            var isLinked = tempArray[5];
                            var lessonTR = $(this).parents(".lessonTR");
                            
                            // isLinked нового урока = 0, старых - 1, следовательно старые уроки удалятся
                            if(isLinked != newlesson.isLinked){
                                if(lessonTR.children(".dayName").length > 0){
                                    // если мы удалили TR с TD dayName
                                    hasDayNameDeleted = true;
                                    oldRowSpanDeletedTR = lessonTR.children(".dayName").attr("rowspan");
                                }
                                lessonTR.remove();
                            }
                        }
                    );
                if(hasDayNameDeleted){
                    // восстановление TD dayName
                    var dayName;
                    switch(newlesson.dayNumber){
                        case "1":
                            dayName = "Понедельник";
                            break;
                        case "2":
                            dayName = "Вторник";
                            break;
                        case "3":
                            dayName = "Среда";
                            break;
                        case "4":
                            dayName = "Четверг";
                            break;
                        case "5":
                            dayName = "Пятница";
                            break;
                        case "6":
                            dayName = "Суббота";
                            break;
                    }
                    newrowspan = intval(oldRowSpanDeletedTR) - intval(newlessonFromPHP["countsubgrouptodelete"]);
                    comboBoxObj.parents(".lessonTR").prepend('\
                                                    <td class="dayName" id="dayName_'+newlesson.idClass+'_'+newlesson.dayNumber+'"\
                                                                        rowspan="'+newrowspan+'">\
                                                      <p class="mon">\
                                                        <span>'+dayName+'</span>\
                                                      </p>\
                                                    </td>');
                } else {
                    newrowspan = $("#dayName_"+newlesson.idClass+"_"+newlesson.dayNumber).attr("rowspan") - newlessonFromPHP["countsubgrouptodelete"];
                    $("#dayName_"+newlesson.idClass+"_"+newlesson.dayNumber).attr("rowspan", newrowspan);
                }
                comboBoxObj.blur();
                // очищаем поле учителя и часов
                comboBoxObj.parents(".rowOne").siblings().children(".lesMaster").html("");
                comboBoxObj.parents(".rowOne").siblings().children(".lesHours").html("");
                break;
            case "unsetLinkedNotLast":
                comboBoxObj.parents(".rowOne").siblings().children(".lesMaster").html("");
                comboBoxObj.parents(".rowOne").siblings().children(".lesHours").html("");
                break;
            case "setLinkedFromLinked":
                comboBoxObj.parents(".rowOne").siblings().children(".lesMaster").html(newlessonFromPHP["teachersFIO"]);
                comboBoxObj.parents(".rowOne").siblings().children(".lesHours").html(newlessonFromPHP["hours"]);
                break;
            case "unsetUnlinked":
                comboBoxObj.parents(".rowOne").siblings().children(".lesMaster").html("");
                comboBoxObj.parents(".rowOne").siblings().children(".lesHours").html("");
                break;
            case "setLinkedFromUnlinked":
                // назначение связанного урока 
               // добавляем столько ячеек, сколько у нас связанных уроков
                for(var i = 0; i < newlessonFromPHP["countsubgrouptoadd"]; i++){
                    insHTML += 
                   '<tr class="lessonTR">\
                      <td class="lesson" colspan="3">\
                        <table cellspacing="0" cellpadding="0">\
                          <tbody>\
                            <tr class="rowOne">\
                              <td class="lesName nopaddingName" id="lessonName_'
                                                                +newlesson.idClass+'_'
                                                                +newlesson.dayNumber+'_'
                                                                +newlesson.number+'_'
                                                                +'0_'
                                                                +'1_'
                                                                +'window">\
                                '+newlesson.number+'.\
                                <div class="styled-select">\
                                  <select id="comboEditLessonName_'+newlesson.idClass+'_'+newlesson.dayNumber+'_'+newlesson.number+'_0_1_window"\
                                          class="comboEditLessonName_'+newlesson.idClass+'_'+newlesson.dayNumber+'_'+newlesson.number+'"\
                                          onfocus="SchedulePattern.loadLessonsInListUI($(this));"\
                                          onchange="SchedulePattern.refreshLessonUI($(this));">\
                                    <option value="0">Не выбран</option>\
                                  </select>\
                                </div>\
                              </td>\
                              <td class="nopadding">\
                                <div class="styled-select">\
                                  <select id="comboEditLessonClassroom_'+newlesson.idClass+'_'+newlesson.dayNumber+'_'+newlesson.number+'_0_1_window_0"\
                                          onfocus="SchedulePattern.loadClassroomsInListUI($(this));"\
                                          onchange="SchedulePattern.refreshClassroomUI($(this));">\
                                    <option value="0">---</option>\
                                  </select>\
                                </div>\
                              </td>\
                            </tr>\
                            <tr class="rowTwo">\
                              <td class="lesMaster"></td>\
                              <td class="lesHours"></td>\
                            </tr>\
                          </tbody>\
                        </table>\
                      </td>\
                    </tr>';
                }
                comboBoxObj.parents(".lessonTR").after(insHTML);
                var newrowspan = intval($("#dayName_"+newlesson.idClass+"_"+newlesson.dayNumber).attr("rowspan")) + intval(newlessonFromPHP["countsubgrouptoadd"]);
                $("#dayName_"+newlesson.idClass+"_"+newlesson.dayNumber).attr("rowspan", newrowspan);
                comboBoxObj.parents(".rowOne").siblings().children(".lesMaster").html(newlessonFromPHP["teachersFIO"]);
                comboBoxObj.parents(".rowOne").siblings().children(".lesHours").html(newlessonFromPHP["hours"]);
                break;
            case "setUnlinkedFromUnlinked":
                comboBoxObj.parents(".rowOne").siblings().children(".lesMaster").html(newlessonFromPHP["teachersFIO"]);
                comboBoxObj.parents(".rowOne").siblings().children(".lesHours").html(newlessonFromPHP["hours"]);
                break;
            
        }
    },
    /*
     * Функция для дорисовки урока
     */
    addLessonUI: function(spanObj){
        var lastTR = spanObj.parents(".addLesson").prev().prev();
        var idCombobox = lastTR.find(".lesName").attr("id");
        var tempArray = idCombobox.split("_");
        var newnumber = intval(tempArray[3]) + intval(1);
        var newlesson = {
            idClass: tempArray[1],
            dayNumber: tempArray[2],
            number : newnumber
        };
        var insHTML = '';
        insHTML += '<tr class="lessonTR">\
                      <td class="lesson" colspan="3">\
                        <table cellspacing="0" cellpadding="0">\
                          <tbody>\
                            <tr class="rowOne">\
                              <td class="lesName nopaddingName" id="lessonName_'
                                                                +newlesson.idClass+'_'
                                                                +newlesson.dayNumber+'_'
                                                                +newlesson.number+'_'
                                                                +'0_'
                                                                +'0_'
                                                                +'window">\
                                '+newlesson.number+'.\
                                <div class="styled-select">\
                                  <select id="comboEditLessonName_'+newlesson.idClass+'_'+newlesson.dayNumber+'_'+newlesson.number+'_0_0_window"\
                                          class="comboEditLessonName_'+newlesson.idClass+'_'+newlesson.dayNumber+'_'+newlesson.number+'"\
                                          onfocus="SchedulePattern.loadLessonsInListUI($(this));"\
                                          onchange="SchedulePattern.refreshLessonUI($(this));">\
                                    <option value="0">Не выбран</option>\
                                  </select>\
                                </div>\
                              </td>\
                              <td class="nopadding">\
                                <div class="styled-select">\
                                  <select id="comboEditLessonClassroom_'+newlesson.idClass+'_'+newlesson.dayNumber+'_'+newlesson.number+'_0_0_window_0"\
                                          onfocus="SchedulePattern.loadClassroomsInListUI($(this));"\
                                          onchange="SchedulePattern.refreshClassroomUI($(this));">\
                                    <option value="0">---</option>\
                                  </select>\
                                </div>\
                              </td>\
                            </tr>\
                            <tr class="rowTwo">\
                              <td class="lesMaster"></td>\
                              <td class="lesHours"></td>\
                            </tr>\
                          </tbody>\
                        </table>\
                      </td>\
                    </tr>';
        lastTR.after(insHTML);
        var oldrowspan = $("#dayName_"+newlesson.idClass+"_"+newlesson.dayNumber).attr("rowspan");
        $("#dayName_"+newlesson.idClass+"_"+newlesson.dayNumber).attr("rowspan", intval(oldrowspan) + intval(1));
        
    },
    /*
     * Функция для загрузки значений в комбобокс кабинетов (onfocus)
     */
    loadClassroomsInListUI: function(comboBoxObj){
        var insHTML = '', classrooms;
        var idCombobox = comboBoxObj.attr("id");
        var tempArray = idCombobox.split("_");
        var idClass = tempArray[1];
        var idCurrentClassroom = tempArray[7];
        ajax.post_sync(
            "do/schedulePattern/loadClassrooms.php",
            "c="+idClass,
            function (response){
                classrooms = response;
                console.log(response);
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus +
                        '.<br>Error thrown: '+ errorThrown +
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            }                 
        );
        if(idCurrentClassroom == 0){
            insHTML += '<option value="0" selected="">---</option>';
        } else {
            insHTML += '<option value="0">---</option>';
        }
        for (var i in classrooms){
            if(idCurrentClassroom == classrooms[i]["id"]){
                insHTML += '<option value="'+classrooms[i]["id"]+'" selected="">'+classrooms[i]["number"]+'</option>';
            } else {
                insHTML += '<option value="'+classrooms[i]["id"]+'">'+classrooms[i]["number"]+'</option>';
            }
        }
        comboBoxObj.empty().append(insHTML);
    },
    /*
     * Функция для изменения значений комбобокса кабинетов (onchange)
     */
    refreshClassroomUI: function(comboBoxObj){
        var idComboBox = comboBoxObj.attr("id");
        var tempArray = idComboBox.split("_");
        var newClassroomID = comboBoxObj.val();
        var newIDForJS = tempArray[0]+'_'+tempArray[1]+'_'+tempArray[2]+'_'+tempArray[3]+'_'+tempArray[4]+'_'+tempArray[5]+'_'+tempArray[6]+'_'+newClassroomID;
        comboBoxObj.attr("id", newIDForJS);
    },
    /*
     * Функция для сохранения уроков и перерисовки JS
     */
    saveClassDayLessons: function(buttonObject){
        var idClass, dayNumber;
        var tmp = buttonObject.parents(".day").attr("id").split("_");
        var isTimetable;
        idClass = tmp[1];
        dayNumber = tmp[2];
        var resultArray = Array();
        buttonObject.parents(".day").find(".lessonTR").each(function(){
            var tempArray = $(this).find(".lesName").children().children().attr("id").split("_");
            var idclassroom = $(this).find(".lesName").next().children().children().attr("id").split("_")[7];
            var idsubgroup = tempArray[4];
            var number = tempArray[3];
            if(idsubgroup != 0){
                var lesson = {
                    idsubgroup: idsubgroup,
                    number: number,
                    idclassroom: idclassroom
                };
                resultArray.push(lesson);
            }
            
        });
        console.log(resultArray);
        if(resultArray.length === 0){
            isTimetable = 0;
        } else {
            isTimetable = 1;
        }
        var resultString = JSON.stringify(resultArray);
        
        ajax.post_sync(
            "do/schedulePattern/saveLessonsClassDay.php",
            "c="+idClass+"&dn="+dayNumber+"&t="+resultString+"&isT="+isTimetable,
            function (response){
                console.log(response);
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus +
                        '.<br>Error thrown: '+ errorThrown +
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            }                 
        );
        SchedulePattern.cancelSaveClassDayLessons(buttonObject.next());
    },
    /*
     * Функция для отмены действий редактирования урока и перерисовки JS
     */
    cancelSaveClassDayLessons: function(buttonObject){
        var insHTML = '', classname, shift, lessons, dayname, number;
        var dayDiv = buttonObject.parents(".day");
        console.log(dayDiv);
        var tempArray = dayDiv.attr("id").split("_");
        var idClass = tempArray[1];
        var dayNumber = tempArray[2];
        
        ajax.post_sync(
            "do/schedulePattern/loadTimetableClassDay.php",
            "c="+idClass+"&dn="+dayNumber,
            function (response){
                lessons = response["lessons"];
                classname = response["classname"];
                shift = response["shift"];
                dayname = response["dayname"];
                console.log(response);
            },
            function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error textstatus: ' + textStatus +
                        '.<br>Error thrown: '+ errorThrown +
                        '<br>Xml: ' + XMLHttpRequest.responseText);
            }                 
        );
            
        insHTML += '<table>\
                      <thead>\
                        <tr>\
                          <td class="instrument" onclick="SchedulePattern.loadEditDayUI('+idClass+', '+dayNumber+');">\
                          <td class="group">'+classname+'</td>\
                          <td>\
                            <span class="yesBorder">Смена</span>\
                            <span>'+shift+'</span>\
                          </td>\
                          <td class="help">\
                        </tr>\
                      </thead>\
                      <tbody>';
        var countlessons = 0;
        for(var i in lessons){
            var lessonsnumber = lessons[i];
            for(var j in lessonsnumber){
                countlessons++;
            }
        }
        var issideday = true; // переменная для отображения 
        for(var i in lessons){
            var lessonsnumber = lessons[i];
            for(var j in lessonsnumber){
                var subjectName, teacherFIO, classroom, counthours;
                switch(lessonsnumber[j]["status"]){
                    case "noLessonsDay":
                    case "window":
                        subjectName = "";
                        teacherFIO = "";
                        classroom = "";
                        number = lessonsnumber[j]["number"];
                        counthours = "";
                        break;
                    case "lesson":
                        if(lessonsnumber[j]["subjectname"] == 0){
                            classroom = "";
                        } else {
                            classroom = lessonsnumber[j]["cabinet"];
                        }
                        subjectName = lessonsnumber[j]["subjectname"];
                        teacherFIO = lessonsnumber[j]["TeacherFIO"];
                        
                        number = lessonsnumber[j]["number"];
                        counthours = lessonsnumber[j]["subjectCountHours"]+"ч";
                        break;
                }

                var lesson = {
                    number: number,
                    teacherFIO: teacherFIO,
                    subjectName: subjectName,
                    classroom : classroom,
                    counthours: counthours
                };
                insHTML += 
                   '<tr>';
            if(issideday){
                insHTML += 
                     '<td class="dayName" rowspan="'+countlessons+'">\
                        <p class="mon">\
                          <span>'+dayname+'</span>\
                        </p>\
                      </td>';
            }
            insHTML += 
                     '<td class="lesson" colspan="3">\
                        <table cellspacing="0" cellpadding="0">\
                          <tbody>\
                            <tr class="rowOne">\
                              <td class="lesName">'+lesson.number+'. '+lesson.subjectName+'</td>\
                              <td class="rihgtCol">'+lesson.classroom+'</td>\
                            </tr>\
                            <tr class="rowTwo">\
                              <td class="lesMaster">'+lesson.teacherFIO+'</td>\
                              <td>'+lesson.counthours+'</td>\
                            </tr>\
                          </tbody>\
                        </table>\
                      </td>\
                    </tr>';
            issideday = false;
            }
        }
        insHTML += '\
                  </tbody>\
                </table>';
        dayDiv.empty().append(insHTML);
    },
    /*
     * Функция для генерации расписания на весь учебный промежуток
     */
    generateLessons: function(spanObj){
        var isGenerated = spanObj.attr("id").split("_")[2];
        var idSchool = spanObj.attr("id").split("_")[1];
        if(isGenerated == "0"){
            var responsePrompt = confirm("Вы абсолютно в этом уверены?");
            if(responsePrompt){
                var status;
                ajax.post_sync(
                    "do/schedulePattern/generateLessons.php",
                    "s="+idSchool,
                    function (response){
                        status = response["status"];
                        console.log(response);
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){
                        alert('Error textstatus: ' + textStatus +
                                '.<br>Error thrown: '+ errorThrown +
                                '<br>Xml: ' + XMLHttpRequest.responseText);
                    }                 
                );
                spanObj.attr("id", "generate"+idSchool+"_1");
            }
        } else {
            alert("Вы не можете сгенерировать расписание, так как идет семестр");
        }
    }
};