<form method="post" action="{url}">
    <input name="name" value="{$name}"/>
    <input type="submit"/>
</form>
<br>
<h4>
    {if $isPost}This is a POST request!!  -  {/if}

    Hello "{$name}"
</h4>
