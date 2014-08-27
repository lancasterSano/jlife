<div class="subl">
  <ul id="lineTabs" >
    {if $type == "k"}
    <li><a href="./cabinet.php?school={$numTab}&type=k&s=1"{if $activeTab==1}class="active"{/if}>Классы</a></li>
    <li><a href="./cabinet.php?school={$numTab}&type=k&s=2"{if $activeTab==2}class="active"{/if}>Расписание</a></li>
    <li><a href="./cabinet.php?school={$numTab}&type=k&s=3"{if $activeTab==3}class="active"{/if}>Журнал</a></li>
    <li><a href="/auth/{$cur_role_attr.idschool}/evolution/{$cur_role_attr.role}"{if $activeTab==4}class="active"{/if}>Evolution</a></li>
    {/if}
    {if $type == "s"}
    <li><a href="./cabinet.php?school={$numTab}&type=s&s=1"{if $activeTab==1}class="active"{/if}>Расписание</a></li>
    <li><a href="./cabinet.php?school={$numTab}&type=s&s=2"{if $activeTab==2}class="active"{/if}>Предметы</a></li>
    <li><a href="./cabinet.php?school={$numTab}&type=s&s=3"{if $activeTab==3}class="active"{/if}>Журнал</a></li>
    <li><a href="./cabinet.php?school={$numTab}&type=s&s=4"{if $activeTab==4}class="active"{/if}>Результаты тестов</a></li>
    <!--
    <li><a href="./study.php?school={$numTab}&type=s&s=5"{if $activeTab==5}class="active"{/if}>Система роста</a></li>
    <li><a href="./study.php?school={$numTab}&type=s&s=6"{if $activeTab==6}class="active"{/if}>Гонка</a></li>
    <li><a href="./study.php?school={$numTab}&type=s&s=7"{if $activeTab==7}class="active"{/if}>Общее</a></li>
    -->
    {/if}
    {if $type == "l"}
    <li><a href="./cabinet.php?learner={$numTab}&type=l&s=1"{if $activeTab==1}class="active"{/if}>Расписание</a></li>
    <li><a href="./cabinet.php?learner={$numTab}&type=l&s=2"{if $activeTab==2}class="active"{/if}>Предметы</a></li>
    <li><a href="./cabinet.php?learner={$numTab}&type=l&s=3"{if $activeTab==3}class="active"{/if}>Журнал</a></li>
    <li><a href="./cabinet.php?learner={$numTab}&type=l&s=4"{if $activeTab==4}class="active"{/if}>Результаты тестов</a></li>
    {/if}
  </ul>
</div>