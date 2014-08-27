{if !$ProfileAuth->private}{$op="create"}{else}{$op="ch"}{/if}
<div class="secureItem" id="verify_{$op}_email">
	<div class="managettl">
	  <span>Подтверждение {if !$ProfileAuth->private}создания{else}смены{/if} почтового ящика</span>
	</div>
	<div class="managetct">
		<div class="load-layer">
			<div class="load"></div>
		</div>
		<div class="changeFields">
			<div class="preConfirmation">
			</div>
			<div class="changeItem">
				<span>Текущий пароль:</span>
				<input type="password" name="pswd" id="f_pswd" placeholder="" value=""/>
			</div>
		</div>
		<div class="confirmationPanel">
			<div class="confirmation">
			</div>
			<div class="confirmbtn" id="btn_form" data="{$hexverify}">
				<span>Подтвердить</span>
			</div>
		</div>	
	</div>
</div>