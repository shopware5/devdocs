REPLACE INTO `s_user_addresses` (`id`, `user_id`, `company`, `department`, `salutation`, `firstname`, `lastname`, `street`, `zipcode`, `city`, `phone`, `country_id`, `state_id`, `ustid`) VALUES
  (28, 250, 'Debtor GMBH GmbH', NULL, 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (41, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (42, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (43, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (44, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (45, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (46, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (47, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (48, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (49, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (50, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (51, 250, 'Debtor GMBH GmbH', 'Einkauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (35, 250, 'Debtor FQM', 'Einkauf', 'mr', 'Händler', 'Kundengruppe-Netto', 'Musterweg 1', '55555', 'Musterstadt', '012345 / 6789', 2, 3, NULL);

INSERT INTO  `s_user_addresses_attributes` (`address_id`, `b2b_type`) VALUES
  (28, 'billing'),
  (41, 'billing'),
  (42, 'billing'),
  (43, 'billing'),
  (44, 'billing'),
  (45, 'billing'),
  (46, 'billing'),
  (47, 'billing'),
  (48, 'billing'),
  (49, 'billing'),
  (50, 'billing'),
  (51, 'billing'),
  (35, 'shipping');

INSERT INTO `b2b_acl_contact_address` (`entity_id`, `referenced_entity_id`, `grantable`) VALUES
  (33, 28, 1),
  (33, 41, 1),
  (33, 42, 1),
  (33, 43, 1),
  (33, 44, 1),
  (33, 45, 1),
  (33, 46, 1),
  (33, 47, 1),
  (33, 48, 1),
  (33, 49, 1),
  (33, 50, 1),
  (33, 51, 1),
  (11, 28, 1),
  (11, 41, 1),
  (11, 42, 0),
  (11, 43, 1),
  (11, 44, 1),
  (11, 45, 0),
  (11, 46, 1),
  (11, 47, 1),
  (11, 48, 0),
  (11, 49, 1),
  (11, 50, 1),
  (11, 51, 1),
  (11, 35, 1);


INSERT INTO `s_categories` (`id`, `parent`, `path`, `description`, `position`, `left`, `right`, `level`, `added`, `changed`, `metakeywords`, `metadescription`, `cmsheadline`, `cmstext`, `template`, `active`, `blog`, `external`, `hidefilter`, `hidetop`, `mediaID`, `product_box_layout`, `meta_title`, `stream_id`) VALUES
  (500, 3, '|3|', 'B2B', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, '', 'B2B', NULL, NULL, 1, 0, NULL, 0, 0, 0, NULL, NULL, NULL);

INSERT INTO `s_articles` (`id`, `supplierID`, `name`, `description`, `description_long`, `shippingtime`, `datum`, `active`, `taxID`, `pseudosales`, `topseller`, `keywords`, `changetime`, `pricegroupID`, `pricegroupActive`, `filtergroupID`, `laststock`, `crossbundlelook`, `notification`, `template`, `mode`, `main_detail_id`, `available_from`, `available_to`, `configurator_set_id`) VALUES
  (500, 2, 'B2B Product 1', 'B2B Product 1', 'B2B Product 1', NULL, '2012-08-15', 1, 1, 20, 0, 'b2b', '2012-08-30 16:57:00', 1, 0, 1, 0, 0, 0, '', 0, 1600, NULL, NULL, NULL),
  (501, 2, 'B2B Product 2', 'B2B Product 2', 'B2B Product 2', NULL, '2012-08-15', 1, 1, 30, 0, 'b2b', '2012-08-20 15:16:45', NULL, 0, 1, 0, 0, 0, '', 0, 1601, NULL, NULL, NULL);

INSERT INTO `s_articles_details` (`id`, `articleID`, `ordernumber`, `suppliernumber`, `kind`, `additionaltext`, `sales`, `active`, `instock`, `stockmin`, `weight`, `position`, `width`, `height`, `length`, `ean`, `unitID`, `purchasesteps`, `maxpurchase`, `minpurchase`, `purchaseunit`, `referenceunit`, `packunit`, `releasedate`, `shippingfree`, `shippingtime`, `purchaseprice`) VALUES
  (1600, 500, 'B2B01', '', 1, '', 0, 1, 25, 0, 0.000, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 0.7000, 1.000, 'Flasche(n)', '2012-06-13', 0, '', 1.0),
  (1601, 501, 'B2B02', '', 1, '', 0, 1, 5, 0, 0.000, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 0.7000, 1.000, 'Flasche(n)', '2012-05-07', 0, '10', 1.0);

INSERT INTO `s_articles_categories` (`articleID`, `categoryID`) VALUES
  (500, 500),
  (501, 500);

INSERT INTO `s_articles_categories_ro` (`articleID`, `categoryID`, `parentCategoryID`) VALUES
  (500, 500,  3),
  (500,   3, 14),
  (500,  14, 14),
  (501, 500,  3),
  (501,   3,  14),
  (501,  14,  14);

INSERT INTO `s_articles_attributes` (`articleID`, `articledetailsID`) VALUES
  (500, 1600),
  (501, 1601);

INSERT INTO `s_articles_prices` (`pricegroup`, `from`, `to`, `articleID`, `articledetailsID`, `price`, `pseudoprice`, `baseprice`, `percent`) VALUES
  ('EK', 1, 'beliebig', 500, 1600, 15.957983193277, 0, 0, 0.00),
  ('EK', 1, 'beliebig', 501, 1601, 15.957983193277, 0, 0, 0.00);

INSERT INTO `b2b_acl_route_privilege` (`id`, `resource_name`, `privilege_type`) VALUES
  (5, 'resource', 'privilege');

INSERT INTO `b2b_acl_route` (`id`, `controller`, `action`, `privilege_id`) VALUES
  (1, 'unit', 'test', 5);

DELETE FROM s_core_sessions;

INSERT INTO `b2b_role` (`id`, `name`, `context_owner_id`) VALUES
  (11, 'Einkauf', 1),
  (22, 'Einkauf', 1),
  (33, 'Verkauf', 1),
  (44, 'Core', 1),
  (55, 'Enterprise', 1),
  (66, 'Qa', 1),
  (77, 'Einkauf', 1),
  (88, 'Verkauf', 1),
  (99, 'Core', 1),
  (111, 'Enterprise', 1),
  (122, 'Aa', 1),
  (133, 'Za', 1),
  (144, 'Debtor2role', 40);

INSERT INTO `b2b_role_contact` (`id`, `role_id`, `debtor_contact_id`) VALUES
(11, 11, 11),
(22, 22,22),
(33, 33,33);
