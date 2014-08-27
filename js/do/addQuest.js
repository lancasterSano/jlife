var Questions = 
{
	/*****|sessionActionFALSE|****** Функция сворачивания всех частей параграфа кроме того, к которому добавляется вопрос ***********/
		sessionActionFALSE: function(idPartParagraph){
			$(".questLvl").hide();
			ge("partParagraph_"+idPartParagraph).children(".questLvl").show();
			Questions.addQuestionUI("partParagraph_"+idPartParagraph);
		},

	/*****|transformComplexity|****** Преобразование цифры сложности в соответствующее слово ***********/
		transformComplexity: function(number){
			var complexity;
			switch (number){
	        case '1': complexity = 'Легкий'; break;
	        case '2': complexity = 'Средний'; break;
	        case '3': complexity = 'Тяжелый'; break;
	    	}
	    	return complexity;
		},

	/*****|loadQuestionsUI|****** Свернуть/Развернуть вопросы части параграфа ***********/
		loadQuestionsUI: function(post){
			var sps = post.split("_");
			var idPartParagraph = sps[1];
			var reloadStateAfterDeleteQuestion = sps[2];
			var complexity = "";
			var a = $("#partParagraph_"+idPartParagraph).children(".questLvl");
			if(!reloadStateAfterDeleteQuestion)
			{
				if (a.css('display') == 'block')
		        {
		            a.hide(250); // slideToggle
		            $("span[id^=addQuestMenu_"+idPartParagraph+"_]").attr('id','addQuestMenu_'+idPartParagraph+'_2');
		            $("span[id^=addQuestMenu_"+idPartParagraph+"_]").text('Развернуть');
		            // $("#turnButtonPartParagraph_"+idPartParagraph).css('background-image',  'url("../../img/rwnu.png")');
		            $("#turnButtonPartParagraph_"+idPartParagraph).attr('class', 'cutCl');
		        }
		        else if(a.css('display') == 'none')
		        {
		            a.show(250); // slideToggle
		            $("span[id^=addQuestMenu_"+idPartParagraph+"_]").attr('id','addQuestMenu_'+idPartParagraph+'_1');
		            $("span[id^=addQuestMenu_"+idPartParagraph+"_]").text('Свернуть');
		            // $("#turnButtonPartParagraph_"+idPartParagraph).css('background-image', 'url("../../img/rwn.png")');
		            $("#turnButtonPartParagraph_"+idPartParagraph).attr('class', 'cutOp');
		        }
		    }
		    else
		    {
		    	var number = 0;
		    	ajax.post_sync("do/addQuest/loadQuestionsUI.php","idPartParagraph=" + idPartParagraph,
	            function(data){
	            	var prepareHTML = "";
	            	for(var i in data)
		            	{
			            	data[i]["complexity"] = Questions.transformComplexity(data[i]["complexity"]);
			            	number=i;
			            	var	insertHTML = "";
			            		question = {
			                        id: data[i]["id"],
			                        text: data[i]["text"],
			                        complexity: data[i]["complexity"],
			                        countAnswer: data[i]["countanswer"],
			                        partParagraph: data[i]["partparagraphS_id1"]
			                    };
			                insertHTML += "<div id ='question_"+question.id+"' class='questLvl' style='display: block;'>";
							insertHTML +=       "<div id='questName' class='questName'>\
								                    <div id='turnButtonQuestion_"+question.partParagraph+"_"+question.id+"'class='cutCl' onclick='Questions.loadAnswersUI(\"question_"+question.id+"_"+question.partParagraph+"\");'>\
								                    </div>\
								                    \
								                    <span id='title'>"+ ++number +". "+question.text+"(<span class='count'>"+question.countAnswer+"</span>)</span>\
								                	\
								                    <div id='' style='float:right;'>\
								                        <div id='' class='finnish'>\
								                            <span>\
								                                Добавить ответ<img src='../../img/settings.png'>\
								                            </span>\
								                            \
								                            <div class='displayMenu' style='display:none;'>\
								                                <span class='headermenu'>Добавить ответ<img src='../../img/settings.png'></span>\
								                                <span id='editQuestion_"+question.id+"' class='menuitem' onclick='Questions.editQuestion(\"editQuestion_"+question.id+"_"+number+"_"+question.countAnswer+"_"+question.complexity+"\")'>Редактировать</span>\
								                                <span id='' class='menuitem' onclick='Questions.deleteQuestion(\"question_"+question.id+"_"+question.partParagraph+"\");'>Удалить</span>\
								                                <span id='addQuestMenu_3_"+question.id+"_2' class='menuitem' onclick='Questions.loadAnswersUI(\"question_"+question.id+"_"+question.partParagraph+"\");''>Развернуть</span>\
								                            </div>\
								                        </div>\
								                    </div>\
								                </div>\
								                \
								                <div id='questTools' class='questTools'>\
								                    <span>\
								                          сложность вопроса   - "+question.complexity+"\
								                    </span>\
								                </div>\
								            </div>";
							prepareHTML += insertHTML;
			            }
				        	$("#partParagraph_"+idPartParagraph).children("div[id^=question_]").remove();
				        	$("#partParagraph_"+idPartParagraph).append(prepareHTML);
				        	ComboList.initAction();
	            }, 
	            function(XMLHttpRequest, textStatus, errorThrown){ 
	                var errorMessage = "";
	            }
	        	);
		    }
		},

	/*****|loadAnswersUI|****** Свернуть/Развернуть варианты ответов вопроса ***********/
		loadAnswersUI: function(post){
			var number=0;
			var sps = post.split("_");
			var idQuestion = sps[1];
			var idPartParagraph = sps[2];
			var reloadStateAfterDeleteAnswer = sps[3];
			var classHover = "";
			var tmp = $("span[id^=addQuestMenu_3_"+idQuestion+"]").attr('id').split("_");
	        if (tmp[3]==1 && reloadStateAfterDeleteAnswer == null)
	        {
	            $("span[id^=addQuestMenu_3_"+idQuestion+"_]").attr('id','addQuestMenu_3_'+idQuestion+'_2');
	            $("span[id^=addQuestMenu_3_"+idQuestion+"_]").text('Развернуть');
	            $("#turnButtonQuestion_"+idPartParagraph+"_"+idQuestion).css('background-image',  'url("../../img/rwnu.png")');
	            $('#addAnswerFast_'+idPartParagraph+'_'+idQuestion+'').attr('onclick','Questions.addAnswerFast('+idPartParagraph+','+idQuestion+')');
	        }
	        else
	        {
	            $("span[id^=addQuestMenu_3_"+idQuestion+"_]").attr('id','addQuestMenu_3_'+idQuestion+'_1');
	            $("span[id^=addQuestMenu_3_"+idQuestion+"_]").text('Свернуть');
	            $("#turnButtonQuestion_"+idPartParagraph+"_"+idQuestion).css('background-image', 'url("../../img/rwn.png")');
	            $('#addAnswerFast_'+idPartParagraph+'_'+idQuestion+'').attr('onclick','').unbind('click');
	        }

			/*AJAX запрос на проверку валидности вопроса*/ 
			ajax.post_sync("do/addQuest/checkValidQuestion.php","idQuestion=" + idQuestion,
	            function(data){
	            	valid = data[0];
	            }, 
	            function(XMLHttpRequest, textStatus, errorThrown){ 
	                var errorMessage = "";
	            }
	        	);

			ajax.post_sync("do/addQuest/loadAnswersUI.php","idQuestion=" + idQuestion,
	            function(data){
	            	var prepareHTML = "";
					var className = $("#question_"+idQuestion).children(".questAnsw");
	            	if( className.length == 0 || reloadStateAfterDeleteAnswer)
		            {
		            	prepareHTML = "<div class='questAnsw'>";
		            	for(var i in data)
		            	{
			            	number=i;
			            	var	insertHTML = "";
			            		answer = {
			                        id: data[i]["id"],
			                        text: data[i]["text"],
			                        right: data[i]["right"],
			                        deleted: data[i]["deleted"]
			                    };
			                if(answer.right == 0)
			                	classHover = "class='no-hover'";
			                else
			                	classHover = "class='selected'";
			                insertHTML += "	<div id='answerRow_"+answer.id+"' class='answItem'>\
								              <a id='deleteAnswer_"+ answer.id +"' onclick='Questions.deleteAnswer(\"deleteAnswer_"+ answer.id +"_"+idQuestion+"\");' class='remark' title='Удалить'></a>\
								              <a id='editAnswer_"+ answer.id +"' onclick='Questions.editAnswer(\"editAnswer_"+ answer.id +"_"+ ++number +"\");' class='remarkOth' title='Редактировать'></a>\
								              <span "+classHover+" id='answer_" + answer.id + "' onclick='Questions.setRightAnswer(\"answer_" + answer.id + "_"+idQuestion+"\");'>" + ++i + ") " + answer.text + "</span>\
								            </div>";
							prepareHTML += insertHTML;
			            }
			            prepareHTML +="		<div id='manageAnswAdd_"+idQuestion+"' class='manageAnsw'>\
								            	  <a onclick='Questions.addAnswerUI(\"question_"+idQuestion+"\");'>Добавить ответ</a>\
								            </div>";
						if(valid == 0)
							prepareHTML += "<span class='comment'>Для активизации вопроса необходимо добавить минимум 4 варианта ответов и отметить минимум 1 верный</span>";
			            	prepareHTML += "</div>";
				        
				        if(reloadStateAfterDeleteAnswer)
				        {
				        	$("#question_"+idQuestion).children(".questAnsw").remove();
				        	$("#question_"+idQuestion).children(".questName").after(prepareHTML);
				        }
				        else
				        {
				            $.when( $("#question_"+idQuestion).children(".questName").after(prepareHTML) ).done(function( ) {
							  $("#question_"+idQuestion).find(".questAnsw").hide().show(250);
							});
				        }
			        }
			        else
			        {
			        	$.when( $("#question_"+idQuestion).children(".questAnsw").hide(250) ).done(function( ) {
						  this.remove(); // Alerts "123"
						});
			        	// $("#question_"+idQuestion).children(".questAnsw").hide(500,
			        	// 	function(){
			        	// 		this.remove(); // удаляем элемент из DOM дерева $("#question_"+idQuestion).children(".questAnsw")
			        	// 	});
	            	}
	            },
				function(XMLHttpRequest, textStatus, errorThrown){ var errorMessage = ""; }
			);
		},

	/*****|setRightAnswer|****** Установить верный/неверный ответ ***********/
		setRightAnswer: function(post){
			var sps = post.split("_");
			var idAnswer = sps[1];
			var idQuestion = sps[2];
			if(ge("answer_"+idAnswer).hasClass("no-hover"))
				ge("answer_"+idAnswer).removeClass("no-hover").addClass("selected");	
			else
				ge("answer_"+idAnswer).removeClass("selected").addClass("no-hover");
			
				ajax.post_sync("do/addQuest/setRightAnswer.php","idAnswer=" + idAnswer + "&idQuestion=" + idQuestion,
	            function(data){
	            	valid = data[0];
	            	if(valid == 0)
	            	{	
	            		if(ge("question_"+idQuestion).find(".comment").length != 1)
	            		ge("question_"+idQuestion).children(".questAnsw").append("<span class='comment'>Для активизации вопроса необходимо минимум 4 варианта ответов и 1 верный</span>");
	            	}
	            	else
	            		ge("question_"+idQuestion).find(".comment").remove();
	            }, 
	            function(XMLHttpRequest, textStatus, errorThrown){ 
	                var errorMessage = "";
	            }
	        	);
		},

	/*****|addAnswerUI|****** Отобразить поле для добавления нового ответа ***********/
		addAnswerUI: function(post, idPartParagraph){
			var sps = post.split("_");
			var idQuestion = sps[1];
			var countAnswers = $("#question_"+idQuestion).find("div [id^=answerRow_]").length+1;
			var addAnswerButton = ge("question_"+idQuestion).find(".manageAnsw");
			$("div[id^=manageAnswAdd_]").show();
			$("#manageAnswAdd_"+idQuestion).hide();
			$("#answerRow").remove();
			ge('question').remove();
			Questions.cancelChangesAnswer();
			addAnswerButton.before("<div id='answerRow' class='answItem' style='display: none;'>\
								        <a id='deleteAnswer' class='remark'></a>\
								        <a id='editAnswer' class='remarkOth'></a>\
								        <span class='answLetR'>"+countAnswers+")</span><input id='idInpute' type='text' value=\"\"></input>\
										<div id='manageAnswEdit'class='manageAnsw'>\
							              <span onclick='Questions.saveAddingAnswer(\"question_"+idQuestion+"\",\""+idPartParagraph+"\");'>Сохранить ответ</span>\
							              <span onclick='Questions.cancelAddingAnswer(\"question_"+idQuestion+"\",\""+idPartParagraph+"\");'>Отменить</span>\
							            </div>\
								    </div>");
			$("#answerRow").show(250);
			$("#idInpute").focus();
		},

	/*****|addAnswerFast|****** Быстрый доступ к добавлению нового ответа ***********/
		addAnswerFast: function(idPartParagraph, idQuestion){
			Questions.loadAnswersUI('question_'+idQuestion+'_'+idPartParagraph+'');
			Questions.addAnswerUI('question_'+idQuestion+'',''+idPartParagraph+'');
			$('#addAnswerFast_'+idPartParagraph+'_'+idQuestion+'').attr('onclick','').unbind('click');
		},

	/*****|editAnswer|****** Открыть поле для редактирования ответа ***********/
		editAnswer: function(post){
			var insertHTML = "";
			var sps = post.split("_");
			var idAnswer = sps[1];
			var number = sps[2]; // номер ответа в списке
			var lengthNumber = number.length+2; // длина строки number
			var answerText = ge("answer_"+idAnswer).text();
			var lengthAnswerText = answerText.length; // длина строки answerText
			answerText = answerText.substr(lengthNumber); // обрезаем номер


			insertHTML += "<span class='answLetR'>"+number+")</span><input id='idInpute' type='text' value=\""+ answerText +"\"></input>\
							<div id='manageAnswEdit'class='manageAnsw'>\
				              <span onclick='Questions.saveChangesAnswer(\"answer_"+idAnswer+"_"+number+"\");'>Сохранить ответ</span>\
				              <span onclick='Questions.cancelChangesAnswer(\"\");'>Отменить</span>\
				            </div>";
			if(ge("answerRow_"+idAnswer).find(".answLetR").length == 0)
			{
				$("div [id^=answer_]").show();
				$(".answLetR").remove();
				$('#idInpute').remove();
				$('#manageAnswEdit').remove();
				$("#answerRow").remove();
				ge('question').remove();
				$("div [id^=manageAnswAdd_]").show();
				var answerHided = ge("answer_"+idAnswer);
				answerHided.hide();
				ge("answerRow_"+idAnswer).append(insertHTML);
			}

		},

	/*****|saveChangesAnswer|****** Сохранение изминений варианта ответа ***********/
		saveChangesAnswer: function(post){
			var newText = $("#idInpute").val();
			var objInputeFieldAnswer = ge("idInpute");
			if ($.trim(newText).length == 0 || $.trim(newText) == "Заполните поле ответа")
	        {
	            objInputeFieldAnswer.attr('onclick', "$(this).removeClass('entererror'); this.value = '';");
	            objInputeFieldAnswer.addClass("entererror");
	            objInputeFieldAnswer.val("Заполните поле ответа");//alert("Вы не можете создать раздел параграфа без названия.");
	        }
			else
			{
				var sps = post.split("_");
				var idAnswer = sps[1];
				var number = sps[2];
				ajax.post_sync("do/addQuest/saveChangesAnswer.php","idAnswer=" + idAnswer + "&newText=" + newText,
		            function(data){
		            	$(".answLetR").remove();
						$('#idInpute').remove();
						$('#manageAnswEdit').remove();
		            	var textOld = ge("answer_"+sps[1]).text(number+") "+newText); //
						ge("answer_"+sps[1]).show();
		            	ge("answer_"+sps[1]).children(".answLet").text(number);
		            }, 
		            function(XMLHttpRequest, textStatus, errorThrown){ 
		                var errorMessage = "";
		            }
		        );
		    }
		},

	/*****|cancelChangesAnswer|****** Закрытие поля для редактирвоания ответа ***********/
		cancelChangesAnswer: function(){
			$(".answLetR").remove();
			$('#idInpute').remove();
			$('#manageAnswEdit').remove();
			$('div [id^=answer_]').show();
		},

	/*****|saveAddingAnswer|****** Сохранение нового варианта ответа ***********/
		saveAddingAnswer: function(post, idPartParagraph){
			var text = ge("idInpute").val();
			var objInputeFieldAnswer = ge("idInpute");
			if ($.trim(text).length == 0 || $.trim(text) == "Заполните поле ответа")
	        {
	            objInputeFieldAnswer.attr('onclick', "$(this).removeClass('entererror'); this.value = '';");
	            objInputeFieldAnswer.addClass("entererror");
	            objInputeFieldAnswer.val("Заполните поле ответа");//alert("Вы не можете создать раздел параграфа без названия.");
	        }
			else
			{
				var sps = post.split("_");
				var idQuestion = sps[1];
				var numberText = $(".answLetR").text(); // вытаскиваем номер по списку как текст в переменную, например: "1)"
				var lengthNumberText = numberText.length-1;
				numberText = numberText.substring(0,lengthNumberText);
				ajax.post_sync("do/addQuest/saveAddingAnswer.php","text=" + text + "&idQuestion=" + idQuestion,
		            function(data){
		            	var idAnswer = data["idAnswer"];
		            	var valid = data["validQuestion"];

		            	ge("answerRow").attr('id','answerRow_'+idAnswer);

		            	ge("deleteAnswer").attr('onclick',"Questions.deleteAnswer('deleteAnswer_"+idAnswer+"_"+idQuestion+"');");
		            	ge("deleteAnswer").attr('id','deleteAnswer_'+ idAnswer +'_'+ idQuestion);
		            	
		            	ge("editAnswer").attr('onclick',"Questions.editAnswer('editAnswer_"+idAnswer+"_"+numberText+"');");
		            	ge("editAnswer").attr('id','editAnswer_'+ idAnswer);
		            	
		            	var onclick = $("#editQuestion_"+idQuestion).attr('onclick').split("_");
		            	var countanswers = parseInt(onclick[3])+1;
		            	var complexity = onclick[4].substring(0,onclick[4].length-2);
		            	$("#editQuestion_"+idQuestion).attr('onclick',"Questions.editQuestion('editQuestion_"+idQuestion+"_"+onclick[2]+"_"+ countanswers+"_"+complexity+"')");
		            	$(".answLetR").remove();
		            	ge("idInpute").remove();
		            	ge("manageAnswEdit").remove();
		            	ge("answerRow_"+idAnswer).append("<span class='no-hover' id='answer_" + idAnswer + "' onclick='Questions.setRightAnswer(\"answer_" + idAnswer + "_"+idQuestion+"\");'>" + numberText + ") " + text + "</span>");
		            	$("#manageAnswAdd_"+idQuestion).show();
		            	
		            	if(valid == 0)
		            	{	
		            		if(ge("question_"+idQuestion).find(".comment").length != 1)
		            		ge("question_"+idQuestion).children(".questAnsw").append("<span class='comment'>Для активизации вопроса необходимо минимум 4 варианта ответов и 1 верный</span>");
		            	}
		            	else
		            		ge("question_"+idQuestion).find(".comment").remove();

		            	var countAnswers = parseInt(ge("question_"+idQuestion).find(".count").text());
		            	ge("question_"+idQuestion).find(".count").text(countAnswers + 1);

		            }, 
		            function(XMLHttpRequest, textStatus, errorThrown){ 
		                var errorMessage = "";
		            }
		        	);
			}
		},

	/*****|cancelAddingAnswer|****** Закрытие добавление нового ответа ***********/
		cancelAddingAnswer: function(post, idPartParagraph){
			var sps = post.split("_");
			var idQuestion = sps[1];
			$("#answerRow").hide(250, function(){
				this.remove();
			});
			$("#manageAnswAdd_"+idQuestion).show();
		},

	/*****|deleteAnswer|****** Удаление варианта ответа ***********/
		deleteAnswer: function(post){
			var sps = post.split("_");
			var idAnswer = sps[1];
			var idQuestion = sps[2];
			ajax.post_sync("do/addQuest/deleteAnswer.php","idAnswer=" + idAnswer + "&idQuestion=" + idQuestion,
	            function(data){
	            	var valid = data[0];
	            	var a = ge("answerRow_"+idAnswer);
	            	$.when( a.hide(150) ).done(function( ) {
							  this.remove();
							
					if(valid == 0)
	            	{	
	            		if(ge("question_"+idQuestion).find(".comment").length != 1)
	            		ge("question_"+idQuestion).children(".questAnsw").append("<span class='comment'>Для активизации вопроса необходимо минимум 4 варианта ответов и 1 верный</span>");
	            	}
	            	else
	            		ge("question_"+idQuestion).find(".comment").remove();
					var countAnswers = ge("question_"+idQuestion).find(".count");
					var countanswer = ge("question_"+idQuestion).find(".count").text() - 1;
	            	countAnswers.text(countanswer);

	            	var onclick = $("#editQuestion_"+idQuestion).attr('onclick').split("_");
	            	var complexity = onclick[4].substring(0,onclick[4].length-2);
	            	$("#editQuestion_"+idQuestion).attr('onclick',"Questions.editQuestion('editQuestion_"+idQuestion+"_"+onclick[2]+"_"+countanswer+"_"+complexity+"')");
	            	var partpparent = ge("question_"+idQuestion).parent().attr('id').split("_");

	            	Questions.loadAnswersUI("question_"+idQuestion+"_"+partpparent[1]+"_reload");

	            	});
	            }, 
	            function(XMLHttpRequest, textStatus, errorThrown){ 
	                var errorMessage = "";
	            }
	        	);
		},
		
	/*****|addQuestionUI|****** Отобразить поле для добавления нового вопроса ***********/
		addQuestionUI: function(post){
			sps = post.split("_");
			var insertHTML = "";
			var j;
			var idPartParagraph = sps[1];
			var countQuestions = $("#partParagraph_"+idPartParagraph).find("div[id^=question_]").length+1;
			var complexityArray = new Array('Легкий','Средний','Тяжелый');
			insertHTML +="	<div id ='question' class='questLvl'>\
								<div class='questText'>\
						          <div class='questName'>\
						              <div class='cutCl'>\
						              </div>\
						              \
						              <span id='numberOfQuestion'>"+countQuestions+".</span>\
						              \
						              <textarea id='questionText'></textarea>\
						              \
						              <div class='manage'>\
						              	  <a style='margin-bottom:3px;'>\
						              	    <span onclick='Questions.saveAddingQuestion(\"partParagraph_"+idPartParagraph+"\");'>\
						              	    	Сохранить\
						              	    </span>\
						              	  </a>\
						                  <a>\
						                  	<span onclick='Questions.cancelAddingQuestion(\"\");'>\
						                  		Отменить\
						                  	</span>\
						                  </a>\
						              </div>\
						          </div>\
						          \
						          <div class='questTools'>\
				                    <span>\
				                          сложность вопроса   -\
				                    </span>\
					                    \
					                    <div class='styled-select'>\
					                        <select id='complexity'>";

					                        for(var i=0; i<3;i++)
						                    {
						                       var j = i; 
						                       if(i == 0)
						                        	stateSelected = "selected";
						                       else
						                        	stateSelected = "";

						                        insertHTML += "<option "+stateSelected+" value='"+ ++j +"'>"+complexityArray[i]+"</option>";
						                    }
					                        insertHTML += "</select>\
					                    </div>\
					                </div>\
						        </div>\
						    </div>";

			ge("answerRow").remove();
			ge("question").remove();
			Questions.cancelChangesAnswer();
			$("div [id^=manageAnswAdd_]").show();
			$("#partParagraph_"+idPartParagraph).children(".questLvl").show(200);
	        $("#partParagraph_"+idPartParagraph).append(insertHTML);
	        ge("question").hide().show(200);

		},

	/*****|saveAddingQuestion|****** Сохранение нового вопроса ***********/
		saveAddingQuestion: function(post){
			var text = $("#questionText").val();
			var objInputeFieldQuestion = ge("questionText");
			if ($.trim(text).length == 0 || $.trim(text) == "Заполните поле вопроса")
	        {
	            objInputeFieldQuestion.attr('onclick', "$(this).removeClass('entererror'); this.value = '';");
	            objInputeFieldQuestion.addClass("entererror");
	            objInputeFieldQuestion.val("Заполните поле вопроса");//alert("Вы не можете создать раздел параграфа без названия.");
	        }
			else
			{
				var sps = post.split("_");
				var idPartParagraph = sps[1];
				var insertHTML = "";

				var complexity = $("#complexity").val();
				var numberOfQuestion = ge("numberOfQuestion").text(); // вытаскиваем номер вопроса в списке, например: "1."
				var lengthNumberOfQuestion = numberOfQuestion.length-1;
				numberOfQuestion = numberOfQuestion.substring(0,lengthNumberOfQuestion);

				ajax.post_sync("do/addQuest/saveAddingQuestion.php","text=" + text + "&idPartParagraph=" + idPartParagraph + "&complexity=" + complexity,
		            function(data){
		            	var idQuestion = data;
		            	complexity = Questions.transformComplexity(complexity);
		  				insertHTML += "<div id ='question_"+idQuestion+"' class='questLvl' style='display: block;'>\
							                <div id='questName' class='questName'>\
							                    <div id='turnButtonQuestion_"+idPartParagraph+"_"+idQuestion+"'class='cutCl' onclick='Questions.loadAnswersUI(\"question_"+idQuestion+"_"+idPartParagraph+"\");'>\
							                    </div>\
							                    \
							                    <span id='title'>"+numberOfQuestion+". "+text+"(<span class='count'>0</span>)</span>\
							                	\
							                    <div id='' style='float:right;'>\
							                        <div id='' class='finnish'>\
							                            <span>\
							                                Управление<img src='../../img/settings.png'>\
							                            </span>\
							                            \
							                            <div class='displayMenu' style='display:none;'>\
							                                <span class='headermenu'>Управление<img src='../../img/settings.png'></span>\
							                                <span id='addAnswerFast_"+idPartParagraph+"_"+idQuestion+"' class='menuitem' onclick='Questions.addAnswerFast("+idPartParagraph+","+idQuestion+")'>Добавить ответ</span>\
							                                <span id='editQuestion_"+idQuestion+"' class='menuitem' onclick='Questions.editQuestion(\"editQuestion_"+idQuestion+"_"+numberOfQuestion+"_0_"+complexity+"\")'>Редактировать</span>\
							                                <span id='' class='menuitem' onclick='Questions.deleteQuestion(\"question_"+idQuestion+"_"+idPartParagraph+"\");'>Удалить</span>\
							                                <span id='addQuestMenu_3_"+idQuestion+"_2' class='menuitem' onclick='Questions.loadAnswersUI(\"question_"+idQuestion+"_"+idPartParagraph+"\");'>Развернуть</span>\
							                            </div>\
							                        </div>\
							                    </div>\
							                </div>\
							                \
							                <div id='questTools' class='questTools'>\
							                    <span>\
							                          сложность вопроса   - "+complexity+"\
							                    </span>\
							                </div>\
							            </div>";
						ge("question").remove();
						ge("partParagraph_"+idPartParagraph).append(insertHTML);
						ComboList.initAction();

						var countQuestions = parseInt(ge("countQuestions_"+idPartParagraph).text());
		            	ge("countQuestions_"+idPartParagraph).text(countQuestions + 1);

		            	var turnButton = $('#turnButtonPartParagraph_'+idPartParagraph+'');
		            	if(turnButton.attr('class') == 'cutNoActive')
		            	{
		            		turnButton.toggleClass('cutOp cutNoActive');
		            		turnButton.attr('onclick','Questions.loadQuestionsUI("partParagraph_'+idPartParagraph+'");')
		            	}
		            	Questions.loadAnswersUI("question_"+idQuestion+"_"+idPartParagraph+"");
		            	$('#addAnswerFast_'+idPartParagraph+'_'+idQuestion+'').attr('onclick','').unbind('click');

		            }, 
		            function(XMLHttpRequest, textStatus, errorThrown){ 
		                var errorMessage = "";
		            }
		        	);
			}
		},

	/*****|editQuestion|****** Отобразить поле для редактирования вопроса ***********/
		editQuestion: function(post){
			// var idLoad = PM.idLoad;
			// var idAuth = PM.idAuth;
			// if(idLoad == idAuth)
			// {
				var sps = post.split("_");
				var idQuestion = sps[1];
				var number = sps[2]; // номер вопроса в списке
				var countAnswer = sps[3]; // количество ответов в вопросе
				var complexity = sps[4]; // сложность вопроса
				var lengthNumber = number.length+2; // длина строки number
				var lengthCountAnswer = countAnswer.length+2; // длина строки countAnswer
				var questionText = ge("question_"+idQuestion).find("#title").text();
				var lengthQuestionText = questionText.length; // длина строки questionText
				questionText = questionText.substring(lengthNumber,lengthQuestionText-lengthCountAnswer); // обрезаем номер и количество ответов
				
				$("div [id^=question_]").children(".questName").show();
				$("div [id^=question_]").children(".questTools").show();

				$("#question_"+idQuestion).children(".questName").hide();
				$("#question_"+idQuestion).children(".questTools").hide();
				$("#EditSure").remove();
				$(".questText").remove();

				var complexityArray = new Array('Легкий','Средний','Тяжелый');

				var stateSelected = "";
				var insertHTML = "";
				insertHTML +="  <div id='EditSure' class='btn' style='display:none;' > <div> Вы уверены что хотите сохранить  внесенные изменения?</div>\
							       <span  onclick ='Questions.saveChangesQuestion(\"question_"+idQuestion+"\");' >Сохранить&nbsp</span>\
							       <span onclick='$(\"div[id^=EditSure]\").hide();'>&nbspОтменить&nbsp</span>\
						        </div>\
								<div class='questText'>\
						          <div class='questName'>\
						              <div class='cutCl'>\
						              </div>\
						              \
						              <span>"+number+".</span>\
						              \
						              <textarea id='questionText'>"+questionText+"</textarea>\
						              \
						              <div class='manage'>\
						              	  <a style='margin-bottom:3px;'>\
						              	    <span onclick='$(\"#EditSure\").show();'>\
						              	    	Сохранить\
						              	    </span>\
						              	  </a>\
						                  <a>\
						                  	<span onclick='Questions.abortChangesQuestion(\"question_"+idQuestion+"\");'>\
						                  		Отменить\
						                  	</span>\
						                  </a>\
						              </div>\
						          </div>\
						          \
						          <div class='questTools'>\
				                    <span>\
				                          сложность вопроса   -\
				                    </span>\
					                    \
					                    <div class='styled-select'>\
					                        <select id='complexity'>";

					                        for(var i=0; i<3;i++)
						                    {
						                       var j = i; 
						                       if(complexity == complexityArray[i])
						                        	stateSelected = "selected";
						                       else
						                        	stateSelected = "";

						                        insertHTML += "<option "+stateSelected+" value='"+ ++j +"'>"+complexityArray[i]+"</option>";
						                    }
					                        insertHTML += "</select>\
					                    </div>\
					                </div>\
						        </div>";
		        $("#question_"+idQuestion).prepend(insertHTML);
			// }
		},

	/*****|saveChangesQuestion|****** Сохранение изминений вопроса ***********/
		saveChangesQuestion: function(post){
			var newText = $("#questionText").val();
			var objInputeFieldQuestion = ge("questionText");
			if ($.trim(newText).length == 0 || $.trim(newText) == "Заполните поле вопроса")
	        {
	            objInputeFieldQuestion.attr('onclick', "$(this).removeClass('entererror'); this.value = '';");
	            objInputeFieldQuestion.addClass("entererror");
	            objInputeFieldQuestion.val("Заполните поле вопроса");//alert("Вы не можете создать раздел параграфа без названия.");
	        }
			else
			{
				// var sps = post.split("_");
				var idQuestion = post.split("_")[1];
				var complexity = $("#complexity").val();
				var idPartParagraph = $("#EditSure").parent().parent().attr('id').split('_')[1];

				ajax.post_sync("do/addQuest/saveChangesQuestion.php","idQuestion=" + idQuestion + "&newText=" + newText + "&complexity=" + complexity + "&idPartParagraph=" + idPartParagraph,
		            function(data){
		                location.reload();
		            }, 
		            function(XMLHttpRequest, textStatus, errorThrown){ 
		                var errorMessage = "";
		            }
		        	);
			}
		},

	/*****|abortChangesQuestion|****** Отменить подтверждение на сохранение изминений ***********/
		abortChangesQuestion: function(post){
			$("#"+post).children(".questText").remove();
			ge("EditSure").remove();
			$("#"+post).children(".questName").show();
			$("#"+post).children(".questTools").show();
		},

	/*****|cancelAddingQuestion|****** Закрытие добавление вопроса ***********/
		cancelAddingQuestion: function(){
			ge("question").hide(200, function(){
				ge("question").remove();
			});
		},

	/*****|deleteQuestion|****** Удаление вопроса ***********/
		deleteQuestion: function(post){
			var sps = post.split("_");
			var idQuestion = sps[1];
			var idPartParagraph = sps[2];
			ajax.post_sync("do/addQuest/deleteQuestion.php","idQuestion=" + idQuestion,
	            function(data){
					var countQuestionsOld = ge("countQuestions_"+idPartParagraph);
					var countQuestionsNew = countQuestionsOld.text() - 1;
	            	countQuestionsOld.text(countQuestionsNew);
					ge("question_"+idQuestion).hide(150, function(){
						this.remove(); // удаление из DOM дерева ge("question_"+idQuestion)
						Questions.loadQuestionsUI("partParagraph_"+idPartParagraph+"_reload");
					});
					if(countQuestionsNew == 0)
					{
						var turnButton = $('#turnButtonPartParagraph_'+idPartParagraph+'');
						turnButton.toggleClass('cutNoActive cutOp');
	            		turnButton.attr('onclick','').unbind('click');
	            	}
	            }, 
	            function(XMLHttpRequest, textStatus, errorThrown){ 
	                var errorMessage = "";
	            }
	        	);
		}
};