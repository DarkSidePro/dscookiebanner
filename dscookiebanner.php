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
*  @author    Dark-Side <contact@dark-side.pro>
*  @copyright 2007-2019 Dark-Side
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Dscookiebanner extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'dscookiebanner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Dark-Side.pro';
        $this->need_instance = 1;
        $this->module_key = '204e289d57f756be10f3db747ffab013';

        /*
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('DS: Cookie baner');
        $this->description = $this->l('This module add cookie info on your store');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    private function createTab()
    {
        $response = true;
        $parentTabID = Tab::getIdFromClassName('AdminDarkSideMenu');
        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = 'AdminDarkSideMenu';
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = 'Dark-Side.pro';
            }
            $parentTab->id_parent = 0;
            $parentTab->module = '';
            $response &= $parentTab->add();
        }
        $parentTab_2ID = Tab::getIdFromClassName('AdminDarkSideMenuSecond');
        if ($parentTab_2ID) {
            $parentTab_2 = new Tab($parentTab_2ID);
        } else {
            $parentTab_2 = new Tab();
            $parentTab_2->active = 1;
            $parentTab_2->name = array();
            $parentTab_2->class_name = 'AdminDarkSideMenuSecond';
            foreach (Language::getLanguages() as $lang) {
                $parentTab_2->name[$lang['id_lang']] = 'Dark-Side Config';
            }
            $parentTab_2->id_parent = $parentTab->id;
            $parentTab_2->module = '';
            $response &= $parentTab_2->add();
        }
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdministratorCookieBanner';
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = 'Cookie Banner';
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    private function tabRem()
    {
        $id_tab = Tab::getIdFromClassName('AdministratorCookieBanner');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        $parentTab_2ID = Tab::getIdFromClassName('AdminDarkSideMenuSecond');
        if ($parentTab_2ID) {
            $tabCount_2 = Tab::getNbTabs($parentTab_2ID);
            if ($tabCount_2 == 0) {
                $parentTab_2 = new Tab($parentTab_2ID);
                $parentTab_2->delete();
            }
        }
        $parentTabID = Tab::getIdFromClassName('AdminDarkSideMenu');
        if ($parentTabID) {
            $tabCount = Tab::getNbTabs($parentTabID);
            if ($tabCount == 0) {
                $parentTab = new Tab($parentTabID);
                $parentTab->delete();
            }
        }

        return true;
    }

    public function install()
    {
        $languages = $this->context->controller->getLanguages();
        include dirname(__FILE__).'/sql/install.php';        
        Configuration::updateValue('DSCOOKIEBANNER_COLOR', null);
        Configuration::updateValue('DSCOOKIEBANNER_BACKGROUND', null);
        Configuration::updateValue('DSCOOKIEBANNER_POSITION', null);
        Configuration::updateValue('DSCOOKIEBANNER_BUTTONCOLOR', null);
        Configuration::updateValue('DSCOOKIEBANNER_BUTTONBACKGROUND', null);
        Configuration::updateValue('DSCOOKIEBANNER_COOKIELINK', null);

        $this->createTab(); //init add tab function

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        Configuration::deleteByName('DSCOOKIEBANNER_COLOR');
        Configuration::deleteByName('DSCOOKIEBANNER_BACKGROUND');
        Configuration::deleteByName('DSCOOKIEBANNER_POSITION');
        Configuration::deleteByName('DSCOOKIEBANNER_BUTTONCOLOR');
        Configuration::deleteByName('DSCOOKIEBANNER_BUTTONBACKGROUND');
        Configuration::deleteByName('DSCOOKIEBANNER_COOKIELINK');

        $this->tabRem(); //init remove tab function

        return parent::uninstall();
    }

    /**
     * Load the configuration form.
     */
    public function getContent()
    {
        /*
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitCookiebannerModule')) == true) {
            $msg = $this->postProcess();

            return $msg.$this->renderForm();
        }

        return $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCookiebannerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'tinymce' => true,
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Select cookie notice position'),
                        'name' => 'DSCOOKIEBANNER_POSITION',
                        'label' => $this->l('Cookie banner position'),
                        'values' => array(
                            array(
                                'id' => 'top',
                                'value' => null,
                                'label' => $this->getTranslator()->trans('Top', array(), 'Modules.Dscookiebanner.Admin'),
                            ),
                            array(
                                'id' => 'buttom',
                                'value' => true,
                                'label' => $this->getTranslator()->trans('Bottom', array(), 'Modules.Dscookiebanner.Admin'),
                            ),
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'color',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Choose color for font in cookie banner'),
                        'name' => 'DSCOOKIEBANNER_COLOR',
                        'label' => $this->l('Font color'),
                        'required' => false,
                    ),
                    array(
                        'col' => 3,
                        'type' => 'color',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Choose background color in cookie banner'),
                        'name' => 'DSCOOKIEBANNER_BACKGROUND',
                        'label' => $this->l('Background color'),
                        'required' => false,
                    ),
                    array(
                        'col' => 3,
                        'type' => 'color',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Choose button color'),
                        'name' => 'DSCOOKIEBANNER_BUTTONCOLOR',
                        'label' => $this->l('Button color'),
                        'required' => false,
                    ),
                    array(
                        'col' => 3,
                        'type' => 'color',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Choose button background color'),
                        'name' => 'DSCOOKIEBANNER_BUTTONBACKGROUND',
                        'label' => $this->l('Button background color'),
                        'required' => false,
                    ),
                    array(
                        'col' => 3,
                        'type' => 'color',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Choose link color'),
                        'name' => 'DSCOOKIEBANNER_COOKIELINK',
                        'label' => $this->l('Link color'),
                        'required' => false,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Cookie content'),
                        'desc' => $this->l('Write message about cookie for your clients'),
                        'name' => 'DSCOOKIEBANNER_CONTENT',
                        'lang' => true,
                        'cols' => 60,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $fields_value = $this->getDatabaseAdmin();

        return array(
            'DSCOOKIEBANNER_COLOR' => Configuration::get('DSCOOKIEBANNER_COLOR', null),
            'DSCOOKIEBANNER_BACKGROUND' => Configuration::get('DSCOOKIEBANNER_BACKGROUND', null),
            'DSCOOKIEBANNER_POSITION' => Configuration::get('DSCOOKIEBANNER_POSITION', null),
            'DSCOOKIEBANNER_BUTTONCOLOR' => Configuration::get('DSCOOKIEBANNER_BUTTONCOLOR', null),
            'DSCOOKIEBANNER_BUTTONBACKGROUND' => Configuration::get('DSCOOKIEBANNER_BUTTONBACKGROUND', null),
            'DSCOOKIEBANNER_COOKIELINK' => Configuration::get('DSCOOKIEBANNER_COOKIELINK', null),
            'DSCOOKIEBANNER_CONTENT' => $fields_value,
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        $text = array();
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $content = Tools::getValue('DSCOOKIEBANNER_CONTENT_'.$lang['id_lang']);
            $content = htmlspecialchars($content);
            $id_lang = $lang['id_lang'];
            $this->updateDatabase($content, $id_lang);
        }

        $color = Tools::getValue('DSCOOKIEBANNER_COLOR');
        $background = Tools::getValue('DSCOOKIEBANNER_BACKGROUND');
        $buttonColor = Tools::getValue('DSCOOKIEBANNER_BUTTONCOLOR');
        $buttonBackground = Tools::getValue('DSCOOKIEBANNER_BUTTONBACKGROUND');
        $link = Tools::getValue('DSCOOKIEBANNER_COOKIELINK');

        if (Validate::isColor($color) != true) {
            return $this->displayError($this->trans('You must correct fill the color field.', array(), 'Admin.Dscookiebanner.Error'));
        }

        if (Validate::isColor($background) != true) {
            return $this->displayError($this->trans('You must correct fill the background color field.', array(), 'Admin.Dscookiebanner.Error'));
        }

        if (Validate::isColor($buttonColor) != true) {
            return $this->displayError($this->trans('You must correct fill the button color field.', array(), 'Admin.Dscookiebanner.Error'));
        }

        if (Validate::isColor($buttonBackground) != true) {
            return $this->displayError($this->trans('You must correct fill the button background color field.', array(), 'Admin.Dscookiebanner.Error'));
        }

        if (Validate::isColor($link) != true) {
            return $this->displayError($this->trans('You must correct fill the link color field.', array(), 'Admin.Dscookiebanner.Error'));
        }         

        Configuration::updateValue('DSCOOKIEBANNER_COLOR', Tools::getValue('DSCOOKIEBANNER_COLOR'));
        Configuration::updateValue('DSCOOKIEBANNER_BACKGROUND', Tools::getValue('DSCOOKIEBANNER_BACKGROUND'));
        Configuration::updateValue('DSCOOKIEBANNER_POSITION', Tools::getValue('DSCOOKIEBANNER_POSITION'));
        Configuration::updateValue('DSCOOKIEBANNER_BUTTONCOLOR', Tools::getValue('DSCOOKIEBANNER_BUTTONCOLOR'));
        Configuration::updateValue('DSCOOKIEBANNER_BUTTONBACKGROUND', Tools::getValue('DSCOOKIEBANNER_BUTTONBACKGROUND'));
        Configuration::updateValue('DSCOOKIEBANNER_COOKIELINK', Tools::getValue('DSCOOKIEBANNER_COOKIELINK'));

        return $this->displayConfirmation($this->trans('Settings updated.', array(), 'Admin.Dscookiebanner.Success'));
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/front.js');
    }

    public function hookDisplayFooter()
    {
        $cookie = Context::getContext()->cookie->DarkSide;
        $color = Configuration::get('DSCOOKIEBANNER_COLOR');
        $background = Configuration::get('DSCOOKIEBANNER_BACKGROUND');
        $position = Configuration::get('DSCOOKIEBANNER_POSITION');
        $buttonColor = Configuration::get('DSCOOKIEBANNER_BUTTONCOLOR');
        $buttonBackground = Configuration::get('DSCOOKIEBANNER_BUTTONBACKGROUND');
        $cookieLink = Configuration::get('DSCOOKIEBANNER_COOKIELINK');
        $text = $this->getDatabase();
        $text = html_entity_decode($text);

        $this->context->smarty->assign(array('position' => $position, 'color' => $color, 'background' => $background, 'text' => $text, 'cookieLink' => $cookieLink, 'colorButton' => $buttonColor, 'backgroundButton' => $buttonBackground));
        $output = $this->display(__FILE__, 'views/templates/hook/hookDisplayFooter.tpl');
        if (!isset($_COOKIE['DarkSide'])) {
            return $output;
        }
    }

    public function updateDatabase($content, $lang)
    {
        $id_lang = (int) $lang;
        Db::getInstance()->delete('ds_cookiebanner', 'id_lang = '.(int) $lang);
        Db::getInstance()->update('ds_cookiebanner', array(
            'cb_content' => pSQL($content),
            'id_lang' => (int) $id_lang,
        ));
    }

    public function getDatabase()
    {
        $id_lang = Context::getContext()->language->id;
        $sql = 'SELECT cb_content FROM '._DB_PREFIX_.'ds_cookiebanner WHERE `id_lang` = '.(int)$id_lang;
        $sql = Db::getInstance()->ExecuteS($sql);
        $field_value = htmlspecialchars_decode($sql[0]['cb_content']);
        return $field_value;
    }

    public function getDatabaseAdmin()
    {
        $fields_value = array();
        $id_info = 1;
        $languages = $this->context->controller->getLanguages();
        foreach ($languages as $lang) {
            $sql = 'SELECT cb_content FROM '._DB_PREFIX_.'ds_cookiebanner WHERE `id_lang` = '.(int) $lang['id_lang'];
            $sql = Db::getInstance()->ExecuteS($sql);
            $fields_value[(int) $lang['id_lang']] = htmlspecialchars_decode($sql[0]['cb_content']);
        }
        return $fields_value;
    }
}
