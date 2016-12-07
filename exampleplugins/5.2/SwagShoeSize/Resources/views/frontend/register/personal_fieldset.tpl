{extends file="parent:frontend/register/personal_fieldset.tpl"}

{block name='frontend_register_personal_fieldset_input_lastname'}
    {$smarty.block.parent}

    <div class="register--shoesize">
        <input autocomplete="section-personal shoesize"
               name="register[personal][attribute][swagShoesize]"
               type="number"
               placeholder="Shoesize"
               id="shoesize"
               value="{$form_data.attribute.swagShoesize|escape}"
               class="register--field"
               min="10"
               max="70" />
    </div>
{/block}