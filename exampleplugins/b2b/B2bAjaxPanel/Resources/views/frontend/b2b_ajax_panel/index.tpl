{extends file="parent:frontend/index/index.tpl"}

{* Reset sidebar categories *}
{block name="frontend_index_content_left"}{/block}

{* B2b Account Main Content *}
{block name="frontend_index_content"}

    <h1>Ajax Panel Demo</h1>

    <div class="panel has--border is--rounded">
        <div class="panel--title is--underline">
            <div class="b2b--ajax-panel" data-id="nav-panel" data-url="{url action=nav}"></div>

        </div>
        <div class="panel--body is--wide">
            <div class="b2b--ajax-panel" data-id="demo-panel" data-url="{url action=sub}"></div>
        </div>
    </div>
{/block}
