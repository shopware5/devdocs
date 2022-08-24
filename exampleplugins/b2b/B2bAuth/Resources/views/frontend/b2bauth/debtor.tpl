{extends file="parent:frontend/index/index.tpl"}

{* Reset sidebar categories *}
{block name="frontend_index_content_left"}{/block}

{* B2B Top Navigation *}
{block name="frontend_index_content_top"}
{/block}

{* B2b Account Main Content *}
{block name="frontend_index_content"}

    <div class="panel">
        <h3 class="panel--title is--underline">Debtor (current Identity)</h3>
        <div class="panel--body is--wide">
            <pre>{$identity|@print_r}</pre>
        </div>
    </div>

    <div class="panel">
        <h3 class="panel--title is--underline">Context (Identity of the context owner - also the debtor)</h3>
        <div class="panel--body is--wide">
            <pre>{$contextOwner|@print_r}</pre>
        </div>
    </div>

{/block}
