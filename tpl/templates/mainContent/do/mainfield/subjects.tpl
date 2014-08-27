{* mainfield: Предметы - пособия по всем предметам, изучаемым учеником. *}
{* author: CHERRY *}
{* create: 04.09.2013 *}
{* last edited: 04.09.2013 *}
{* Требования : *}
<div class="mainfield">     
	<div class="content">
	{* header of table *}
        <div class="next_prev">
            <div class="prev">
                <a href="{$PROJECT_PATH}/pages/do/study.php?school={$numTab}&class={$PNCLASS["idPREVClass"]}&s=2">{$PNCLASS["namePREVClass"]}</a>
            </div>
            <div style="float:left;margin-left: 300px;">{$CURCLASS->name} класс</div>
            <div class="next">
                <a href="{$PROJECT_PATH}/pages/do/study.php?school={$numTab}&class={$PNCLASS["idNEXTClass"]}&s=2">{$PNCLASS["nameNEXTClass"]}</a>
        	</div>
        </div>
	{* body of table *}
        {if $isSubjects}
        <table class="subjects" cellspacing="0" >
        	<tr id="topH">
            	<td><p>Предмет/ прогресс</p></td>
                <td id="tasks"><p>Домашнее задание</p></td>
            </tr>
            {foreach key=idGroup item=subject from=$subjects name=comments}
            	{if $subject.material.idMaterial != NULL}
            		{assign var="stIdMaterial" value=$subject.material.idMaterial}
            	{else}
            		{assign var="stIdMaterial" value=0}
            	{/if}
            	{assign var="stUnic" value=$subject.subject.idSubject|cat:'_'|cat:$idGroup|cat:'_'|cat:$stIdMaterial}
				<tr id="study_{$stUnic}" onclick="Subjects.expandSubject('{$stUnic}');">
					<td>
						<div>
							<div id="info"><p>{$subject.subject.name}</p> <!--<span>{$subject.progress}</span>--></div>
							<div id="procent" style="width:20%;background:{$subject.subject.color};"></div>
						</div>
					</td>
					<td id="tasks">
						<span><p> {if $subject.teacher != NULL} {$subject.teacher.TeacherObject->FIO()} {/if} </p></span>
					</td>
				</tr>
				{if $select_subject != NULL && $select_subject == $subject.subject.idSubject && $select_subgroup == $idGroup}
				<tr id="select_subject_{$stUnic}">
					<td colspan="3" class="droped" style="border-left:5px solid {$subject.subject.color}">
						<ul>
							{if $subject.material.idMaterial != NULL &&  $subject.material.idMaterial == $material_id}
								{foreach key=key item=section from=$material_sections name=sectionsS}
									<li id="section_{$stUnic}_{$key}">
										<!-- <a href="#"><img src="{$PROJECT_PATH}/img/unlock.png" /></a> -->
										<span onclick='Subjects.expandSection("{$stUnic}_{$key}");'>Раздел {$section.number}:{$section.name}</span>
										<ul id="paragraphs_{$key}"></ul>
									</li>
								{/foreach}
							{else}
								<li><span>пусто</span></li>
							{/if}
						</ul>
					</td>
				</tr>
				{/if}										
			{/foreach} 
	   </table>
           {else}
             <div class='conformation'>
               {include file="../../general/notice.tpl" IDNOTICE="MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS"}
             </div>
           {/if}
	</div><!--end content-->   
</div> <!--end mainfield-->