{extends file="parent:frontend/index/index.tpl"}

{* Hide the left navigation bar*}
{block name='frontend_index_content_left'}
{/block}

{block name='frontend_index_content'}
    <div class="content content--home">


        <form>
            <select name="store" id="storeSelector" onchange="this.form.submit()">
                {foreach from=$stores item=store}
                    <option value="{$store->getId()}" {if $currentStore eq $store->getId()}selected="selected"{/if}>{$store->getName()}</option>
                {/foreach}
            </select>
        </form>

        <br>
        <div class="content--emotions">
            {action controller=Emotion module=Widgets emotionId={$storeEmotionId} currentStore={$currentStore}}
        </div>
    </div>
{/block}