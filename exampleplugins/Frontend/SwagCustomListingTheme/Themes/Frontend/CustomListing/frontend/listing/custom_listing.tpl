{extends file='parent:frontend/listing/index.tpl'}

{* Wrap the content into a new element *}
{block name='frontend_index_content'}
    <div class="custom-listing">
        {$smarty.block.parent}
    </div>
{/block}

{* Exclude the sidebar *}
{block name='frontend_index_content_left'}
{/block}

{* Exclude the topseller *}
{block name='frontend_listing_index_topseller'}
{/block}

{* Overwrite the default CMS content *}
{block name='frontend_listing_index_text'}
    {if $sCategoryContent.cmsHeadline || $sCategoryContent.cmsText}
        <div class="custom-listing--cms">
            <h1 class="cms--headline">{$sCategoryContent.cmsheadline}</h1>
            <span class="cms--text">{$sCategoryContent.cmstext}</span>
        </div>
    {/if}
{/block}

{* Make the listing changes *}
{block name='frontend_listing_list_inline'}
    {$productBoxLayout = 'custom'}
    <div class="custom-listing--listing">
        {foreach $sArticles as $sArticle}
            {include file='frontend/listing/product-box/box-custom.tpl'}
        {/foreach}
    </div>
{/block}