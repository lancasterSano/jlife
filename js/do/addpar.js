
// Вставляем CKEditor по окончанию загрузки страницы вместо newPart_text

$(document).ready(function(){
    CKEDITOR.replace('newPart_text');
});

var addpar = {
  //Добавить пользователя в друзья 
    Turn: function(post) {
        var tmp = $("span[id^="+post+"]").attr('id').split("_");
        if (tmp[3]==1)
        {
            $("span[id^=addparMenu_4_"+tmp[2]+"_]").attr('id','addparMenu_4_'+tmp[2]+'_2');
            $("span[id^=addparMenu_4_"+tmp[2]+"_]").text('Развернуть');
            $("#partParagraph_"+tmp[2]).slideUp(250);
            $("#turnButton_"+tmp[2]).css('background-image', 'url("../../img/rwnu.png")');
        }
        else
        {
            $("span[id^=addparMenu_4_"+tmp[2]+"_]").attr('id','addparMenu_4_'+tmp[2]+'_1');
            $("span[id^=addparMenu_4_"+tmp[2]+"_]").text('Свернуть');
            $("#partParagraph_"+tmp[2]).slideToggle(250);
            $("#turnButton_"+tmp[2]).css('background-image',  'url("../../img/rwn.png")');
        }
    },  
    Delete: function(post) {
        var tmp = $("span[id^="+post+"]").attr('id').split("_");
        ajax.post("do/addpar/deletePartParagraph.php",
                "idLoad=" + PM.idLoad + "&idAuth=" + PM.idAuth + "&partParagraph=" + tmp[2],
                function(data)
                {    
                    
                    $("#DeleteSure_"+tmp[2]).remove();
                    $("#partParagraph_"+tmp[2]).remove();
                    $("#namePartParagraph_"+tmp[2]).remove();
                    $.each($("span[id^=number_]"), function(index) {
                        tmp = $(this).text().split(".");
                        $(this).text(data[index]["number"] + "." + tmp[1])
                    });
                    $("#newPart_number").val($("#newPart_number").val()-1);
                    $("#newPart_number").text($("#newPart_number").text()-1);


                    
                  
                }, 
                function(msg){
                    alert(msg);
                }
        );    
        
    }, 
    EditCancel: function(post){
        var tmp = post.split("_");
        ajax.post("do/addpar/loadPartParagraph.php",
                "idLoad=" + PM.idLoad + "&idAuth=" + PM.idAuth + "&partParagraph=" + tmp[2],
                function(data)
                {    
                  ge("namePartParagraph_"+ data[1]["id"]).show();
                  ge("partParagraph_"+ data[1]["id"]).show();
                  ge("EditParagraph_"+ data[1]["id"]).empty();
                  $("div[id^=namePartParagraph_]").show();
                  $("div[id^=partParagraph_]").show();
                  $("div[id^=EditParagraph_]").empty();
                  


                  $.each($("span[id^=addparMenu_4_]"), function(index) {
                    tmp = $(this).attr("id").split("_");

                   if (tmp[3]==1)
                   {
                       $("span[id^=addparMenu_4_"+tmp[2]+"_]").text('Свернуть');
                       $("#partParagraph_"+tmp[2]).show();
                       $("#turnButton_"+tmp[2]).css('background-image', 'url("../../img/rwn.png")');
                   }
                   else
                   {
                       $("span[id^=addparMenu_4_"+tmp[2]+"_]").attr('id','addparMenu_4_'+tmp[2]+'_2');
                       $("span[id^=addparMenu_4_"+tmp[2]+"_]").text('Развернуть');
                       $("#partParagraph_"+tmp[2]).hide();
                       $("#turnButton_"+tmp[2]).css('background-image',  'url("../../img/rwnu.png")');
                   }


                    });

                }, 
                function(msg){
                    alert(msg);
                }
        );    
    },
    EditOk: function(post, idEditor){
        // var tmp = post.split("_");
        var name = $("#name_EditOk").val();
        var objName = $("#name_EditOk");
        // var text2 = CKEDITOR.editor.getSnapshot();
        // var text = $("#text_EditOk").val();
        // var number = $("#number_EditOk").val();
        // ajax.post("do/addpar/savePartParagraph.php",
        //         "idLoad=" + PM.idLoad + "&idAuth=" + PM.idAuth + "&partParagraph=" + tmp[2] + "&name=" + name + "&text=" + text + "&number=" + number + "&paragraph=" + tmp[3],
        //         function(data)
        //         {    
        //             location.reload();
        //         }, 
        //     function(msg){
        //         alert(msg);
        //     }
    // );
        if ($.trim(name).length == 0 || $.trim(name) == "Заполните поле 'Название под параграфа'")
        {
          objName.attr('onclick', "$(this).removeClass('entererror'); this.value = '';");
          objName.addClass("entererror");
          objName.val("Заполните поле 'Название под параграфа'");//alert("Вы не можете создать раздел параграфа без названия.");
        }
        else
          CKEDITOR.instances[idEditor].execCommand('ajaxsave');
        // $('.cke_button__ajaxsave').eq(0).click();
},
    Edit: function(post) {
        var tmp = post.split("_");
        ajax.post("do/addpar/loadPartParagraph.php",
                "idLoad=" + PM.idLoad + "&idAuth=" + PM.idAuth + "&partParagraph=" + tmp[2],
                function(data){
    ins = " <div class='questName'>\
     <div id='EditSure' class='btn' style='display:none;' > <div> Вы уверены что хотите сохранить  внесенные изменения?</div>\
     <span onclick = \"addpar.EditOk('addparMenu_2_" + data[1]["id"] + "_"+ tmp[3] +"', 'text_EditOk_"+ data[1]['id'] + "_"+ tmp[3] +"');\" >Сохранить&nbsp</span>\
     <span onclick='$(\"div[id^=EditSure]\").hide();'>&nbspОтменить&nbsp</span>\
     </div>\
          <div class='styled-select'>\
            <select id='number_EditOk'>";
        $.each(data[2], function(index) {
            if (index + 1 !=data[1]["number"])
            {
                ins +=  data[2][index]["number"] + "<option value=" + data[2][index]["number"]+ ">" + data[2][index]["number"] + "</option>";                
            }
            else
            {
                ins +=  data[2][index]["number"] + "<option selected  value=" + data[2][index]["number"]+ ">" + data[2][index]["number"] + "</option>";
            }
          });
    ins+= "</select>\
          </div>\
          <textarea id='name_EditOk'>" + data[1]["name"] + "  </textarea>\
          <div class='manage'>\
             <span style='margin-bottom:3px;' onclick='$(\"div[id^=EditSure]\").show();' >Сохранить</span>\
             <span onclick = 'addpar.EditCancel(\"addparMenu_2_" + data[1]["id"] + "\");' >Отменить</span>\
          </div>\
        <div class='editorContainer'>\
            <textarea id='text_EditOk_"+ data[1]['id'] + "_"+ tmp[3] +"' class='quest'>" + data[1]["text"] + "</textarea>\
        </div>\
        </div>";

        var replace = "text_EditOk_"+ data[1]['id'] + "_"+ tmp[3];

                    $("div[id^=DeleteSure_]").hide();
                    $("div[id^=namePartParagraph_]").show();
                    $("div[id^=partParagraph_]").show();
                    $("div[id^=EditParagraph_]").empty();
                    $('#s_newPart').hide();
                    $.each($("span[id^=addparMenu_4_]"), function(index) {
                      tmp = $(this).attr("id").split("_");

                     if (tmp[3]==1)
                     {
                         $("span[id^=addparMenu_4_"+tmp[2]+"_]").text('Свернуть');
                         $("#partParagraph_"+tmp[2]).show();
                         $("#turnButton_"+tmp[2]).css('background-image', 'url("../../img/rwn.png")');
                     }
                     else
                     {
                         $("span[id^=addparMenu_4_"+tmp[2]+"_]").attr('id','addparMenu_4_'+tmp[2]+'_2');
                         $("span[id^=addparMenu_4_"+tmp[2]+"_]").text('Развернуть');
                         $("#partParagraph_"+tmp[2]).hide();
                         $("#turnButton_"+tmp[2]).css('background-image',  'url("../../img/rwnu.png")');
                     }
                      });

                    ge("namePartParagraph_"+ data[1]["id"]).hide();
                    ge("partParagraph_"+ data[1]["id"]).hide();
                    ge("EditParagraph_"+ data[1]["id"]).append(normToEdite(ins));

                    CKEDITOR.replace(replace);
                }, 
                function(msg){
                    alert(msg);
                }
        );    
        },
        
         addPart: function(post, idEditor){
            var tmp = post.split("_");
            var name = $("#newPart_name").val();
            var objName = $("#newPart_name");
            var text = $("#newPart_text").val();
            var number = $("#newPart_number").val();
            if ($.trim(name).length == 0 || $.trim(name) == "Заполните поле 'Название под параграфа'")
            {
                objName.attr('onclick', "$(this).removeClass('entererror'); this.value = '';");
                objName.addClass("entererror");
                objName.val("Заполните поле 'Название под параграфа'");//alert("Вы не можете создать раздел параграфа без названия.");
            }
            else
            {
                CKEDITOR.instances['newPart_text'].execCommand('ajaxsave');
                // ajax.post("do/addpar/addPartParagraph.php",
                //         "idLoad=" + PM.idLoad + "&idAuth=" + PM.idAuth + "&paragraph=" + tmp[1] + "&name=" + name + "&text=" + text + "&number=" + number,
                //      function(data)
                //      {    
                //          location.reload();
                //      }, 
                //      function(msg){
                //         alert(msg);
                //         }
                //  );  
            }
        },

        addPartUI: function(){
            $('#s_newPart').show(); // Показываем Окно добавления нового раздела
            $('div[id^=DeleteSure_]').hide(); // Скрываем блок подтверждения удаления
            $('div[id^=namePartParagraph_]').show(); // Показываем все названия частей параграфа
            $('div[id^=partParagraph_]').show(); // Показываем контент всех частей параграфа
            $('div[id^=EditParagraph_]').empty(); // Скрываем редактирование параграфа
        },
};