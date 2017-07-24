{extends file="parent:frontend/glossary/index.tpl"}

{block name="frontend_index_content"}
	<h1>{$word|ucfirst}</h1>

    <p>{$description}</p>
{/block}