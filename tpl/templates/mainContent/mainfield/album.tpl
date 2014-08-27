{* mainfield страницы Альбом. *}
{* Требования :

*}

  	<div class="mainfield"> 
        {assign var="stUnic" value=$ProfileLoad->ID}
    	<div class="content">
        {if isset($debug_context[1]) } <div class="debug"> {debug_out context=$debug_context[1]} </div> {/if} 
			<div class="album_info"	>
            
            	<div class="likeimg">
                    <img src="../img/album1.png" />
                    <span>{$photoAlbom.countLikePhotoAlbom}</span>
                	  <img src="../img/album2.png" />
                    <span>{$photoAlbom.countPhoto}</span>
                </div>
                <div class="albumDesc">
                	<span><a href="{$smarty.server.SETTING_PROFILE_ADRESS}{'?us='}{$ProfileID}{'&id='}{$photoAlbom.idPhotoAlbom}">{$photoAlbom.namePhotoAlbom}</a></span>
                </div>    
                
            </div>
            <div class="albumTable">
              {foreach key=massKey item=photos from=$allPhotosChoosenAlbum name=A}
              <div class="albumItem">
                
            	<a id="delete" href="{$smarty.server.SETTING_PROFILE_ADRESS}{'?us='}{$ProfileID}{'&id='}{$photo.photoPath}"><img src="{$photo.photoPath}" /></a>
                <div class="manageAlbumItem">
                	
                     <div class="likeimg1">
                    	<a href="#"><img  src="../img/like2.png" /><span>{$photo.countComent}</span></a>
                            
                     </div>
                     <div class="photimg">
                  		 <a href="#"><img src="../img/phot.png" /><span>{$photo.countLikePhoto}</span></a>   
                     </div>
                     <div class="delete">
                  		 <a href="#"><img src="../img/delete.png" /></a> 
                    </div>
                     
                </div>
              </div>
              {/foreach}      
            </div>
            
    	</div><!--end content-->   
	</div> <!--end mainfield-->