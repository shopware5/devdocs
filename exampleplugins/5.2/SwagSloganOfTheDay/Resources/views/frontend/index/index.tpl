{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_navigation_categories_top_include"}

    <style>
        .slogan-box {
            width:100%;
            text-align:center;
        }
        .slogan {
            {if $swagSloganItalic}font-style:italic;{/if}
            font-size:{$swagSloganFontSize}px;
        }
    </style>


    <div class="slogan-box">
        <span class="slogan">{$swagSloganContent}</span>
    </div>
    {$smarty.block.parent}
{/block}
