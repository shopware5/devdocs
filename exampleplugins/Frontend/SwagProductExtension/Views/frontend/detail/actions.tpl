{extends file="parent:frontend/detail/actions.tpl"}

{block name='frontend_detail_actions_voucher' append}
    {if $sArticle.attributes.swag_seo_category}
        {$swagSeoAttribute = $sArticle.attributes.swag_seo_category}
        {include file="frontend/swag_product_extension/detail-link.tpl" seoCategory=$swagSeoAttribute->get('category')}
    {/if}
{/block}