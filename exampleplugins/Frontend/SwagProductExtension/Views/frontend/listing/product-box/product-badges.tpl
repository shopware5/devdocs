{extends file="parent:frontend/listing/product-box/product-badges.tpl"}

{block name="frontend_listing_box_article_new" append}
    {if $sArticle.attributes.swag_seo_category}
        {$swagSeoAttribute = $sArticle.attributes.swag_seo_category}

        {include file="frontend/swag_product_extension/listing-badge.tpl" seoCategory=$swagSeoAttribute->get('category')}
    {/if}
{/block}