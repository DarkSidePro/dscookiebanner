<?php
/**
* 2007-2019 PrestaShop.
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

    $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ds_cookiebanner` (
    `id_cookiebanner` int(11) NOT NULL AUTO_INCREMENT,
    `id_lang` int(10) NOT NULL,
    `cb_content` text NOT NULL,
    PRIMARY KEY  (`id_cookiebanner`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

    Db::getInstance()->execute($sql);
    Db::getInstance()->delete('ds_cookiebanner');

    foreach ($languages as $lang) {
        $content = htmlspecialchars('<p style="text-align: center;">THIS website uses cookies. <span style="text-decoration: underline;"><a href="http://ec.europa.eu/ipg/basics/legal/cookies/">EU cookie</a></span></p>');
        $echotest = Db::getInstance()->insert('ds_cookiebanner', array(
            'cb_content' => pSQL($content),
            'id_lang' => (int) $lang['id_lang'],
        ));
    }
