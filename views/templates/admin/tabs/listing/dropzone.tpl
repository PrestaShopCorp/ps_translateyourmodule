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
<div id="drop_zone" class="zone-body">
    <div class="row">
        <div class="col-md-12">
            <form action="#" class="dropzone dz-clickable" id="importTranslationFile">
                <div class="loader"></div>
                <div class="import-start">
                    <i class="import-start-icon material-icons">cloud_upload</i><br>
                    <p class="import-start-main-text">
                        {l s='Drop xlsx file here or' mod='ps_translateyourmodule'} <a href="#" class="import-start-select-manual">{l s='select file' mod='ps_translateyourmodule'}</a>
                    </p>
                    <p class="import-start-footer-text">
                        {l s='Please upload one file at a time, .xlsx' mod='ps_translateyourmodule'}
                    </p>
                </div>
                <div class="import-failure">
                    <i class="import-failure-icon material-icons">error</i><br>
                    <p class="import-failure-msg">{l s='Oops... Upload failed.' mod='ps_translateyourmodule'}</p>
                    <a href="#" class="import-failure-details-action">{l s='What happened?' mod='ps_translateyourmodule'}</a>
                    <div class="import-failure-details">{l s='An error has occurred.' mod='ps_translateyourmodule'}</div>
                    <p>
                        <a class="import-failure-retry btn btn-tertiary" href="#">{l s='Try again' mod='ps_translateyourmodule'}</a>
                    </p>
                </div>
                <div class="import-success">
                    <i class="import-success-icon material-icons">done</i><br>
                    <p class="import-success-msg"></p>
                </div>
                <div class="dz-default dz-message"><span></span></div><input name="translationFile" type="file" class="dz-hidden-input" accept=".xlsx" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
            </form>
        </div>
    </div>
</div>
