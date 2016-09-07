{extends file="parent:frontend/detail/actions.tpl"}

{block name='frontend_detail_actions_voucher' append}
    {if $sArticle.attributes.swag_plugin_system}
        {$swagSeoAttribute = $sArticle.attributes.swag_plugin_system}
        {include file="frontend/swag_plugin_system/detail-link.tpl" seoCategory=$swagSeoAttribute->get('category')}
    {/if}
{/block}