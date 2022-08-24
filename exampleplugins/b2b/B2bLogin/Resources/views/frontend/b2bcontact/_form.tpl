{namespace name=frontend/plugins/b2b_debtor_plugin}

<div class="block-group b2b--form">
    <div class="block box--label is--full">
        {s name="Firstname"}Firstname{/s}: *
    </div>
    <div class="block box--input is--full">
        <input type="text" name="firstName" value="{$contact->firstName}"
               placeholder="{s name="Firstname"}Firstname{/s}">
    </div>
</div>

<div class="block-group  b2b--form">
    <div class="block box--label is--full">
        {s name="Surname"}Surname{/s}: *
    </div>
    <div class="block box--input is--full">
        <input type="text" name="lastName" value="{$contact->lastName}" placeholder="{s name="Surname"}Surname{/s}">
    </div>
</div>

<div class="block-group b2b--form">
    <div class="block box--label is--full">
        {s name="Email"}E-Mail{/s}: 
    </div>
    <div class="block box--input is--full">
        <input type="email" name="email" value="{if strpos($contact->email, "@")}{$contact->email}{/if}" placeholder="{s name="Email"}E-Mail{/s}">
    </div>
</div>

<div class="block-group  b2b--form">
    <div class="block box--label is--full">
        {s name="Department"}Department{/s}:
    </div>
    <div class="block box--input is--full">
        <input type="text" name="department" value="{$contact->department}" placeholder="{s name="Department"}Department{/s}">
    </div>
</div>

<div class="block-group b2b--form">
    <div class="block box--label is--full">
        {s name="Password"}Password{/s}: *
    </div>
    <div class="block box--input is--full">
        <input type="password" name="passwordNew" value="" placeholder="{s name="Password"}Password{/s}">
    </div>
</div>

<div class="block-group b2b--form">
    <div class="block box--label is--full">
        {s name="Confirm"}Confirm{/s}: *
    </div>
    <div class="block box--input is--full">
        <input type="password" name="passwordRepeat" value="" placeholder="{s name="ConfirmPassword"}Confirm your password{/s}">
    </div>
</div>

<div class="block-group b2b--form">
    <div class="block box--label is--full">
        {s name="Active"}Active{/s}:
    </div>
    <div class="block box--input is--full">
        <span class="checkbox">
            <input type="checkbox" name="active" value="1" {if $contact->active || $isNew}checked="checked"{/if}>
            <span class="checkbox--state"></span>
        </span>
    </div>
</div>