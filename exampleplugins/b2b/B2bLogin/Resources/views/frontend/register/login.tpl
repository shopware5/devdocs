{extends file='parent:frontend/register/login.tpl'}

{block name='frontend_register_login_input_email'}
    <div class="register--login-email">
        <input name="staffId" type="text" tabindex="1" id="staffId" class="register--login-field{if $sErrorFlag.staffId} has--error{/if}" />
    </div>
{/block}

{block name="frontend_register_login_input_lostpassword"}{/block}