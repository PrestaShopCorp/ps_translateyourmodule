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

class CheckFile
{
    const TRANSLATION_FILE_TYPE = '.php';
    const INITIAL_LANGUAGE = 'en';
    const INDEX_FILE_NAME = 'index';

    /**
     * Check if the file is correct.
     * It must be a translations file having for name format 'en.php'
     *
     * @param \SplFileInfo $file
     * @param string $fileName
     *
     * @return bool
     */
    public function isAllowedTranslationFile($file, $fileName)
    {
        // Extension must be 'php'
        if (self::TRANSLATION_FILE_TYPE !== substr($file, -4)) {
            return false;
        }

        // Default language is EN, we don't need it
        if (self::INITIAL_LANGUAGE === $fileName) {
            return false;
        }

        // We don't take index file
        if (self::INDEX_FILE_NAME === $fileName) {
            return false;
        }

        return true;
    }
}
