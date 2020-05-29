<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\PsTranslateYourModule\Translations;

/**
 * Inheritance TranslateCore.
 * Avoids using the latest translation of a sentence in another language if it does not exist in this language
 *
 * @since 1.5.0
 */
class TranslateInheritance
{
    /**
     * Get a translation for a module.
     *
     * @param string|\Module $module
     * @param string $originalString
     * @param string $source
     * @param null $sprintf
     * @param bool $js
     * @param string|null $locale
     * @param bool $fallback [default=true] If true, this method falls back to the new translation system if no translation is found
     *
     * @return mixed|string
     */
    public static function getModuleTranslation(
        $module,
        $originalString,
        $source,
        $sprintf = null,
        $js = false,
        $locale = null,
        $fallback = true,
        $escape = true
    ) {
        global $_MODULE, $_LANGADM;

        static $langCache = [];

        $name = $module->name;

        if (null !== $locale) {
            $iso = \Language::getIsoByLocale($locale);
        }

        if (empty($iso)) {
            $iso = \Context::getContext()->language->iso_code;
        }

        $filesByPriority = [
            // PrestaShop 1.5 translations
            _PS_MODULE_DIR_ . $name . '/translations/' . $iso . '.php',
            // PrestaShop 1.4 translations
            _PS_MODULE_DIR_ . $name . '/' . $iso . '.php',
            // Translations in theme
            _PS_THEME_DIR_ . 'modules/' . $name . '/translations/' . $iso . '.php',
            _PS_THEME_DIR_ . 'modules/' . $name . '/' . $iso . '.php',
        ];
        foreach ($filesByPriority as $file) {
            if (file_exists($file)) {
                require $file;
            }
        }

        $string = preg_replace("/\\\*'/", "\'", $originalString);
        $key = md5($string);

        $cacheKey = $name . '|' . $string . '|' . $source . '|' . (int) $js . '|' . $iso;
        if (isset($langCache[$cacheKey])) {
            $ret = $langCache[$cacheKey];
        } else {
            $currentKey = strtolower('<{' . $name . '}' . _THEME_NAME_ . '>' . $source) . '_' . $key;
            $defaultKey = strtolower('<{' . $name . '}prestashop>' . $source) . '_' . $key;

            if ('controller' == substr($source, -10, 10)) {
                $file = substr($source, 0, -10);
                $currentKeyFile = strtolower('<{' . $name . '}' . _THEME_NAME_ . '>' . $file) . '_' . $key;
                $defaultKeyFile = strtolower('<{' . $name . '}prestashop>' . $file) . '_' . $key;
            }

            if (isset($currentKeyFile) && !empty($_MODULE[$currentKeyFile])) {
                $ret = stripslashes($_MODULE[$currentKeyFile]);
            } elseif (isset($defaultKeyFile) && !empty($_MODULE[$defaultKeyFile])) {
                $ret = stripslashes($_MODULE[$defaultKeyFile]);
            } elseif (!empty($_MODULE[$currentKey])) {
                $ret = stripslashes($_MODULE[$currentKey]);
            } elseif (!empty($_MODULE[$defaultKey])) {
                $ret = stripslashes($_MODULE[$defaultKey]);
            } elseif (!empty($_LANGADM)) {
                // if translation was not found in module, look for it in AdminController or Helpers
                $ret = stripslashes(\Translate::getGenericAdminTranslation($string, $key, $_LANGADM));
            } else {
                $ret = '';
            }

            if (
                $sprintf !== null
                && (!is_array($sprintf) || !empty($sprintf))
                && !(count($sprintf) === 1 && isset($sprintf['legacy']))
            ) {
                $ret = \Translate::checkAndReplaceArgs($ret, $sprintf);
            }

            if ($js) {
                $ret = addslashes($ret);
            } elseif ($escape) {
                $ret = htmlspecialchars($ret, ENT_COMPAT, 'UTF-8');
            }

            if ($sprintf === null) {
                $langCache[$cacheKey] = $ret;
            }
        }

        if (!is_array($sprintf) && null !== $sprintf) {
            $sprintf_for_trans = [$sprintf];
        } elseif (null === $sprintf) {
            $sprintf_for_trans = [];
        } else {
            $sprintf_for_trans = $sprintf;
        }

        /*
         * Native modules working on both 1.6 & 1.7 are translated in messages.xlf
         * So we need to check in the Symfony catalog for translations
         */
        if ($ret === $originalString && $fallback) {
            $ret = \Context::getContext()->getTranslator()->trans($originalString, $sprintf_for_trans, null, $locale);
        }

        return $ret;
    }
}
