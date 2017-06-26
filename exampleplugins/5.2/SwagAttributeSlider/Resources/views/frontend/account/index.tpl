{extends file="parent:frontend/account/index.tpl"}
{block name="frontend_account_index_welcome"}
    {$smarty.block.parent}

    {$data = $sUserData.additional.user}

	<div>
		<h2>Recommended variants for you</h2>
        {action module=widgets controller=listing action=products numbers=$data.recommendedvariants type="slider"}
	</div>

	<div>
		<h2>Recommended stream</h2>
        {action module=widgets controller=listing action=stream streamId=$data.recommendedstream type="slider"}
	</div>

	<div>
		<h2>Recommended variants for you - As listing</h2>
        {action module=widgets controller=listing action=products numbers=$data.recommendedvariants productBoxLayout='image'}
	</div>

	<div>
		<h2>Recommended stream  - As listing</h2>
        {action module=widgets controller=listing action=stream streamId=$data.recommendedstream productBoxLayout='list' sPerPage=2}
	</div>
{/block}