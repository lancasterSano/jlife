$(document).ready(function(){
    var menuMaterials = $("div [id ^= menuMaterial]");
    menuMaterials.each(function(){
        var idMaterial = $(this).attr("id").split("_")[1];
        $(this).cPopup({
            idWND: "rf_wnd_materialPopup_" + idMaterial,
            btns: window._NONE,
            location : {
                position: "default",
                offset: { left: 0, top: 0, right: 0, bottom: 50 },
             proportions: { width: undefined, height: undefined },
                quarters: [4,3]
            }
        });
    }); 
    $("#addConspectSpan").cPopup({
        idWND: "rf_wnd_addconspect",
        btns: window._NONE,
        location : {
            position: "default",
            offset: { left: 0, top: 0, right: 0, bottom: 50 },
         proportions: { width: undefined, height: undefined },
            quarters: [3]
        }
    });
});
var subjectTeacher = {
    // ADDS SUBJECT TO USER INTERFACE
    addMaterialUI: function(idTeacher, _idschool){
        $("#addConspectSpan").cPopup("close");
        var tableToAdd = null;
        var number = null;
        var isSubject = false;
        
        // 1. receive data from ui
         var namesubject = $("#selectSubjects option:selected").text();
         var level = $("#selectLevels option:selected").val();
         var complexity = $("#selectComplexities option:selected").val();
         if(level == 0 || complexity == 0) {
             return;
         }
         
         // 2. save data to db
        var material = subjectTeacher.saveMaterialToDB(idTeacher, namesubject, level, complexity);
        
        $("span.subjectnamearchive").each(function(){
            console.log($(this));
            if($(this).text() == namesubject) {
                isSubject = true;
                tableToAdd = $(this).parent().parent().parent();
            }
        });
        if(isSubject) {
            number = tableToAdd.children().length-2;
        } else {
            number = 1;
        }
        var insHTML = subjectTeacher.prepareMaterialUI(level, material["countsection"], number, idTeacher, material["id"], isSubject, namesubject, material["date"], complexity, _idschool);
            
        if(isSubject) {
            tableToAdd.append(insHTML);
        }
        else {
            if($("#archiveDiv").children(".conformation").length){
                $("#archiveDiv").empty();
            }
            $("#archiveDiv").append(insHTML);
        }
    }, 
    saveMaterialToDB: function(idTeacher, namesubject, level, complexity){
        var material = null;
        ajax.post_sync(
            "/do/subjectTeacher/saveMaterialDB.php",
            "idT=" + idTeacher + "&name=" + namesubject + "&l=" + level + "&c=" + complexity,
            function(response){
                material = response;
            },
            function(){
        
            }
        );
        return material;
    },
    prepareMaterialUI: function (level, countsection, number, idteacher, idmaterial, issubject, namesubject, date, complexity, _idschool){
        var insHTML = "";
        // FORM HTML
        if(issubject) {
            insHTML = "<tr class='defaultA' onclick='location.href=\"cabinet.php?school="+_idschool+"&type=s&s=21&material="+idmaterial+"&t="+idteacher+"\";'>\
                            <td>"+(number+1)+".</td>\
                            <td>"+level+"</td>\
                            <td>0/0</td>\
                            <td>"+date+"</td>\
                            <td title=\"Разделы\">"+countsection+"</td>\
                            <td>Не назначен</td>\
                            <td>"+complexity+"</td>\
                            <td style=\"width: 18px;\"></td>\
                        </tr>";
        } else {
            var countArch = $('.archiveM').length;
            var classMod;
            if(countArch%2 == 1) {
                classMod = "subjRight";
            } else {
                classMod = "subjLeft";
            }
            insHTML = "<div class='"+classMod+" archiveM'>\
                          <table class=\"subjItem\" cellspacing=\"0\" cellpadding=\"0\">\
                            <tbody>\
                              <tr class=\"archiv\" align=\"center\">\
                                <td colspan=\"8\">\
                                  <span class=\"subjectnamearchive\">"+namesubject+"</span>\
                                  <a style=\"display: none;\" href=\"#\"><img src=\"../img/secure.png\"></a>\
                                </td>\
                              </tr>\
                              <tr class=\"colsNamed\">\
                                <td>№</td>\
                                <td>Класс</td>\
                                <td>§</td>\
                                <td>Дата</td>\
                                <td>Разд.</td>\
                                <td>Статус</td>\
                                <td colspan=\"2\">Сложность</td>\
                              </tr>\
                              <tr class=\"defaultA\" onclick=\"location.href='cabinet.php?school="+_idschool+"&type=s&s=21&material="+idmaterial+"&amp;t="+idteacher+"';\">\
                                <td>"+number+".</td>\
                                <td>"+level+"</td>\
                                <td>0/0</td>\
                                <td>"+date+"</td>\
                                <td>"+countsection+"</td>\
                                <td>Не назначен</td>\
                                <td>"+complexity+"</td>\
                                <td style=\"width:18px;\"></td>\
                              </tr>\
                            </tbody>\
                          </table>\
                        </div>";
        }
        return insHTML;
    },
    showAddMaterialPopup: function(obj, _idTeacher, _idschool){
        var subjects = subjectTeacher.loadSubjectsPopup(_idTeacher);
        if(!obj.cPopup('isInit')) {
            obj.cPopup('open', {
                data: { idTeacher: _idTeacher},
                body: subjectTeacher.preparePopupUI(subjects, _idTeacher, _idschool)
            });
        } else obj.cPopup('open');
    }, 
    loadSubjectsPopup: function(idTeacher) {
        var subjects = null;
        ajax.post_sync(
            "/do/subjectTeacher/loadSubjects.php",
            "idT=" + idTeacher,
            function(response) {
                subjects = response;
            },
            function() {
                alert("error");
            }
        );
            return subjects;
    },
    loadLevelsPopup: function (idteacher, namesubject) {
        var levels = null;
       
        ajax.post_sync(
            "/do/subjectTeacher/loadLevels.php",
            "name=" + namesubject + "&idT=" + idteacher,
            function(response) {
                levels = response;
            },
            function() {
                alert("error");
            }
        );
            return levels;
    },
    loadComplexitiesPopup: function (idteacher, namesubject, level) {
        var complexities = null;
        ajax.post_sync(
            "/do/subjectTeacher/loadComplexities.php",
            "name=" + namesubject + "&idT=" + idteacher + "&l=" + level,
            function(response) {
                complexities = response;
            },
            function() {
                alert("error");
            }
        );
        return complexities;
    },
    loadLevelsPopupUI: function (idteacher) {
       var namesubject = $("#selectSubjects :selected").text();
       var levels = subjectTeacher.loadLevelsPopup(idteacher, namesubject);
       $("#selectLevels").empty();
//       $("#selectLevels").append('<option disabled="" selected="" value="0">---</option>');
       for (var i in levels) {
           $("#selectLevels").append('<option value="'+levels[i]["level"]+'">'+levels[i]["level"]+'</option>');
       }
       $("#selectLevels :first-child").attr("selected", "selected");
       subjectTeacher.loadComplexitiesPopupUI(idteacher);
       
    },
    loadComplexitiesPopupUI: function (idteacher) {
       var namesubject = $("#selectSubjects :selected").text();
       var level = $("#selectLevels :selected").text();
       var complexities = subjectTeacher.loadComplexitiesPopup(idteacher, namesubject, level);
       $("#selectComplexities").empty();
//       $("#selectComplexities").append('<option disabled="" selected="" value="0">---</option>');
       for (var i in complexities) {
           $("#selectComplexities").append('<option value="'+complexities[i]["complexity"]+'">'+complexities[i]["complexity"]+'</option>');
       }
       $("#selectComplexities :first-child").attr("selected", "selected");
    },
    preparePopupUI: function(_subjects, _idTeacher, _idschool) {
        var insHTML = tmpl($("#popupAddMaterial").html(), {idTeacher: _idTeacher,subjects: _subjects, idschool: _idschool});
        return insHTML;
    },
    hidePopup: function() {
        $("#addConspectSpan").cPopup("close");
    },
    showAssignMaterialPopup: function(obj, _idTeacher, _idmaterial){
        var subgroups = subjectTeacher.loadTeachersSubgroupsID(_idTeacher, _idmaterial);
        var material = subjectTeacher.getMaterialState(_idmaterial);
        console.log(material);
        if(!obj.cPopup('isInit')) {
            obj.cPopup('open', {
                data: { idTeacher: _idTeacher},
                body: subjectTeacher.prepareMaterialsPopupUI(material, subgroups)
            });
        } else obj.cPopup('open');
    },
    prepareMaterialsPopupUI: function(_material, _subgroups){
        var insHTML = tmpl($("#popupAssignMaterial").html(), {material: _material, subgroups: _subgroups});
        return insHTML;
    },
    loadTeachersSubgroupsID: function(_idTeacher, _idmaterial){
        var subgroups;
        ajax.post_sync(
            "/do/subjectTeacher/loadTeachersSubgroups.php",
            "idT="+_idTeacher+"&m="+_idmaterial,
            function(response) {
                subgroups = response;
            },
            function() {
                alert("error");
            }
        );
        return subgroups;
    },
    getMaterialState: function(_idmaterial){
        var material;
        ajax.post_sync(
            "/do/subjectTeacher/getMaterialState.php",
            "m="+_idmaterial,
            function(response) {
                material = response;
            },
            function() {
                alert("error");
            }
        );
        return material;
    },
    assignMaterialToGroup: function(_idmaterial, _notstudy, _idgroup, _state){
        var isAssigned = subjectTeacher.setMaterialGroup(_idmaterial, _notstudy, _idgroup, _state);
        if(isAssigned){
            location.href = document.URL;
        } else {
            
        }
    },
    setMaterialGroup: function(_idmaterial, _notstudy, _idgroup, _state){
        var isAssigned = false;
        ajax.post_sync(
            "/do/subjectTeacher/setMaterialGroup.php",
            "m="+_idmaterial+"&g="+_idgroup+"&ns="+_notstudy+"&s="+_state,
            function(response) {
                isAssigned = response;
            },
            function() {
                isAssigned = false;
            }
        );
        return isAssigned;
    }
};