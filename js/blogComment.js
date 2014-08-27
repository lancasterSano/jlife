var BlogComment = {
	deleteComment: function(post){
		var sps = post.split("_");
		console.log(sps);
		ajax.post("blog/deleteCommentBlog.php",
                    "idPost=" + sps[1] + "&idOwner=" + sps[0],
                    function(response){
                    	ge("blogcomment_" + post).remove();

                        expand_count = ge("count_blogcomment_" + sps[0]).text();
                        BlogComment.loadCountBlogCommentUI(sps[0],--expand_count);
                        BlogComment.overloadBlogCommentUI(sps[0]);
                  	},
                    function(XMLHttpRequest, textStatus, errorThrown){
                       	alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                    }
        );
	},
    loadCountBlogCommentUI: function(post, count){
        var count_blogcomment = ge("count_blogcomment_" + post);
        //var count_blog_label = ge("count_note_label_" + post);        
        if(count != 0) 
        {
            count_blogcomment.text(count);
            //count_note_label.text(notes_count);
        }
        else 
        {
            count_note.text(0);
            //count_note_label.text(notes_count_empty);
        }
    },
    overloadBlogCommentUI: function(post){ // idLoad
        var blogcomment_count_ui = $("div[id^=blogcomment_]").length,
        blogcomment_real_count = ge("count_blogcomment_" + post).text();
        // Догрузить до SETTING_COUNT_FIRST_LOAD_ENTITY если не хватает, но есть в БД
        var сount =  blogcomment_count_ui < blogcomment_real_count ? SETTING_COUNT_FIRST_LOAD_ENTITY - blogcomment_count_ui : 0;     
        if( сount > 0) BlogComment.loadBlogCommentUI(PM.idLoad, сount);
        // Убрать нет записей
        Blog.loadBlogBtnEmptyUI(post);

    },
    loadBlogCommentUI: function(post, cContinuation){ // idLoad , count_note (default: SETTING_COUNT_CONTINUATION_LOAD_ENTITY )
        cContinuation = parseInt(cContinuation) ? parseInt(cContinuation) : SETTING_COUNT_CONTINUATION_LOAD_ENTITY;
        var idLoad = post;
        var idAuth = PM.idAuth;
        //var idMetka =  $('#selectMetka').val();     
        // 1. get ID last
        var blogComment_last = $("div[id^=blogcomment_]:last");
        var idBlogCommentLast = (blogComment_last.length >= 1) ? blogComment_last.attr('id').split("_")[2] : null;
        // 2. execute ajax load
        ajax.post_bs("blog/loadBlogComment.php","idLoad=" + idLoad + "&idAuth=" + idAuth + "&countContinuation=" + cContinuation + "&idBlogCommentLast=" + idBlogCommentLast,
            function(data){ if(cContinuation>5) ge("loadBlogComments_" + idLoad).addClass('activeLoad'); },
            function(data){
                if(data[0] == 'unknown' && data[1] == null) {}
                else if(data[0] == 'loadblogcomments' && data[1] == null) {}
                else if(data[0] == 'loadblogcomments' && data[1] != null)
                {
                    
                    ge("loadBlogComments_" + idLoad).removeClass('activeLoad');
                    var insertHTML = BlogComment.prepareBlogCommentUI(idLoad + "_*", data[1]);
                    //console.log(ins);
                    blogComment = ge("blogComments_" + idLoad);
                    blogComment.append(insertHTML);
                    // btn еще... скрыть / отобразить
                    BlogComment.loadBlogCommentBtnMoreUI(idLoad);
                    BlogComment.reBinding();
                }
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                console.log("error from ajax: " + XMLHttpRequest.responseText);
                var errorMessage = "";
                //Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
    },
    prepareBlogCommentUI: function(post, data) { // prepareNotes to HTML // idLoad
        var prepareHTML = "", comment = null, stUnic = null, sps = post.split("_"), insertHTML = "";
        var count = data.length;
        var idAuth = PM.idAuth;
        var idLoad = PM.idLoad;
        //alert( typeof (+'+8'));

        for(var i in data)
        {
            insertHTML = "", note = "";
            comment = {
                id: data[i]["id"],
                text: data[i]["text"],
                datetime: data[i]["datetime"],
                authorCommentID: data[i]["authorCommentID"],
                authorCommentFio: data[i]["authorCommentFIO"],
                authorCommentPathAvatar: data[i]["authorCommentPathAvatar"],
                countlikeComment: data[i]["countLikeComment"],
                extension: data[i]["extension"],
                postName: data[i]["postName"],
                answerCommentProfile_id: data[i]["answerCommentId"],
                answerAuthorCommentFio: data[i]["answerAuthorCommentFIO"],
                isProfileAuthSetLike: data[i]["isProfileAuthSetLike"]
            };
            stUnic = sps[0] + '_' + comment.id;
            insertHTML += "<div class='BlogComment' id='blogcomment_"+stUnic+"'>\
                                            <div class='title'>Коментарий к статье\
                                                <a href='#'>"+comment.postName+"</a>\
                                            </div>\
                                            <div class='avalike'>\
                                                <div class='CommentImage'>\
                                                    <a href='#'><img src='.."+comment.authorCommentPathAvatar+"'/></a>\
                                                </div>\
                                            </div>\
                                            <div class='CommentTitle'>\
                                                <a href='#'>"+comment.authorCommentFio+"</a>\
                                                <span>"+comment.datetime+"</span>\
                                            </div>";
                            insertHTML += (comment.answerCommentProfile_id != null ? "<p><a id='hashtag' href='#'>"+comment.answerAuthorCommentFio+"</a>, "+comment.text+"</p>" : "<p>"+comment.text+"</p>");
                            
                            insertHTML += "<div class='CommentBottom'>\
                                                <div class='commentLike'>\
                                                    <div id ='like_"+stUnic+"'";
                                                        likes = (comment.countlikeComment > 0 ? "likes" + (comment.isProfileAuthSetLike ? " my_like" : ' f'): "nolikes");
                                                        insertHTML +="class='" + likes + "'";
                                                        insertHTML +="onclick='BlogComment.likeComment(\"" + stUnic + "\")'>";
                                                        insertHTML +="<span id='like_count_" + stUnic + "'>";
                                                        insertHTML += comment.countlikeComment > 0 ? comment.countlikeComment : "";
                                                        insertHTML += "</span>\
                                                    </div>\
                                                </div>";
                            insertHTML += "<div class='commentManage'>";
                            insertHTML += ((comment.authorCommentID == idAuth) || (idLoad == idAuth) ? "<a href='#'id='"+comment.id+"' onclick='BlogComment.deleteComment(\""+stUnic+"\")'>Удалить</a>" : "<p>НИФИГА</p>");
                            
                            insertHTML +="</div>\
                                        </div>\
                                    </div>";
                            console.log(insertHTML);
                            // response.contents[i].id
                            // response.contents[i].text
                            // response.contents[i].datetime
                            // response.contents[i].idauthor
                            // response.contents[i].countlike
                            // response.contents[i].countcomment
                            // response.contents[i].extension
                            // response.contents[i].name
                            // response.contents[i].source
                            // response.contents[i].nameLink
                            // response.contents[i].metkas
        prepareHTML += insertHTML; 
        }
        //alert(prepareHTML);              
        
        return prepareHTML;
    },
    loadBlogCommentBtnMoreUI: function(post){ // idLoad
        var loadBlogComment = ge("loadBlogComments_" + post),
        blogComment_real_count = ge("count_blogcomment_" + post).text();
            //console.log('в базе: ' + note_real_count);
        var blogComment_count_ui = $("div[id^=blogcomment_]").length;
        //console.log(loadNotes);
        //console.log(note_real_count);
        //console.log(note_count_ui);
        if(blogComment_real_count-blogComment_count_ui == 0) loadBlogComment.hide();
        else if(blogComment_real_count-blogComment_count_ui > 0) loadBlogComment.show();
        else console.log('error: notes in db less than the UI ');
    },
    reBinding: function (){ Common.append();
    },
	likeComment: function(post) {
        var like = ge("like_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("blog/likeCommentBlog.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idCommentBlog=" + sps[1],
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_count_' + post));
        BlogComment.likeCommentUI(like, !my, intval(real_count) + (my ? -1 : 1));           
    },
    likeCommentUI: function(item, my, count) {
        count = intval(count);
        item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
        item.children("span").text(count>0?count:"");
    }
};
