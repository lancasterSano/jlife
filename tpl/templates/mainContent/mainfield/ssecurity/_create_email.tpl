{if !$ProfileAuth->private}{$op='create'}{else}{$op='ch'}{/if}
<div class="secureItemN" id="{$op}_email">
	<div class="managettl">
	  <span>Создание почтового ящика</span>
	</div>
	<div class="managetct">
		<div class='load-layer'>
			<div class="load"></div>
		</div>
		<div class="changeFields">
			<div class="preConfirmation">		
	  		{include file="../../general/notice.tpl" IDNOTICE="INFO_SEND_MAIL_CREATEMAIL"}
	  		</div>
		  <div class="changeItem">
		    <span>Почтовый адресс:</span>
		    <input type="text" name="email" id="f_email" placeholder="" value=""/>
		  </div>  
		  <div class="changeItem">
		    <span>Пароль:</span>
		    <input type="password" name="pswd" id="f_pswd" placeholder="" value=""/>
		  </div>
		</div>
		<div class='confirmationPanel'>
			<div class="confirmation">
				<!-- {include file="../../general/notice.tpl" IDNOTICE="777"} -->
				{*include file="../../general/notice.tpl" IDNOTICE="VALID_MAIL_NOT_CURRECT"*}
			</div>
			<div class="confirmbtn" id="btn_form">
			    <span>Изменить</span>
			</div>
		</div>	
	</div>
</div>