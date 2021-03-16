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

class TranslationsCode
{
    /**
     * Get all the translations codes
     *
     * @param array $translations
     * @param string $moduleName
     *
     * @return array
     */
    public function getAllTranslationsCodes($translations, $moduleName)
    {
        foreach ($translations as $file => $matches) {
            foreach ($matches['matches'] as $id => $sentence) {
                $translations[$file]['codes'][$id] = $this->getOneTranslationCode($file, $sentence, $moduleName);
            }
        }

        return $translations;
    }

    /**
     * Get only one translation code
     *
     * @param string $domain
     * @param string $sentence
     * @param string $moduleName
     *
     * @return string
     */
    public function getOneTranslationCode($domain, $sentence, $moduleName)
    {
        $sentence = stripcslashes($sentence);
        $translationCode = '<{' . $moduleName . '}prestashop>' . strtolower($domain) . '_' . $this->getTranslationMd5($sentence);

        return $translationCode;
    }

    /**
     * Escape then generate md5 from string
     *
     * @param string $sentence
     *
     * @return string
     */
    public function getTranslationMd5($sentence)
    {
        return md5(preg_replace("/\\\*'/", "\'", $sentence));
    }
}
