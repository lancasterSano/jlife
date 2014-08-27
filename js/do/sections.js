$(document).ready(function(){
    sections.assignPopupToParagraphs();
    sections.hideParagraphs();
    var subjectsTable = $(".subjTable:first");
    var idArray = subjectsTable.attr("id").split("_");
    var idschool = idArray[3];
    sections.assignClickEventToParagraphs(idschool);
    $(".cancelOrNo").each(function() {
        $(this).click(function() {
            var tmp = $(this).attr("id");
            var idsection;
            idsection = tmp.split("_")[1];
            $(this).toggleClass("cancelled");
            $("#saveOrYesBut_" + idsection).toggleClass("cancelled");
            if ($(this).hasClass("cancelled")) {
                var name = $("#textarea_" + idsection).html();
                name = name.replace(new RegExp('<br>', 'g'), '');
                name = name.replace(new RegExp('""', 'g'), '');
                name = name.replace(new RegExp(' ', 'g'), '');
                name = name.replace(new RegExp('\n', 'g'), '');
                name = name.replace(new RegExp('&nbsp;', 'g'), '');
                //        console.log(name);
                if (name == "") {
                    $('#cancelOrNoBut_' + idsection).toggleClass("cancelled");
                    $('#saveOrYesBut_' + idsection).toggleClass("cancelled");
                    $('#infospan_' + idsection).html("");
                    $('#parText_' + idsection).hide();
                    $('#addPar_' + idsection).show();
                    $('#info_section_' + idsection).show();
                } else {
                    $('#infospan_' + idsection).html("Вы уверены, что хотите отменить добавление параграфа?");
                    $('#saveOrYesBut_' + idsection).html("Да");
                    $('#cancelOrNoBut_' + idsection).html("Нет");
                }
            } else {
                $('#infospan_' + idsection).html("");
                $('#saveOrYesBut_' + idsection).html("Сохранить");
                $('#cancelOrNoBut_' + idsection).html("Отменить");
            }
        });
    });
    $(".confirmOrYes").each(function() {
        $(this).click(function() {
            var tmp = $(this).attr("id");
            var idsection;
            idsection = tmp.split("_")[1];
            if ($(this).hasClass("cancelled")) {
                $('#cancelOrNoBut_' + idsection).toggleClass("cancelled");
                $(this).toggleClass("cancelled");
                $('#infospan_' + idsection).html("");
                $(this).html("Сохранить");
                $('#cancelOrNoBut_' + idsection).html("Отменить");
                $('#textarea_' + idsection).html("");
                $('#parText_' + idsection).hide();
                $('#addPar_' + idsection).show();
                $('#info_section_' + idsection).show();
            } else {
                var tmp = $(".subjTable:first-child").attr("id");
                tmp = tmp.split("_");
                var idmaterial = tmp[2];
                var idteacher = tmp[1];
                var idschool = tmp[3];
                sections.addParagraph(idsection, idmaterial, idteacher, idschool);
            }
        });
    });
});
var sections = {
    assignPopupToParagraphs: function(){
        var paragraphMenuBtns = $("div [id ^= menu]");
        paragraphMenuBtns.each(function(){
            var idParagraph = $(this).attr("id").split("_")[2];
            $(this).cPopup({
                idWND: "rf_wnd_paragraphPopup_" + idParagraph,
                btns: window._NONE,
                location : {
                    position: "default",
                    offset: { left: 0, top: 0, right: 0, bottom: 50 },
                 proportions: { width: undefined, height: undefined },
                    quarters: [4]
                }
            });
        }); 
    },
    addParagraph: function(idsection, idmaterial, idteacher, _idschool) {
        var name = $("#textarea_" + idsection).text();
//        if(document.getElementById("textarea_" + idsection).innerText){
//            name = document.getElementById("textarea_" + idsection).innerText;
//        } else {
//            name = document.getElementById("textarea_" + idsection).innerHTML.replace(/\&lt;br\&gt;/gi,"\n").replace(/(&lt;([^&gt;]+)&gt;)/gi, "");
//        }
        if (name.replace(/^(\s+)/g,'') == "") {
            $("#infospan_" + idsection).html("Вы не можете добавить пустой параграф");
            return;
        }
        if (name.length > MAX_PARAGRAPH_LENGTH) {
            $("#infospan_" + idsection).html("Слишком большое название");
            return;
        }
        ajax.post_sync(
                "do/sections/addParagraphInSection.php",
                "m=" + idmaterial + "&s=" + idsection + "&name=" + name,
                function(response) {
                    location.href = 'cabinet.php?school='+_idschool+'&type=s&s=21&material=' + idmaterial + '&t=' + idteacher + '&es=' + idsection;
                },
                function() {

                }
        );
    },
    
    hideParagraphs: function() {
        $(".section").each(function() {
            var idElement = $(this).attr("id");
            var arr = idElement.split("_");
            var id = arr[1];
            $("#sectiontd_" + id).click(function() {
                if ($(this).hasClass("hidden")) {
                    $(".paragraph_" + id).show();
                    $(".title_" + id).show();
                    $("#addParagraph_" + id).show();
                } else {
                    $(".paragraph_" + id).hide();
                    $(".title_" + id).hide();
                    $("#addParagraph_" + id).hide();
                }
                $(this).toggleClass("hidden");
            });
        });
    },
    onForMenuClick: function(obj, idpar, idschool, idsection, idTeacher, idMaterial) {
        if($("#textarea_edit_"+idpar).length == 0){
            obj.cPopup('open', {
                data: { idpar: idpar,
                    idschool: idschool,
                    idsection: idsection},
                body: sections.prepareMenuUI(idpar,idschool,idsection, idTeacher, idMaterial)
            });
        }
    },
    prepareMenuUI: function(idpar, idschool, idsection, idTeacher, idMaterial) {
        var insertHTML = "";
        var isParActive;
        if($("#par_"+idpar).hasClass("unact"))
            isParActive = false;
        else
            isParActive = true;
        insertHTML += "<div class=\"displayMenu\">\
                            <span id=\"editParSpan\" onclick=\"sections.editParTitle("+idpar+","+idsection+","+idschool+");\">Изменить название</span>\
                            <span id=\"deleteParSpan\" onclick=\"sections.delPar("+idpar+","+idsection+","+idTeacher+","+idMaterial+","+idschool+");\">Удалить параграф</span>\
                            <span id=\"unactParSpan\" onclick=\"sections.actUnactPar("+idpar+","+idsection+","+isParActive+");\">";
        if(isParActive)
            insertHTML += 'Деактивировать';
        else
            insertHTML += 'Активировать';
        insertHTML += '</span>\
                            <span id="openParSpan" onclick="sections.openPar('+idpar+','+idschool+','+idsection+');">Открыть</span>\
                        </div>';
        return insertHTML;
    },
    openPar: function (idPar, idSchool){
        location.href = '../../pages/do/addpar.php?paragraph='+idPar+'&school='+idSchool;
    },
    actUnactPar: function (idPar, idSection, isActive) {
    
        ajax.post_sync(
            "do/sections/changeStateParagraph.php",
            "p="+idPar+"&isA="+isActive,
            function(response) {
                if(isActive) {
                    $("#par_"+idPar).addClass("unact");
                    $("#par_"+idPar).removeClass("drop");
                } else {
                    $("#par_"+idPar).removeClass("unact");
                    $("#par_"+idPar).addClass("drop");
                }
                $("#menu_"+idSection+"_"+idPar).cPopup("close");
            },
            function() {
                alert("error");
            }
        );
        
    },
    onChangePar: function(idSection){
        var textParLength = $("#textarea_"+idSection).text().length;
        if(textParLength > MAX_PARAGRAPH_LENGTH){
            $("#textarea_"+idSection).addClass("entererror");
        } else {
            $("#textarea_"+idSection).removeClass("entererror");
        }
    },
    onKeyDownEditPar: function(idPar){
        var textParLength = $("#textarea_edit_"+idPar).text().length;
        if(textParLength > MAX_PARAGRAPH_LENGTH){
            $("#textarea_edit_"+idPar).addClass("entererror");
        } else {
            $("#textarea_edit_"+idPar).removeClass("entererror");
        }
    },
    editParTitle: function(idPar, idSection, idschool){
        var namepar = $("#parname_"+idPar).children().first().text();
        $("#parname_"+idPar).empty();
        var insHTML = '<div class="addNew" onkeyup="sections.onKeyDownEditPar('+idPar+');" style="width: 570px; margin-left: 5px; margin-top: 0" contenteditable="true" id="textarea_edit_'+idPar+'">'+namepar+'</div>';
        $("#parname_"+idPar).append(insHTML);
        $("#parname_"+idPar).after("<div id='editparbuttons_"+idPar+"' \
                                         class='okcancelchangeparname'>\
                                      <span onclick=\"sections.saveEditPar("+idPar+","+idschool+","+idSection+"); event.stopPropagation();\">Добавить</span>\
                                      <span onclick=\"sections.cancelEditPar("+idPar+","+idschool+",'"+namepar.replace(/'/g, "\\'")+"'); event.stopPropagation();\">Отменить</span>\
                                    </div>\n\
                                    <div id='info_editpar_"+idPar+"' class='infoEditPar'></div>");
        $("#paragraphtd_"+idPar).unbind("click");
        $("#paragraphtd_"+idPar).parent().removeClass("default").addClass("defaultEditing");
        $("#menu_"+idSection+"_"+idPar).cPopup("close");
    },
    cancelEditPar: function(idPar, idschool, namepar){
        $("#parname_"+idPar).empty();
        var insHTML = "<span style='margin: 0'>"+namepar+"</span>";
        $("#parname_"+idPar).append(insHTML);
        $("#editparbuttons_"+idPar).remove();
        $("#info_editpar_"+idPar).remove();
        $("#paragraphtd_"+idPar).parent().removeClass("defaultEditing").addClass("default");
        $("#paragraphtd_"+idPar).bind("click", function(){
            location.href='../do/addpar.php?school='+idschool+'&paragraph='+idPar;
        });
    },
    saveEditPar: function(idPar, idschool, idsection){
        // GET DATA FROM DOM
        var name = $("#textarea_edit_" + idPar).text();
        if (name.replace(/^(\s+)/g,'') == "") {
            $("#info_editpar_" + idPar).html("Вы не можете добавить пустой параграф");
            return;
        }
        if (name.length > MAX_PARAGRAPH_LENGTH) {
            $("#info_editpar_" + idPar).html("Слишком большое название");
            return;
        }
        // SEND AJAX REQUEST TO SERVER
         ajax.post_sync(
            "do/sections/changeParagraphName.php",
            "idP=" + idPar + "&n=" + name,
            function(response) {
                
            },
            function() {

            }
        );
        
        $("#parname_"+idPar).empty();
        var insHTML = "<span style='margin: 0'>"+name+"</span>";
        $("#parname_"+idPar).append(insHTML);
        $("#editparbuttons_"+idPar).remove();
        $("#info_editpar_"+idPar).remove();
        $("#paragraphtd_"+idPar).parent().removeClass("defaultEditing").addClass("default");
        $("#paragraphtd_"+idPar).bind("click", function(){
            location.href='../do/addpar.php?school='+idschool+'&paragraph='+idPar;
        });
        $("paragraphtd_"+idPar).prev().attr("onclick", "");
    },
    delPar: function(_idPar, _idSection, _idTeacher, _idMaterial, _idschool){
        ajax.post_sync(
            "do/sections/delParagraph.php",
            "p=" + _idPar,
            function(response) {
                $("#menu_"+_idSection+"_"+_idPar).cPopup("close");
                location.href = 'cabinet.php?school='+_idschool+'&type=s&s=21&material=' + _idMaterial + '&t=' + _idTeacher + '&es=' + _idSection;
            },
            function() {
                alert("error");
            }
        );
    },
    assignClickEventToParagraphs: function(idschool){
        $(".paragraphClickable").each(function (){
            var tmp = $(this).attr("id");
            var idpar;
            idpar = tmp.split("_")[1];
            $(this).bind("click", function(){
                location.href='../do/addpar.php?school='+idschool+'&paragraph='+idpar;
            });
        });
    },
    openInfoSection: function(idSection){
        $("#infosection_"+idSection).toggleClass("visible");
        if($("#infosection_"+idSection).hasClass("visible")){
            $("#infosection_"+idSection).show();
        } else {
            $("#infosection_"+idSection).hide();
        }
    },
    showAddingParagraph: function(obj, idSection){
        $('#parText_'+idSection).show();
        obj.hide();
        $('#info_section_'+idSection).hide();
        $('#infosection_'+idSection).hide();
        $('#infosection_'+idSection).removeClass('visible');
    }
};
