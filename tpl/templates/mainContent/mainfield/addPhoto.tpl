 	<div class="mainfield"> 
    	<div class="content">
			<div class="addLabl">
                <span>Выберите альбом</span>
            </div>
            <div class="selectAlbum">
                <select>
                    <option disabled selected value="name" >Название альбома</option>
                    <option value="{$photoAlbom.idPhotoAlbom}">{$photoAlbom.namePhotoAlbom}</option>
                </select>
            </div>
            <div class="downloadPhoto">
                <div class="downloadForm">
                    <a href="">Выберите фото</a>
                    <input type="file" />
                </div>
                <div class="downloadForm">
                    <a href="">Выберите фото</a>
                    <input type="file" />
                </div>
               <div class="downloadForm">
                    <a href="">Выберите фото</a>
                    <input type="file" />
                </div>
                <div class="downloadForm">
                    <a href="">Выберите фото</a>
                    <input class="error" type="file" />
                </div>
                
            </div>
            <div class="readyButt">
                <a href="">Готово!</a>
            </div>
    	</div><!--end content-->   
	</div> <!--end mainfield-->