{* Extend the base template to get the header, navbar etc *}
{extends file="parent:frontend/index/index.tpl"}

{* Overwrite the main content section to add some custom content*}
{block name="frontend_index_content"}
    <h1>Hello World</h1>
    <h2>Number of the day: {$someNumber}</h2>
{/block}