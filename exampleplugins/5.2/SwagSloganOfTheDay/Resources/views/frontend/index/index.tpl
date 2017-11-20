{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_navigation_categories_top_include"}

    <style>
        .slogan-box {
            width:100%;
            text-align:center;
        }
        .slogan {
            {if $italic}font-style:italic;{/if}
            font-size:{$fontSize}px;
        }
    </style>


    <div class="slogan-box">
        <span class="slogan">{$slogan}</span>
    </div>
    {$smarty.block.parent}
{/block}
