<div class='secureItemN' id='change_mail'>
	<div class='managettl'>
	  <span>Изменение логина/почтового ящика</span>
	</div>
	<div class="managetct">
		<div class='load-layer'>
			<div class="load"></div>
		</div>
		<div class='changeFields'>
			<div class="preConfirmation">		
		  	{include file='../../general/notice.tpl' IDNOTICE='INFO_SEND_MAIL_CHMAIL'}
		  	</div>
		  <div class='changeItem'>
		    <span>Почтовый адресс:</span>
		    <input type='text' id='ch_f_email'/>
		  </div>  
		  <div class='changeItem'>
		    <span>Пароль:</span>
		    <input type='password' id='ch_f_pswd'/>
		  </div>
		</div>
		<div class='confirmationPanel'>
			<div class='confirmation'>
				<!-- {include file='../../general/notice.tpl' IDNOTICE='777'} -->
				{*include file='../../general/notice.tpl' IDNOTICE='VALID_MAIL_NOT_CURRECT'*}
				<div class='confirmbtn' id='btn_ch_email'>
				    <span>Изменить</span>
				</div>
			</div>
		</div>
	</div>
</div>