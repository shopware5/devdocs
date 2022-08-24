{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="parent:frontend/_grid/grid.tpl"}

{block name="b2b_grid_table_head"}
    <tr>
        <th width="20%">old Value</th>
        <th width="20%">new value</th>
        <th>comment</th>
    </tr>
{/block}

{block name="b2b_grid_table_row"}
    <tr>
        <td>{$row->logValue->oldValue}</td>
        <td>{$row->logValue->newValue}</td>
        <td>{$row->logValue->comment}</td>
    </tr>
{/block}
