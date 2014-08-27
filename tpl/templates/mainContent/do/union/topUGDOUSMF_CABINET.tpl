<div class="union">
  <div class="left_side">
    <div class="switch">
      <div class="subl">
        <ul id="lineTabs">
        {*Отображаем роли завуча*}
        {foreach $KoSchools as $KoSchool}
        {if ($numTab == $KoSchool.id) and ($type == "k")}
          {assign var="isKoRoleSchoolSelected" value=true}
        {else}
          {assign var="isKoRoleSchoolSelected" value=false}
        {/if}
          <li id="school_{$KoSchool.id}_{$ProfileKo->idKo}">
            <a href="./cabinet.php?school={$KoSchool.id}&type=k" 
               {if $isKoRoleSchoolSelected == true} class="active" {/if}
            >{$KoSchool.name} З</a>
          </li>
        {/foreach}
        {*Отображаем роли преподавателя*}
        {foreach $TeacherSchools as $TeacherSchool}
          {if ($numTab == $TeacherSchool.id) and ($type == "s")}
            {assign var="isTeacherRoleSchoolSelected" value=true}
          {else}
            {assign var="isTeacherRoleSchoolSelected" value=false}
          {/if}
          <li id="school_{$TeacherSchool.id}_{$ProfileTeacher->idTeacher}">
            <a href="./cabinet.php?school={$TeacherSchool.id}&type=s" 
               {if $isTeacherRoleSchoolSelected == true} class="active" {/if}
            >{$TeacherSchool.name} П</a>
          </li>
        {/foreach}
        {*Отображаем роли ответственного*}
        {foreach $Learners as $LearnersResponsible}
          {foreach $LearnersResponsible as $LearnerResponsible}
            {if ($numTab == $LearnerResponsible->idLearner) and ($type == "l")}
              {assign var="isLearnerSelected" value=true}
            {else}
              {assign var="isLearnerSelected" value=false}
            {/if}
          <li id="learner_{$LearnerResponsible->idLearner}_{$LearnerResponsible->idSchool}">
            <a href="./cabinet.php?learner={$LearnerResponsible->idLearner}&type=l" 
               {if $isLearnerSelected == true} class="active" {/if}
            >{$LearnerResponsible->FirstName}</a>
          </li>
          {/foreach}
        {/foreach}
        </ul>
      </div>
    {$topUnionSub_subl}
    </div>
  </div>
</div><!-- end union-->