CKEDITOR.plugins.add('ajaxsave',
            {
                icons: 'ntrue',
                init: function(editor)
                {
                    var pluginName = 'ajaxsave';

                        editor.addCommand( pluginName,
                        {
                            exec : function( editor )
                            {
                                var name_EditOk = jQuery("#name_EditOk");
                                name_EditOk = name_EditOk.length;
                                if(name_EditOk == 0)
                                {
                                    var tmp = jQuery("a[id ^= addPart]").attr('id').split('_');
                                    var name = jQuery("#newPart_name").val();
                                    var objName = jQuery("#newPart_name");
                                    // var text = editor.getSnapshot();
                                    var text = CKEDITOR.instances[editor.name].getData();
                                    var number = jQuery("#newPart_number").val();
                                    if (jQuery.trim(name).length == 0 || jQuery.trim(name) == "Заполните поле 'Название под параграфа'")
                                    {
                                        objName.attr('onclick', "jQuery(this).removeClass('entererror'); this.value = '';");
                                        objName.addClass("entererror");
                                        objName.val("Заполните поле 'Название под параграфа'");
                                    }
                                    else
                                    {


                                        var post = {
                                            idLoad: PM.idLoad,
                                            idAuth: PM.idAuth,
                                            paragraph: tmp[1],
                                            name: name,
                                            text: text,
                                            number: number,
                                        };
                                        
                                        ajax.post("do/addpar/addPartParagraph.php",post,
                                             function(data) {location.reload(); },
                                             function(msg){alert(msg); }
                                         );
                                    }
                                }
                                else
                                {
                                    tmp = editor.name.split("_");
                                    var name = jQuery("#name_EditOk").val();
                                    // var text = editor.getSnapshot();
                                    var text = CKEDITOR.instances[editor.name].getData();
                                    var number = jQuery("#number_EditOk").val();

                                    var post = {
                                        idLoad: PM.idLoad,
                                        idAuth: PM.idAuth,
                                        partParagraph: tmp[2],
                                        name: name,
                                        text: text,
                                        number: number,
                                        paragraph: tmp[3],
                                    };

                                    ajax.post("do/addpar/savePartParagraph.php",post,
                                        function(data)
                                        {    
                                            location.reload();
                                        }, 
                                    function(msg){
                                        alert(msg);
                                    });
                                }
                            },
                            canUndo : true
                        });
                        editor.ui.addButton('Ajaxsave',
                        {
                            label: 'Сохранить изменения',
                            command: pluginName,
                            className : 'cke_button_save',
                            icon: 'ntrue'
                        });
                }
            });