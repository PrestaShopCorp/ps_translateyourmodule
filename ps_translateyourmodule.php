<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ps_translateyourmodule extends Module
{
    const DEFAULT_LANGUAGE_ISO = 'en';
    const AJAX_CONTROLLER_NAME = 'AdminAjaxPsTranslateYourModule';
    const MIME_TYPE_EXPECTED_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    const EXPECTED_EXTENSION = '.xlsx';
    const FORM_ERROR_CODES = [
        'modulename' => 0,
        'ziperror' => 1,
        'translation' => 2,
    ];

    public $name;
    public $tab;
    public $version;
    public $author;
    public $module_key;
    public $author_address;
    public $bootstrap;
    public $displayName;
    public $description;
    protected $css_path;
    protected $js_path;
    protected $img_path;
    protected $modulePageConfiguration;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = 'ps_translateyourmodule';
        $this->tab = 'other';
        $this->version = '1.5.0';
        $this->author = 'PrestaShop';
        $this->module_key = '';
        $this->author_address = '';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Translate your module');
        $this->description = $this->l('It allow to export all the sentences needed to be translated and to import it');
        $this->css_path = $this->_path . 'views/css/';
        $this->js_path = $this->_path . 'views/js/';
        $this->img_path = $this->_path . 'views/img/';
        $this->setControllers();
    }

    /**
     * Loads asset resources
     *
     * @return void
     */
    public function loadAsset()
    {
        Media::addJsDef([
            'ajax_controller_url' => $this->context->link->getAdminLink(self::AJAX_CONTROLLER_NAME),
            'ajax_controller_name' => self::AJAX_CONTROLLER_NAME,
            'mimetype_xlsx' => self::MIME_TYPE_EXPECTED_XLSX,
        ]);

        $jsFile = [
            $this->js_path . 'admin/general.js',
            $this->js_path . 'admin/loadDropZone.js',
            $this->js_path . 'admin/dropzone.min.js',
        ];

        $cssFile = [
            $this->css_path . 'admin/general.css',
            $this->css_path . 'admin/versions/17_style.css',
            $this->css_path . 'admin/dropzone.css',
        ];

        $this->context->controller->addJS($jsFile);
        $this->context->controller->addCSS($cssFile, 'all');
    }

    /**
     * Admin module content
     *
     * @return string
     */
    public function getContent()
    {
        $this->loadAsset();

        $this->context->smarty->assign([
            'modulesFoldersPath' => _MODULE_DIR_,
            'imagePath' => $this->img_path,
            'languagesList' => \Language::getLanguages(),
            'moduleList' => array_reverse(\Module::getModulesInstalled()),
            'loadExportLink' => $this->context->link->getAdminLink('AdminExportTranslations', true, [], ['export_type' => 'load']) . '&module_name=',
            'emptyExportLink' => $this->context->link->getAdminLink('AdminExportTranslations', true, [], ['export_type' => 'empty']) . '&module_name=',
            'downloadTranslationsZip' => $this->context->link->getAdminLink('AdminDownloadZip') . '&module_name=',
            'postError' => Tools::getValue('error_controller', false),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    /**
     * Get module page configuration with errors if there is
     *
     * @param array $error
     *
     * @return string
     */
    public function getModulePageConfiguration(array $error = [])
    {
        $parameters = array_merge(
            ['configure' => $this->name],
            $error
        );

        return $this->context->link->getAdminLink(
            'AdminModules',
            true,
            [],
            $parameters
        );
    }

    /**
     * Install the module
     *
     * @return bool
     */
    public function install()
    {
        return parent::install();
    }

    /**
     * Uninstall the module
     *
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * setControllers
     *
     * @return void
     */
    public function setControllers()
    {
        $this->controllers = [
            self::AJAX_CONTROLLER_NAME,
            'AdminExportTranslations',
            'AdminDownloadZip',
        ];
    }

    /**
     * getControllers
     *
     * @return array
     */
    public function getControllers()
    {
        return $this->controllers;
    }
}
