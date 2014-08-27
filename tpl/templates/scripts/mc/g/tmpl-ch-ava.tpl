<script id="ch-ava" type="text/js">
	<div class="pre-main pre-alert" style="display:none;">
	</div>
	<div class="subHeader">
		 <div style="display: inline-block;">
			 <div id="sh-c-one" class="circle done"><span class="title fix cd">1</span></div>
			 <div id="sh-l-one" class="line done"><span class="context btn_upload cp">Загрузить</span></div>
	  	 </div>
		 <div style="display: inline-block;">
			<div id="sh-c-two" class="circle"><span class="title cd">2</span></div>
		 	<div id="sh-l-two" class="line"><span class="context btn-send">Выбрать</span></div>
	  	 </div>
		 <div style="display: inline-block;">
			<div id="sh-c-three" class="circle"><span class="title cd">3</span></div>
		 	<div id="sh-l-three" class="line"><span class="context">Готово</span></div>
	  	 </div>
	</div>
	<div id="slide_ONE" style="min-width:912px;">
		<div id="oooops" style="display: none; margin: 10px; padding: 10px; border: 2px solid #f60; border-radius: 4px;">
			Увы, ваш браузер не поддерживает html5 и flash,\
			поэтому смотреть тут нечего, а iframe не даёт всей красоты :]\
		</div>
		<div id="sendpanel" style="margin:0; /*background: #e31;*/">
			<div id="imgcropcont" class="full">
				<div id="pre-loaded" class="pre-context">
					<span>Однокласникам, коллегам, друзьям будет проще найти и узнать Вас, если Вы загрузите свою личную фотографию.</span>
					<span>Для изменения Вы можете нажать по пунктирной области.</span>
					<span>Вы можете загрузить изображение в формате JPG, GIF или PNG.</span>
					<div class="b-button b-button-upload-big  js-fileapi-wrapper" style="">
						<input id="name" name="cherry" class="b-button__input" type="file" accept="image/*" style="" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="slide_TWO" style="min-width:912px;display:none;">
		<div id="sendpanel" style="margin:0; /*background: #e31;*/">
			<div id="imgcropcont">
				<div class="bg"><span class="size"> минимальный размер </span></div>
				<div id="imgcropavatar"></div>
				<!--<span class="size"> максимальный размер </span>-->
			</div>
			<div id="preview-pane-avatar" style="display:none;">
			  <div class="preview-container">
			    <img src="" class="jcrop-preview" alt="Avatar" />
			  </div>
			</div>
		</div>
	</div>
	<div id="slide_THREE" style="min-width:912px;display:none;">
		<div id="sendpanel" style="margin:0;">
			<div id="imgcropcont" class="full result">
				<div id="pre-result" class="pre-context pre-alert result">
					<span class="alert">Аватарка успешно сохранена на сервере!</span>
				</div>
			</div>
		</div>
	</div>
	<div id="slide_error_structure" style="display:none;">
		<div id="sendpanel" style="margin:0;">
			<div id="imgcropcont" class="full result fail">
				<div id="pre-result" class="pre-context pre-alert result">
					<span class="alert">Ошибка! Повторите операцию позже.</span>
				</div>
			</div>
		</div>
	</div>
	<div id="slide_loader" style="display:none;">
		<div id="sendpanel" style="margin:0;">
			<div id="imgcropcont" class="full result">
				<div id="pre-loaded" class="pre-context pre-alert">
					<span class="alert">Сохранение...</span>
				</div>
			</div>
		</div>
	</div>
</script>