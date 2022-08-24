{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="parent:frontend/_grid/grid.tpl"}

{block name="b2b_grid_col_sort"}
    <option value="name::asc"{if $gridState.sortBy == 'name::asc'} selected="selected"{/if}>
        {s name="NameAsc"}Name Ascending{/s}
    </option>
    <option value="name::desc"{if $gridState.sortBy == 'name::desc'} selected="selected"{/if}>
        {s name="NameDesc"}Name Descending{/s}
    </option>
    <option value="description::asc"{if $gridState.sortBy == 'description::asc'} selected="selected"{/if}>
        {s name="DescriptionAsc"}Description Ascending{/s}
    </option>
    <option value="description::desc"{if $gridState.sortBy == 'description::desc'} selected="selected"{/if}>
        {s name="DescriptionDesc"}Description Descending{/s}
    </option>
{/block}

{block name="b2b_grid_table_head"}
    <tr>
        <th width="20%">{s name="OfferName"}Name{/s}</th>
        <th>{s name="OfferDescription"}Description{/s}</th>
        <th width="10%">{s name="Action"}Action{/s}</th>
    </tr>
{/block}

{block name="b2b_grid_table_row"}
    <tr data-row-id="{$row->id}" class="ajax-panel-link {b2b_acl controller=b2boffer action=detail}" data-target="offer-detail" data-href="{url action=detail id=$row->id}">
        <td>{$row->name}</td>
        <td>{$row->description}</td>
        <td class="col-actions">
            <form action="{url action=remove}" method="post">
                <input type="hidden" name="id" value="{$row->id}">
                <button type="submit" class="btn btn-primary is--small component-action-delete {b2b_acl controller=b2boffer action=remove}">
                    <i class="icon--trash"></i>
                </button>
            </form>
        </td>
    </tr>
{/block}