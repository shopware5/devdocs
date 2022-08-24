{namespace name=frontend/plugins/b2b_debtor_plugin}

<div class="block-group b2b--form">
    <div class="block box--label  is--full">
        {s name="Weekday"}Weekday{/s}: *
    </div>
    <div class="block box--input select-field is--full">
        <select name="weekdayId">
            <option value="" disabled selected="selected">{s name="Weekday"}Weekday{/s}</option>
            <option value="1" {if $rule->weekdayId == 1}selected="selected"{/if}>Monday</option>
            <option value="2" {if $rule->weekdayId == 2}selected="selected"{/if}>Tuesday</option>
            <option value="3" {if $rule->weekdayId == 3}selected="selected"{/if}>Wednesday</option>
            <option value="4" {if $rule->weekdayId == 4}selected="selected"{/if}>Thursday</option>
            <option value="5" {if $rule->weekdayId == 5}selected="selected"{/if}>Friday</option>
            <option value="6" {if $rule->weekdayId == 6}selected="selected"{/if}>Saturday</option>
            <option value="7" {if $rule->weekdayId == 7}selected="selected"{/if}>Sunday</option>
        </select>
    </div>
</div>
