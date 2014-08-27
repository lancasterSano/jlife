{* mainfield страницы Записи. *}
{* Требования :
*}
<div class="mainfield"> 
	<div class="content">
        {if isset($debug_context[1]) } <div class="debug"> {debug_out context=$debug_context[1]} </div> {/if} 
        {assign var="stUnic" value=$ProfileLoad->ID}
    {* Шапка всех заметок *} 
        <div class="notesnum"	>
			<img src="{$PROJECT_PATH}/img/notes.png" />
			<span id="count_note_{$stUnic}">
                {if $m}
                    {if $ProfileLoad->countwall_my != 0}{$ProfileLoad->countwall_my}{/if}
                {else}
                    {if $ProfileLoad->countwall != 0}{$ProfileLoad->countwall}{/if}
                {/if}
            </span>
            <span id="count_note_label_{$stUnic}">
                {if $m}
                    {if $ProfileLoad->countwall_my == 0}{$smarty.const.NOTES_COUNT_EMPTY}{else}{$smarty.const.NOTES_COUNT}{/if}
                {else}
                    {if $ProfileLoad->countwall == 0}{$smarty.const.NOTES_COUNT_EMPTY}{else}{$smarty.const.NOTES_COUNT}{/if}
                {/if}
            </span>
		</div>
    {* Создание новой заметки *}
    {if ($m==true && $ProfileAuth->iAm($ProfileLoad->ID)) || $m==false}
		<div id="new_entity_{$stUnic}" class="createnewUn">
    		<div class="senderImg">
    			<img src="{$PROJECT_PATH}{$ProfileAuth->ProfilePathAvatar()}" />
    		</div>
    		<div class="enterNew">
    			<textarea id="n_enter_{$stUnic}" autocomplete='off' name="textNote">{$smarty.const.NOTES_CREATE_NOTE}</textarea>
    		</div>
    		<div id="n_post_{$stUnic}" class="postNew" onclick="Notes.addNote('{$stUnic}')">
    		</div>
		</div>
    {/if}
    {* Список всех заметок *}
        <div id="notes_{$stUnic}">
        {foreach key=outer item=note from=$notes name=notes}
        {* Заметка div[postedOld] *}
            {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$note.idNote}
    		<div id="note_{$stUnic}" class="postedOld">
    			<a href="index.php?id={$note.authorNoteID}"><img class="senderImgPost" src="{$PROJECT_PATH}{$note.authorNotePathAvatar}" /></a>
    			<a href="index.php?id={$note.authorNoteID}"><span style="margin:0 0 0 20px">{$note.authorNoteFIO}</span></a>
    			<span id="right">{$note.dateNote}</span> {*|date_format:"%d %B %Y"*}
    			<p style="margin:20px 0 0 0;">{$note.textNote}</p>
    			<div id="commentmanage_{$stUnic}" class="CommentManage">				
    				<div class="likediv">
                        {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$note.idNote}
                        <div id="like_{$stUnic}" 
                            class="{if $note.countLikeNote}likes{if $note.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                            onclick="Notes.likeNote('{$stUnic}')">  
                            <span id="like_count_{$stUnic}">{if $note.countLikeNote > 0}{$note.countLikeNote}{/if}</span>
                        </div>

    				</div>				
    				<div id="manage_{$stUnic}" class="Manage">
    					<div id="commanage_{$stUnic}" class="comManage">
       						{if $note.countComments>2 }
                                <div id="expand_{$stUnic}" class="abbreviated" onclick="Notes.expandCommentUI('{$stUnic}')">   
                            {else if $note.countComments>=0 && $note.countComments<=2}
                                <div id="expand_{$stUnic}" class="" style="display:none;" onclick="Notes.expandCommentUI('{$stUnic}')">
        					{/if}
                                <span id="expand_label_{$stUnic}" class="changetext">Развернуть комментарии</span>
                                <span id="expand_count_{$stUnic}">{$note.countComments}</span>
                            </div>  
    					</div>
    					<div id="postmanage_{$stUnic}" class="postManage">
                            {*Проверка на доступность ссылки удаления*}
                            {if ($m==false && ($ProfileAuth->iAm($note.authorNoteID) || $ProfileAuth->iAm($ProfileLoad->ID))) ||
                                ($m==true && $ProfileAuth->iAm($ProfileLoad->ID))}
                                {*подстановка id галвной части записи*}
                                {*assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$note.idNote*}
                                <div class="deleteNote">
                                    <span id="delete_note_{$stUnic}" onclick="Notes.deleteNote('{$stUnic}')" class='actionItem' >Удалить</span>
                                </div>
                            {/if}
                            {*<a href="#">Отметить как спам</a>-->*}
    					</div>
    				</div>							  
    			</div>
                {* Коммент div[NoteComment] *}
                <div id="comments_{$stUnic}">
                {foreach key=key item=comment from=$note.comments name=comments}
        			{assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.idComment} 
                    <div id="comment_{$stUnic}" class="NoteComment" onclick="Notes.checkAnswerCommnetUI('{$stUnic}')">
        				<div class="avalike">
        					<div class="CommentImage">
        						<a href="index.php?id={$comment.authorCommentID}" onclick="event.stopPropagation();"><img src="{$PROJECT_PATH}{$comment.authorCommentPathAvatar}" /></a>
        					</div>  
        				</div>
        				<div class="CommentTitle">
                            <a id="comment_author" href="index.php?id={$comment.authorCommentID}" onclick="event.stopPropagation();">{$comment.authorCommentFIO}</a>
                            <span id="comment_date">{$comment.dateComment}</span> {*|date_format:"%d %B %Y"*}
        				</div>
                        <p>
                        {if $comment.answerCommentId != null && $comment.adress != null}
                            <a id="hashtag" href="index.php?id={$comment.answerAuthorID}" onclick="event.stopPropagation();">{$comment.adress}</a>{$smarty.const.SETTING_ADRESS_COMMENT}
                        {/if}
                            {$comment.textComment}
                        </p>
                        <div class="CommentBottom">
        					<div class="commentLike">
                                {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.idComment}
                                <div id="like_comment_{$stUnic}" 
                                    class="{if $comment.countLikeComment}likes{if $comment.isProfileAuthSetLike} my_like{/if}{else}nolikes{/if}"
                                    onclick="Notes.likeComment('{$stUnic}'); event.stopPropagation();"> 
                                    <span id="like_comment_count_{$stUnic}">{if $comment.countLikeComment > 0}{$comment.countLikeComment}{/if}</span>
                                </div>
        					</div>                      	
        					<div class="commentManage">
                                {*Проверка на доступность ссылки удаления*}
                                {if ($m==false && ($ProfileAuth->iAm($comment.authorCommentID) || $ProfileAuth->iAm($ProfileLoad->ID))) ||
                                    ($m==true && $ProfileAuth->iAm($ProfileLoad->ID))}
                                    {*подстановка id главной части коммента записи*}
                                    {if $comment.extension==null}
                                        {assign var="deleteIdComment" value=$comment.idComment nocache}
                                    {else}
                                        {assign var="deleteIdComment" value=$comment.extension nocache}
                                    {/if}
                                    {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$comment.idComment}
                                    <div class="deleteNote">
                                        <span id="delete_comment_{$stUnic}" onclick="Notes.deleteComment('{$stUnic}'); event.stopPropagation();" class='actionItem'>Удалить</span>
                                    </div>
                                {/if}
                                <!--<a href="#">Отметить как спам</a>-->				
        					</div>
            			</div>
        			</div>  <!--end NoteComment--> 
                {/foreach}
                </div> <!-- comments -->
                {assign var="stUnic" value=$ProfileLoad->ID|cat:'_'|cat:$note.idNote}
    			<div id="new_comment_{$stUnic}" class="c_new_commUn">
                    <div class="senderImgCom">
    					<img src="{$PROJECT_PATH}{$ProfileAuth->ProfilePathAvatar()}" />
    				</div>
    				<div class="enterNewCom">
                        <!--<span id="answer_comment_id" style="display:none;"></span>
                        <span id="answer_author_id" style="display:none;"></span>-->
    					<textarea id="nc_enter_{$stUnic}" autocomplete="off" name="textCommentNote">{$smarty.const.NOTES_CREATE_NOTE_COMMENT}</textarea>
    				</div>
    				<div id="nc_post_{$stUnic}" class="postNewCom" onclick="Notes.addComment('{$stUnic}')">
    				</div>
    			</div>
    		</div><!--end postOld-->        		
        {/foreach}
        </div>
        {assign var="stUnic" value=$ProfileLoad->ID}
        <!-- нет заметок -->
            <div class="warningMessage" id="loadNotesEmpty_{$stUnic}"
            {if $m && $ProfileLoad->countwall_my != 0}style="display: none;"
            {elseif !$m && $ProfileLoad->countwall != 0}style="display: none;"{/if}>
    			<div class="centeredm"><span>У данного пользователя нет заметок.</span></div>
    		</div>
        <!-- Блок ЕЩЕ... -->
            <div class="warningMessage" id="loadNotes_{$stUnic}" 
                onclick="Notes.loadNotesUI('{$stUnic}')" 
                {if !$m && $ProfileLoad->countwall <= $smarty.foreach.notes.total}style="display: none;"
                {elseif $m && $ProfileLoad->countwall_my <= $smarty.foreach.notes.total}style="display: none;"{/if}
                onmouseover="$(this).css('background','#ececec');" 
                onmouseout="$(this).css('background','#fff');" >
    			<div class="centeredm"><span>Выгрузить еще...</span></div>
    		</div>
        {* КОНЕЦ: Список всех заметок *}
</div> <!--end mainfield-->
    </div><!--end content-->   