{extends file="parent:frontend/account/index.tpl"}
{block name="frontend_account_index_welcome"}
	{$smarty.block.parent}

	{$data = $sUserData.additional.user}

	<h2>Recommended variants for you</h2>

	{action module=widgets controller=listing action=productSlider numbers=$data.recommendedvariants}

	<h2>Recommended stream</h2>

	{action module=widgets controller=listing action=streamSlider streamId=$data.recommendedstream}

{/block}