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
use PrestaShop\Module\PsTranslateYourModule\Export;
use PrestaShop\Module\PsTranslateYourModule\Translations\GetTranslations;

class AdminExportTranslationsController extends ModuleAdminController
{
    /**
     * Construct initHeader
     * Redirect to module page configuration if error
     *
     * @return void
     */
    public function initHeader()
    {
        $moduleName = Tools::getValue('module_name', '');
        $exportType = Tools::getValue('export_type', 'empty');

        if (empty($moduleName)) {
            Tools::Redirect($this->module->getModulePageConfiguration(
                ['error_controller' => ps_translateyourmodule::FORM_ERROR_CODES['modulename']]
            ));
        }

        $moduleTranslations = new GetTranslations($moduleName);
        $moduleStringsToTranslate = $moduleTranslations->findTranslations();

        if (false == is_array($moduleStringsToTranslate)) {
            Tools::Redirect($this->module->getModulePageConfiguration(
                ['error_controller' => ps_translateyourmodule::FORM_ERROR_CODES['translation']]
            ));
        }

        $languages = [];
        $formatedTranslatedArray = $this->formatTranslationsArrayForExport($moduleStringsToTranslate);

        // Add translations text if we want to load existing translations
        if ('load' === $exportType) {
            $allTranslationsSentences = $moduleTranslations->fillWithExisting($moduleStringsToTranslate);
            $formatedTranslatedArray = $this->formatTranslationsArrayForExport($allTranslationsSentences);
            $languages = $allTranslationsSentences['languages'];
        }

        if (false === $formatedTranslatedArray) {
            Tools::Redirect($this->module->getModulePageConfiguration(
                ['error_controller' => ps_translateyourmodule::FORM_ERROR_CODES['translation']]
            ));
        }

        (new Export($moduleName, $formatedTranslatedArray, $exportType))->xlsx($languages);

        exit();
    }

    /**
     * Format the Translation array
     *
     * @param array $translations
     *
     * @return array|false
     */
    public function formatTranslationsArrayForExport(array $translations)
    {
        if (!is_array($translations['translations']) || empty($translations['translations'])) {
            return false;
        }

        $formatedArray = [];

        foreach ($translations['translations'] as $fileName => $value) {
            $formatedArray[$fileName]['matches'] = $value['matches'];

            if (!empty($value['languages'])) {
                $formatedArray[$fileName]['languages'] = $value['languages'];
            }
        }

        return $formatedArray;
    }
}
