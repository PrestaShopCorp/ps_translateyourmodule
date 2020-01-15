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
use PrestaShop\Module\PsTranslateYourModule\File\MoveFile;
use PrestaShop\Module\PsTranslateYourModule\File\ReadXlsxFile;
use PrestaShop\Module\PsTranslateYourModule\Import;
use PrestaShop\Module\PsTranslateYourModule\Validate\ValidateFile;

class AdminAjaxPsTranslateYourModuleController extends ModuleAdminController
{
    /**
     * Give the link to the Module's Translation page
     * Need a module name and a lang
     *
     * @return void
     */
    public function ajaxProcessGetModuleTranslationLink()
    {
        $moduleName = Tools::getValue('module', $this->module->name);
        $lang = Tools::getValue('lang', ps_translateyourmodule::DEFAULT_LANGUAGE_ISO);

        $urlParams = [
            'type' => 'modules',
            'lang' => $lang,
            'module' => $moduleName,
        ];

        echo $this->context->link->getLegacyAdminLink('AdminTranslations', true, $urlParams);
    }

    /**
     * Get the XLSX uploaded file by the merchant
     *
     * @return void
     */
    public function ajaxProcessUploadTranslation()
    {
        $uploadedFile = Tools::fileAttachment('file');
        $validateFile = new ValidateFile();
        $moduleName = str_replace(ps_translateyourmodule::EXPECTED_EXTENSION, '', $uploadedFile['name']);

        if (false === $validateFile->validateMimeType($uploadedFile['mime'], ps_translateyourmodule::MIME_TYPE_EXPECTED_XLSX)) {
            throw new PrestaShopException('Mimetype is not valid');
        }

        if (empty($uploadedFile)) {
            throw new PrestaShopException('Uploaded file can\'t be empty');
        }

        if (false === $validateFile->validateModuleName($moduleName)) {
            throw new PrestaShopException('Module doesn\'t exist');
        }

        $tmpFilePath = (new MoveFile($uploadedFile))->moveInPrestaShopSandbox();

        if (false === $tmpFilePath) {
            throw new PrestaShopException('Unabled to move file into sandbox');
        }

        $translations = (new ReadXlsxFile($tmpFilePath))->getFileDataInArray();
        $importErrors = (new Import($moduleName, $translations))->importTranslations();
        $ajaxStateReturned = empty($importErrors) ? 1 : 0;

        $this->ajaxDie(
            json_encode([
                'state' => $ajaxStateReturned,
                'errors' => $importErrors,
            ])
        );
    }
}
