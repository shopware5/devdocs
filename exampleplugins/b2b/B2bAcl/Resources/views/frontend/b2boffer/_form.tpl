{namespace name=frontend/plugins/b2b_debtor_plugin}

<div class="content--inner">

    <h2 class="panel--title">
        {s name="MasterData"}Master data{/s}
    </h2>

    <div class="block-group b2b--form">
        <div class="block box--label is--full">
            {s name="OfferName"}Name{/s}: *
        </div>
        <div class="block box--input is--full">
            <input type="text" name="name" value="{$offer->name}" placeholder="{s name="OfferName"}Name{/s}">
        </div>
    </div>
    <div class="block-group b2b--form">
        <div class="block box--label is--full">
            {s name="OfferDescription"}Description{/s}:
        </div>
        <div class="block box--input is--full">
            <input type="text" name="description" value="{$offer->description}" placeholder="{s name="OfferDescription"}Description{/s}">
        </div>
    </div>

</div>

<div class="content--actions">

    <div class="block-group">
        <div class="block box--actions">
            <button class="btn is--primary" type="submit">{s name="Save"}Save{/s}</button>
        </div>
    </div>

</div>
