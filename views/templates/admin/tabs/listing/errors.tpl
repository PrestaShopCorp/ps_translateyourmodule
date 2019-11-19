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

{if false !== $postError}
<div class="col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0">
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {if 0 == $postError}
            {l s='Module name shouldn\'t be empty.' mod='ps_translateyourmodule'}
        {else if 1 == $postError}
            {l s='Not abled to create a ZIP with existing translations.' mod='ps_translateyourmodule'}
        {else if 2 == $postError}
            {l s='Not abled to export the translations.' mod='ps_translateyourmodule'}
        {else}
            {l s='An error occured' mod='ps_translateyourmodule'}
        {/if}
    </div>
</div>
{/if}