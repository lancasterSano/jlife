<div class="main">
    <div class="header">
      <div class="salutation">
        Здравствуйте, {$ProfileAuth->FIO()}!
      </div>
<!--       <div class="topic">
        Поздравляем с регистрацией
      </div> -->
    </div>
    <div class="content">
      <table>
        <tbody><tr>
          <td class="left">Ваш логин:</td>
          <td>{$ProfileAuth->email}</td>
        </tr>
      </tbody></table>
      <p>Чтобы подтвердить смену email, пожалуйста, откройте данную ссылку в браузере:</p>
      <p><a href="http://{$JLIFE_DOMEN}/pages/ssecurity.php?asec={$hex}" target="_blank">http://{$JLIFE_DOMEN}/pages/ssecurity.php?asec={$hex}</a></p>
      <p class="red">
        Внимание! 
        <br>
        Смена email производится только по данной ссылке.
        <br>
        Отвечать на данное сообщение не нужно.
      </p>
    </div>
    <div class="status">
      Вы получили это сообщение потому, что Ваш e-mail адрес был указан при попытке привязать его к учетной записи на сайте <a href="http://{$JLIFE_DOMEN}" target="_blank">{$JLIFE_DOMEN}</a>. 
      Если вы не совершали данное действие на этом сайте, пожалуйста, проигнорируйте это письмо администрации. 
    </div>
    <div class="footer">
      С наилучшими пожеланиями, администрация сайта <a href="http://{$JLIFE_DOMEN}" target="_blank">{$JLIFE_DOMEN}</a>.
    }
    </div>
  </div>