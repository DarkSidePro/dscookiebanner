{**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    Dark-Side <contact@dark-side.pro>
*  @copyright 2007-2019 Dark-Side
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<style>
    div#cookie {
    position: fixed;
    bottom: 0px;
    background:  {if $background != null}{$background}{else}#222{/if};
    width: 100%;
    left: 0px;
    z-index: 1000;
}

div#cookie strong, div#cookie p {
    margin-bottom: 0px;
    color: {if $color != null}{$color}{else}#7a7a7a{/if};
}
span#closeCookie {
    margin-top: 10px;
    margin-bottom: 10px;
    background: {if $backgroundButton != null}{$backgroundButton}{else}#cd0a0a{/if};
    color: {if $colorButton != null}{$colorButton}{else}#f7f7f7{/if};
    border: 1px solid {if $color != null}{$backgroundButton}{else}#cd0a0a{/if};
    border-radius: 5px;
}
div#cookie a {
    color: {if $cookieLink != null}{$cookieLink}{else}#f7f7f7{/if};
}

div#cookie .col-lg-12 {
    display: flex;
    justify-content: center;
    align-items: center;
}

div#cookie a.noticelink {
    color: #fff;
    margin-bottom: 0px;
    font-weight: bold;
    text-decoration: underline;
}

div#cookie.top {
    bottom: unset;
    top: 0px !important;
}

.notice {
    display: inline-block;
    width: 100%;
}
</style>
<div id="cookie" class="container-fluid {if $position == null}top{/if}">
    <div class="row">
        <div class='col-lg-12'>
            <div class='notice'>
                {* HTML CONTENT*}
                {$text nofilter} 
            </div>
            <span id='closeCookie' class='btn'>{l s='Accept' mod='dscookiebanner'} </span>
        </div>
    </div>
</div>