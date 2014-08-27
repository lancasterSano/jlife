{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
            <div class="subl">
                <ul id="lineTabs" >
                    <li ><a href="#" class="active">Общее</a></li>
                    <li ><a href="#" >Достижения</a></li>
                </ul>
            </div>
            
        </div>
    </div> 
    <div class="right_side">
        <img id="ava" src="{$PROJECT_PATH}{$ProfileLoad->ProfilePathAvatar()}" />
    </div>
</div><!-- end union-->