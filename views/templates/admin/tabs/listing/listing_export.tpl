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

<div id="module_list" class="panel panel-default col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0">
    <div class="panel-heading">
        <i class="material-icons">get_app</i>{l s='Export translations' mod='ps_translateyourmodule'}
    </div>
    <div class="panel-body">
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">{l s='Module name' mod='ps_translateyourmodule'}</th>
                        <th>{l s='Version' mod='ps_translateyourmodule'}</th>
                        <th class="table_select">{l s='See translations for' mod='ps_translateyourmodule'}</th>
                        <th colspan="2">{l s='Export translation XLSX' mod='ps_translateyourmodule'}</th>
                        <th>{l s='Export translation\'s folder' mod='ps_translateyourmodule'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$moduleList item=module key=key}
                        <tr>
                            <td><img src="{$modulesFoldersPath}{$module.name}/logo.png" class="card-img-top" style="width: 45px;"></td>
                            <td>{$module.name}</td>
                            <td>{$module.version}</td>
                            <td>
                                <select name="see_translations" data-module="{$module.name}">
                                    {foreach from=$languagesList item=language key=key}
                                        <option value="{$language.iso_code}">{$language.name}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><a class="btn btn-secondary export" href="{$emptyExportLink}{$module.name}">{l s='Without translations' mod='ps_translateyourmodule'}</a></td>
                            <td><a class="btn btn-secondary export" href="{$loadExportLink}{$module.name}">{l s='With translations' mod='ps_translateyourmodule'}</a></td>
                            <td><a class="btn btn-secondary export" href="{$downloadTranslationsZip}{$module.name}">{l s='ZIP' mod='ps_translateyourmodule'}</a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
