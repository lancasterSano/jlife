{* mainfield страницы Альбомы. *}
{* Требования : 
$ProfileID
$photoAlboms

*}
<div class="mainfield"> 
    <div class="content">
      {assign var="stUnic" value=$ProfileLoad->ID}
      <div class="postssum">
          <img src="../img/albums_pict.png" />
          <span id="count_album_{$stUnic}">
              {$ProfileLoad->countalbum}
          </span><span> альбомов</span>
      </div>

      <div id="albums_{$stUnic}">
        {foreach $albums as $album}
        {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$album.id}
        <div class="post" id = "album_{$stUnic}">
            <div class="albumpic">
              <!-- <a href="{$smarty.server.SETTING_PROFILE_ADRESS}{'?us='}{$ProfileID}{'&id='}{$album.id}"> -->  {*Передать профиль*}
              <a href="../pages/album.php?id={$idLoad}&album={$album.id}">
                  <img src="{$album.photoPathAlbum}"/>
              </a>
            </div>
                
            <div class="albumtitle">
              <a href="{$smarty.server.SETTING_PROFILE_ADRESS}{'?us='}{$ProfileID}{'&id='}{$album.id}">{$album.name}</a>
                <div class="buttons">
                    <a href="#">
                      <img src="../img/phot.png" />
                      <span>{$album.countPhoto}</span>
                    </a>    
                </div>

                <div class="albumdesc">
                    <span>{$album.description}</span>
                </div>

                <div class="albumBottomInfo">
                    <div class="albumLike">
                        {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$album.id}
                        <div class="likediv">
                          <div 
                              id="like_{$stUnic}" 
                              class="{if $album.countLike}likes{if $album.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                              onclick="Albums.likeAlbum('{$stUnic}')"> 
                              <span id="like_count_{$stUnic}">{if $album.countLike > 0}{$album.countLike}{/if}</span>
                          </div> 
                        </div> 
                        <div class="manage">        
                                {if $idAuth == $idLoad}
                                <div class="deleteAlbum">
                                    <span id="delete_album_{$stUnic}" onclick="Albums.deleteAlbum('{$stUnic}')">Удалить</span>
                                </div>
                                {/if}

                                <div id = "commanage_{$stUnic}"class="albumCommentInfo">
                                    {if $album.countComment > 2}
                                    <div id="expand_{$stUnic}" class="abbreviated" onclick="Albums.expandCommentUI('{$stUnic}')">
                                        {else if $album.countComment >= 0 && $album.countComment <= 2}
                                        <div id="expand_{$stUnic}" class="" style="display:none;" onclick="Albums.expandCommentUI('{$stUnic}')">
                                        {/if}
                                        <span id="expand_label_{$stUnic}" class="changetext">Развернуть комментарии</span>
                                        <span id="expand_count_{$stUnic}">{$album.countComment}</span>
                                    </div>
                                </div>   
                            </div>
                         </div>   
                </div>

                <div id="comments_{$stUnic}">
                  {foreach item=comment from=$album.comments}
                  {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.id}
                  <div id="comment_{$stUnic}" class="AlbumComment" onclick="Albums.checkAnswerCommnetUI('{$stUnic}')">
                    <div class="avalike">
                        <div class="CommentImage">
                          <a href="index.php?id={$comment.profile_id}" onclick="event.stopPropagation();"><img src="{$PROJECT_PATH}{$comment.authorCommentPathAvatar}" /></a>
                        </div>
                    </div>

                    <div class="right">
                        <div class="CommentTitle">
                            <a id = "comment_author" href="./index.php?id={$comment.profile_id}" onclick="event.stopPropagation();">{$comment.authorCommentFIO}</a>
                            <span>{$comment.datetime}</span>
                        </div>
                        
                        <div class="CommentText">
                            {*проверка на ответ*}
                            {if $comment.answerCommentId != null}
                              <p><a id="hashtag" href="index.php?id={$comment.answerAuthorID}" onclick="event.stopPropagation();">{$comment.answerAuthorCommentFIO}</a>, {$comment.text}</p>
                            {else}
                              <p>{$comment.text}</p>
                            {/if}
                        </div>
                    </div>     
                    
                    <div class="CommentBottom">
                        <div class="commentLike">
                              {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.id}
                              <div 
                                  id="like_comment_{$stUnic}" 
                                  class="{if $comment.countLike}likes{if $comment.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                                  onclick="Albums.likeComment('{$stUnic}');  event.stopPropagation();"> 
                                  <span id="like_comment_count_{$stUnic}">{if $comment.countLike > 0}{$comment.countLike}{/if}</span>
                              </div> 
                        </div>                        
                              
                        <div class="commentManage">
                                  {*Проверка на доступность ссылки удаления*}
                                  {if $idAuth == $idLoad || $comment.authorCommentId == $idAuth}
                                  {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.id}
                                  <span id="delete_comment_{$stUnic}" onclick="Albums.deleteAlbumComment('{$stUnic}'); event.stopPropagation();">Удалить</span>
                                  {/if}              
                        </div>
                    </div>
                  </div><!--end AlbumComment-->
                  {/foreach}
                </div> 
            </div> <!--end albumtitle-->

            {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$album.id}
            <div id="new_comment_{$stUnic}" class="c_new_commUn">
                <div class="senderImgCom">
                  <img src="{$PROJECT_PATH}{$ProfileAuth->ProfilePathAvatar()}" />
                </div>

                <div class="enterNewCom">
                    <textarea id="nc_enter_{$stUnic}" autocomplete="off" name="textCommentNote">{$smarty.const.NOTES_CREATE_NOTE_COMMENT}</textarea>
                </div>
              
                <div id="nc_post_{$stUnic}" class="postNewCom" onclick="Albums.addComment('{$stUnic}')">
                </div>
            </div>
        </div> <!--end post-->
        {/foreach}
      </div> <!-- end albums= -->
      {assign var="stUnic" value=$ProfileLoad->ID}
      <!-- нет заметок -->

            <div class="warningMessage" id="loadAlbumsEmpty_{$stUnic}"
            {if $ProfileLoad->countalbum != 0}style="display: none;"{/if}>
              <div class="centeredm">
                <span>У данного пользователя нет альбомов.</span>
              </div>
            </div>
            
      {if $ProfileLoad->countalbum >2} 
        {assign var="stUnic" value=$ProfileLoad->ID}
        <div class="warningMessage" id="loadAlbums_{$stUnic}"
             onclick="Albums.loadAlbumUI('{$stUnic}')"
             onmouseover="$(this).css('background','#ececec');"
             onmouseout="$(this).css('background','#fff');" >
            <div class="centeredm"><span>Выгрузить еще...</span></div>
        </div>
      {/if}
    </div> <!--end content-->
</div> <!--end mainfield-->