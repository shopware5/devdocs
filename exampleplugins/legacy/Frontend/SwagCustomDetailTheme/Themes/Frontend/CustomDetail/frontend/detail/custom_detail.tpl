{extends file='parent:frontend/detail/index.tpl'}

{block name='frontend_index_content'}
    <div class="custom-detail">
        {$smarty.block.parent}
    </div>
{/block}

{block name='frontend_detail_index_buy_container'}
{/block}

{block name='frontend_detail_index_header_inner'}
    <h1 class="custom-detail--title">
        {$sArticle.articleName}
    </h1>

    {if $sArticle.description}
        <div class="custom-detail--claim">
            {$sArticle.description}
        </div>
    {/if}
{/block}

{block name="frontend_detail_index_detail"}
    <div class="custom-detail--description">
        {$sArticle.description_long|truncate:450}
    </div>

    <div class="custom-detail--actions">
        <a class="link--notepad btn is--primary is--large is--icon-left"
           href="{url controller='note' action='add' ordernumber=$sArticle.ordernumber}"
           data-ajaxUrl="{url controller='note' action='ajaxAdd' ordernumber=$sArticle.ordernumber}"
           title="{"{s name='DetailLinkNotepad' namespace="frontend/detail/actions"}{/s}"|escape}">
            <i class="icon--heart"></i>
            <span class="action--text">
                {s name="DetailLinkNotepadShort" namespace="frontend/detail/actions"}{/s}
            </span>
        </a>
        <a class="btn is--large"
           href="{url controller='listing' action='manufacturer' sSupplier=$sArticle.supplierID}"
           title="{"{s name="DetailDescriptionLinkInformation" namespace="frontend/detail/description"}{/s}"|escape}">
            {s name="DetailDescriptionLinkInformation" namespace="frontend/detail/description"}{/s}
        </a>
    </div>
{/block}