{block name="widgets_emotion_components_vimeo_element"}

    {$videoURL = "https://player.vimeo.com/video/{$Data.vimeo_video_id}?color={$Data.vimeo_interface_color|substr:1}"}

    {if !$Data.vimeo_show_title}
        {$videoURL = "{$videoURL}&title=0"}
    {/if}

    {if !$Data.vimeo_show_portrait}
        {$videoURL = "{$videoURL}&portrait=0"}
    {/if}

    {if !$Data.vimeo_show_author}
        {$videoURL = "{$videoURL}&byline=0"}
    {/if}

    {if $Data.vimeo_loop}
        {$videoURL = "{$videoURL}&loop=1"}
    {/if}

    {if $Data.vimeo_autoplay}
        {$videoURL = "{$videoURL}&autoplay=1"}
    {/if}

    <iframe src="{$videoURL}"
            width="100%"
            height="100%"
            frameborder="0"
            webkitallowfullscreen
            mozallowfullscreen
            allowfullscreen>
    </iframe>
{/block}