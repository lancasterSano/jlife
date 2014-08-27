var PhotoAlbumComment =
{

  deletePhotoAlbumComment: function(post){		
  		// var comments = ge("comment_" + post).parent(),
    //       album = comments.parent().parent(),
  		// comment_first = comments.children().first(),
  		// comment_second = comments.children().last();
  		
    //       var sps_album = album.attr("id").split("_"),
  		// sps = comment_first.attr("id").split("_"),
    //       sps_comments = comments.attr("id").split("_");
          
    //       ajax.post("albums/loadCommentAlbum.php","idLoad=" + sps[1] + "&idCurNComment=" + sps[2] + "&idAlbum=" + sps_album[2] + "&idAuth=" + PM.idAuth,
    //           function(data){
  		// 		Albums.deleteAlbumCommentUI(post);
    //               if(data[1] != null) Albums.loadCommentFirstUI(sps_comments[1] + "_" + sps_comments[2], data);
  		// 		var expand = ge("expand_" + sps_album[1] + "_" + sps_album[2]);
  		// 		var count = ge("expand_count_" + sps_album[1] + "_" + sps_album[2]).text();			
  		// 		Albums.loadCountCommentUI(sps_album[1] + "_" + sps_album[2], --count, hasClass(expand, "expanded"));
  				
    //               var sps = post.split("_");
    //               ajax.post_bs("albums/deleteCommentAlbum.php","idLoad=" + sps[0] + "&idComment=" + sps[1],
    //   				function(data){ console.log(" Оповещение: коммент удален"); }, 
    //   				function(data, textStatus){
    //   					if(data[0] == "unkmnown" && data[1]==null)
    //                       { 
    //                           console.log(textStatus + " из БД не удалено. ошибка."); 
    //                       }
    //   				}, 
    //   				function(XMLHttpRequest, textStatus, errorThrown){ 
    //   					// console.log("error from ajax: " + XMLHttpRequest.responseText);
    //   					var errorMessage = "";
    //   					//Notes.loadCommentLastUI(post,errorMessage,true);
    //   				}
    //   			);
  		// 	},
    //           function(XMLHttpRequest, textStatus, errorThrown){ 
    //           	// console.log("error from ajax: " + XMLHttpRequest.responseText);
    //           	var errorMessage = "";
    //           	//Notes.loadCommentLastUI(post,errorMessage,true);
    //           }
    //       );
          var time = post.split("_")[0];
          var idCommentAlbum = post.split("_")[1];
          ajax.post_sync("photoAlbumComment/deletePhotoAlbumComment.php", "idCommentAlbum="+idCommentAlbum+"&idLoad="+PM.idLoad+"&time="+time,
                  function(result){
                      if(result == "done")
                          PhotoAlbumComment.deletePhotoAlbumCommentUI(idCommentAlbum);
                  },
                  function(XMLHttpRequest, textStatus, errorThrown){
                      alert("ERROR. Text Status: " + textStatus + "\nError thrown: "+ errorThrown + "\nxml: " + XMLHttpRequest.responseText);
                  } 
              );
      },
  deletePhotoAlbumCommentUI: function(idCommentAlbum){
          $.when( ge("photo_" + idCommentAlbum).slideUp(250) ).done(function( ) {
                            this.remove();
                          });
          // ge("photo_" + idCommentAlbum).remove(); // чето другое
          return true;
      },

  likeCommentAlbum: function(post) {
        var like = ge("like_commentAlbum_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("photoAlbumComment/likeCommentAlbum.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idCommentAlbum=" + sps[1] + "&typeLike=album", // typeLike - параметр указывает на то, что мы лайкаем, альбом или фото
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
              // console.log("error from ajax: " + XMLHttpRequest.responseText);
              var errorMessage = "";
              //Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_albumCount_' + post));
        PhotoAlbumComment.likeCommentAlbumUI(like, !my, intval(real_count) + (my ? -1 : 1));           
  },
  likeCommentAlbumUI: function(item, my, count) {
      count = intval(count);
      item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
      item.children("span").text(count>0?count:"");
  },

  likeCommentPhoto: function(post) {
        var like = ge("like_commentPhoto_" + post);
        var my = hasClass(like, 'my_like');
        var sps = post.split("_");
        ajax.post("photoAlbumComment/likeCommentAlbum.php","idLoad=" + sps[0] + "&idAuth=" + PM.idAuth + "&my=" + (my?25:30) + "&idCommentPhoto=" + sps[1] + "&typeLike=photo", // typeLike - параметр указывает на то, что мы лайкаем, альбом или фото
            function(data){}, 
            function(XMLHttpRequest, textStatus, errorThrown){ 
              // console.log("error from ajax: " + XMLHttpRequest.responseText);
              var errorMessage = "";
              //Notes.loadCommentLastUI(post,errorMessage,true);
            }
        );
        var real_count = val(ge('like_photoCount_' + post));
        PhotoAlbumComment.likeCommentPhotoUI(like, !my, intval(real_count) + (my ? -1 : 1));           
  },
  likeCommentPhotoUI: function(item, my, count) {
      count = intval(count);
      item.removeClass().addClass(my ? "likes my_like" : (count==0 ? "nolikes" : "likes"));
      item.children("span").text(count>0?count:"");
  },

}