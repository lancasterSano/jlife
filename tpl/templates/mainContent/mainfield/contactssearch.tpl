<div class="mainfield"> 
    <div class="searchCont">
        <div class="findCont">
            <input id="searchInput" type="text" value=" Поиск контакта..."  onfocus="if(this.value == ' Поиск контакта...')this.value = '';" 
                   onblur="if(this.value == '') this.value =' Поиск контакта...';" 
                   onkeyup="Friend.searchUsersUI(event, 0);"/>
        </div>
        <div class="searchManage" onclick="Friend.searchUsersUI(undefined, 0);">
            <img src="../img/searchcontact.png" />
        </div>
    </div>
    <div class="content">
        <div class="friendsnum" >
            <a><span>Всего найденных (<span id="countOutboxRequestsSpan" style="float: none;margin: auto;">0</span>)</span></a>
        </div>
        <div class="friend">
            {$notices['MI_FRIENDSSEARCH_EMPTY_SEARCHKEY']['type'] = 1}
            {$notices['MI_FRIENDSSEARCH_EMPTY_SEARCHKEY']['messages'][]['title'] = $smarty.const.MI_FRIENDSSEARCH_EMPTY_SEARCHKEY_TEXT}
            <div class='conformation'>
                {include file="../general/notice.tpl" IDNOTICE="MI_FRIENDSSEARCH_EMPTY_SEARCHKEY"}
            </div>
        </div>
    </div>
</div> <!--end mainfield-->
	
	