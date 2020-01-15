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

namespace PrestaShop\Module\PsTranslateYourModule\File;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ReadXlsxFile
{
    public $filePath;

    /**
     * __construct
     *
     * @param string $filePath
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->setFilePath($filePath);
    }

    /**
     * Get the file data and put them in an array
     *
     * @return array
     */
    public function getFileDataInArray()
    {
        $spreadsheet = IOFactory::load($this->getFilePath());

        return $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    }

    /**
     * setFilePath
     *
     * @param string $filePath
     *
     * @return void
     */
    protected function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * getFilePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}
