{* mainfield страницы Блог. *}
{* Требования :
*}
<div class="mainfield">
	<div class="content">        
<!--         <div class="secureItemN" id="responsibles">
        	<div class="managettl">
        	  <span>Школьная деятельность:</span>
        	</div>
        	<div class="managetct">
        		<div class="changeFields">
	        	</div>
        	</div>
        </div> -->
        <div class="secureItemN" id="register_responsible">
        	<div class="managettl">
        	  <span>Импорт данных [Profile]:</span>
        	</div>
        	<div class="managetct">
        		<div class="load-layer">
        			<div class="load"></div>
        		</div>
        		<div class="changeFields">
					<div class="preConfirmation"></div>
        			<form method="post" action="../php/registration.php">
						<div class="changeItem">
							<span>Имя</span>
							<input type="file" id="files" name="files[]" multiple />
						</div>
						<div class="changeItem">
							<output id="list"></output>
							<output id="list_xml_context"></output>
							<div id="log">
							  <!-- <h3>Content:</h3> -->
							</div>
						</div>
						<script>
							document.getElementById('files').addEventListener('change', handleFileSelect, false);
						</script>
        			</form>	
        		</div>
        		<div class="confirmationPanel">
        			<div class="confirmation">
        			</div>
        			<div class="confirmbtn" id="btn_form">
        				<span>Зарегистрировать</span>
        			</div>
        		</div>
        	</div>
        </div>
	</div>
	</div><!--end content-->     
</div> <!--end mainfield-->