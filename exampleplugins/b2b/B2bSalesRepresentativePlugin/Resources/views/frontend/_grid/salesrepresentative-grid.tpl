{namespace name=frontend/plugins/salesrepresentative}

{extends file="parent:frontend/_grid/salesrepresentative-grid.tpl"}

{block name="b2b_grid_col_sort"}
    <option value="clientData_firstname::asc"{if $gridState.sortBy == 'clientData_firstname::asc'} selected="selected"{/if}>{s name="FirstNameAsc"}Firstname ascending{/s}</option>
    <option value="clientData_firstname::desc"{if $gridState.sortBy == 'clientData_firstname::desc'} selected="selected"{/if}>{s name="FirstNameDesc"}Firstname descending{/s}</option>
    <option value="clientData_lastname::asc"{if $gridState.sortBy == 'clientData_lastname::asc'} selected="selected"{/if}>{s name="LastNameAsc"}Lastname ascending{/s}</option>
    <option value="clientData_lastname::desc"{if $gridState.sortBy == 'clientData_lastname::desc'} selected="selected"{/if}>{s name="LastNameDesc"}Lastname descending{/s}</option>
    <option value="clientData_email::asc"{if $gridState.sortBy == 'clientData_email::asc'} selected="selected"{/if}>{s name="EmailAsc"}E-Mail ascending{/s}</option>
    <option value="clientData_email::desc"{if $gridState.sortBy == 'clientData_email::desc'} selected="selected"{/if}>{s name="EmailDesc"}E-Mail descending{/s}</option>
    <option value="clientData_active::asc"{if $gridState.sortBy == 'clientData_active::asc'} selected="selected"{/if}>{s name="StateAsc"}State ascending{/s}</option>
    <option value="clientData_active::desc"{if $gridState.sortBy == 'clientData_active::desc'} selected="selected"{/if}>{s name="StateDesc"}State descending{/s}</option>
    <option value="clientData_company::asc"{if $gridState.sortBy == 'clientData_company::asc'} selected="selected"{/if}>{s name="CompanyAsc"}Company ascending{/s}</option>
    <option value="clientData_company::desc"{if $gridState.sortBy == 'clientData_company::desc'} selected="selected"{/if}>{s name="CompanyDesc"}Company descending{/s}</option>
    <option value="clientData_customernumber::asc"{if $gridState.sortBy == 'clientData_customernumber::asc'} selected="selected"{/if}>{s name="CustomernumberAsc"}Customernumber ascending{/s}</option>
    <option value="clientData_customernumber::desc"{if $gridState.sortBy == 'clientData_customernumber::desc'} selected="selected"{/if}>{s name="CustomernumberDesc"}Customernumber descending{/s}</option>
{/block}

{block name="b2b_grid_table_head"}
    <tr>
        <th>{s name="FirstName"}Firstname{/s}</th>
        <th>{s name="SurName"}Surname{/s}</th>
        <th>{s name="Email"}E-Mail{/s}</th>
        <th>{s name="Phone"}Phone{/s}</th>
        <th>{s name="State"}State{/s}</th>
        <th>{s name="Company"}Company{/s}</th>
        <th>{s name="Customernumber"}customernumber{/s}</th>
        <th width="10%">{s name="Actions"}Actions{/s}</th>
    </tr>
{/block}

{block name="b2b_grid_table_row"}
    <tr class="ignore--b2b-ajax-panel is--auto-submit {if !$row->active}is--b2b-acl-forbidden{/if}" data-linked-form="sales-representative-form-{$row->authId}">
        <td>{$row->firstName}</td>
        <td>{$row->lastName}</td>
        <td>{$row->email}</td>
        <td>{$row->phone}</td>
        <td class="col-status">
            {if $row->active}
                <i class="icon--record color--active" title="{s name="Active"}Active{/s}"></i>
            {else}
                <i class="icon--record color--inactive" title="{s name="Disabled"}Disabled{/s}"></i>
            {/if}
        </td>
        <td>{$row->company}</td>
        <td>{$row->customernumber}</td>
        <td class="col-actions">
            {if $row->active}
                <form action="{url action=clientLogin}" method="post" class="form--inline ignore--b2b-ajax-panel sales-representative-form-{$row->authId}">
                    <input type="hidden" name="id" value="{$row->authId}">

                    <button type="submit" class="btn is--primary is--small" title="{s name="LoginButtonTitle"}Login{/s}">
                        <i class="icon--account"></i>
                    </button>
                </form>
            {/if}
        </td>
    </tr>
{/block}