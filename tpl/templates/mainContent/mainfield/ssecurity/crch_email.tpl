{if !$ProfileAuth->private}{$op="create"}{else}{$op="ch"}{/if}
<div class="secureItemN" id="{$op}_email">
	<div class="managettl">
	  <span>{if !$ProfileAuth->private}Создание{else}Смена{/if} почтового ящика для текущего аккаунта</span>
	</div>
	<div class="managetct">
		<div class="load-layer">
			<div class="load"></div>
		</div>
		<div class="changeFields">
			<div class="preConfirmation">		
				{if !$ProfileAuth->private}
					{include file="../../general/notice.tpl" IDNOTICE="INFO_SEND_MAIL_CREATEMAIL"}
					{include file="../../general/notice.tpl" IDNOTICE="INFO_CHANE_PLEASE_MAIL"}
				{else}
					{include file="../../general/notice.tpl" IDNOTICE="INFO_SEND_MAIL_CHMAIL"}
				{/if}
				{include file="../../general/notice.tpl" IDNOTICE="INFO_MAIL_CHANGED"}
			</div>
			<div class="changeItem">
				{if $ProfileAuth->private}
					<span>Текущий почтовый адресс:</span>
					<span class='showEmail'>{$ProfileAuth->hiddenEmail()}</span>
				{else}
					{*include file="../../general/notice.tpl" IDNOTICE="INFO_CHANE_PLEASE_MAIL"*}
					<span>Текущий Ваш логин :</span>
					<span class='showEmail'>{$ProfileAuth->ID}</span>
				{/if}
				<!-- <input type="text" name="email_old" id="f_email_old" placeholder="" value="{$ProfileAuth->hiddenEmail()}"/> -->
			</div>
			<div class="changeItem">
				<span>Почтовый адресс:</span>
				<input type="text" name="email" id="f_email" placeholder="" value=""/>
			</div>
			<div class="changeItem">
				<span>Текущий пароль:</span>
				<input type="password" name="pswd" id="f_pswd" placeholder="" value=""/>
			</div>
		</div>
		<div class="confirmationPanel">
			<div class="confirmation">
			</div>
			<div class="confirmbtn" id="btn_form">
			    {if !$ProfileAuth->private}
			    	<span>Создать</span>
			    {else}
			    	<span>Изменить</span>
			    {/if}
			</div>
		</div>
	</div>
</div>