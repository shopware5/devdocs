{block name="frontend_detail_swag_custom_products_options_customtype"}
    <input class="wizard--input" type="text" name="custom-option-id--{$option['id']}"
           id="custom-products-option-{$key}"
           data-field="true"
        {if $option['required']}
           data-validate="true"
           data-validate-message="{s name='detail/validate/textfield'}{/s}"
        {/if}/>
{/block}
