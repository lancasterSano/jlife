{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
            <div class="subl">
                <ul id="lineTabs" >
                    <li ><a href="./my_album.php?id={$ProfileAuth->ID}" >Фото-обзор</a></li>
                    <li ><a href="./my_photoComment.php?id={$ProfileAuth->ID}" >Коментарии</a></li>
                    <li ><a href="./my_albums.php?id={$ProfileAuth->ID}">Альбомы</a></li>
                </ul>
                <div class="UmanageBut">
                	<a href="#"><img src="../img/add.png"/></a>
                    <a href="#"><img src="../img/edit.png"/></a>
                    <a href="#"><img src="../img/delete.png"/></a>
                </div>

    	    </div>