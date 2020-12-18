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
use PrestaShop\Module\PsTranslateYourModule\Zip;

class AdminDownloadZipController extends ModuleAdminController
{
    const SANDBOX_PATH = _PS_CACHE_DIR_ . 'sandbox/';

    /**
     * Construct initHeader
     * Redirect to module page configuration if error
     *
     * @return void
     */
    public function initHeader()
    {
        $moduleName = Tools::getValue('module_name');

        if (!$moduleName) {
            Tools::Redirect($this->module->getModulePageConfiguration(
                ['error_controller' => ps_translateyourmodule::FORM_ERROR_CODES['modulename']]
            ));
        }

        $archiveName = $moduleName . '_translations_' . date('ymdhis') . '.zip';
        $folderToZip = _PS_MODULE_DIR_ . $moduleName . '/translations/';

        $getZip = new Zip($archiveName, $folderToZip);

        if (false === $getZip->createZip()) {
            Tools::Redirect($this->module->getModulePageConfiguration(
                ['error_controller' => ps_translateyourmodule::FORM_ERROR_CODES['ziperror']]
            ));
        }

        $getZip->downloadZip();

        exit();
    }
}
