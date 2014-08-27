{* Заголовок всех страниц. Подклюяается в шаблонах: page и page* *}
{* Требования : 
    $UserAuth - Авторизированный пользователь.
*}
<div class="menu">
   <A href=''> <img src="{$PROJECT_PATH}/img/logo.png"/></a>
    <div class="menuitems">
        {*<ul>
            <li><a href="#">Маркет</a></li>
            <li><a href="#">Мероприятия</a></li>
            <li><a href="#">Кино</a></li>
            <li><a href="#">Блог</a></li>
            <li><a href="#">Библиотека</a></li>
        </ul> *}
    </div>
    <div class="search">
        <div class="login-logout">        
            {if $ProfileAuth != ''}
                <span>
                    {if $is_do}
                        {assign var=pathLogout value=preg_replace( $smarty.const.YII_REG_SUBFOLDER_REPLACER_do, '/auth', $YII_APP->createUrl('auth/logout'))}
                    {else}
                        {assign var=pathLogout value=preg_replace( $smarty.const.YII_REG_SUBFOLDER_REPLACER_social, '/auth', $YII_APP->createUrl('auth/logout'))}
                    {/if}
                        {*$pathLogout*}
                    &nbsp{$ProfileAuth->FI()|default:"ФИ"}<a href="{$pathLogout}"><img src="{$PROJECT_PATH}/img/shut_down.png"/></a>
                </span>
            {else}
                <a href="{$PROJECT_PATH}/pages/register.php">Регистрация<a>
            {/if}
        </div>
        {if isset($block_page_sys) && isset($is_do) && $is_do && $block_page_sys!=='development'}
            {assign var=numbersPages value=NumbersPages::NumberPageByRoute($block_page_sys)}
            {assign var=paramLearner value=$cur_role_attr.idlearner|default:null}
            <!-- [{NumbersPages::NumberPageByRoute($block_page_sys)}:{$block_page_sys}:{$numbersPages}]        -->
            <div id="faqp">
            {if isset($paramLearner)}
                <a href="/auth/{$cur_role_attr.idschool}/{$cur_role_attr.pageName}/{$cur_role_attr.role}/{$cur_role_attr.numberlearner}/faq" title="Справка">?</a>
            {else}
                <a href="/auth/{$cur_role_attr.idschool}/{$cur_role_attr.pageName}/{$cur_role_attr.role}/faq" title="Справка">?</a>
            {/if}
            </div>
        {/if}
    </div>
<!--     <div>
        <span>ID школы - {$cur_role_attr.idschool}</span>
        <span>ID адресс - {$cur_role_attr.idadress}</span>
        <span>Роль - {$cur_role_attr.role}</span>
        <span>Ученик - {$cur_role_attr.idlearner}</span>
        <span>Имя страницы - {$cur_role_attr.pageName}</span>
    </div> -->
</div>
