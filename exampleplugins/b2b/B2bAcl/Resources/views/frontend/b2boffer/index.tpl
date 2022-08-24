{extends file="frontend/_base/index.tpl"}

{namespace name=frontend/plugins/b2b_debtor_plugin}

{* B2b Account Main Content *}
{block name="frontend_index_content_b2b"}

    {* B2B Account Header *}
    {include file="frontend/b2bcontact/topbar.tpl"}

    <div class="b2b--ajax-panel" data-id="offer-grid" data-url="{url action=grid}" data-plugins="b2bGridComponent"></div>

    <div class="is--b2b-ajax-panel b2b--ajax-panel has--b2b-form" data-id="contact-profile"></div>

    <div class="b2b--ajax-panel b2b-modal-panel" data-id="offer-detail"></div>
{/block}
