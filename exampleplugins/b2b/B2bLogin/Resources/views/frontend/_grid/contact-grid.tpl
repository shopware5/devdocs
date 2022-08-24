{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="parent:frontend/_grid/contact-grid.tpl"}

{block name="b2b_grid_table_row"}
    <tr class="ajax-panel-link {b2b_acl controller=b2bcontact action=detail}" data-target="contact-detail" data-row-id="{$row->id}" data-href="{url action=detail email=$row->email}">
        <td>{$row->firstName}</td>
        <td>{$row->lastName}</td>
        <td>{if strpos($row->email, "@")}{$row->email}{/if}</td>
        <td class="col-status">
            {if $row->active}
                <i class="icon--record color--active"></i>
            {else}
                <i class="icon--record color--inactive"></i>
            {/if}
        </td>
        <td class="col-actions">
            <button type="button" class="btn btn--edit is--small"><i class="icon--pencil"></i></button>

            <form action="{url action=remove}" method="post" class="form--inline">
                <input type="hidden" name="id" value="{$row->id}">

                <button type="submit" class="btn btn-primary is--small component-action-delete {b2b_acl controller=b2bcontact action=remove}">
                    <i class="icon--trash"></i>
                </button>
            </form>
        </td>
    </tr>
{/block}