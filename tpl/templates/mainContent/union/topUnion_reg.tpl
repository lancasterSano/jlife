<div class="mainfield"> 
    <div class="content">
        <!-- <h1>{$PageTitle}</h1> -->
<!--        <form method="post" action="../php/registration.php">
            Логин* <input name="login" type="text" value="{$login}"/><br/>
            Пароль* <input name="pswd" type="password" value="{$pswd}"/><br/>
            Email* <input name="email" type="text" value="{$email}"/><br/>
            FirstName* <input name="firstName" type="text" value="{$firstName}"/><br/>
            LastName* <input name="lastName" type="text" value="{$lastName}"/><br/>
            MiddleName <input name="middleName" type="text" value="{$middleName}"/><br/>
            <input name="registe_sbt" type="submit" value="Зарегистрироваться"/>
                    <a href="login.php"> On start! </a>
        </form>  -->
        
<div class="secureItem" id="ch_pswd">
    <div class="managettl">
      <span>Регистрация в социалке новый профиль</span>
    </div>
    <div class="managetct">
        <form method="post" action="../php/registration.php">
            <div class="load-layer">
                <div class="load"></div>
            </div>
            <div class="changeFields">
                <div class="preConfirmation">          
                </div>      
                <div class="changeItem">
                    <span>Логин*</span> <input name="login" type="text" value="{$login}"/>
                </div>
                <div class="changeItem">
                    <span>Пароль*</span> <input name="pswd" type="password" value="{$pswd}"/>
                </div>
                <div class="changeItem">                    
                    <span>Email*</span> <input name="email" type="text" value="{$email}"/>
                </div>
                <div class="changeItem">
                    <span>FirstName*</span> <input name="firstName" type="text" value="{$firstName}"/>
                </div>
                <div class="changeItem">
                    <span>LastName*</span> <input name="lastName" type="text" value="{$lastName}"/>
                </div>
                <div class="changeItem">
                    <span>MiddleName</span> <input name="middleName" type="text" value="{$middleName}"/>
                </div>
            </div>
            <div class="confirmationPanel">
                <div class="confirmation">
                {if isset($regError) && $regError == true}
                    <h3><b>При регистрации произошли ошибки!</b></h3>
                {/if}
                {if isset($regError) && $regError == false}
                    <h3><b>Регистрации прошла успешно!</b></h3>
                {/if}
                </div>
                <div class="confirmbtn" id="btn_form">
                    <input name="registe_sbt" type="submit" value="Зарегистрироваться">
                    <span>
                    dcdv
                    </span>
                    </input>
                </div>
            </div>
            <div class="confirmationPanel">
                <div class="confirmbtn" id="btn_form">
                    <span>
                    Зарегистрироваться
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
    </div>
</div>