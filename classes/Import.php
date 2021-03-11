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

use PrestaShop\Module\PsTranslateYourModule\File\WriteLanguageFile;
use PrestaShop\Module\PsTranslateYourModule\Translations\TranslationsCode;

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr)
    {
        foreach ($arr as $key => $unused) {
            return $key;
        }

        return null;
    }
}

class Import
{
    const TRANSLATION_LICENSE = "/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */\n";
    const TRANSLATION_FILE_BEGINS = "<?php\n" . self::TRANSLATION_LICENSE . "global \$_MODULE;\n\$_MODULE = [];\n";
    const CODE_BEGINS = "\n\$_MODULE['";
    const CODE_ENDS = '\'] = ';
    const SENTENCE_BEGINS = '\'';
    const SENTENCE_ENDS = '\';';

    protected $moduleName;
    protected $translationsArray;

    /**
     * __construct
     *
     * @param string $moduleName
     * @param array $translationsArray
     *
     * @return void
     */
    public function __construct($moduleName, array $translationsArray)
    {
        $this->setModuleName($moduleName);
        $this->setTranslationsArray($translationsArray);
    }

    /**
     * Imports translations from Translation's file
     *
     * @return void|array
     */
    public function importTranslations()
    {
        $languageTranslations = $this->prepareTranslationsForEachLanguage();
        $scriptToWriteInLanguageFile = $this->constructLanguagesForFiles($languageTranslations);
        $writeInFile = new WriteLanguageFile();
        $errors = [];

        foreach ($scriptToWriteInLanguageFile as $isoLang => $script) {
            $saveFile = $writeInFile->writeScript($this->getModuleName(), $isoLang, $script);

            // set errors if permission denied on write
            if (!$saveFile) {
                $errors[$isoLang] = 1;
            }
        }

        return $errors;
    }

    /**
     * Prepare an array for each languages with its own translations
     *
     * @return array
     */
    protected function prepareTranslationsForEachLanguage()
    {
        $translations = $this->getTranslationsArray();
        $arrayFirstKey = array_key_first($translations);
        $columns = range('C', 'Z');
        $languageTranslations = [];

        // we set the 2 first key with the filesnames (domain) and en translations (to get the translation code later)
        $languageTranslations['filesname'] = array_column($translations, 'A');
        $languageTranslations['en'] = array_column($translations, 'B');

        foreach ($columns as $column) {
            // prevent Undefined index on column
            if (!array_key_exists($column, $translations[$arrayFirstKey])) {
                break;
            }

            $isoLang = $translations[$arrayFirstKey][$column];

            if (null !== $isoLang) {
                $languageTranslations[$isoLang] = array_column($translations, $column);
            }
        }

        return $languageTranslations;
    }

    /**
     * Construct the script to write in each languages files
     *
     * @param array $translations
     *
     * @return array
     */
    protected function constructLanguagesForFiles($translations)
    {
        $moduleName = $this->getModuleName();
        $translationsToWriteInFile = [];
        $translationsCode = new TranslationsCode();

        foreach ($translations as $lang => $languageSentences) {
            // filesnames is only used to get the translationCode
            if ('filesname' === $lang) {
                continue;
            }

            $translationLines = [];
            $translationsToWriteInFile[$lang] = self::TRANSLATION_FILE_BEGINS;

            foreach ($languageSentences as $key => $sentence) {
                // the first key isn't used to be written (because data are => 'fr', 'es', 'module filename', ...)
                if (0 === $key) {
                    continue;
                }

                if (empty($sentence)) {
                    continue;
                }

                $sentenceCode = $translationsCode->getOneTranslationCode($translations['filesname'][$key], $translations['en'][$key], $moduleName);
                $translationLines[$sentenceCode] = $sentence;
            }

            ksort($translationLines);

            foreach ($translationLines as $sentenceCode => $sentence) {
                // replace : ' to \'   but not   \' to \\'
                $sentenceToWrite = preg_replace('/(?<!\\\\)\'/', '\\\'', stripcslashes($sentence));
                $translationsToWriteInFile[$lang] .= self::CODE_BEGINS . $sentenceCode . self::CODE_ENDS . self::SENTENCE_BEGINS . $sentenceToWrite . self::SENTENCE_ENDS;
            }
        }

        return $translationsToWriteInFile;
    }

    /**
     * setModuleName
     *
     * @param string $moduleName
     *
     * @return void
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * setFilePath
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * setTranslationsArray
     *
     * @param array $translationsArray
     *
     * @return void
     */
    public function setTranslationsArray($translationsArray)
    {
        $this->translationsArray = $translationsArray;
    }

    /**
     * getTranslationsArray
     *
     * @return array
     */
    public function getTranslationsArray()
    {
        return $this->translationsArray;
    }
}
