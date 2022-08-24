{extends file="parent:frontend/index/index.tpl"}

{* Reset sidebar categories *}
{block name="frontend_index_content_left"}{/block}

{* B2B Top Navigation *}
{block name="frontend_index_content_top"}
{/block}

{* B2b Account Main Content *}
{block name="frontend_index_content"}
    <div class="panel">
        <h3 class="panel--title is--underline">Be someone else</h3>
        <div class="panel--body is--wide">
            <a href="{url action=debtor}" class="btn">Be a debtor</a>
            <a href="{url action=contact}" class="btn">Be a contact</a>
        </div>
    </div>
{/block}