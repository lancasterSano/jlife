{if !$ProfileAuth->private}{$op="create"}{else}{$op="ch"}{/if}
<div class="secureItem" id="ch_pswd">
	<div class="managettl">
	  <span>Смена пароля для текущего аккаунта</span>
	</div>
	<div class="managetct">
		<div class="load-layer">
			<div class="load"></div>
		</div>
		<div class="changeFields">
			<div class="preConfirmation">
				{include file="../../general/notice.tpl" IDNOTICE="INFO_CHANGE_PSWD"}			
			</div>		
			<div class="changeItem">
				<span>Текущий пароль:</span>
				<input type="password" name="f_pswd_old" id="f_pswdo" value=""/>
			</div>  
			<div class="changeItem">
				<span>Новый пароль:</span>
				<input type="password" name="f_pswd_new" id="f_pswdn" value=""/>
			</div>
			<div class="changeItem">
				<span>Подтверждение пароля:</span>
				<input type="password" name="f_pswd_new_second" id="f_pswdns" value=""/>
			</div>
		</div>
		<div class="confirmationPanel">
			<div class="confirmation">
			</div>
			<div class="confirmbtn" id="btn_form">
				<span>Изменить</span>
			</div>
		</div>
	</div>
</div>