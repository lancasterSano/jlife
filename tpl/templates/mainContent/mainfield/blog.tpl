{* mainfield страницы Блог. *}
{* Требования :

*}
<div class="mainfield"> 
    <div class="content">
        {assign var="stUnic" value=$ProfileLoad->ID}
        <div class="postssum">
            <img src="../img/blog_pict.png" />
            <span id="count_blog_{$stUnic}">
                {$ProfileLoad->countblog}
            </span><span> статьи</span>
        </div>
        <div id="blogs_{$stUnic}">
        {foreach $contents as $content}
        {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$content.id}
		<div class="post" id="blog_{$stUnic}">
            <div class="title">
            	<a href="{$smarty.server.SETTING_PROFILE_ADRESS}{'?id='}{$post.idPost}{'&us='}{$post.authorID}">{$content.name}</a>
                <span>{$content.datetime|date_format:"%d %B %Y"}</span>
            </div>
            <div class="author">
            	<span>Автор:</span><a href="{*Вставить сылку на страницу  профиля*}">{$author}</a>
                {if $idAuth == $idLoad}
                    <a href="#" class="deletePost" id="{$content.id}" onclick="Blog.deletePost('{$stUnic}');">Удалить</a>
                {/if}
            </div>
            
			<div class="text">
                <span>{$content.text}
           		</span>
            </div>
            
            <div class="hashtag">
				{foreach item=metka from=$content.metkas}
            	<a href="#" class="metka" id="{$metka.idblogmetka}" style="background-color:{$metka.color};" onclick="Blog.loadPostsByHref(event);">&lt{$metka.name}&gt</a>
                {/foreach}
            </div>
            <div class="bottominfo">
            	<div class="like">
                    {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$content.id}
                    <div 
                        id="like_{$stUnic}" 
                        class="{if $content.countlike}likes{if $content.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                        onclick="Blog.likeBlog('{$stUnic}')"> 
                            <span id="like_count_{$stUnic}">{if $content.countlike > 0}{$content.countlike}{/if}</span>
                    </div>  
                </div>

                <div class="out"> 
                    {if $content.source != null}
                        <span>Источник:</span><a href="{$content.source}">{$content.nameLink}</a>
                    {/if}
                </div>

                <div class="more">
                   <a href="#">Подробнее...</a>
                </div>

                <div class="comm">
                	<a href="#"><img src="../img/switch.png"/></a>
                	<span>Коментарии</span><a href="#">{$content.countcomment}</a>
                </div>
				
                
				
            </div>
        	
        </div>
        {/foreach}
        </div>
    </div>
    {assign var="stUnic" value=$ProfileLoad->ID}
      <!-- нет заметок -->

            <div class="warningMessage" id="loadBlogsEmpty_{$stUnic}"
            {if $ProfileLoad->countblog != 0}style="display: none;"{/if}>
              <div class="centeredm">
                <span>У данного пользователя нет заметок.</span>
              </div>
            </div>
    </div><!--end content-->
    


    {if $ProfileLoad->countblog >2}
    {assign var="stUnic" value=$ProfileLoad->ID}
        <div class="warningMessage" id="loadBlogs_{$stUnic}"
                onclick="Blog.loadBlogUI('{$stUnic}')"
                onmouseover="$(this).css('background','#ececec');"
                onmouseout="$(this).css('background','#fff');" >
                <div class="centeredm"><span>Выгрузить еще...</span></div>
        </div>
    {/if}  
</div> <!--end mainfield-->