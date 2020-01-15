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

class Zip
{
    const TRANSLATION_FILE_NAME_LENGTH = 6;

    private $archiveName;
    private $folderToZip;

    /**
     * __construct
     *
     * @param string $archiveName
     * @param string $folderToZip
     *
     * @return void
     */
    public function __construct($archiveName, $folderToZip)
    {
        $this->setArchiveName($archiveName);
        $this->setFolderToZip($folderToZip);
    }

    /**
     * Create a zip from a folder
     *
     * @return bool
     */
    public function createZip()
    {
        $archiveName = $this->getArchiveName();
        $folderToZip = $this->getFolderToZip();

        if (false === $this->folderHasTranslationsfiles()) {
            return false;
        }

        $zip = new \ZipArchive();

        //create the file and throw the error if unsuccessful
        if ($zip->open($archiveName, \ZIPARCHIVE::CREATE) !== true) {
            return false;
        }

        $filesInTranslationsFolder = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $folderToZip,
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        if (empty($filesInTranslationsFolder)) {
            return false;
        }

        foreach ($filesInTranslationsFolder as $file) {
            // Translation file must be 'iso.php' =>  fr.php, en.php, es.php ...
            if (self::TRANSLATION_FILE_NAME_LENGTH !== strlen($file->getFileName())) {
                continue;
            }

            $zip->addFile($file->getPathName(), $file->getFileName());
        }

        if (false === $zip->close()) {
            return false;
        }

        return true;
    }

    /**
     * Manage the header to get the ZIP from the URL
     *
     * @return void
     */
    public function downloadZip()
    {
        $archiveName = $this->getArchiveName();

        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename=' . $archiveName);
        header('Content-length: ' . filesize($archiveName));
        header('Pragma: no-cache');
        header('Expires: 0');

        readfile($archiveName);
    }

    /**
     * Check if the folder exists and if it has files other than '.', '..' and 'index.php'
     *
     * @return bool
     */
    public function folderHasTranslationsfiles()
    {
        $folderToZip = $this->getFolderToZip();

        if (!file_exists($folderToZip)) {
            return false;
        }

        $folderFiles = scandir($folderToZip);
        $removeFilesParasite = ['.', '..', 'index.php'];

        foreach ($folderFiles as $key => $value) {
            if (false !== array_search($value, $removeFilesParasite)) {
                unset($folderFiles[$key]);
            }
        }

        if (0 === count($folderFiles)) {
            return false;
        }

        return true;
    }

    /**
     * setArchiveName
     *
     * @param string $archiveName
     *
     * @return void
     */
    public function setArchiveName($archiveName)
    {
        $this->archiveName = $archiveName;
    }

    /**
     * getArchiveName
     *
     * @return string
     */
    public function getArchiveName()
    {
        return $this->archiveName;
    }

    /**
     * setFolderToZip
     *
     * @param string $folderToZip
     *
     * @return void
     */
    public function setFolderToZip($folderToZip)
    {
        $this->folderToZip = $folderToZip;
    }

    /**
     * getFolderToZip
     *
     * @return string
     */
    public function getFolderToZip()
    {
        return $this->folderToZip;
    }
}
