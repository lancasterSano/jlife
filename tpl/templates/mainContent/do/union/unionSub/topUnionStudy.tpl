{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
    <div class="subl">
        <ul id="lineTabs" >
            {$curschool}
            <li >{$curschool}<a href="./study.php?school={$numTab}&s=1"{if $activeTab==1}class="active"{/if}>{$curschool}Расписание</a></li>
            <li ><a href="./study.php?school={$numTab}&s=2"{if $activeTab==2}class="active"{/if}>Предметы</a></li>
            <li ><a href="./study.php?school={$numTab}&s=3"{if $activeTab==3}class="active"{/if}>Журнал</a></li>
            <li ><a href="./study.php?school={$numTab}&s=4"{if $activeTab==4}class="active"{/if}>Результаты тестов</a></li>
             <!-- <li ><a href="./study.php?school={$numTab}&s=4"{if $activeTab==4}class="active"{/if}>Система роста</a></li> -->
            <!-- li ><a href="./study.php?school={$numTab}&s=5"{if $activeTab==5}class="active"{/if}>Гонка</a></li> -->
{*            <li ><a href="./study.php?school={$numTab}&s=6"{if $activeTab==6}class="active"{/if}>Общее</a></li>*}
        </ul>
    </div>