
{block name="frontend_swag_plugin_system_detail_link"}
    <a href="{url controller=cat sCategory=$seoCategory->getId() sPage=1}"
       rel="nofollow"
       class="action--link">

        {block name="frontend_swag_plugin_system_detail_link_icon"}
            <i class="icon--comment"></i>
        {/block}

        {block name="frontend_swag_plugin_system_detail_link_text"}
            {$seoCategory->getName()}
        {/block}
    </a>
{/block}
