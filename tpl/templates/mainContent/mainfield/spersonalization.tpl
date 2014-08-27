{* mainfield страницы Блог. *}
{* Требования :
*}

<div class="mainfield">
	<div class="content">

	<div class="secureItem" id="{$op}_email">
		<div class="managettl">
		  <span>Настройки внешнего вида </span>
		</div>
		<div class="managetct">
			<div class="load-layer">
				<div class="load"></div>
			</div>
			<div class="changeFields">
				<div class="preConfirmation">
				</div>
				<div class="changeItem">

				<span class="spn" style="margin: 0 0 0 20px">
					Загрузить новое аватар-изображения для вашей персональной страницы - <a onclick="DialogSettings.openDialogAvatar(this);">загрузить</a>
				</span>

				</div>
			</div>
<!-- 			<div class="confirmationPanel">
				<div class="confirmation">
				</div>
				<div class="confirmbtn" id="btn_form">
				    	<span>Создать</span>
				</div>
			</div> -->
		</div>
	</div>


		<!-- <br/>
		<span class="spn" style="margin: 0 0 0 20px">
		<a onclick="DialogSettings.openDialogHandband(this);">(перейти)</a>
		Загрузить новую фотографию для подложки () : 
		<a onclick="DialogSettings.openDialogHandband(this);">(перейти)</a> </span>
		
	 -->	<!-- <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/> -->
		<!-- <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/> -->
		<!-- <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	
		<div class="T-I J-J5-Ji T-I-KE L3" role="button" tabindex="0" style="-webkit-user-select: none;" gh="cm">НАПИСАТЬ</div>

		<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/> -->
		
		<style type="text/css">
			span.spn { color: #a1a1a1; }
			span.spn a:hover {
				border-radius: 5px;
				text-decoration: underline;
				cursor: pointer;
				color: #8eb4e3;
				z-index: 30;
			}
			span.spn a {
				color: #8eb4e3;
				height: 16px;
				border-radius: 5px;
				padding: 4px 15px;
				text-decoration: none;
			}
		</style>

		<script id="b-file-ejs" type="text/ejs">
			// <div id="file-<%=FileAPI.uid(file)%>" class="js-file b-file b-file_<%=file.type.split('/')[0]%>">
			// 	<div class="js-left b-file__left">
			// 		<img src="<%=icon[file.type.split('/')[0]]||icon.def%>" width="100" height="100" style="margin: 2px 0 0 3px"/>
			// 	</div>
			// 	<div class="b-file__right">
			// 		<div><a class="js-name b-file__name"><%=file.name%></a></div>
			// 		<div class="js-info b-file__info">size: <%=(file.size/FileAPI.KB).toFixed(2)%> KB</div>
			// 		<div class="js-progress b-file__bar" style="display: none">
			// 			<div class="b-progress"><div class="js-bar b-progress__bar"></div></div>
			// 		</div>
			// 	</div>
			// 	<i class="js-abort b-file__abort" title="abort">&times;</i>
			// </div>
		</script>

		<script id="b-layer-ejs" type="text/ejs">
			// <div class="b-layer">
			// 	<div class="b-layer__h1"><%=file.name%></div>
			// 	<div class="js-img b-layer__img"></div>
			// 	<div class="b-layer__info">
			// 		<%
			// 		FileAPI.each(info, function(val, key){
			// 			if( Object.prototype.toString.call(val) == '[object Object]' ){
			// 				var sub = '';
			// 				FileAPI.each(val, function (val, key){ sub += '<div>'+key+': '+val+'</div>'; });
			// 				if( sub ){
			// 					%><%=key%><div style="margin: 0 0 5px 20px;"><%=sub%></div><%
			// 				}
			// 			} else {
			// 		%>
			// 			<div><%=key%>: <%=val%></div>
			// 		<%
			// 			}
			// 		});
			// 		%>
			// 	</div>
			// </div>
		</script>
    </div><!--end content-->   
</div> <!--end mainfield-->