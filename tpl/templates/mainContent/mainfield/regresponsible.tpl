{* mainfield страницы Блог. *}
{* Требования :
*}
<div class="mainfield">
	<div class="content">
        {if isset($debug_context[1]) } <div class="debug"> {debug_out context=$debug_context[1]} </div> {/if} 

        <div class="secureItemN" id="responsibles">
        	<div class="managettl">
        	  <span>Список родителей:</span>
        	</div>
        	<div class="managetct">
        		<div class="changeFields">
		        	{foreach key=outer item=School from=$Schools name=Schools}
	        			<div class="changeItem">
	        				<span id="study_">
	        					Ученик <a id="learner_{$ProfileLearner->idLearner}" href="index.php?id={$ProfileLearner->idProfile}"> {$ProfileLearner->LastName} {$ProfileLearner->FirstName} {$ProfileLearner->MiddleName}</a> - 
	        					школа <a id="school_{$School.id}" href="./do/study.php?school={$School.id}">"{$School.name}"</a> -
	        					Класс <a id="class_{$ProfileLearner->idClass}" href="">"{$LearnerClass->name}"</a>
	        				</span>
						</div>
		        	{/foreach}
	        		</table>
	        	</div>
        	</div>
        </div>
		{if !isset($parentLearnerlist['responsibles']['error'])}
        <div class="secureItemN" id="responsibles">
        	<div class="managettl">
        	  <span>Список родителей:</span>
        	</div>
        	<div class="managetct">
        		<div class="changeFields">
		    			{foreach key=idpresponsible item=responsible from=$parentLearnerlist.responsibles name=responsible}
		        			<div class="changeItem">
		        				<!-- <span>Папа :</span> -->
		        				<a href="index.php?id={$responsible['Profile']->ID}">{$responsible['fio']}</a>
		        				<!-- {$responsible['Profile']->private} -->
		        				{if !($responsible['Profile']->private)}
		        					<span> - </span>
		        					<span>Логин: </span> <span>{$responsible['login']}</span>
		        					<span>Пароль: </span> <span>{$responsible['pswd']}</span>
		        				{/if}
							</div>
		        		{/foreach}
	        		{*else*}
	        			<!-- <div class="changeItem">
		        				<span>ни кто не привязан</span>
							</div> -->
        		</div>
        	</div>
        </div>
		{/if}
        <div class="secureItem" id="register_responsible">
        	<div class="managettl">
        	  <span>Регистрация родителей:</span>
        	</div>
        	<div class="managetct">
        		<div class="load-layer">
        			<div class="load"></div>
        		</div>
        		<div class="changeFields">
					<div class="preConfirmation"> {*include file="../general/notice.tpl" IDNOTICE="INFO_SEND_MAIL_CREATEMAIL"*} </div>
        			<form method="post" action="../php/registration.php">
						<div class="changeItem"> 
							<span>Родственная связь:</span>
							<select name="login" id="groupSelect">
							  {foreach item=relation from=$relations}
							    <option  value="{$relation.id}" >{$relation.name}</option>
							  {/foreach}
							</select>
						</div>
						<div class="changeItem"> <span>Имя</span> <input name="firstname" type="text" id="f_firstname" value=""/> </div>
						<div class="changeItem"> <span>Фамилия</span> <input name="lastname" type="text" id="f_lastname" value=""/> </div>
						<div class="changeItem"> <span>Отчество</span> <input name="middlename" type="text" id="f_middlename" value=""/> </div>
						<br/>
						<div class="changeItem">
							<span class="field-t">Логин</span>
							<!-- <input name="login" type="text" value="" readonly/> -->
							<span id="login" class="field"></span>
							<a id="genLogin" onclick="regResponsible.genLogin(event)" class="btn">сгенерировать</a>
						</div>
						<br/>
						<div class="changeItem">
							<span class="field-t">Пароль</span>
							<!-- <input name="pswd" type="text" value="" readonly/> -->
							<span id="pswd" class="field"></span>
							<a id="genPswd" onclick="regResponsible.genPswd(event)" class="btn">сгенерировать</a>
						</div>
						<!-- <input name="email" type="hidden" value="{$email}"/> -->
						<!-- <input name="registe_sbt" type="submit" value="Зарегистрироваться"/> -->
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