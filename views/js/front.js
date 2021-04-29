/**
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

*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}


let close = document.getElementById('closeCookie'),
    notice = document.getElementById('cookie'),
    random = getRndInteger(0, 19234);

$('.closeCookie').on('click', function() {
    console.log('click');
    setCookie('DarkSide', random, 2);
    $('div#cookie').fadeOut('fast');
})