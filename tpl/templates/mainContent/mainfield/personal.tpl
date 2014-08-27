{* mainfield страницы Блог. *}
{* Требования :

*}
<div class="mainfield"> 
	<div class="content">
        <div class="uInfoContacts">
            <div class="mainInfo">  
                <div class="uInfo">
                    <table>
                        {if $personal.birthday}
                        <tr>
                        	<td id="field">
                            	Дата рождения
                            </td>
                            <td>
                            	{$personal.birthday}
                            </td>
                        </tr>
                        {/if}
                        {if $personal.country}
                        <tr>
                        	<td id="field">
                            	Страна
                            </td>
                            <td>
                            	{$personal.country}
                            </td>
                        </tr>
                        {/if}
                        {if $personal.city}
                        <tr>
                        	<td id="field">
                            	Город
                            </td>
                            <td>
                            	{$personal.city}
                            </td>
                        </tr>
                        {/if}
                        {if $personal.telephone}
                        <tr>
                        	<td id="field">
                            	Телефон
                            </td>
                            <td>
                            	{$personal.telephone}
                            </td>
                        </tr>
                        {/if}
                        {if $personal.mobile}
                        <tr>
                        	<td id="field">
                            	Мобильный
                            </td>
                            <td>
                            	{$personal.mobile}
                            </td>
                        </tr>
                        {/if}
                    </table>
                </div>

                <div class="underInfo">
                 	<!--<table>
                    	<tr>
                        	<td id="field">
                            	Деятельность
                            </td>
                            <td>
                            	Ученик Школа №62
                            </td>
                        </tr>
                    </table> -->
                </div>
            </div> 
            <div class="Contacts">
              	<div class="contactNum">
                    <span><a href="contacts.php?id={$idLoad}">Контакты({$countcontact})</a></span>
                </div>
                <div class="photoTable">
                    {foreach $friends as $friend}
                    <a href="index.php?id={$friend.id}"> <img src="..{$friend.pathAvatar}" />
                    {/foreach}
                    </a>
                </div>
            </div> 
        </div>
	</div><!--end content-->   
</div> <!--end mainfield-->