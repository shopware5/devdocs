{extends file="frontend/index/index.tpl"}

{block name="frontend_index_content"}
    <div id="payment">
        <iframe src="{$gatewayUrl}"
                scrolling="yes"
                style="x-overflow: none;"
                frameborder="0">
        </iframe>
    </div>
{/block}
