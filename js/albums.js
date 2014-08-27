var Albums = 
{
	loadAlbumUI: function(post, cContinuation){ // idLoad , count_note (default: SETTING_COUNT_CONTINUATION_LOAD_ENTITY )
		cContinuation = parseInt(cContinuation) ? parseInt(cContinuation) : SETTING_COUNT_CONTINUATION_LOAD_ENTITY;
		var idLoad = post;
		var idAuth = PM.idAuth;
		//var idMetka =  $('#selectMetka').val();		
		// 1. get ID last
		var album_last = $("div[id^=album_]:last");
		var idAlbumLast = (album_last.length >= 1) ? album_last.attr('id').split("_")[2] : null;
		// 2. execute ajax load
		ajax.post_bs("albums/loadAlbums.php","idLoad=" + idLoad + "&idAuth=" + idAuth + "&countContinuation=" + cContinuation + "&idAlbumLast=" + idAlbumLast,
            function(data){ if(cContinuation>5) ge("loadBlogs_" + idLoad).addClass('activeLoad'); },
			function(data){
				if(data[0] == 'unknown' && data[1] == null) {}
				else if(data[0] == 'loadalbum' && data[1] == null) {}
				else if(data[0] == 'loadalbum' && data[1] != null)
				{
					
					ge("loadAlbums_" + idLoad).removeClass('activeLoad');
					var insertHTML = Albums.prepareAlbumUI(idLoad + "_*", data[1]);
					//console.log(ins);
					album = ge("albums_" + idLoad);
					album.append(insertHTML);
					// btn еще... скрыть / отобразить
					Albums.loadAlbumsBtnMoreUI(idLoad);
					Albums.ReBinding();
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				// console.log("error from ajax: " + XMLHttpRequest.responseText);
				var errorMessage = "";
				//Notes.loadCommentLastUI(post,errorMessage,true);
			}
		);
	},
	prepareAlbumUI: function(post, data) { // prepareNotes to HTML // idLoad
		var prepareHTML = "", content = null, stUnic = null, sps = post.split("_"), insertHTML = "";
        var count = data.length;

        for(var i in data)
        {
            insertHTML = "", note = "";
            album = {
				id: data[i]["id"],
				photoPathAlbum: data[i]["photoPathAlbum"],
				profilePathAvatar: data[i]["profilePathAvatar"],
				name: data[i]["name"],
				description: data[i]["description"],
				countPhoto: data[i]["countPhoto"],
				countComment: data[i]["countComment"],
				countLike: data[i]["countLike"],
				isProfileAuthSetLike: data[i]["isProfileAuthSetLike"],
				comments: data[i]["comments"]
			};
			stUnic = sps[0] + '_' + album.id;
						insertHTML += "<div class='post' id='album_" + stUnic + "'>\
											<div class='albumpic'>\
												<a href='"+ album.id +"'>\
                									<img src='" + album.photoPathAlbum + "'/>\
                								</a>\
                							</div>\
                							\
                							<div class='albumtitle'>\
                								<a href='"+ album.id +"'>" + album.name + "</a>\
									                <div class='buttons'>\
									                    <a href='#'>\
									                      <img src='" + PM.navigate + "/img/phot.png'/>\
									                    	<span>" + album.countPhoto + "</span>\
									                    </a>\
									                </div>\
									                \
									                <div class='albumdesc'>\
									                		<span>" + album.description + "</span>\
									                </div>\
									                \
									             	<div class='albumBottomInfo'>\
									                    <div class='albumLike'>\
									                        <div id='like_" + stUnic + "'";
									                        likes = (album.countLike > 0 ? "likes" + (album.isProfileAuthSetLike ? " my_like" : ' f'): "nolikes");
									                        insertHTML +="class='" + likes + "'";
									                        insertHTML +="onclick='Albums.likeAlbum(\"" + stUnic + "\")'>";
 															insertHTML +="<span id='like_count_" + stUnic + "'>";
 															insertHTML += album.countLike > 0 ? album.countLike : "";
															insertHTML += "</span>\
									                    	</div>\
									                    	";
insertHTML += PM.idAuth == PM.idLoad ? "\
										<div class='deleteAlbum'> <span id='delete_album_" + stUnic + "'\
											  onclick='Albums.deleteAlbum(\"" + stUnic + "\")'>Удалить</span>\
                        				</div>" : "";
insertHTML +=							"<div id ='commanage_" + stUnic + "'class='albumCommentInfo'>";
insertHTML += (album.countComment > 2 ? 					"<div id='expand_" + stUnic +"' class='abbreviated'\
																	onclick='Albums.expandCommentUI(\"" + stUnic + "\")'>" : "");
insertHTML += (album.countComment >= 0 & album.countComment <= 2 ? "<div id='expand_" + stUnic + "' class=''\
																		style='display:none;' onclick='Albums.expandCommentUI(\"" + stUnic + "\")'" : ""); 
insertHTML +=											"<span id='expand_label_" + stUnic + "' class='changetext'>\
																						Развернуть комментарии</span>\
														<span id='expand_count_" + stUnic + "'>" + album.countComment + "\
														</span>";
insertHTML +=											"</div>";
insertHTML +=						                "</div>\
												</div>\
											</div>\
									                \
									                <div id='comments_" + stUnic + "'>\
									                \
									                ";
if(album.comments != null)
insertHTML += Albums.prepareCommentUI(post, album.comments);	
								
insertHTML += 										"</div>\
											</div>\
											";
						stUnic = sps[0] + '_' + album.id;
insertHTML += 								"<div id='new_comment_" + stUnic + "' class='c_new_commUn'>\
												<div class='senderImgCom'>\
								                  <img src='" + PM.navigate + album.profilePathAvatar + "'/>\
								                </div>\
								               	\
								               	<div class='enterNewCom'>\
								                    <textarea id='nc_enter_" + stUnic + "' autocomplete='off' name='textCommentNote'>Написать комментарий...</textarea>\
								                </div>\
								                \
								                <div id='nc_post_" + stUnic + "' class='postNewCom' onclick='Albums.addComment(\"" + stUnic + "\")'></div>\
								            </div>\
								        </div>";								

		prepareHTML += insertHTML; 
		}
		//alert(prepareHTML);	           
        
        return prepareHTML;
	},
	deleteAlbum: function(post){ // idLoad_idNote
        var note = ge("delete_album_" + post);
        var sps = post.split("_");
        ajax.post_bs("albums/deleteAlbum.php","idLoad=" + sps[0] + "&idAlbum=" + sps[1],
            function(data){ Albums.deleteAlbumUI(post); console.log(" Оповещение: запись удалена"); }, 
			function(data){
				expand_count = ge("count_album_" + sps[0]).text();
				Albums.loadCountAlbumUI(sps[0],--expand_count);
				Albums.overloadAlbumsUI(sps[0]);
			}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
    },
	deleteAlbumUI: function(post){ // idLoad_idNote
		ge("album_" + post).remove(); // чето другое
		ge("new_comment_" + post).remove(); // чето другое
		return true;
	},
	loadCountAlbumUI: function(post, count){
		var count_album = ge("count_album_" + post);
		var count_album_label = ge("count_album_label_" + post);		
		if(count != 0) 
		{
			count_album.text(count);
			//count_album_label.text(notes_count);
		}
		else 
		{
			count_album.text(0);
			//count_note_label.text(notes_count_empty);
		}
	},
	overloadAlbumsUI: function(post){ // idLoad
		var album_count_ui = $("div[id^=albums_]").length,
		album_real_count = ge("count_album_" + post).text();
		// Догрузить до SETTING_COUNT_FIRST_LOAD_ENTITY если не хватает, но есть в БД
		var сount =  album_count_ui < album_real_count ? SETTING_COUNT_FIRST_LOAD_ENTITY - album_count_ui : 0;		
		if( сount > 0) Albums.loadAlbumUI(PM.idLoad, сount);
		// Убрать нет записей
		Albums.loadAlbumsBtnEmptyUI(post);

	},
	loadAlbumsBtnMoreUI: function(post){ // idLoad
		var loadAlbums = ge("loadAlbums_" + post),
		album_real_count = ge("count_album_" + post).text();
			//console.log('в базе: ' + note_real_count);
		var album_count_ui = $("div[id^=album_]").length;
		//console.log(loadNotes);
		//console.log(note_real_count);
		//console.log(note_count_ui);
		if(album_real_count-album_count_ui == 0) loadAlbums.hide();
		else if(album_real_count-album_count_ui > 0) loadAlbums.show();
		else console.log('error: notes in db less than the UI ');
	},
	loadAlbumsBtnEmptyUI: function(post){ // idLoad
		var loadAlbumsEmpty = ge("loadAlbumsEmpty_" + post),
		album_real_count = ge("count_album_" + post).text();
		if(album_real_count>0) loadAlbumsEmpty.hide();
		else if(album_real_count == 0) loadAlbumsEmpty.show();
		else console.log('error: notes in db less than the ZERO ');
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
	likeAlbum: function(post) {
        var like = ge("like_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("albums/likeAlbum.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idAlbum=" + sps[1],
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_count_' + post));
        Albums.likeAlbumUI(like, !my, intval(real_count) + (my ? -1 : 1));           
    },
    likeAlbumUI: function(item, my, count) {
        count = intval(count);
        item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
        item.children("span").text(count>0?count:"");
    },
    likeComment: function(post) {
        var like = ge("like_comment_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("albums/likeCommentAlbum.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idCommentAlbum=" + sps[1],
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_comment_count_' + post));
        Albums.likeCommentUI(like, !my, intval(real_count) + (my ? -1 : 1));           
    },
    likeCommentUI: function(item, my, count) {
        count = intval(count);
        item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
        item.children("span").text(count>0?count:"");
    },
    deleteAlbumComment: function(post){		
		var comments = ge("comment_" + post).parent(),
        album = comments.parent().parent(),
		comment_first = comments.children().first(),
		comment_second = comments.children().last();
		
        var sps_album = album.attr("id").split("_"),
		sps = comment_first.attr("id").split("_"),
        sps_comments = comments.attr("id").split("_");
        
        ajax.post("albums/loadCommentAlbum.php","idLoad=" + sps[1] + "&idCurNComment=" + sps[2] + "&idAlbum=" + sps_album[2] + "&idAuth=" + PM.idAuth,
            function(data){
				Albums.deleteAlbumCommentUI(post);
                if(data[1] != null) Albums.loadCommentFirstUI(sps_comments[1] + "_" + sps_comments[2], data);
				var expand = ge("expand_" + sps_album[1] + "_" + sps_album[2]);
				var count = ge("expand_count_" + sps_album[1] + "_" + sps_album[2]).text();			
				Albums.loadCountCommentUI(sps_album[1] + "_" + sps_album[2], --count, hasClass(expand, "expanded"));
				
                var sps = post.split("_");
                ajax.post_bs("albums/deleteCommentAlbum.php","idLoad=" + sps[0] + "&idComment=" + sps[1],
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
    addComment: function(post){
		var textarea = ge("nc_enter_" + post);
		textarea.trigger(jQuery.Event("validate"));
		if(!textarea.data("valid")) { console.log("stop"); return; }
		sps = post.split("_"),
		idAnswer = textarea.data().idComment;
		tmp = textarea.val();
		Common.refreshT("nc_enter_" + post);
		ajax.post("albums/addCommentAlbum.php","idLoad=" + sps[0] + "&idAnswer=" + idAnswer + "&idAlbum=" + sps[1] + "&textCA=" + tmp + "&idAuth=" + PM.idAuth,
            function(data){
				// 1. get inserted
				if(data[0] == 'unkmnown' && data[1] == null) {}
				if(data[0] == 'insertedcomment' && data[1] == null) {}
				if(data[0] == 'insertedcomment' && data[1] != null) { 
					// 2. loadUI inserted
					data[1][data[2]]["text"] = norm(htmlSpecialChars(tmp));
					Albums.loadCommentLastUI(post,data);
					expand_count = ge("expand_count_" + post).text();
					Albums.loadCountCommentUI(post, ++expand_count, true);
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				// console.log("error from ajax: " + XMLHttpRequest.responseText);
				var errorMessage = ERROR_ADD_COMMENT;
				Albums.loadCommentLastUI(post,errorMessage,true);
			}
		);
	},
	loadCommentLastUI: function(post, data, error){ // add to DOM last in parent
		if(error == undefined)
		{
			//console.log("START DOM");
			var insertHTML = Albums.prepareCommentUI(post, data[1]),
	        comments = ge("comments_" + post);
	        comments.append(insertHTML);
	        //console.log("/ END DOM");
	    }
	    else if(error == true)
	    {
	    	var insertHTML = Albums.prepareCommentErrorUI(post, data);
	    		//"<h3>Ошибка отправки данных.</h3>",
	        comments = ge("comments_" + post);
	        comments.append(insertHTML);
	    }
    },
    prepareCommentErrorUI: function(post, data){ // prepareComment Error to HTML
        var prepareHTML = "", comment = null, stUnic = null, sps = post.split("_"), insertHTML = "";

        stUnic = sps[0] + '_ERROR';
        insertHTML = "<div id='comment_" + stUnic + "' class='AlbumComment ERROR'>";                    
        insertHTML += "<p style='margin:0; padding:0;'><a id='hashtag' href='' onclick=''></a>";
        insertHTML += data;
        insertHTML += "</p></div>";
        
        prepareHTML += insertHTML;        
        return prepareHTML;
    },
    loadCountCommentUI: function(post, count, expanded){
		var commanage = ge("commanage_" + post);
		insertHTML = "<div id='expand_" + post + "' class='";
					if(expanded) insertHTML += "expanded"; else insertHTML += "abbreviated";
					insertHTML += "'";
					if(count >= 0 && count <=2) insertHTML += "style='display:none;'";
					insertHTML += " onclick='Albums.expandCommentUI(\"" + post + "\")'>";
					insertHTML += "<span id='expand_label_" + post + "' class='changetext'>";
					if(expanded) insertHTML += "Свернуть комментарии</span>"; else insertHTML += "Развернуть комментарии</span>";
					insertHTML += "<span id='expand_count_" + post + "'>" + (count > 0 ? count : "")+ "</span>\
				</div>";
		commanage.empty().prepend(insertHTML);
	},
	expandCommentUI: function(post){
		var expand = ge("expand_" + post),
		expanded = hasClass(expand, "expanded"),
		expand_count = ge("expand_count_" + post).text();
		// 2. load from DB all comment (or some 100)	
		var comments = ge("comments_" + post),
        album = comments.parent().parent(),
        comment_second = comments.children().last(),
		comment_first = comment_second.prev();
				
        var sps_album = album.attr("id").split("_"),
		sps = comment_first.attr("id").split("_");
        // 0. Грузить / Скрыть	
        if(!expanded)
        {
    		ajax.post_timeout("albums/loadCommentsAlbum.php","idLoad=" + sps[1] + "&idCurAComment=" + sps[2] + "&idAlbum=" + sps_album[2] + "&idAuth=" + PM.idAuth,
    			function(data){
                    Albums.loadCountCommentUI(post, expand_count, true);
                    var commentmanage = ge("comments_" + post);
                    // 1. грузятся...
                    commentmanage.prepend("<div id=alert_" + post + " class='AlbumComment'><div class='CommentTitle'><i>грузятся...</i></div></div>");
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
                            var _html = Albums.prepareCommentUI(post, data[1]);
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
            Albums.loadCountCommentUI(post, expand_count, false);
            ge("alert_" + post).hide().remove();
            comments.hide().empty();
            comments.prepend(comment_second);
            comments.prepend(comment_first);
            comments.show();
        }
	},
    deleteAlbumCommentUI: function(post){
		ge("comment_" + post).remove(); // чето другое
		return true;
	},
	loadCommentFirstUI: function(post, data){ // add to DOM first in parent
		//console.log("START DOM");
		var ins = Albums.prepareCommentUI(post, data[1]),
        comments = ge("comments_" + post);
        comments.prepend(ins);
        //console.log("/ END DOM");
    },
    prepareCommentUI: function(post, data){ // prepareComments to HTML
        var prepareHTML = "", comment = null, stUnic = null, sps = post.split("_"), insertHTML = "";
        var count = data.length;
        for(var i in data)
        {
            insertHTML = "", comment = "";
            var matches_adress = null, matches = null;
            if(data[i]["answerCommentId"]!=null)
			{            
				// полное обращение
			    matches = data[i]["text"].match(REG_LCN_START_ADRESS_COMMENT_FULL);
			    console.log('> ' + matches);
			    // Обращение безя запятой
			    if(matches != null && matches != undefined) 
			    {
			    	matches_adress = matches[0].match(REG_LCN_START_ADRESS_COMMENT_WITHOUT_SAC);
				    // текст без обращения и запятой
				    data[i]["text"] = data[i]["text"].replace(REG_LCN_TEXT_COMMENT_WITHOUT_ADRESS_AND_SAC, "");
			    }
			} else matches_adress = '';

            comment = {
    			albumNNN_id: data[i]["albumNNN_id"],
    			adress: matches_adress == null || matches_adress == undefined ? "" : matches_adress[0],
                id: data[i]["id"],
                text: data[i]["text"],
                profile_id: data[i]["profile_id"],
                authorCommentFIO: data[i]["authorCommentFIO"],
                authorCommentPathAvatar: data[i]["authorCommentPathAvatar"],
                datetime: data[i]["datetime"],
                countLike: data[i]["countLike"],
                isProfileAuthSetLike: data[i]["isProfileAuthSetLike"],
                answerCommentId: data[i]["answerCommentId"],
                answerAuthorCommentFIO: data[i]["answerAuthorCommentFIO"],
                answerAuthorCID: data[i]["answerAuthorID"],
                extension: data[i]["extension"]
                //subCommnt: data[i]["subCommnt"]
            };
    		
            stUnic = sps[0] + '_' + comment.id;
insertHTML = "<div id='comment_" + stUnic + "' class='AlbumComment' onclick='Albums.checkAnswerCommnetUI(\"" + stUnic + "\");'>\
				<div class='avalike'>\
                    <div class='CommentImage'>\
                    	<a href='index.php?id=" + comment.profile_id + "' onclick='event.stopPropagation();'/>\
                    		<img src='" + PM.navigate + comment.authorCommentPathAvatar + "'>\
                    	</a>\
                    </div>\
                </div>\
                \
                <div class='right'>\
                        <div class='CommentTitle'>\
                            <a id = 'comment_author' href='./index.php?id=" + comment.profile_id + "' onclick='event.stopPropagation();'>" + comment.authorCommentFIO + "</a>\
                            <span>" + comment.datetime + "</span>\
                        </div>\
                        \
                        <div class='CommentText'>";
insertHTML +="<p>";
insertHTML +=comment.answerCommentId != null && comment.adress != null ? "<a id='hashtag' href='index.php?id=" + comment.answerAuthorCID + "' onclick='event.stopPropagation();'>" + comment.adress + "</a>" + SETTING_ADRESS_COMMENT : "";                           
insertHTML += comment.text;
insertHTML += "</p>\
 						</div>\
                </div>";
insertHTML +="<div class='CommentBottom'>\
  					<div class='commentLike'>\
 						<div id='like_comment_" + stUnic + "' class='";
insertHTML += comment.countLike > 0 ? "likes" + (comment.isProfileAuthSetLike ? " my_like" : ""): "nolikes";
insertHTML += "' onclick='Albums.likeComment(\"" + stUnic + "\");'>\
               <span id='like_comment_count_" + stUnic + "'>";
insertHTML += comment.countLike > 0 ? comment.countLike : "";
insertHTML += "<span>\
						</div>\
					</div>\
					\
                    <div class='commentManage'>";
insertHTML += (PM.idAuth == PM.idLoad ? "<a id='delete_comment_" + stUnic + "'\
										onclick='Albums.deleteAlbumComment(\"" + stUnic + "\"); event.stopPropagation();'>Удалить</a>" : "");
insertHTML += "		</div>\
              </div>\
              </div>";

 		// prepareHTML += tmpl($("#album_comment").html(), {SETTING_ADRESS_COMMENT: SETTING_ADRESS_COMMENT ,stUnic:stUnic, comment: comment});
        prepareHTML += insertHTML;
        }
        return prepareHTML;
    },
    checkAnswerCommnetUI: function(post){ // idLoad_idNoteComment
		
		var sps = post.split("_");
		cur_answer_el = ge('comment_' + post);
        comment_author = cur_answer_el.find('#comment_author');
		FIO = comment_author.text();
        id_author = comment_author.attr("href").split('=')[1];
        id_comment = sps[1];
        //console.log(FIO);
        //console.log(id_author);
        //console.log(id_comment);
		var new_commnent = cur_answer_el.parent().parent().parent().find("div[id^=new_comment]").first(),
        //span_id_comment = new_commnent.find("span[id=answer_comment_id]"),
        //span_id_author = new_commnent.find("span[id=answer_author_id]"),
        nc_enter = new_commnent.find("textarea[id^=nc_enter]");
        
        //span_id_comment.text(id_comment);
        //span_id_author.text(id_author);
        //nc_enter.text(FIO + " ,");
        nc_enter.trigger(jQuery.Event("setanswer"),[{adress:FIO + ', ', idComment: id_comment, idAuthor: id_author }]);
	},
	ReBinding: function (){	Common.append(); }

};
