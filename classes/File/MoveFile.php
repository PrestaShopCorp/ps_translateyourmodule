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

class MoveFile
{
    const SANDBOX_PATH = _PS_CACHE_DIR_ . 'sandbox/';

    protected $filePath;
    protected $fileName;

    /**
     * __construct
     *
     * @param array $file
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->setFilePath($file);
        $this->setFileName($file);
    }

    /**
     * Move file into PrestaShop sandbox's folder and return the path
     *
     * @return string|false
     */
    public function moveInPrestaShopSandbox()
    {
        if (!move_uploaded_file(
            $this->getFilePath(),
            self::SANDBOX_PATH . $this->getFileName()
        )) {
            return false;
        }

        return self::SANDBOX_PATH . $this->getFileName();
    }

    /**
     * setFilePath
     *
     * @param array $file
     *
     * @return void
     */
    public function setFilePath($file)
    {
        $this->filePath = $file['tmp_name'];
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

    /**
     * setFileName
     *
     * @param array $file
     *
     * @return void
     */
    public function setFileName($file)
    {
        $this->fileName = $file['rename'];
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
}
