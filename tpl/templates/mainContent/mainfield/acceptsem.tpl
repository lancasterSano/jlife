{* mainfield страницы Блог. *}
{* Требования :
*}
<div class="mainfield">
	<div class="content">
        {if isset($debug_context[1]) } <div class="debug"> {debug_out context=$debug_context[1]} </div> {/if}
        {include file='./ssecurity/verify_email.tpl'}
    </div>
	</div><!--end content-->     
</div> <!--end mainfield-->