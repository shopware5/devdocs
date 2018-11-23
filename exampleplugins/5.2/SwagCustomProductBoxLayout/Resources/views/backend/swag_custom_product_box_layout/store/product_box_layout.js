/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 *
 * @category   Shopware
 * @package    Base
 * @subpackage Store
 * @version    $Id$
 * @author shopware AG
 */

/**
 * Shopware Store - Global Stores and Models
 */
//{namespace name=backend/base/product_box_layout}
//{block name="backend/base/store/product_box_layout" append}
Ext.override(Shopware.apps.Base.store.ProductBoxLayout, {

    createLayoutData: function(config) {
        var me = this,
            data = me.callParent(arguments);

        data.push({
            key: 'shopware',
            label: '{s name=box_layout_shopware_label}Shopware{/s}',
            description: '{s name=box_layout_shopware_description}This is the custom Shopware box layout{/s}',
            image: '{link file="backend/_resources/images/category/layout_box_basic.png"}'
        });

        return data;
    }
});
//{/block}
