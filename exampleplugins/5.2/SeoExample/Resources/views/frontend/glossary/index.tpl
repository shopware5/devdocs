{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_content_left"}{/block}

{block name="frontend_index_content"}
    {foreach $words as $wordData}
        <div class="glossary--word-container">
            <span class="word-container--word">{$wordData['word']}</span>
            <div class="word-container--description">{$wordData['description']}</div>
        </div>
    {/foreach}
{/block}
