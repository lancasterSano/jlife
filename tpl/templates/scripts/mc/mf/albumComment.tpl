<script id="album_comment" type="text/js">
    <div id="comment_<%=stUnic%>" class="AlbumComment" onclick="Albums.checkAnswerCommnetUI(\"<%=stUnic%>\");">
    	<div class="avalike">
            <div class="CommentImage">
            	<a href="index.php?id=<%=comment.profile_id%>" onclick="event.stopPropagation();"/>
            		<img src="<%=PM.navigate%><%=comment.authorCommentPathAvatar%>">
            	</a>
            </div>
        </div>
                    
        <div class="right">
            <div class="CommentTitle">
                <a id = "comment_author" href="./index.php?id=<%=comment.profile_id%>" onclick="event.stopPropagation();"><%=comment.authorCommentFIO%></a>
                <span><%=comment.datetime%></span>
            </div>
            
            <div class="CommentText">
                <p>
                    <%
                        if(comment.answerCommentId != null && comment.adress != null)
                        { 
                    %>
                        <a id="hashtag" href="index.php?id=<%=comment.answerAuthorCID%>" onclick="event.stopPropagation();"><%=comment.adress%></a>
                        <%=SETTING_ADRESS_COMMENT%>
                    <%
                        }
                    %>         
                    <%=comment.text%>
                </p>
    		</div>
        </div>
        <div class="CommentBottom">
        	<div class="commentLike">
        		<div id="like_comment_<%=stUnic%>" class="likes my_like" onclick="Albums.likeComment(\"<%=stUnic%>\");">
                       <span id="like_comment_count_<%=stUnic%>"> </span>
        		</div>
        	</div>
        					
            <div class="commentManage">
                <a id="delete_comment_<%=stUnic%>" onclick="Albums.deleteAlbumComment(\"<%=stUnic%>\"); event.stopPropagation();">Удалить
                </a>
            </div>
        </div>
    </div>
</script>