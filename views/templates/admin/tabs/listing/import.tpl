{**
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
 *}

<div id="import_translation" class="panel panel-default col-lg-10 col-lg-offset-1 col-md-6 col-md-offset-0">
    <div class="panel-heading">
        <i class="material-icons">attach_file</i>{l s='Import translations' mod='ps_translateyourmodule'}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-2">
                <img class="vertical-align" src="{$imagePath}international.svg"/>
            </div>
            <div class="col-lg-4">
                <p>{l s='To import your translation file, the document must respect the following rules:' mod='ps_translateyourmodule'}</p>
                <ul>
                    <li><i class="material-icons">keyboard_arrow_right</i>{l s='The document name must be MODULE_TECHNICAL_NAME.xlsx' mod='ps_translateyourmodule'}</li>
                    <li><i class="material-icons">keyboard_arrow_right</i>{l s='Your Translations folder must have sufficient rights (writable / executable)' mod='ps_translateyourmodule'}</li>
                </ul>
            </div>
            <div class="col-lg-5 col-lg-offset-1">
                {include file="./dropzone.tpl"}
            </div>
        </div>
    </div>
</div>
