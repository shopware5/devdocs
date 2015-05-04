{extends file='parent:frontend/detail/tabs.tpl'}

{block name="frontend_detail_tabs_rating" append}
    {if $swagArticleTabConfiguration}
        {foreach $swagArticleTabConfiguration as $tab}
            {if $tab.content}
                <a href="#" class="tab--link" title="123">
                    {$tab.headline}
                </a>
            {/if}
        {/foreach}
    {/if}
{/block}

{block name="frontend_detail_tabs_content_rating" append}
    {if $swagArticleTabConfiguration}
        {foreach from=$swagArticleTabConfiguration key=key item=tab}
            {if $tab.content}
                <div class="tab--container">
                    <div class="tab--content" style="padding:30px;">
                        {$tab.content}
                    </div>
                </div>
            {/if}
        {/foreach}
    {/if}
{/block}