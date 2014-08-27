<div class="union">
    <div class="left_side">
        <div class="name">
            <div class='fio'>
                <span class="fiosmall" title="ФИ" alt="ФИ">
                    Черешня Роман <?php echo Yii::app()->user->name; ?>
                </span>
            </div>
            <?php if(1001 != 1000) { $ProfileLoad = 1001; ?>
                <div class="pageBut">
                    <div id="subscriber" onclick="" class="notsubscribe subscribe" ></div>
                    <div id="friends_$ProfileLoad" 
                        onclick=""
                        class="sendAddRequest editFriendRequest editAddRequest acceptFriendRequest acceptFriendRequestOld" >
                    </div>
                    <a href=""><div id="messagner_$ProfileLoad" class="message"></div></a>
                </div>      
            <?php }?>
           <img class="pic" src="" />
        </div>
        <div class="switch">
            <!-- <div class="subl">
                <ul id="lineTabs">
                    <li {if $numTab == 11 || $numTab == 12} class="active" {/if}><a href="./index.php?id=$ProfileLoad">Заметки</a></li>
                    <li {if $numTab == 41 || $numTab == 42} class="active" {/if}><a href="./personal.php?id=$ProfileLoad">Общее</a></li>
                    <li {if $numTab == 51 || $numTab == 52 || $numTab == 53 } class="active" {/if}><a href="./albums.php?id=$ProfileLoad">Фотографии</a></li>
                </ul>
            </div> -->
            <?php echo $content; ?>
        </div>
    </div> 
    <div class="right_side">
        <img id="ava" src="{$PROJECT_PATH}{$ProfileLoad->ProfilePathAvatar(1, true)}" />
    </div>
</div>