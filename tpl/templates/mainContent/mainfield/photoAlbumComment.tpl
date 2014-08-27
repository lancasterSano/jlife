{* mainfield страницы Блог. *}
{* Требования :

*}
<div class="mainfield"> 
    
    	<div class="content">
        <!-- {if $Album.idPhotoAlbom != null}
			<div class="album_info"	>
            	<div class="likeimg">
                {*запись*}
                    {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$Album.idPhotoAlbom}
                    <div id="like_{$stUnic}" 
                        class="{if $Album.countLikePhotoAlbom}likes{if $Album.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                        {*onclick="Notes.likeNote('{$stUnic}')"*}>	
                        <span id="like_count_{$stUnic}">{if $Album.countLikePhotoAlbom > 0}{$Album.countLikePhotoAlbom}{/if}</span>
                    </div>
                	<img src="../img/photoComment1.png" />
                    <span>{$Album.countPhoto}</span>
                </div>
                <div class="albumDesc">
                	<a href="#"{*тут путь*}><span>{$Album.namePhotoAlbom}</span></a>
                </div>    
            </div>
        {/if} -->




        <!-- ****************************************************** -->





            {foreach key=time item=comment from=$allComments name=A}
            {if $comment.idCommentAlbum != null}
            <div class="albumComment" id="album_{$time}_{$comment.idCommentAlbum}">
                <div class="title">Коментарии к альбому 
                    <a href="album.php?id={$ProfileLoad->ID}&album={$comment.albumNNN_id}">{$comment.nameAlbum}</a>
                </div>

                <div class="avalike">
                    <div class="CommentImage">
                        <img src="{$comment.authorCommentPathAvatar}" />
                    </div>  
                </div>
                
                <div class="CommentTitle">
                    <a href="index.php?id={$comment.authorCommentId}">{$comment.authorCommentFIO}</a>
                    <span>{$comment.datetimeToShow}</span>
                </div>
                
                <div class="comentedImg">
                    <a href="album.php?id={$ProfileLoad->ID}&album={$comment.albumNNN_id}"><img src="{$comment.photoPathAlbum}" /></a>
                </div>
                
                {*Проверка на ответ*}
                 {if $comment.answerCommentId != null}
                    <p><a id="hashtag" href="#">{$comment.answerAuthorCommentFIO}</a>, {$comment.text}</p>
                    {else}
                    <p>{$comment.text}</p>
                {/if}			

                <div class="CommentBottom">
                    <div class="commentLike">
                            <a>
                            {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.idCommentAlbum}
                        <div id="like_commentAlbum_{$stUnic}" 
                            class="{if $comment.countLike}likes{if $comment.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                            onclick="PhotoAlbumComment.likeCommentAlbum('{$stUnic}');  event.stopPropagation();">	
                            <span id="like_albumCount_{$stUnic}">{if $comment.countLike > 0}{$comment.countLike}{/if}</span>
                        </div>    
                            </a>
                    </div>                      	
                    <div class="commentManage">
                        {*Проверка на доступность ссылки удаления*}
                          {if ($comment.authorCommentID == $ProfileAuth->ID ) || ($ProfileLoad->ID == $ProfileAuth->ID)}
                            {*подстановка id главной части коммента записи*}
                            {if $comment.extension==null}
                                {assign var="deleteIdComment" value=$comment.idCommentAlbum nocache}
                            {else}
                                {assign var="deleteIdComment" value=$comment.extension nocache}
                            {/if}
                            {assign var="stUnic" value=$comment.idCommentAlbum}
                            <a id="delete_comment_{$comment.idCommentAlbum}" onclick="alert('Удаление в данный момент недоступно');"{*onclick="PhotoAlbumComment.deleteAlbumComment({$comment.idCommentAlbum})"*}>Удалить</a>
                        {/if}
                    </div>
                </div>
            </div>  <!--end AlbumComment--> 
            {else}
            <div class="photoComment" id="photo_{$time}_{$comment.idCommentPhoto}">
                    
                    <div class="avalike">
                        
                        <div class="CommentImage">
                            <img src="{$comment.authorCommentPathAvatar}" />
                        </div>  
                            
                    </div>
                    
                          <div class="CommentTitle">
                                <a href="index.php?id={$comment.authorCommentId}">{$comment.authorCommentFIO}</a>
                                <span>{$comment.datetimeToShow}</span>
                          </div>
                          
                          <div class="comentedImg">
                                <a href="#"><img src="../img/photo.jpg" /></a>
                          </div>
                          {*Проверка на ответ*}
                             {if $comment.answerCommentId != null}
                                <p><a id="hashtag" href="#">{$comment.answerAuthorCommentFIO}</a>, {$comment.text}</p>
                                {else}
                                <p>{$comment.text}</p>
                            {/if}   
                          <div class="CommentBottom">
                              <div class="commentLike">
                                  <a>
                                    {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.idCommentPhoto}
                                    <div id="like_commentPhoto_{$stUnic}" 
                                        class="{if $comment.countLike}likes{if $comment.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                                        onclick="PhotoAlbumComment.likeCommentPhoto('{$stUnic}');  event.stopPropagation();">
                                        <span id="like_photoCount_{$stUnic}">{if $comment.countLike > 0}{$comment.countLike}{/if}</span>
                                    </div>
                                  </a>
                              </div>                        
                              <div class="commentManage">
                                  {*Проверка на доступность ссылки удаления*}
                                    {if ($comment.authorCommentID == $ProfileAuth->ID ) || ($ProfileLoad->ID == $ProfileAuth->ID)}
                                    {*подстановка id главной части коммента записи*}
                                    {if $comment.extension==null}
                                        {assign var="deleteIdComment" value=$comment.idCommentPhoto nocache}
                                    {else}
                                        {assign var="deleteIdComment" value=$comment.extension nocache}
                                    {/if}
                                    {assign var="stUnic" value=$time|cat:'_'|cat:$comment.idCommentPhoto}
                                    <a id="delete_comment_{$stUnic}" onclick="PhotoAlbumComment.deletePhotoAlbumComment('{$stUnic}')">Удалить</a>
                                    {/if}
                              </div>
                          </div>
                    </div>  <!--end PhotoComment--> 
            {/if}
            {/foreach}

            {if $allComments == null}
                <div class="warningMessage" id="loadCommentsEmpty">
                  <div class="centeredm">
                    <span>У данного пользователя нет комментариев.</span>
                  </div>
                </div>
            {/if}
    	</div><!--end content-->
	</div> <!--end mainfield-->