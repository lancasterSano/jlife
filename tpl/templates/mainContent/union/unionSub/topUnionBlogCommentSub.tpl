{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
            <div class="subl">
                <ul id="lineTabs" >
                        <li ><a href="./{if $pageFormat == 2}my_{/if}blog.php?id={$ProfileLoad->ID}" {if $numTab==21}class="active"{/if}>Блог</a></li>
                        <li ><a href="./{if $pageFormat == 2}my_{/if}blogComment.php?id={$ProfileLoad->ID}" {if $numTab==22}class="active"{/if}>Коментарии блога</a></li>
                </ul>
                <!--<div class="styled-select1">
                    <select>
                        <option selected value="школы">Школы</option>
                        <option>1</option>
                        <option>2</option>
                    </select>
                </div>-->
                

    	    </div>