{if isset($debug_context[1]) } <div class="debug"> {debug_out context=$debug_context[1]} </div> {/if} 

{if $authError == md5(md5(true))}
    <div class="texting">
        <p>Не удается войти.</p>
        <p>Пожалуйста, проверьте правильность написания логина и пароля. </p>
        <ul>
            <li>Возможно, нажата клавиша CAPS-lock?</li>
            <li>Может быть у Вас включена неправильная раскладка? (русская или английская)</li>
            <li>Попробуйте набрать свой пароль в текстовом редакторе и скопировать в графу «Пароль»</li>
        </ul>
        <!-- <p>Если Вы всё внимательно проверили, но войти всё равно не удается, Вы можете <a href="#">нажать сюда.</a></p> -->
    </div>
{/if}
       
<div style='float:left;font-weight:bold;color:#8eb4e3;font-size:24px;margin-top:469px;margin-left:110px;'>
        <span style='color:red;float:left;'>J</span><span>Life</span>
        <span style='font-weight:normal;'>- ваша интеллектуальная сеть</span>
    </div> 
<div class="registration">
	<div class="enter"><span>Войти</span></div>
   	<div class="Registrlbl">
    	<span>Логин</span>
    	<span id="correction">Пароль</span>
        
    </div>
        <form method="POST" action="../php/authorization.php">
            <div class="RegistrFields">
                    <input id="rf_login" autocomplete="off" name="login" type="text" value="{$login}"/>
                    <input id="rf_password" autocomplete="off" name="password" type="password" value=""/>
                    <input name="loginSbt" id="loginSbt" type="submit" value="Войти"/> 
                        
             </div>
             <!-- <div class="notify">
             	<span>Запомнить меня</span> <input type="checkbox" name="saveInAuth"/>
                
                <span id="notfound">Такой Пользователь не найден</span>
             </div> -->
        </form>
</div>