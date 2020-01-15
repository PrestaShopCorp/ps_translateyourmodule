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

namespace PrestaShop\Module\PsTranslateYourModule;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export
{
    protected $fileName;
    protected $moduleTranslations;
    protected $exportType;

    /**
     * __construct
     *
     * @param string $moduleName
     * @param array $translations
     * @param string $exportType
     *
     * @return void
     */
    public function __construct($moduleName, array $translations, $exportType)
    {
        $this->setFileName($moduleName);
        $this->setModuleTranslations($translations);
        $this->setExportType($exportType);
    }

    /**
     * Export a XLSX file
     *
     * @param array $languages
     *
     * @return void
     */
    public function xlsx(array $languages)
    {
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet
        $writer = new Xlsx($spreadsheet); // instantiate Xlsx
        $sheet = $spreadsheet->getActiveSheet();

        $this->setData($sheet, $languages);
        $this->saveFile($writer, '.xlsx');
    }

    /**
     * Set the data in the Spreadsheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $languages
     *
     * @return void
     */
    private function setData(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, array $languages)
    {
        $this->setHeader($sheet, $languages);
        $this->setBody($sheet, $languages);
    }

    /**
     * Set the Header (2lines) in the Spreadsheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $languages
     *
     * @return void
     */
    private function setHeader(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, array $languages)
    {
        $totalLanguages = count($languages);

        $sheet->setCellValue('A1', 'Module filename');
        $sheet->setCellValue('B1', 'From module');

        // If multiple languages in $languages
        if ($totalLanguages > 0) {
            $cellAlphabetForLaguages = range('C', 'Z');

            foreach ($languages as $key => $lang) {
                // We set the line data
                $sheet->setCellValue($cellAlphabetForLaguages[$key] . '1', $lang);
            }
        }
    }

    /**
     * Set the body in the Spreadsheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $languages
     *
     * @return void
     */
    private function setBody(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, array $languages)
    {
        $translations = $this->getModuleTranslations();
        $totalLanguages = count($languages);
        $cellAlphabetForLanguages = range('C', 'Z');

        // As header takes 1 line, the offsets begin at line 2
        $lineOffset = 2;
        $mainLineOffset = 2;

        foreach ($translations as $domainName => $domainTranslations) {
            // No translations
            foreach ($domainTranslations['matches'] as $value) {
                $sheet->setCellValue('A' . $mainLineOffset, $domainName);
                $sheet->setCellValue('B' . $mainLineOffset, $value);
                ++$mainLineOffset;
            }

            // If translations already exist
            if ($totalLanguages > 0) {
                $langColIndex = 0;
                $initialLineOffset = $lineOffset;

                foreach ($domainTranslations['languages'] as $value) {
                    foreach ($value as $sentence) {
                        // We set the line data
                        $sheet->setCellValue($cellAlphabetForLanguages[$langColIndex] . $lineOffset, $sentence);
                        ++$lineOffset;
                    }
                    // We change the language column
                    ++$langColIndex;

                    // If there is another language we set the lineOffset to the initialLineOffset.
                    if ($langColIndex < $totalLanguages) {
                        $lineOffset = $initialLineOffset;
                    }
                }
            }
        }
    }

    /**
     * Save File by exporting its output
     *
     * @param mixed $writer (can be from Xlsx)
     * @param string $fileType
     *
     * @return void
     */
    private function saveFile($writer, $fileType)
    {
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $this->getFileName() . $fileType . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');	// download file
    }

    /**
     * setFileName
     *
     * @param string $moduleName
     *
     * @return void
     */
    protected function setFileName($moduleName)
    {
        $this->fileName = $moduleName;
    }

    /**
     * getFileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * setModuleTranslations
     *
     * @param array $moduleTranslations
     *
     * @return void
     */
    protected function setModuleTranslations($moduleTranslations)
    {
        $this->moduleTranslations = $moduleTranslations;
    }

    /**
     * getModuleTranslations
     *
     * @return array
     */
    public function getModuleTranslations()
    {
        return $this->moduleTranslations;
    }

    /**
     * setExportType
     *
     * @param string $exportType
     *
     * @return void
     */
    protected function setExportType($exportType)
    {
        if ('load' !== $exportType && 'empty' !== $exportType) {
            $exportType = 'empty';
        }

        $this->exportType = $exportType;
    }

    /**
     * getExportType
     *
     * @return string
     */
    public function getExportType()
    {
        return $this->exportType;
    }
}
