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

namespace PrestaShop\Module\PsTranslateYourModule\Translations;

use PrestaShop\Module\PsTranslateYourModule\File\CheckFile;

class GetTranslations
{
    const REGEX_TPL = "/{l s=['\"]((?=\S)[^}]+)['\"] mod='[a-z_]+'.*}/U";
    const REGEX_CLASS = "/this->l\(\s*[\"'](.+?)[\"'](?:,\s*'(.+?)'\s*)?\)/m";
    const REGEX_ADMIN_CLASS = "/this->module->l\(\s*[\"'](.+?)[\"'](?:,\s*'(.+?)'\s*)?\)/m";

    protected $moduleName;
    protected $modulePath;
    protected $moduleTranslationsPath;
    /** @var TranslationsCode */
    protected $translationsCode;

    /**
     * __construct
     *
     * @param string $moduleName
     *
     * @return void
     */
    public function __construct($moduleName)
    {
        $this->setModuleName($moduleName);
        $this->setModulePath();
        $this->setModuleTranslationsPath();

        $this->translationsCode = new TranslationsCode();
    }

    /**
     * Get all the translations files
     *
     * @return array
     */
    public function findTranslations()
    {
        $translations = [];
        $moduleName = $this->getModuleName();
        $allFiles = $this->getFilesFromDir($this->getModulePath());

        foreach ($allFiles as $file) {
            if (substr($file, -4) !== '.tpl' && substr($file, -4) !== '.php') {
                continue;
            }

            $filename = $this->getFileName($file);
            $fileContent = file_get_contents($file);

            preg_match_all(self::REGEX_TPL, $fileContent, $matchesTpl);
            preg_match_all(self::REGEX_CLASS, $fileContent, $matchesClass);
            preg_match_all(self::REGEX_ADMIN_CLASS, $fileContent, $matchesAdminClass);

            $searchDomainInSentence = '\', \'' . $filename;

            if (!empty($matchesTpl[1])) {
                $sentence = array_unique($matchesTpl[1]);
                $translations[$filename]['matches'] = str_ireplace($searchDomainInSentence, '', $sentence);
            }
            if (!empty($matchesClass[1])) {
                $sentence = array_unique($matchesClass[1]);
                $translations[$filename]['matches'] = str_ireplace($searchDomainInSentence, '', $sentence);
            }
            if (!empty($matchesAdminClass[1])) {
                $sentence = array_unique($matchesAdminClass[1]);
                $translations[$filename]['matches'] = str_ireplace($searchDomainInSentence, '', $sentence);
            }
        }

        return [
            'module_name' => $moduleName,
            'translations' => $this->translationsCode->getAllTranslationsCodes($translations, $moduleName),
        ];
    }

    /**
     * Get the existing translations for each existing translations files
     *
     *  @param array $translations
     *
     * @return array
     */
    public function fillWithExisting($translations)
    {
        $translate = new TranslateInheritance();
        $checkFile = new CheckFile();

        $moduleInstance = \Module::getInstanceByName($this->getModuleName());
        $allFiles = $this->getFilesFromDir($this->getModuleTranslationsPath());
        $translations['languages'] = [];

        foreach ($allFiles as $file) {
            $fileName = $this->getFileName($file);

            if (false === $checkFile->isAllowedTranslationFile($file, $fileName)) {
                continue;
            }

            $isoLang = substr($fileName, 0, 2);
            $translations['languages'][] = $isoLang;

            foreach ($translations['translations'] as &$m) {
                $m['languages'][$isoLang] = [];
                foreach ($m['matches'] as $id => $sentence) {
                    $locale = \Language::getLocaleByIso($isoLang);

                    if (false === $locale) {
                        continue;
                    }

                    $sentence = stripcslashes($sentence);

                    // get the sentence translation
                    $m['languages'][$isoLang][] = $translate->getModuleTranslation(
                        $moduleInstance,
                        $sentence,
                        $this->getDomain($sentence, $m['codes'][$id]),
                        null,
                        false,
                        $locale,
                        true
                    );
                }
            }
        }

        return $translations;
    }

    /**
     * Get the translation domain from the Code
     *
     * @param string $code
     *
     * @return string
     */
    private function getDomain($sentence, $code)
    {
        $moduleName = $this->getModuleName();
        $removeString = [
            '<{' . $moduleName . '}prestashop>',
            '_' . $this->translationsCode->getTranslationMd5($sentence),
        ];

        return str_replace($removeString, '', $code);
    }

    /**
     * Get recursively files from a given directory
     *
     * @param string $directory
     *
     * @return \RecursiveIteratorIterator
     */
    public function getFilesFromDir($directory)
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $directory,
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
     * Get File Name
     *
     * @param \SplFileInfo $fileInfo
     *
     * @return string
     */
    public function getFileName(\SplFileInfo $fileInfo)
    {
        $path_parts = pathinfo($fileInfo);

        return $path_parts['filename'];
    }

    /**
     * setModuleName
     *
     * @return void
     */
    protected function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * getModuleName
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * setModulePath
     */
    private function setModulePath()
    {
        $this->modulePath = _PS_MODULE_DIR_ . $this->getModuleName() . '/';
    }

    /**
     * getModulePath
     *
     * @return string
     */
    public function getModulePath()
    {
        return $this->modulePath;
    }

    /**
     * setModuleTranslationsPath
     */
    protected function setModuleTranslationsPath()
    {
        $moduleTranslationsPath = $this->getModulePath() . 'translations/';

        $this->moduleTranslationsPath = $moduleTranslationsPath;
    }

    /**
     * getModuleTranslationsPath
     *
     * @return string $moduleName
     */
    public function getModuleTranslationsPath()
    {
        return $this->moduleTranslationsPath;
    }
}
