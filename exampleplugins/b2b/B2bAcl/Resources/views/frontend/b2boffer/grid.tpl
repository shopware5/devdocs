{namespace name=frontend/plugins/b2b_debtor_plugin}

<div class="panel has--border is--rounded">
    <div class="panel--title is--underline">

        <div class="block-group b2b--block-panel">
            <div class="block block--title">
                <h3>{s name="ManageOffers"}Manage Offers{/s}</h3>
            </div>
            <div class="block block--actions">

                <button type="button" data-target="offer-detail" data-href="{url action=new}" class="btn ajax-panel-link component-action-create {b2b_acl controller=b2boffer action=new}">
                    {s name="CreateOffer"}Create Offer{/s}
                </button>

            </div>
        </div>
    </div>
    <div class="panel--body is--wide">

        {include file="frontend/_grid/offer-grid.tpl"}

    </div>
</div>