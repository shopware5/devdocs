{extends file="parent:frontend/listing/actions/action-sorting.tpl"}

{block name='frontend_listing_actions_sort_values'}
    <option value="random"{if $sSort eq "random"} selected="selected"{/if}>Random</option>
{/block}