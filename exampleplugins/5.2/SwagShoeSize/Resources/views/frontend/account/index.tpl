{extends file="parent:frontend/account/index.tpl"}

{block name="frontend_account_index_info_content"}
    {$smarty.block.parent}

    {if not empty($sUserData.additional.user.swag_shoesize)}
        <div class="panel--body is--wide">
            Shoesize: {$sUserData.additional.user.swag_shoesize}
        </div>
    {/if}
{/block}

