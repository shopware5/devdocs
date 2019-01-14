{extends file="parent:frontend/index/shop-navigation.tpl"}

{block name='frontend_index_checkout_actions'}
    <li class="navigation--entry">
        <a href="#" class="btn starButton">
            <i class="icon--star"></i>
        </a>
    </li>
    {$smarty.block.parent}
{/block}


{* Menu (Off canvas left) trigger *}
{block name='frontend_index_offcanvas_left_trigger'}
    <li class="navigation--entry entry--menu-left" role="menuitem">
        <a class="entry--link entry--trigger btn is--icon-left" href="#offcanvas--left" data-offcanvas="true" data-offCanvasSelector=".sidebar-main">
            <i class="icon--menu"></i>
        </a>
    </li>
{/block}