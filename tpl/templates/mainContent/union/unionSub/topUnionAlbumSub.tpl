{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
            <div class="subl">
                <ul id="lineTabs" >
                    <li ><a href="./{if $pageFormat == 2}my_{/if}album.php?id={$ProfileLoad->ID}" {if $numTab==51}class="active"{/if}>Фото-обзор</a></li>
                    <li ><a href="./{if $pageFormat == 2}my_{/if}photoAlbumComment.php?id={$ProfileLoad->ID}" {if $numTab==52}class="active"{/if}>Коментарии</a></li>
                    <li ><a href="./{if $pageFormat == 2}my_{/if}albums.php?id={$ProfileLoad->ID}" {if $numTab==53}class="active"{/if}>Альбомы</a></li>
                </ul>
                <div class="UmanageBut">
                	<a href="./addphoto.php"><img src="../img/add.png"/></a>
                    <a href="#"><img src="../img/edit.png"/></a>
                    <a href="#"><img src="../img/delete.png"/></a>
                </div>

    	    </div>