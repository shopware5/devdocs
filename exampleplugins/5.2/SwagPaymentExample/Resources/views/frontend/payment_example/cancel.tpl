{extends file='frontend/index/index.tpl'}

{* Main content *}
{block name='frontend_index_content'}
    <div class="example-content content custom-page--content">
        <div class="example-content--actions">
            <a class="btn"
               href="{url controller=checkout action=cart}"
               title="change cart">change cart
            </a>
            <a class="btn is--primary right"
               href="{url controller=checkout action=shippingPayment sTarget=checkout}"
               title="change payment method">change payment method
            </a>
        </div>
    </div>
{/block}

{block name='frontend_index_actions'}{/block}
