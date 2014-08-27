.{* mainfield страницы Альбом. *}
{* Требования :

*}

<div class="mainfield"> 
    {assign var="stUnic" value=$ProfileLoad->ID}
    	<div class="content">
			<div class="album_info">
                <div class="albumDesc">
                	<a href="#"><span>{$photo.photoNum}/{$photo.photoCol}</span><span>{$photo.photoDescAlbum}</span></a>
                </div>    
                <div class="manageBut">
                	<a href="#"><img src="../img/add.png" /></a>
                    <a href="#"><img src="../img/edit.png" /></a>
                    <a href="#"><img src="../img/delete.png" /></a>
                </div>
            </div>
            <div class="photoList">
            	<div class="photoMain">
                	<a href="#"><div class="rwb">
                                </div>
                    </a>
                    <div class="photoBorder">
                    	<a href="#">
                        		<img src="{$photo.photoPath}"/>
                        </a>
                    </div>
                    <a href="#"><div class="rwn">
                                </div>
                    </a>
                </div>
                <div class="photoInfo">
                	<div class="likephoto">
                    	<a href="#"><img src="../img/like2.png " /><span>{$photo.countLikePhoto}</span></a>
                    </div>
                    <div class="senderInfo">
                        <a href="#">{$photo.photoOwner}</a> <br />
                        <span>{$photo.photoDate}</span>
                    </div>
                </div>
                <p> {$photo.photoDesc}</p>				
					<div class="comManage">
                        	<a href="#">
                        	<img src="../img/full.png" />
                            </a>
                            <span>Комментарии</span>
                            <a href="#">{$photo.countComent}</a>
                     </div>
                     
                     
                     
                     <div class="NoteComment">
                	
                        <div class="avalike">
                        	
                            <div class="CommentImage">
                            	<img src="{$comment.authorCommentPathAvatar}"/>
                            </div>  
                                
                        </div>
                    
                          <div class="CommentTitle">
                              <a href="{$comment.idComment}">{$comment.authorCommentFIO}</a>
                              <span>{$comment.dateComment} </span>
                          </div>
                          
                          
                          {*проверка на ответ*}
                            {if $comment.answerCommentId != null}
                                <p><a id="hashtag" href="#">{$comment.answerAuthorCommentFIO}</a>, {$comment.textComment}</p>
                            {else}
                                <p>{$comment.textComment}</p>
                            {/if}	
                        
                        		

                          <div class="CommentBottom">
  							  <div class="commentLike">
                                  <a href="#">
                                      <img src="../img/like2.png" />
                                      <span>{$comment.countLikeComment}</span>
                                  </a>
                        	  </div>                      	
                              <div class="commentManage">
                                  {*Проверка на доступность ссылки удаления*}
                                  {if ($comment.authorCommentID == $ProfileAuth->ID ) || ($ProfileLoad->ID == $ProfileAuth->ID)}
                                    {*подстановка id главной части коммента записи*}
                                    {if $comment.extension==null}
                                        {assign var="deleteIdComment" value=$comment.idComment nocache}
                                    {else}
                                        {assign var="deleteIdComment" value=$comment.extension nocache}
                                    {/if}
                                    {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.idComment}
                                    <a id="delete_comment_{$stUnic}" {*onclick="Notes.deleteComment('{$stUnic}')"*}>Удалить</a>
                                {/if}
                                  <a href="#">Отметить как спам</a>
                                  
                                                    
                              </div>
                          </div>
                    </div>  <!--end NoteComment--> 
                     
            </div><!--end photo-->
            
            
            
            
            {*
            <div id="new_comment_{$stUnic}" class="c_new_commUn">
                    <div class="senderImgCom">
    					<img src="{$PROJECT_PATH}{$ProfileLoad->ProfilePathAvatar()}" />
    				</div>
    				<div class="enterNewCom">
    					<textarea id="nc_enter_{$stUnic}" name="textCommentPhoto">{$smarty.const.NOTES_CREATE_NOTE_COMMENT}</textarea>
    				</div>
    				<div id="nc_post_{$stUnic}" class="postNewCom" onclick="Notes.addComment('{$stUnic}')">
    				</div>
            </div>*}
    	</div><!--end content--> 
          
	</div> <!--end mainfield-->