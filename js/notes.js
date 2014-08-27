var Notes = {
    likeNote: function(post) {
        var like = ge("like_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("notes/likeNote.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idNote=" + sps[1],
            function(data){ }, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = ERROR_LIKE_NOTE;
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_count_' + post));
        Notes.likeNoteUpdateUI(like, !my, intval(real_count) + (my ? -1 : 1));          
    },
    likeNoteUpdateUI: function(item, my, count) {
        count = intval(count);
        item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
        item.children("span").text(count>0?count:"");
    },
    
	
	addNote: function(post){ // idLoad
		var textarea = ge("n_enter_" + post);
		if(textarea.val() == notes_create_note) return;
		sps = post.split("_");
		ajax.post_bs("notes/addNote.php","idLoad=" + sps[0] + "&textN=" + textarea.val() + "&idAuth=" + PM.idAuth,
            function(data){},
			function(data){
				// 1. get inserted
				if(data[0] == 'unkmnown' && data[1] == null) {}
				else if(data[0] == 'insertednote' && data[1] == null) {}
				else if(data[0] == 'insertednote' && data[1] != null) 
				{
					// 2. loadUI inserted
					var textF = Common.getValueT("n_enter_" + post);
					data[1][ "id"+data[2] ]["textNote"] = norm(htmlSpecialChars(textF));
					Notes.loadNoteFirstUI(post,data);
					Common.refreshT("n_enter_" + post);
					Notes.ReBinding();
					expand_count = ge("count_note_" + post).text();
					Notes.loadCountNoteUI(post,++expand_count);
                    Notes.overloadNotesUI(sps[0]);
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				var errorMessage = ERROR_ADD_NOTE;
				//Notes.loadCommentLastUI(post,errorMessage,true);
			}
		);
	},
    deleteNote: function(post){ // idLoad_idNote
        var note = ge("delete_note_" + post);
        var sps = post.split("_");
        ajax.post_bs("notes/deleteNote.php","idLoad=" + sps[0] + "&idNote=" + sps[1],
            function(data){ Notes.deleteNoteUI(post); console.log(" Оповещение: запись удалена"); }, 
			function(data){
				expand_count = ge("count_note_" + sps[0]).text();
				Notes.loadCountNoteUI(sps[0],--expand_count);
				Notes.overloadNotesUI(sps[0]);
			}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
    },
	deleteNoteUI: function(post){ // idLoad_idNote
		ge("note_" + post).remove(); // чето другое
		return true;
	},
	loadNoteFirstUI: function(post, data) { // idLoad
		//console.log("START DOM");
		var ins = Notes.prepareNotesUI(post, data[1]),
        notes = ge("notes_" + post);
        notes.prepend(ins);
        //console.log("/ END DOM");
	},
	loadNoteLastUI: function(post, data) { // idLoad
		
	},
	loadNotesUI: function(post, cContinuation){ // idLoad , count_note (default: SETTING_COUNT_CONTINUATION_LOAD_ENTITY )
		cContinuation = parseInt(cContinuation) ? parseInt(cContinuation) : SETTING_COUNT_CONTINUATION_LOAD_ENTITY;
		var idLoad = post;		
		// 1. get ID last
		var note_last = $("div[id^=note_]:last"); 
		var idNoteLast = (note_last.length >= 1) ? note_last.attr('id').split("_")[2] : null;
		
		// 2. execute ajax load
		ajax.post_bs("notes/loadNotes.php","idLoad=" + idLoad + "&countContinuation=" + cContinuation + "&idNoteLast=" + idNoteLast,
            function(data){ if(cContinuation>5) ge("loadNotes_" + idLoad).addClass('activeLoad'); },
			function(data){
				if(data[0] == 'unkmnown' && data[1] == null) {}
				else if(data[0] == 'loadnotes' && data[1] == null) {}
				else if(data[0] == 'loadnotes' && data[1] != null) 
				{
					ge("loadNotes_" + idLoad).removeClass('activeLoad');
					var ins = Notes.prepareNotesUI(idLoad + "_*", data[1]);
					//console.log(ins);
					notes = ge("notes_" + idLoad);
					notes.append(ins);
					// btn еще... скрыть / отобразить
					Notes.loadNotesBtnMoreUI(idLoad);
					Notes.ReBinding();
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				// console.log("error from ajax: " + XMLHttpRequest.responseText);
				var errorMessage = "";
				//Notes.loadCommentLastUI(post,errorMessage,true);
			}
		);
	},
	loadNotesBtnMoreUI: function(post){ // idLoad
		var loadNotes = ge("loadNotes_" + post),
		note_real_count = ge("count_note_" + post).text();
			//console.log('в базе: ' + note_real_count);
		var note_count_ui = $("div[id^=note_]").length;
		//console.log(loadNotes);
		//console.log(note_real_count);
		//console.log(note_count_ui);
		if(note_real_count-note_count_ui == 0) loadNotes.hide();
		else if(note_real_count-note_count_ui > 0) loadNotes.show();
		else console.log('error: notes in db less than the UI ');
	},
	loadNotesBtnEmptyUI: function(post){ // idLoad
		var loadNotesEmpty = ge("loadNotesEmpty_" + post),
		note_real_count = ge("count_note_" + post).text();
		if(note_real_count>0) loadNotesEmpty.hide();
		else if(note_real_count == 0) loadNotesEmpty.show();
		else console.log('error: notes in db less than the ZERO ');
	},
	loadCountNoteUI: function(post, count){
		var count_note = ge("count_note_" + post);
		var count_note_label = ge("count_note_label_" + post);		
		if(count != 0) 
		{
			count_note.text(count);
			count_note_label.text(notes_count);
		}
		else 
		{
			count_note.text('');
			count_note_label.text(notes_count_empty);
		}
	},
	prepareNotesUI: function(post, data) { // prepareNotes to HTML // idLoad
		var prepareHTML = "", note = null, stUnic = null, sps = post.split("_"), ins = "";
        var count = data.length;
        //alert( typeof (+'+8'));
        
        for(var i in data)
        {
            ins = "", note = "";
            note = {
				idNote: data[i]["idNote"],
				textNote: data[i]["textNote"],
				authorNoteID: data[i]["authorNoteID"],
				authorNoteFIO: data[i]["authorNoteFIO"],
				authorNotePathAvatar: data[i]["authorNotePathAvatar"],
				dateNote: data[i]["dateNote"],
				countLikeNote: data[i]["countLikeNote"],
				countComments: data[i]["countComments"],
				isProfileAuthSetLike: data[i]["isProfileAuthSetLike"],
				extension: data[i]["extension"],
				comments: data[i]["comments"]
			};
			stUnic = sps[0] + '_' + note.idNote;
			ins += "<div id='note_" + stUnic + "' class='postedOld'>\
				<a href='index.php?id=" + note.authorNoteID + "'><img class='senderImgPost' src='.." + note.authorNotePathAvatar + "' /></a>\
				<a href='index.php?id=" + note.authorNoteID + "'><span style='margin:0 0 0 20px'>" + note.authorNoteFIO + "</span></a>\
				<span id='right'>" + note.dateNote + "</span>\
				<p style='margin:20px 0 0 0;'>" + note.textNote + "</p>\
				<div id='commentmanage_" + stUnic + "' class='CommentManage'>\
					<div class='likediv'>\
						<div id='like_" + stUnic + "'";
						likes = (note.countLikeNote > 0 ? "likes" + (note.isProfileAuthSetLike ? " my_like" : ' f'): "nolikes");
						ins += " class='"  + likes + "'";
						ins += " onclick='Notes.likeNote(\"" + stUnic + "\")'>\
							<span id='like_count_" + stUnic + "'>";
							ins += note.countLikeNote > 0 ? note.countLikeNote : "";
							ins += "</span>\
						</div>\
					</div>\
					<div id='manage_" + stUnic + "' class='Manage'>\
						<div id='commanage_" + stUnic + "' class='comManage'>\
								<div id='expand_ + stUnic}' " 
								+ (note.countComments>2 ? "class='abbreviated'": "class=''") 
								+ (note.countComments>=0 && note.countComments<=2 ? " style='display:none;'":" ")
								+ "onclick='Notes.expandCommentUI(\"" + stUnic + "\")'>\
								<span id='expand_label_" + stUnic + "'>Развернуть комментар</span>\
								<span id='expand_count_" + stUnic + "'>" + note.countComments + "</span>\
							</div>\
						</div>\
						<div id='postmanage_" + stUnic + "' class='postManage'>";
							ins += ((( note.authorNoteID == PM.idAuth ) || (sps[0] == PM.idAuth )) ? 
								"<div class='deleteNote'><span id='delete_note_" + stUnic + "' onclick='Notes.deleteNote(\"" + stUnic + "\")'>Удалить</span></div>":"");							
						ins += "</div>\
					</div>\
				</div>\
				<div id='comments_" + stUnic + "'>";
				ins += Notes.prepareCommentUI(post, note.comments);		
				ins += "</div>\
				<div id='new_comment_" + stUnic + "' class='c_new_commUn'>\
					<div class='senderImgCom'>\
						<img src='.." + note.authorNotePathAvatar + "' />\
					</div>\
					<div class='enterNewCom'>\
						<textarea id='nc_enter_" + stUnic + "' name='textCommentNote'>Написать комментарий...</textarea>\
					</div>\
					<div id='nc_post_" + stUnic +"' class='postNewCom' onclick='Notes.addComment(\"" + stUnic + "\")'>\
					</div>\
				</div>\
			</div>";
			prepareHTML += ins;            
        }
        return prepareHTML;
	},
    overloadNotesUI: function(post){ // idLoad
		var note_count_ui = $("div[id^=note_]").length,
		note_real_count = ge("count_note_" + post).text();
		// Догрузить до SETTING_COUNT_FIRST_LOAD_ENTITY если не хватает, но есть в БД
		var сount =  note_count_ui < note_real_count ? SETTING_COUNT_FIRST_LOAD_ENTITY - note_count_ui : 0;		
		if( сount > 0) Notes.loadNotesUI(PM.idLoad, сount);
		// Убрать нет записей
		Notes.loadNotesBtnEmptyUI(post);

	},
	
	likeComment: function(post) {
        var like = ge("like_comment_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("notes/likeCommentNote.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idCommentNote=" + sps[1],
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_comment_count_' + post));
        Notes.likeCommentUI(like, !my, intval(real_count) + (my ? -1 : 1));           
    },
    likeCommentUI: function(item, my, count) {
        count = intval(count);
        item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
        item.children("span").text(count>0?count:"");
    },
    
	addComment: function(post){
		var textarea = ge("nc_enter_" + post);
		textarea.trigger(jQuery.Event("validate"));
		if(!textarea.data("valid")) { console.log("stop"); return; }
		sps = post.split("_"),
		idAnswer = textarea.data().idComment;
		tmp = textarea.val();
		Common.refreshT("nc_enter_" + post);
		ajax.post("notes/addCommentNote.php","idLoad=" + sps[0] + "&idAnswer=" + idAnswer + "&idNote=" + sps[1] + "&textCN=" + tmp + "&idAuth=" + PM.idAuth,
            function(data){
				// 1. get inserted
				if(data[0] == 'unkmnown' && data[1] == null) {}
				if(data[0] == 'insertedcomment' && data[1] == null) {}
				if(data[0] == 'insertedcomment' && data[1] != null) { 
					// 2. loadUI inserted
					data[1][data[2]]["textComment"] = norm(htmlSpecialChars(tmp));
					Notes.loadCommentLastUI(post,data);
					expand_count = ge("expand_count_" + post).text();
					Notes.loadCountCommentUI(post, ++expand_count, true);
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				// console.log("error from ajax: " + XMLHttpRequest.responseText);
				var errorMessage = ERROR_ADD_COMMENT;
				Notes.loadCommentLastUI(post,errorMessage,true);
			}
		);
	},
	checkAnswerCommnetUI: function(post){ // idLoad_idNoteComment
		
		var sps = post.split("_"),
		cur_answer_el = ge('comment_' + post),
        comment_author = cur_answer_el.find('#comment_author'),
		FIO = comment_author.text(),
        id_author = comment_author.attr("href").split('=')[1],
        id_comment = sps[1];
        //console.log(FIO);
        //console.log(id_author);
        //console.log(id_comment);
		var new_commnent = cur_answer_el.parent().parent().find("div[id^=new_comment]").first(),
        //span_id_comment = new_commnent.find("span[id=answer_comment_id]"),
        //span_id_author = new_commnent.find("span[id=answer_author_id]"),
        nc_enter = new_commnent.find("textarea[id^=nc_enter]");
        
        //span_id_comment.text(id_comment);
        //span_id_author.text(id_author);
        //nc_enter.text(FIO + " ,");
        nc_enter.trigger(jQuery.Event("setanswer"),[{adress:FIO + ', ', idComment: id_comment, idAuthor: id_author }]);
	},
	deleteComment: function(post){		
		var comments = ge("comment_" + post).parent(),
        note = comments.parent(),
		comment_first = comments.children().first(),
		comment_second = comments.children().last();
		
        var sps_note = note.attr("id").split("_"),
		sps = comment_first.attr("id").split("_"),
        sps_comments = comments.attr("id").split("_");
        
        ajax.post("notes/loadCommentNote.php","idLoad=" + sps[1] + "&idCurNComment=" + sps[2] + "&idNote=" + sps_note[2],
            function(data){
				Notes.deleteCommentUI(post);
                if(data[1] != null) Notes.loadCommentFirstUI(sps_comments[1] + "_" + sps_comments[2], data);
				var expand = ge("expand_" + sps_note[1] + "_" + sps_note[2]);
				var count = ge("expand_count_" + sps_note[1] + "_" + sps_note[2]).text();			
				Notes.loadCountCommentUI(sps_note[1] + "_" + sps_note[2], --count, hasClass(expand, "expanded"));
				
                var sps = post.split("_");
                ajax.post_bs("notes/deleteComment.php","idLoad=" + sps[0] + "&idComment=" + sps[1],
    				function(data){ console.log(" Оповещение: коммент удален"); }, 
    				function(data, textStatus){
    					if(data[0] == "unkmnown" && data[1]==null)
                        { 
                            console.log(textStatus + " из БД не удалено. ошибка."); 
                        }
    				}, 
    				function(XMLHttpRequest, textStatus, errorThrown){ 
    					// console.log("error from ajax: " + XMLHttpRequest.responseText);
    					var errorMessage = "";
    					//Notes.loadCommentLastUI(post,errorMessage,true);
    				}
    			);
			},
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
    },
    deleteCommentUI: function(post){
		ge("comment_" + post).remove(); // чето другое
		return true;
	},
    loadCommentFirstUI: function(post, data){ // add to DOM first in parent
		//console.log("START DOM");
		var ins = Notes.prepareCommentUI(post, data[1]),
        comments = ge("comments_" + post);
        comments.prepend(ins);
        //console.log("/ END DOM");
    },
	loadCommentLastUI: function(post, data, error){ // add to DOM last in parent
		if(error == undefined)
		{
			//console.log("START DOM");
			var ins = Notes.prepareCommentUI(post, data[1]),
	        comments = ge("comments_" + post);
	        comments.append(ins);
	        //console.log("/ END DOM");
	    }
	    else if(error == true)
	    {
	    	var ins = Notes.prepareCommentErrorUI(post, data);
	    		//"<h3>Ошибка отправки данных.</h3>",
	        comments = ge("comments_" + post);
	        comments.append(ins);
	    }
    },
	prepareCommentErrorUI: function(post, data){ // prepareComment Error to HTML
        var prepareHTML = "", comment = null, stUnic = null, sps = post.split("_"), ins = "";

        stUnic = sps[0] + '_ERROR';
        ins = "<div id='comment_" + stUnic + "' class='NoteComment ERROR'>";                    
        ins += "<p style='margin:0; padding:0;'><a id='hashtag' href='' onclick=''></a>";
        ins += data;
        ins += "</p></div>";
        
        prepareHTML += ins;        
        return prepareHTML;
    },

	prepareCommentUI: function(post, data){ // prepareComments to HTML
        var prepareHTML = "", comment = null, stUnic = null, sps = post.split("_"), ins = "";
        var count = data.length;
        for(var i in data)
        {
            ins = "", comment = "";
            var matches_adress = null, matches = null;
            if(data[i]["answerCommentId"]!=null)
			{            
				// полное обращение
			    matches = data[i]["textComment"].match(REG_LCN_START_ADRESS_COMMENT_FULL);
			    console.log('> ' + matches);
			    // Обращение безя запятой
			    if(matches != null && matches != undefined) 
			    {
			    	matches_adress = matches[0].match(REG_LCN_START_ADRESS_COMMENT_WITHOUT_SAC);
				    // текст без обращения и запятой
				    data[i]["textComment"] = data[i]["textComment"].replace(REG_LCN_TEXT_COMMENT_WITHOUT_ADRESS_AND_SAC, "");
			    }
			} else matches_adress = '';

			// adress: matches_adress == null || matches_adress == undefined ? "" : matches_adress[0],
            comment = {
    			idNote: data[i]["idNote"],
    			adress: data[i]["adress"],
                idComment: data[i]["idComment"],
                textC: data[i]["textComment"],
                authorCID: data[i]["authorCommentID"],
                authorCFIO: data[i]["authorCommentFIO"],
                authorCPathAvatar: data[i]["authorCommentPathAvatar"],
                dateC: data[i]["dateComment"],
                countLikeC: data[i]["countLikeComment"],
                isProfileAuthSetLike: data[i]["isProfileAuthSetLike"],
                answerCId: data[i]["answerCommentId"],
                answerAuthorCFIO: data[i]["answerAuthorCommentFIO"],
                answerAuthorCID: data[i]["answerAuthorID"],
                extension: data[i]["extension"],
                subCommnt: data[i]["subCommnt"]
            };
    		
            stUnic = sps[0] + '_' + comment.idComment;
            ins = "<div id='comment_" + stUnic + "' class='NoteComment' onclick='Notes.checkAnswerCommnetUI(\"" + stUnic + "\")'>\
                        <div class='avalike'>\
                            <div class='CommentImage'>\
                        		<a href='index.php?id=" + comment.authorCID + "' onclick='event.stopPropagation();'>\
									<img src='.." + comment.authorCPathAvatar + "' onclick='event.stopPropagation();'/>\
								</a>\
                        	</div>\
                        </div>\
                        <div class='CommentTitle'>\
                            <a id='comment_author' href='index.php?id=" + comment.authorCID + "' onclick='event.stopPropagation();'>" + comment.authorCFIO + "</a>\
                            <span id='comment_date'>" + comment.dateC + "</span>\
                        </div>";
            ins += "<p>";
            ins += comment.answerCId != null && comment.adress != null ? "<a id='hashtag' href='index.php?id=" + comment.answerAuthorCID + "' onclick='event.stopPropagation();'>" + comment.adress + "</a>" + SETTING_ADRESS_COMMENT : "";
            ins += comment.textC;
            ins += "</p>";
                ins += "<div class='CommentBottom'>\
                        	<div class='commentLike'>\
                                <div id='like_comment_" + stUnic + "' class='";
                ins += comment.countLikeC > 0 ? "likes" + (comment.isProfileAuthSetLike ? " my_like" : ""): "nolikes";
                ins += "' onclick='Notes.likeComment(\"" + stUnic + "\"); event.stopPropagation();'>\
                        <span id='like_comment_count_"+ stUnic + "'>";
                ins += comment.countLikeC > 0 ? comment.countLikeC : "";
                ins += "</span>\
                    </div></div>\
                    <div class='commentManage'>";
                ins += ((( comment.authorCID == PM.idAuth ) || (sps[0] == PM.idAuth )) ? 
    				"<div class='deleteNote'><span id='delete_comment_" + stUnic + "' onclick='Notes.deleteComment(\"" + stUnic + "\"); event.stopPropagation();'>Удалить</span></div>" : "");
            ins += "</div></div></div>";
            
            prepareHTML += ins;            
        }
        return prepareHTML;
    },
	
	loadCountCommentUI: function(post, count, expanded){
		var commanage = ge("commanage_" + post);
		ins = "<div id='expand_" + post + "' class='";
					if(expanded) ins += "expanded"; else ins += "abbreviated";
					ins += "'";
					if(count >= 0 && count <=2) ins += "style='display:none;'";
					ins += " onclick='Notes.expandCommentUI(\"" + post + "\")'>";
					ins += "<span id='expand_label_" + post + "' class='changetext'>";
					if(expanded) ins += "Свернуть комментарии</span>"; else ins += "Развернуть комментарии</span>";
					ins += "<span id='expand_count_" + post + "'>" + (count > 0 ? count : "")+ "</span>\
				</div>";
		commanage.empty().prepend(ins);
	},
	
	expandCommentUI: function(post){
		var expand = ge("expand_" + post),
		expanded = hasClass(expand, "expanded"),
		expand_count = ge("expand_count_" + post).text();
		// 2. load from DB all comment (or some 100)	
		var comments = ge("comments_" + post),
        note = comments.parent(),
        comment_second = comments.children().last(),
		comment_first = comment_second.prev();
				
        var sps_note = note.attr("id").split("_"),
		sps = comment_first.attr("id").split("_");
        // 0. Грузить / Скрыть	
        if(!expanded)
        {
    		ajax.post_timeout("notes/loadCommentsNote.php","idLoad=" + sps[1] + "&idCurNComment=" + sps[2] + "&idNote=" + sps_note[2],
    			function(data){
                    Notes.loadCountCommentUI(post, expand_count, true);
                    var commentmanage = ge("comments_" + post);
                    // 1. грузятся...
                    commentmanage.prepend("<div id=alert_" + post + " class='NoteComment'><div class='CommentTitle'><i>грузятся...</i></div></div>"); 
    			}, 
    			function(data){
    				if(data[0] == "unkmnown" && data[1]==null)
                    {
                        // 3.1 ошибка не загружено с сервера
                        console.log(" из БД не удалено. ошибка."); 
                    }
                    else if(data[0] != "unkmnown")
                    {
                        
                        // 3.2 отобразить. данные пришли
                        if(data[1] != null)
                        {
                        	//alert("Выгрузка");
                            ge("alert_" + post).hide().remove();
                            var _html = Notes.prepareCommentUI(post, data[1]);
                            comments.hide().prepend(_html).show();
                        }                        
                    }
    			}, 
    			function(XMLHttpRequest, textStatus, errorThrown){ 
    				// console.log("error from ajax: " + XMLHttpRequest.responseText);
    				var errorMessage = "";
    				//Notes.loadCommentLastUI(post,errorMessage,true);
    			}
            );
        }
        else
        {
            Notes.loadCountCommentUI(post, expand_count, false);
            ge("alert_" + post).hide().remove();
            comments.hide().empty();
            comments.prepend(comment_second);
            comments.prepend(comment_first);
            comments.show();
        }
	},
	ReBinding: function (){	Common.append(); }
}

$(window).scroll(function(){
	//console.log($(document).height());
	//console.log($(window).height());
	//console.log($(window).scrollTop());
	if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		//alert($(window).scrollTop(), $(document).height(), $(window).height());
		//Notes.loadNotesUI(11);
	}
});