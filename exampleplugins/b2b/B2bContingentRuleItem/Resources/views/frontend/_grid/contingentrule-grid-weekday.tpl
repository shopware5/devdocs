{namespace name=frontend/plugins/b2b_debtor_plugin}

<td class="is--align-right">
    <ul class="list--unstyled">
        <li>
            <b>{s name="Weekday"}Weekday{/s}:</b>
            <i>
                {if $row->weekdayId == 1}
                    Monday
                {elseif $row->weekdayId == 2}
                    Tuesday
                {elseif $row->weekdayId == 3}
                    Wednesday
                {elseif $row->weekdayId == 4}
                    Thursday
                {elseif $row->weekdayId == 5}
                    Friday
                {elseif $row->weekdayId == 6}
                    Saturday
                {elseif $row->weekdayId == 7}
                    Sunday
                {/if}
            </i>
        </li>
    </ul>
</td>
