{extends file='parent:frontend/listing/product-box/box-basic.tpl'}

{block name='frontend_listing_box_article_content'}

    <div class="box--content is--rounded">
        <a class="box-custom--info" href="{$sArticle.linkDetails}">
            <div class="info--wrapper">
            <span class="info--name">
                {$sArticle.articleName|escape}
            </span>
            <span class="info--price">
                {$sArticle.price|currency}
            </span>
            </div>
        </a>

        <div class="box-custom--image">
            {include file='parent:frontend/listing/product-box/product-image.tpl'}
        </div>
    </div>

{/block}