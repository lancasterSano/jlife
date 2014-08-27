{* mainfield страницы . *}
{* Требования :

*}
<div class="mainfield">    
	<div class="content">
		{if isset($debug_context[1]) } <div class="debug"> {debug_out context=$debug_context[1]} </div> {/if} 
		<h2> Профиля с идентификатором [<a>{$smarty.get.l}</a>] нет в системе</h2>
        <!-- <img src="{$PROJECT_PATH}/img/trash_empty.png"/><br/> -->
		<div><a id="rtn_back_h_button">Вернуться назад</a></div>

	</div><!--end content-->   
</div> <!--end mainfield-->


