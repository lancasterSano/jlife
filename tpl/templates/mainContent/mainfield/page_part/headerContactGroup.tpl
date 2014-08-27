<div class="friendsnum" id="groupTitle_{$ProfileLoad->ID}_{$group.id}">
  <span class="groupHeader">{$group.name}</span>
  <span class="groupHeader">(</span>
  <span class="groupHeader" id="groupcountuser_{$group.id}" style="margin: 0">{$group.countfriends}</span>
  <span class="groupHeader" style="margin: 0">)</span>
  {if $ProfileAuth->ID == $ProfileLoad->ID} {* показываем popup удаления и переименования 
                                             * группы только на странице своих друзей *}
    <span id="groupPopupButton_{$group.id}" class="groupPopup" onclick="Contact.showGroupPopup($(this), {$group.id});"><img src="../img/out_pict.png" /></span>
    {if $showmode == "part"}
      <span class="groupExpand" id="groupButton_{$group.id}" 
      {if $group.countfriends <= $SETTING_COUNT_FIRST_LOAD_FRIENDS}style="display:none;"{/if} 
       onclick="Contact.expandMinimizeGroupUI('{$group.id}');">
      Показать всех
      </span>
    {/if}
  {/if}
</div>