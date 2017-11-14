<div class="dig-pub--youtube">

    {$controls = 0}
    {$showinfo = 0}

    {if $element.controls}
        {$controls = 1}
    {/if}

    {if $element.showinfo}
        {$showinfo = 1}
    {/if}

    <iframe class="youtube--frame"
            style="width: {$element.maxWidth / 16}rem; height: {$element.maxHeight / 16}rem;"
            src="https://www.youtube-nocookie.com/embed/{$element.youTubeId}?rel=0&amp;controls={$controls}&amp;showinfo={$showinfo}"
            frameborder="0"
            allowfullscreen>
    </iframe>
</div>