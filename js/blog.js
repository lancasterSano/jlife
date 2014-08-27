var Blog = 
{
	loadPostsByMetka: function (){
	// Берем значение атрибута value из тега <option>
	var idMetka = $('#selectMetka').val();
	console.log(idMetka);
	insertHTML = "";
	idLoad = PM.idLoad;
        ajax.post("blog/loadPostsByMetka.php",
                    "idMetka=" + idMetka + "&idOwner=" + PM.idLoad,
                    function(response){
                        console.log(countblog);

                        $(".postssum").empty();
                        for (var i in response.countblog)
                        {
	                        insertHTML +="<img src='" + PM.navigate + "/img/like2.png'/>\
								            <span id='count_blog_"+idLoad+"'>";
							insertHTML += (idMetka != 0 ? ""+response.countblog[i].count+"" : ""+response.countblog+"");
							insertHTML +="</span><span> статьи</span>";
						}
						$(".postssum").append(insertHTML);
						insertHTML = "";
						// Делаем div по указанному id пустым
						$("#blogs_" + idLoad).empty();
						// Запускаем цикл по возвращенному запросу
						for (var i in response.contents)
						{
							var idContent = response.contents[i].id;
							var stUnic = idLoad+"_"+idContent;
							var countblog = response.countblog;
							
							
							insertHTML += "<div class='post' id='blog_"+stUnic+"'>\
											<div class='title'>\
												<a href='?id=&us='>"+response.contents[i].name+"</a>\
												<span>"+response.contents[i].datetime+"</span>\
											</div>\
											<div class='author'>\
												<span>Автор:</span><a href=''>"+response.author+"</a>";
							insertHTML += (PM.idAuth == PM.idLoad ? "<a href='#' class='deletePost' id='"+response.contents[i].id+"' onclick='Blog.deletePost(\""+stUnic+"\");'>Удалить</a>" : "");
							
							insertHTML += "</div>\
											<div class='text'>\
												<span>"+response.contents[i].text+"</span>\
											</div>\
											<div class='hashtag'>";
							// Запускаем цикл по меткам внтури одного массива "contents"
							for (var j in response.contents[i].metkas)
							{
								insertHTML += "<a href='#' class='metka' id='"+response.contents[i].metkas[j].idblogmetka+"'style='background-color:"+response.contents[i].metkas[j].color+"' onclick='Blog.loadPostsByHref(event);'>&lt"+response.contents[i].metkas[j].name+"&gt</a>";
							}
							insertHTML +="	</div>\
											<div class='bottominfo'>\
												<div class='like'>\
													<div id ='like_"+stUnic+"'";
														likes = (response.contents[i].countlike > 0 ? "likes" + (response.contents[i].isProfileAuthSetLike ? " my_like" : ' f'): "nolikes");
														insertHTML +="class='" + likes + "'";
														insertHTML +="onclick='Blog.likeBlog(\"" + stUnic + "\")'>";
														insertHTML +="<span id='like_count_" + stUnic + "'>";
														insertHTML += response.contents[i].countlike > 0 ? response.contents[i].countlike : "";
														insertHTML += "</span>\
													</div>\
												</div>\
												<div class='out'>";
							insertHTML += (response.contents[i].source != null ? "<span>Источник:</span><a href='"+response.contents[i].source+"'>"+response.contents[i].nameLink+"</a>" : "");

							insertHTML +="  </div>\
												<div class='more'>\
												   <a href='#'>Подробнее...</a>\
												</div>\
												<div class='comm'>\
													<a href='#'><img src='" + PM.navigate + "/img/switch.png'/></a>\
													<span>Коментарии</span><a href='#'>"+response.contents[i].countcomment+"</a>\
												</div>\
											</div>\
										</div>";
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
						}
						$("div #blogs_" + idLoad).append(insertHTML);
						Blog.loadBlogBtnMoreUI(idLoad);
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){
                        alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                    }
        );
    },
	
	loadPostsByHref: function (e){
	var idMetka = e.target.id;
	var idLoad = PM.idLoad;
				//var valueMetka = e.target.innerText;
	console.log(idMetka);
				//console.log(valueMetka);
	// Изменяем значение value в тэге select
	$('#selectMetka').val(idMetka);
	insertHTML = "";
        ajax.post("blog/loadPostsByMetka.php",
                    "idMetka=" + idMetka + "&idOwner=" + idLoad,
                    function(response){
                        console.log(response);
						// Убираем из HTML кода блок с классом content
						$(".postssum").empty();
                        for (var i in response.countblog)
                        {
	                        insertHTML +="<img src='" + PM.navigate + "/img/like2.png'/>\
								            <span id='count_blog_"+idLoad+"'>";
							insertHTML += (idMetka != 0 ? ""+response.countblog[i].count+"" : ""+response.countblog+"");
							insertHTML +="</span><span> статьи</span>";
						}
						$(".postssum").append(insertHTML);
						insertHTML = "";
						// Делаем div со всеми статьями по указанному id пустым
						$("#blogs_" + idLoad).empty();
						// Запускаем цикл по возвращенному запросу
						for (var i in response.contents)
						{
							var idContent = response.contents[i].id;
							var stUnic = idLoad+"_"+idContent;
							var countblog = response.countblog;

							insertHTML += "<div class='post' id='blog_"+stUnic+"'>\
											<div class='title'>\
												<a href='?id=&us='>"+response.contents[i].name+"</a>\
												<span>"+response.contents[i].datetime+"</span>\
											</div>\
											<div class='author'>\
												<span>Автор:</span><a href=''>"+response.author+"</a>";
							insertHTML += (PM.idAuth == PM.idLoad ? "<a href='#' class='deletePost' id='"+response.contents[i].id+"' onclick='Blog.deletePost(\""+stUnic+"\");'>Удалить</a>" : "");
							
							insertHTML += "</div>\
											<div class='text'>\
												<span>"+response.contents[i].text+"</span>\
											</div>\
											<div class='hashtag'>";
							// Запускаем цикл по меткам внтури одного массива "contents"
							for (var j in response.contents[i].metkas)
							{
								insertHTML += "<a href='#' class='metka' id='"+response.contents[i].metkas[j].idblogmetka+"'style='background-color:"+response.contents[i].metkas[j].color+"' onclick='Blog.loadPostsByHref(event);'>&lt"+response.contents[i].metkas[j].name+"&gt</a>";
							}
							insertHTML +="	</div>\
											<div class='bottominfo'>\
												<div class='like'>\
													<div id ='like_"+stUnic+"'";
														likes = (response.contents[i].countlike > 0 ? "likes" + (response.contents[i].isProfileAuthSetLike ? " my_like" : ' f'): "nolikes");
														insertHTML +="class='" + likes + "'";
														insertHTML +="onclick='Blog.likeBlog(\"" + stUnic + "\")'>";
														insertHTML +="<span id='like_count_" + stUnic + "'>";
														insertHTML += response.contents[i].countlike > 0 ? response.contents[i].countlike : "";
														insertHTML += "</span>\
													</div>\
												</div>\
												<div class='out'>";
							insertHTML += (response.contents[i].source != null ? "<span>Источник:</span><a href='"+response.contents[i].source+"'>"+response.contents[i].nameLink+"</a>" : "");

							insertHTML +="  </div>\
												<div class='more'>\
												   <a href='#'>Подробнее...</a>\
												</div>\
												<div class='comm'>\
													<a href='#'><img src='" + PM.navigate + "/img/switch.png'/></a>\
													<span>Коментарии</span><a href='#'>"+response.contents[i].countcomment+"</a>\
												</div>\
											</div>\
										</div>";
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
						}
						$("div #blogs_" + idLoad).append(insertHTML);
						Blog.loadBlogBtnMoreUI(idLoad);
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){
                        alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                    }
        );
	},
	deletePost: function(post)
	{
		//var idPost = e.target.id;
		var sps = post.split("_");
		console.log(sps);
        ajax.post("blog/deletePost.php",
                    "idPost=" + sps[1] + "&idOwner=" + sps[0],
                    function(response){
                    	// BlogUI
                    	ge("blog_" + post).remove();

                    	expand_count = ge("count_blog_" + sps[0]).text();
						Blog.loadCountBlogUI(sps[0],--expand_count);
						Blog.overloadBlogUI(sps[0]);
                  	},
                    function(XMLHttpRequest, textStatus, errorThrown){
                       	alert("error   ts: " + textStatus + "   et: "+ errorThrown + " xml: " + XMLHttpRequest.responseText);
                    }
        );
	},
	loadCountBlogUI: function(post, count){
		var count_blog = ge("count_blog_" + post);
		//var count_blog_label = ge("count_note_label_" + post);		
		if(count != 0) 
		{
			count_blog.text(count);
			//count_note_label.text(notes_count);
		}
		else 
		{
			count_blog.text(0);
			//count_note_label.text(notes_count_empty);
		}
	},
	overloadBlogUI: function(post){ // idLoad
		var blog_count_ui = $("div[id^=blog_]").length,
		blog_real_count = ge("count_blog_" + post).text();
		// Догрузить до SETTING_COUNT_FIRST_LOAD_ENTITY если не хватает, но есть в БД
		var сount =  blog_count_ui < blog_real_count ? SETTING_COUNT_FIRST_LOAD_ENTITY - blog_count_ui : 0;		
		if( сount > 0) Blog.loadBlogUI(PM.idLoad, сount);
		// Убрать нет записей
		Blog.loadBlogsBtnEmptyUI(post);

	},
	loadBlogsBtnEmptyUI: function(post){ // idLoad
		var loadBlogsEmpty = ge("loadBlogsEmpty_" + post),
		blog_real_count = ge("count_blog_" + post).text();
		if(blog_real_count>0) loadBlogsEmpty.hide();
		else if(blog_real_count == 0) loadBlogsEmpty.show();
		else console.log('error: notes in db less than the ZERO ');
	},
	likeBlog: function(post) {
        var like = ge("like_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("blog/likeBlog.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idBlog=" + sps[1],
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
            	// console.log("error from ajax: " + XMLHttpRequest.responseText);
            	var errorMessage = "";
            	//Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_count_' + post));
        Blog.likeBlogUI(like, !my, intval(real_count) + (my ? -1 : 1));           
    },
    likeBlogUI: function(item, my, count) {
        count = intval(count);
        item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
        item.children("span").text(count>0?count:"");
    },
    loadBlogUI: function(post, cContinuation){ // idLoad , count_note (default: SETTING_COUNT_CONTINUATION_LOAD_ENTITY )
		cContinuation = parseInt(cContinuation) ? parseInt(cContinuation) : SETTING_COUNT_CONTINUATION_LOAD_ENTITY;
		var idLoad = post;
		var idAuth = PM.idAuth;
		var idMetka =  $('#selectMetka').val();
		// 1. get ID last
		var blog_last = $("div[id^=blog_]:last");
		var idBlogLast = (blog_last.length >= 1) ? blog_last.attr('id').split("_")[2] : null;
		// 2. execute ajax load
		ajax.post_bs("blog/loadBlog.php","idLoad=" + idLoad + "&idAuth=" + idAuth + "&countContinuation=" + cContinuation + "&idBlogLast=" + idBlogLast + "&idMetka=" + idMetka,
            function(data){ if(cContinuation>5) ge("loadBlogs_" + idLoad).addClass('activeLoad'); },
			function(data){
				if(data[0] == 'unknown' && data[1] == null) {}
				else if(data[0] == 'loadblog' && data[1] == null) {}
				else if(data[0] == 'loadblog' && data[1] != null)
				{
					
					ge("loadBlogs_" + idLoad).removeClass('activeLoad');
					var insertHTML = Blog.prepareBlogUI(idLoad + "_*", data[1]);
					//console.log(ins);
					blog = ge("blogs_" + idLoad);
					blog.append(insertHTML);
					// btn еще... скрыть / отобразить
					Blog.loadBlogBtnMoreUI(idLoad);
					Blog.reBinding();
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown){ 
				// console.log("error from ajax: " + XMLHttpRequest.responseText);
				var errorMessage = "";
				//Notes.loadCommentLastUI(post,errorMessage,true);
			}
		);
	},
	prepareBlogUI: function(post, data) { // prepareNotes to HTML // idLoad
		var prepareHTML = "", content = null, stUnic = null, sps = post.split("_"), insertHTML = "";
        var count = data.length;
        //alert( typeof (+'+8'));
        
        for(var i in data)
        {
            insertHTML = "", note = "";
            content = {
				id: data[i]["id"],
				text: data[i]["text"],
				datetime: data[i]["datetime"],
				idauthor: data[i]["idauthor"],
				authorFio: data[i]["fioAuthor"],
				countlike: data[i]["countlike"],
				countcomment: data[i]["countcomment"],
				extension: data[i]["extension"],
				name: data[i]["name"],
				source: data[i]["source"],
				nameLink: data[i]["nameLink"],
				metkas: data[i]["metkas"],
				isProfileAuthSetLike: data[i]["isProfileAuthSetLike"]
			};
			stUnic = sps[0] + '_' + content.id;
			insertHTML += "<div class='post' id='blog_"+stUnic+"'>\
											<div class='title'>\
												<a href='?id=&us='>"+content.name+"</a>\
												<span>"+content.datetime+"</span>\
											</div>\
											<div class='author'>\
												<span>Автор:</span><a href=''>"+content.authorFio+"</a>";
							insertHTML += (PM.idAuth == PM.idLoad ? "<a href='#' class='deletePost' id='"+content.id+"' onclick='Blog.deletePost(\""+stUnic+"\");'>Удалить</a>" : "");
							
							insertHTML += "</div>\
											<div class='text'>\
												<span>"+content.text+"</span>\
											</div>\
											<div class='hashtag'>";
							// Запускаем цикл по меткам внтури одного массива "contents"
							for (var j in content.metkas)
							{
								insertHTML += "<a href='#' class='metka' id='"+content.metkas[j].idblogmetka+"'style='background-color:"+content.metkas[j].color+"' onclick='Blog.loadPostsByHref(event);'>&lt"+content.metkas[j].name+"&gt</a>";
							}
							insertHTML +="	</div>\
											<div class='bottominfo'>\
												<div class='like'>\
													<div id ='like_"+stUnic+"'";
														likes = (content.countlike > 0 ? "likes" + (content.isProfileAuthSetLike ? " my_like" : ' f'): "nolikes");
														insertHTML +="class='" + likes + "'";
														insertHTML +="onclick='Blog.likeBlog(\"" + stUnic + "\")'>";
														insertHTML +="<span id='like_count_" + stUnic + "'>";
														insertHTML += content.countlike > 0 ? content.countlike : "";
														insertHTML += "</span>\
													</div>\
												</div>\
												<div class='out'>";
							insertHTML += (content.source != null ? "<span>Источник:</span><a href='"+content.source+"'>"+content.nameLink+"</a>" : "");

							insertHTML +="  </div>\
												<div class='more'>\
												   <a href='#'>Подробнее...</a>\
												</div>\
												<div class='comm'>\
													<a href='#'><img src='" + PM.navigate + "/img/switch.png'/></a>\
													<span>Коментарии</span><a href='#'>"+content.countcomment+"</a>\
												</div>\
											</div>\
										</div>";
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
	loadBlogBtnMoreUI: function(post){ // idLoad
		var loadBlogs = ge("loadBlogs_" + post),
		blog_real_count = ge("count_blog_" + post).text();
			//console.log('в базе: ' + note_real_count);
		var blog_count_ui = $("div[id^=blog_]").length;
		//console.log(loadNotes);
		//console.log(note_real_count);
		//console.log(note_count_ui);
		if(blog_real_count-blog_count_ui == 0) loadBlogs.hide();
		else if(blog_real_count-blog_count_ui > 0) loadBlogs.show();
		else console.log('error: notes in db less than the UI ');
	},
	reBinding: function (){	Common.append(); }
};

