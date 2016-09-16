{block name='frontend_widgets_captcha'}
    <div class="review--captcha">
        {block name='frontend_widgets_captcha_input_code'}
            <div class="captcha--code">
                <script src='https://www.google.com/recaptcha/api.js'></script>
                <div class="g-recaptcha" data-sitekey="{$sitekey}"></div>
            </div>
        {/block}
    </div>
{/block}