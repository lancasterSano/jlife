{* mainfield страницы Блог. *}
{* Требования :

*}
<div class="mainfield">
{assign var="stUnic" value=$ProfileLoad->ID} 
    <div class="content">
        {assign var="stUnic" value=$ProfileLoad->ID}
        <div class="postssum">
            <img src="../img/blogComment.png" />
            <span id="count_blogcomment_{$stUnic}">
                {$ProfileLoad->countblogcomment}
            </span><span> статьи</span>
        </div>
        <div id="blogComments_{$stUnic}">
        {foreach $comments as $comment}
        {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.id}
        <div class="BlogComment" id="blogcomment_{$stUnic}">
            <div class="title">Коментарий к статье 
                <a href="#"{*Сылка на пост*}>{$comment.postName}</a>
            </div>
            <div class="avalike">
                <div class="CommentImage">
                    <a href=""{*путь к странице коментирующего*}><img src="{$PROJECT_PATH}{$comment.authorCommentPathAvatar}"/></a>
                </div>  
            </div>
            <div class="CommentTitle">
                <a href="#"{*путь к странице коментирующего*}>{$comment.authorCommentFIO}</a>
                <span>{$comment.datetime|date_format:"%d %B %Y"}</span>
            </div>
            
            {*проверка на ответ*}
                {if $comment.answerCommentId != null}
                <p><a id="hashtag" href="#">{$comment.answerAuthorCommentFIO}</a>, {$comment.text}</p>
                {else}
                <p>{$comment.text}</p>
                {/if}               
            
            <div class="CommentBottom">
                <div class="commentLike">
                    <!--<a href="#">-->
                            {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.id}
                        <div id="like_{$stUnic}" 
                            class="{if $comment.countLikeComment}likes{if $comment.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                            onclick="BlogComment.likeComment('{$stUnic}')">  
                            <span id="like_count_{$stUnic}">{if $comment.countLikeComment > 0}{$comment.countLikeComment}{/if}</span>
                        </div>    
                    <!--</a>-->
                </div>                          
                <div class="commentManage">
                    {*Проверка на доступность ссылки удаления*}
                      {if ($comment.authorCommentID == $ProfileAuth->ID ) || ($ProfileLoad->ID == $ProfileAuth->ID)}
                        {*подстановка id главной части коммента записи*}
                        {if $comment.extension==null}
                            {assign var="deleteIdComment" value=$comment.id nocache}
                        {else}
                            {assign var="deleteIdComment" value=$comment.extension nocache}
                        {/if}
                        {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.id}
                        <a href='#'id="{$comment.id}" onclick="BlogComment.deleteComment('{$stUnic}')">Удалить</a>
                      {/if}
                </div>
            </div>
        </div>  <!--end NoteComment--> 
        {/foreach}
        </div>
    </div><!--end content-->   
        {if $ProfileLoad->countblogcomment >2}
    {assign var="stUnic" value=$ProfileLoad->ID}
        <div class="warningMessage" id="loadBlogComments_{$stUnic}"
                onclick="BlogComment.loadBlogCommentUI('{$stUnic}')"
                onmouseover="$(this).css('background','#ececec');"
                onmouseout="$(this).css('background','#fff');" >
                <div class="centeredm"><span>Выгрузить еще...</span></div>
        </div>
        {/if}
</div> <!--end mainfield-->