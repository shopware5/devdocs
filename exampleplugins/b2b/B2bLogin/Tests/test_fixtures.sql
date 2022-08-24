INSERT INTO `s_core_customergroups` (`id`, `groupkey`, `description`, `tax`, `taxinput`, `mode`, `discount`, `minimumorder`, `minimumordersurcharge`) VALUES
  (98, 'Emo', 'EK-Emotion', '1', '1', '0', '0', '10', '5')
  ,(99, 'NoEmo', 'EK-No-Emotion', '1', '1', '0', '0', '10', '5')
  ,(97, 'NoEId', 'EK-No-Emotion-Id', '1', '1', '0', '0', '10', '5')
  ,(96, 'NoEAt', 'EK-No-Emotion-Attr', '1', '1', '0', '0', '10', '5')
;

INSERT INTO `s_core_customergroups_attributes` (`id`, `customerGroupID`, `b2b_landingpage`) VALUES
  (98, 98, 99)
  ,(99, 99, 99999)
  ,(97, 97, NULL)
;

INSERT INTO `s_emotion` (`id`, `active`, `name`, `cols`, `cell_spacing`, `cell_height`, `article_height`, `rows`, `userID`, `show_listing`, `is_landingpage`, `create_date`, `modified`, `template_id`, `device`, `fullscreen`, `mode`, `position`) VALUES
  (99, 1, 'Test Einkaufswelt', '4', '10', '185', '2', '22', '1', '0', '0', '2012-08-29 08:41:30', '2016-05-31 08:23:09', '1', '0,1,2,3,4', '0', 'fluid', '1')
;

REPLACE INTO `s_user` (`id`, `customernumber`, `password`, `email`, `active`, `accountmode`, `confirmationkey`, `paymentID`, `firstlogin`, `lastlogin`, `sessionID`, `newsletter`, `validation`, `affiliate`, `customergroup`, `paymentpreset`, `language`, `subshopID`, `referer`, `pricegroupID`, `internalcomment`, `failedlogins`, `lockeduntil`, `default_billing_address_id`, `default_shipping_address_id`, `salutation`, `firstname`, `lastname`) VALUES
  (250, '1324', 'a256a310bc1e5db755fd392c524028a8', 'debtor@example.com',   1, 0, '', 5, '2011-11-23', '2012-01-04 14:12:05', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'Mustermann'),
  (249, '1324', 'a256a310bc1e5db755fd392c524028a8', 'debtor2@example.com',   1, 0, '', 5, '2011-11-23', '2012-01-04 14:12:05', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'Mustermann'),
  (251, '1234', 'a256a310bc1e5db755fd392c524028a8', 'contact1@example.com', 1, 0, '', 5, '2011-11-23', '2012-01-04 14:12:05', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 1, 3, 'mr', 'Max', 'Contact1'),
  (252, '1234', 'a256a310bc1e5db755fd392c524028a8', 'contact2@example.com', 1, 0, '', 5, '2011-11-23', '2012-01-04 14:12:05', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 1, 3, 'mr', 'Max', 'Contact2'),
  (253, '1324', 'a256a310bc1e5db755fd392c524028a8', 'debtor2@example.com',   1, 0, '', 5, '2011-11-23', '2012-01-04 14:12:05', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'Mustermann'),
  (254, '1324', 'a256a310bc1e5db755fd392c524028a8', 'contact3@example.com',   1, 0, '', 5, '2011-11-23', '2012-01-04 14:12:05', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'Mustermann'),
  (255, '2324', 'a256a310bc1e5db755fd392c524028a8', 'salesrepresentative@example.com',1, 0, '', 5, '2011-11-23', '2016-11-18 15:04:17', '', 0, '', 0, 'EK', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'Sales Representative'),
  (256, '2324', 'a256a310bc1e5db755fd392c524028a8', 'shoppingworld@example.com',1, 0, '', 5, '2011-11-23', '2016-11-18 15:04:17', '', 0, '', 0, 'Emo', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'Shoppingworld'),
  (257, '2324', 'a256a310bc1e5db755fd392c524028a8', 'no-shoppingworld@example.com',1, 0, '', 5, '2011-11-23', '2016-11-18 15:04:17', '', 0, '', 0, 'NoEmo', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'No Shoppingworld'),
  (258, '2324', 'a256a310bc1e5db755fd392c524028a8', 'no-shoppingworld-id@example.com',1, 0, '', 5, '2011-11-23', '2016-11-18 15:04:17', '', 0, '', 0, 'NoEId', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'No Shoppingworld Id'),
  (259, '2324', 'a256a310bc1e5db755fd392c524028a8', 'no-shoppingworld-attr@example.com',1, 0, '', 5, '2011-11-23', '2016-11-18 15:04:17', '', 0, '', 0, 'NoEAt', 0, '1', 1, '', NULL, '', 0, NULL, 28, 35, 'mr', 'Max', 'No Shoppingworld Attr');

REPLACE INTO b2b_store_front_auth (`id`, `context_owner_id`, `provider_key`, `provider_context`) VALUES
  (1, 1,    'Shopware\\B2B\\Debtor\\Framework\\DebtorRepository', 'debtor@example.com')
  ,(2, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'contact1@example.com')
  ,(3, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'contact2@example.com')
  ,(4, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'contact3@example.com')
  ,(5, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'contact4@example.com')
  ,(6, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'contact5@example.com')
  ,(40, 40, 'Shopware\\B2B\\Debtor\\Framework\\DebtorRepository', 'debtor2@example.com')
  ,(41, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'shoppingworld@example.com')
  ,(42, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'no-shoppingworld@example.com')
  ,(43, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'no-shoppingworld-id@example.com')
  ,(44, 1,   'Shopware\\B2B\\Contact\\Framework\\ContactRepository', 'no-shoppingworld-attr@example.com')
;

REPLACE INTO `b2b_debtor_contact` (`id`, `password`, `email`, `active`, `language`, `salutation`, `firstname`, `lastname`, `context_owner_id`, `auth_id`, staff_id) VALUES
  (11, 'a256a310bc1e5db755fd392c524028a8', 'contact1@example.com', 1, 0, 'mr', 'Tom', 'Contact1', 1, 2, 'A-1'),
  (22, 'a256a310bc1e5db755fd392c524028a8', 'contact2@example.com', 1, 0, 'mr', 'Tom', 'Contact2', 1, 3, 'A-2'),
  (33, 'a256a310bc1e5db755fd392c524028a8', 'contact3@example.com', 1, 0, 'mr', 'Max', 'Contact3', 1, 4, 'A-3'),
  (44, 'a256a310bc1e5db755fd392c524028a8', 'contact4@example.com', 1, 0, 'mr', 'Max', 'Contact4', 1, 5, 'A-4'),
  (45, 'a256a310bc1e5db755fd392c524028a8', 'contact5@example.com', 1, 0, 'mr', 'Max', 'Contact5', 1, 6, 'A-5'),
  (46, 'a256a310bc1e5db755fd392c524028a8', 'contact6@example.com', 1, 0, 'mr', 'Max', 'Contact6', 1, NULL, 'A-6'),
  (47, 'a256a310bc1e5db755fd392c524028a8', 'contact7@example.com', 1, 0, 'mr', 'Max', 'Contact7', 1, NULL, 'A-7'),
  (48, 'a256a310bc1e5db755fd392c524028a8', 'contact8@example.com', 1, 0, 'mr', 'Max', 'Contact8', 1, NULL, 'A-8'),
  (49, 'a256a310bc1e5db755fd392c524028a8', 'contact9@example.com', 1, 0, 'mr', 'Max', 'Contact9', 1, NULL, 'A-9'),
  (50, 'a256a310bc1e5db755fd392c524028a8', 'contact10@example.com', 1, 0, 'mr', 'Max', 'Contact10', 1, NULL, 'A-10'),
  (51, 'a256a310bc1e5db755fd392c524028a8', 'contact11@example.com', 1, 0, 'mr', 'Max', 'Contact11', 1, NULL, 'A-11'),
  (52, 'a256a310bc1e5db755fd392c524028a8', 'contact12@example.com', 1, 0, 'mr', 'Max', 'Contact12', 1, NULL, 'A-12'),
  (53, 'a256a310bc1e5db755fd392c524028a8', 'contact13@example.com', 1, 0, 'mr', 'Max', 'Contact13', 1, NULL, 'A-13'),
  (54, 'a256a310bc1e5db755fd392c524028a8', 'contact14@example.com', 1, 0, 'mr', 'Max', 'Contact14', 1, NULL, 'A-14'),
  (55, 'a256a310bc1e5db755fd392c524028a8', 'contact15@example.com', 1, 0, 'mr', 'Max', 'Contact15', 1, NULL, 'A-15'),
  (56, 'a256a310bc1e5db755fd392c524028a8', 'contact16@example.com', 1, 0, 'mr', 'Max', 'Contact16', 1, NULL, 'A-16'),
  (57, 'a256a310bc1e5db755fd392c524028a8', 'contact17@example.com', 1, 0, 'mr', 'Max', 'Contact17', 1, NULL, 'A-17'),
  (58, 'a256a310bc1e5db755fd392c524028a8', 'contact18@example.com', 1, 0, 'mr', 'Max', 'Contact18', 1, NULL, 'A-18'),
  (59, 'a256a310bc1e5db755fd392c524028a8', 'contact19@example.com', 1, 0, 'mr', 'Max', 'Contact19', 1, NULL, 'A-19'),
  (60, 'a256a310bc1e5db755fd392c524028a8', 'contact20@example.com', 1, 0, 'mr', 'Max', 'Contact20', 1, NULL, 'A-20'),
  (61, 'a256a310bc1e5db755fd392c524028a8', 'contact21@example.com', 0, 0, 'mr', 'Max', 'Contact21', 1, NULL, 'A-21'),
  (62, 'a256a310bc1e5db755fd392c524028a8', 'contact23@example.com', 0, 0, 'mr', 'Max', 'Contact23', 40, NULL, 'A-22'),
  (63, 'a256a310bc1e5db755fd392c524028a8', 'shoppingworld@example.com', 0, 0, 'mr', 'Max', 'Shoppingworld', 1, 41, 'A-23'),
  (64, 'a256a310bc1e5db755fd392c524028a8', 'no-shoppingworld@example.com', 0, 0, 'mr', 'Max', 'No Shoppingworld', 1, 42, 'A-24'),
  (65, 'a256a310bc1e5db755fd392c524028a8', 'no-shoppingworld-id@example.com', 0, 0, 'mr', 'Max', 'No Shoppingworld Id', 1, 43, 'A-25'),
  (65, 'a256a310bc1e5db755fd392c524028a8', 'no-shoppingworld-attr@example.com', 0, 0, 'mr', 'Max', 'No Shoppingworld Attr', 1, 44, 'A-26');

REPLACE INTO s_user_attributes (id, userID, b2b_is_debtor, b2b_is_sales_representative, b2b_sales_representative_media_id, b2b_sales_representative_id, staff_id) VALUES
  (449, 249, 1, 0, NULL, 255, 'B-1'),
  (450, 250, 1, 0, NULL, 255, 'B-2'),
  (451, 255, 0, 1, NULL, NULL, 'C-3'),
  (452, 251, 0, 0, NULL, 255, null);

REPLACE INTO `s_user_addresses` (`id`, `user_id`, `company`, `department`, `salutation`, `firstname`, `lastname`, `street`, `zipcode`, `city`, `phone`, `country_id`, `state_id`, `ustid`) VALUES
  (28, 250, 'Debtor GMBH GmbH', NULL, 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (41, 250, 'Debtor GMBH GmbH', 'Ankauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
  (42, 250, 'Debtor GMBH GmbH', 'Zuletztkauf', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL),
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

# @todo necessary until SW-16481 is resolved
INSERT INTO `s_user_billingaddress` (`id`, `userID`, `company`, `department`, `salutation`, `firstname`, `lastname`, `street`, `zipcode`, `city`, `phone`, `countryID`, `stateID`, `ustid`) VALUES
  (28, 250, 'Debtor GMBH GmbH', 'foo', 'mr', 'Max', 'Mustermann', 'Musterstr. 55', '55555', 'Musterhausen', '05555 / 555555', 2, 3, NULL);

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

INSERT INTO b2b_acl_role_route_privilege (`entity_id`, `referenced_entity_id`, `grantable`)
  (SELECT 33 AS entity_id, id AS referenced_entity_id, 1 AS grantable FROM b2b_acl_route_privilege);

INSERT INTO b2b_acl_contact_route_privilege (`entity_id`, `referenced_entity_id`, `grantable`)
  (SELECT 33 AS entity_id, id AS referenced_entity_id, 1 AS grantable FROM b2b_acl_route_privilege);

INSERT INTO `b2b_role_contact` (`id`, `role_id`, `debtor_contact_id`) VALUES
(11, 11, 11),
(33, 33, 33);

INSERT INTO `b2b_acl_route_privilege` (`id`, `resource_name`, `privilege_type`) VALUES
  (5, 'resource', 'privilege');

INSERT INTO `b2b_acl_route` (`id`, `controller`, `action`, `privilege_id`) VALUES
  (1, 'unit', 'test', 5);

INSERT INTO b2b_contingent_group (id, context_owner_id, name, description) VALUES
  (3, 1, 'IT', 'IT 250'),
  (4, 1, 'Vertrieb', 'Vertrieb 250'),
  (5, 1, 'Einkauf', 'Einkauf 250'),
  (6, 40, 'Vertrieb', 'Vertrieb 251'),
  (7, 40, 'Einkauf', 'Einkauf 251'),
  (8, 1, 'Technik', 'Technik'),
  (9, 1, 'Einkauf', 'Einkauf'),
  (10, 1, 'Enterprise', 'Enterprise'),
  (11, 1, 'Marketing', 'Marketing'),
  (12, 1, 'Technical E', 'Technical E'),
  (13, 1, 'Core Development', 'Core Development'),
  (14, 1, 'Performance Marketing', 'Performance Marketing'),
  (15, 1, 'Financial Department', 'Performance Marketing'),
  (16, 1, 'Denter', 'Dentist'),
  (100, 1, 'Qa', 'Qa');

INSERT INTO b2b_role_contact (id, role_id, debtor_contact_id) VALUES
  (101, 11, 33);

INSERT INTO b2b_acl_role_contingent_group (id, entity_id, referenced_entity_id, grantable) VALUES
  (9, 11, 3, 1),
  (11, 33, 3, 1);

INSERT INTO b2b_role_contingent_group (id, role_id, contingent_group_id) VALUES
  (11, 11, 10);

INSERT INTO b2b_acl_contact_contingent_group (id, entity_id, referenced_entity_id, grantable) VALUES
  (30, 33, 4, 1),
  (31, 33, 5, 0),
  (39, 33, 6, 0),
  (38, 33, 7, 0),
  (37, 33, 8, 0),
  (36, 33, 9, 0),
  (35, 33, 10, 0),
  (34, 33, 11, 0),
  (33, 33, 12, 0),
  (32, 33, 13, 0);

INSERT INTO b2b_contingent_group_rule (id, contingent_group_id, type) VALUES
  (16, 10, 'OrderQuantity'),
  (17, 10, 'OrderItemQuantity'),
  (18, 10, 'OrderAmount'),
  (19, 11, 'OrderAmount'),
  (20, 11, 'OrderQuantity'),
  (21, 13, 'OrderAmount'),
  (22, 13, 'OrderQuantity'),
  (23, 10, 'Category'),
  (24, 10, 'ProductPrice'),
  (25, 10, 'ProductOrderNumber')
;

INSERT INTO b2b_contingent_group_rule_time_restriction (contingent_rule_id, time_restriction,  value) VALUES
  (16, 'DAYOFYEAR', 3.00),
  (17, 'WEEKOFYEAR', 13.00),
  (18, 'MONTH', 1337.00),
  (19, 'QUARTER', 10.00),
  (20, 'YEAR', 20.00),
  (21, 'QUARTER', 10.00),
  (22, 'YEAR', 20.00);

INSERT INTO b2b_contingent_group_rule_category (contingent_rule_id, category_id) VALUES
  (23, 500);

INSERT INTO b2b_contingent_group_rule_product_price (contingent_rule_id, product_price) VALUES
  (24, 123);

INSERT INTO b2b_contingent_group_rule_product_order_number (contingent_rule_id, product_order_number) VALUES
  (25, 'SW123');

INSERT INTO `b2b_contact_contingent_group` (`id`, `contact_id`, `contingent_group_id`) VALUES
  (10, 11, 13);

INSERT INTO s_order (id, ordernumber, userID, invoice_amount, invoice_amount_net, invoice_shipping, invoice_shipping_net, ordertime, status, cleared, paymentID, transactionID, comment, customercomment, internalcomment, net, taxfree, partnerID, temporaryID, referer, cleareddate, trackingcode, language, dispatchID, currency, currencyFactor, subshopID, remote_addr, deviceType) VALUES
  (1, '20003', 251, 3122.99, 2624.36, 3.9, 3.28, '2016-11-05 08:39:23', -2, 17, 5, '', '', '', '', 0, 0, '', '', '', null, '', '1', 9, 'EUR', 1, 1, '10.100.200.1', 'desktop'),
  (2, '20004', 251, 3122.99, 2624.36, 3.9, 3.28, '2016-11-09 08:39:23', -2, 17, 5, '', '', '', '', 0, 0, '', '', '', null, '', '1', 9, 'EUR', 1, 1, '10.100.200.1', 'desktop'),
  (3, '20005', 254, 321.89, 270.5, 3.9, 3.28, '2016-11-11 14:38:01', -2, 17, 5, '', '', '', '', 0, 0, '', '', '', null, '', '1', 9, 'EUR', 1, 1, '10.100.200.1', 'desktop'),
  (4, '20006', 254, 321.89, 270.5, 3.9, 3.28, '2016-11-11 14:38:01', -2, 17, 5, '', '', '', '', 0, 0, '', '', '', null, '', '1', 9, 'EUR', 1, 1, '10.100.200.1', 'desktop');

INSERT INTO s_order_attributes (orderID, attribute1, attribute2, attribute3, attribute4, attribute5, attribute6, b2b_auth_id) VALUES
  (1, null, null, null, null, null, null, 4),
  (2, null, null, null, null, null, null, 4),
  (3, null, null, null, null, null, null, 3),
  (4, null, null, null, null, null, null, 1);

INSERT INTO `b2b_line_item_list` (`id`, `context_owner_id`, `amount_net`, `amount`) VALUES
  (4, 1, '2624.36', '3122.99'),
  (5, 1, '2624.36', '3122.99'),
  (6, 1, '270.5', '321.89'),
  (7, 1, '270.5', '321.89');

INSERT INTO `b2b_line_item_reference` (`id`, `reference_number`, `quantity`, `comment`, `list_id`, `amount_net`, `amount`)
VALUES
	(4, 'SW10170', 8, '', 4, '33.571428571429', '39,95'),
	(5, 'SW10165', 5, '', 4, '16.798319327731', '19,99'),
	(6, 'SHIPPINGDISCOUNT', 1, '', 4, '-1.68', '-2,00'),
	(7, 'SW10170', 8, '', 5, '33.571428571429', '39,95'),
	(8, 'SW10165', 5, '', 5, '16.798319327731', '19,99'),
	(9, 'SW10170', 8, '', 6, '33.571428571429', '39,95'),
	(10, 'SW10165', 5, '', 6, '16.798319327731', '19,99'),
	(11, 'SW10170', 8, '', 7, '33.571428571429', '39,95'),
	(12, 'SW10165', 5, '', 7, '16.798319327731', '19,99');

INSERT INTO `b2b_order_context` (`id`, `ordernumber`, `list_id`, `created_at`, `shipping_address_id`, `billing_address_id`, `payment_id`, `shipping_id`, `comment`, `device_type`, `s_order_id`, `status_id`, `auth_id`, `order_reference`, `currency_factor`, `requested_delivery_date`, `cleared_at`) VALUES
  (1, '20003', 4, '2016-11-05 13:16:40', 35, 51, 5, 9, 'THE_TEST_COMMENT', 'desktop', 1, -2, 4, 'Reference', 1, 'every monday', NULL),
  (2, '20004', 5, '2016-11-19 13:56:13', 35, 51, 5, 9, '', 'desktop', 2, -2, 4, '', 1, 'every monday', '2017-01-05 13:16:40'),
  (3, '20005', 6, '2017-01-05 13:57:02', 35, 51, 5, 9, '', 'desktop', 3, -2, 4, '', 1, 'every monday', '2017-01-05 13:16:40'),
  (4, '20006', 7, '2017-01-05 13:57:02', 35, 51, 5, 9, '', 'desktop', 4, -2, 1, '', 1, 'every monday', '2017-01-05 13:16:40');


INSERT INTO b2b_audit_log_author (hash, salutation, title, firstname, lastname, email, is_api)
VALUES ('a804b81a0c49fce89e95e1110985ed8f', 'mr', NULL, 'Tom', 'Contact1', 'contact1@example.com', 1),
    ('c2b6a5abf5355c920150c7c12d46af03', 'mr', NULL, 'Tom', 'Contact2', 'contact2@example.com', 0);

INSERT INTO `b2b_audit_log` (`id`, `log_value`, `log_type`, `event_date`, `author_hash`)
VALUES
	(5, 'O:63:\"Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueOrderCommentEntity\":2:{s:8:\"oldValue\";s:0:\"\";s:8:\"newValue\";s:9:\"Kommentar\";}',
   'Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueOrderCommentEntity', '2017-03-06 11:16:11', 'a804b81a0c49fce89e95e1110985ed8f'),
	(6, 'O:67:\"Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemQuantityEntity\":4:{s:11:\"orderNumber\";s:7:\"SW10072\";s:11:\"productName\";s:16:\"Schlüsselkasten\";s:8:\"oldValue\";i:78;s:8:\"newValue\";i:75;}',
   'Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemQuantityEntity', '2017-03-06 11:18:25', 'a804b81a0c49fce89e95e1110985ed8f'),
	(7, 'O:66:\"Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemCommentEntity\":4:{s:11:\"orderNumber\";s:7:\"SW10072\";s:11:\"productName\";s:16:\"Schlüsselkasten\";s:8:\"oldValue\";s:0:\"\";s:8:\"newValue\";s:12:\"item comment\";}',
   'Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemCommentEntity', '2017-03-06 11:18:31', 'a804b81a0c49fce89e95e1110985ed8f'),
	(8, 'O:65:\"Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemRemoveEntity\":4:{s:11:\"orderNumber\";s:16:\"SHIPPINGDISCOUNT\";s:11:\"productName\";s:9:\"NOT FOUND\";s:8:\"oldValue\";i:15;s:8:\"newValue\";N;}',
   'Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemRemoveEntity', '2017-03-06 11:20:40', 'a804b81a0c49fce89e95e1110985ed8f'),
	(9, 'O:55:\"Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueDiffEntity\":2:{s:8:\"oldValue\";s:2:\"-2\";s:8:\"newValue\";s:1:\"0\";}',
   'Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueDiffEntity', '2017-03-06 11:22:32', 'a804b81a0c49fce89e95e1110985ed8f'),
	(10, 'O:66:\"Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemCommentEntity\":4:{s:11:\"orderNumber\";s:7:\"SW10067\";s:11:\"productName\";s:19:\"Kommode Shabby Chic\";s:8:\"oldValue\";s:0:\"\";s:8:\"newValue\";s:12:\"item comment\";}',
   'Shopware\\B2B\\AuditLog\\Framework\\AuditLogValueLineItemCommentEntity', '2017-03-06 15:07:24', 'a804b81a0c49fce89e95e1110985ed8f');

INSERT INTO `b2b_audit_log_index` (`audit_log_id`, `reference_table`, `reference_id`)
VALUES
	(5, 'b2b_order_context', 1),
	(6, 'b2b_order_context', 1),
	(7, 'b2b_order_context', 1),
	(8, 'b2b_order_context', 1),
	(9, 'b2b_order_context', 1),
	(10, 'b2b_order_context', 1);

INSERT INTO `b2b_line_item_list` (`id`, `context_owner_id`, `amount_net`, `amount`) VALUES
  (400, 1, '354.16', '421.45'),
  (500, 1, '43.6',   '51.88');

INSERT INTO `b2b_line_item_reference` (`id`, `reference_number`, `quantity`, `comment`, `list_id`, `amount_net`, `amount`) VALUES
  (400, 'SW10170', 8, '', 400, '33.571428571429', '39,95'),
  (500, 'SW10165', 5, '', 400, '16.798319327731', '19,99'),
  (700, 'SW10088', 2, '',  500, '21', '24,99')
;

INSERT INTO `b2b_order_list` (`id`, `name`, `list_id`, `context_owner_id`) VALUES
  (1, 'List 1' , 400, 1)
  ,(2, 'List 2', 500, 1)
;

INSERT INTO `s_order_basket` (`id`, `sessionID`, `userID`, `articlename`, `articleID`, `ordernumber`, `shippingfree`, `quantity`, `price`, `netprice`, `tax_rate`, `datum`, `modus`, `esdarticle`, `partnerID`, `lastviewport`, `useragent`, `config`, `currencyFactor`) VALUES
  (673, '91ee4151ebab33707b83b96b80b38d91', 250, 'Warenkorbrabatt', 0, 'SHIPPINGDISCOUNT', 0, 1, -2, -1.68, 19, '2017-03-28 10:53:31', 4, 0, '', 'b2borderlist', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '', 1),
  (670, '91ee4151ebab33707b83b96b80b38d91', 250, 'Schüssel Style Blumen', 500, 'SW10170', 0, 2, 24.99, 21, 19, '2017-03-28 10:53:27', 0, 0, '', 'b2borderlist', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '', 1);

INSERT INTO `b2b_acl_contact_order_list` (entity_id, referenced_entity_id, grantable) VALUES (11, 2, 0);

INSERT INTO `b2b_acl_role_order_list` (entity_id, referenced_entity_id, grantable) VALUES (66, 2, 0);

INSERT INTO `b2b_budget` (`id`, `identifier`, `name`, `context_owner_id`, `owner_id`, `amount`, `refresh_type`, `notify_author`, `notify_author_percentage`) VALUES
  (1, 'id-1', 'NoRenewal', 1, 2, 500, 'none', 0, 0)
  ,(2, 'id-2', 'Monthly', 1, 2, 600, 'monthly', 0, 0)
  ,(3, 'id-3', 'Yearly', 1, 2, 400, 'yearly', 0, 0)
  ,(4, 'Invisible1','Debtor only', 1, 2, 40000000, 'yearly', 0, 0)
  ,(5, 'id-5','Mailing', 1, 2, 100, 'monthly', 1, 50)
;

INSERT INTO `b2b_budget_transaction` (`id`, `budget_id`, `auth_id`, `refresh_group`, `amount`) VALUES
  (1, 1, 2, 0, 450)
  ,(2, 2, 2, 201611, 450)
  ,(3, 2, 2, 201611, 10)
  ,(4, 2, 2, 201612, 600)
  ,(5, 2, 2, 201750, 450)
  ,(6, 5, 2, DATE_FORMAT(NOW(), '%Y%m'), 90)
;

INSERT INTO `b2b_acl_role_budget` (`id`, `entity_id`, `referenced_entity_id`, `grantable`) VALUES
  (1, 11, 1, 1)
  ,(2, 11, 2, 0)
  ,(3, 11, 3, 1)
;

INSERT INTO `b2b_acl_contact_budget` (entity_id, referenced_entity_id, grantable) VALUES (11, 2, 0);

INSERT INTO `b2b_acl_role_budget` (entity_id, referenced_entity_id, grantable) VALUES (66, 2, 0);

REPLACE INTO `s_core_config_mails` (`name`, `frommail`, `fromname`, `subject`, `content`, `contentHTML`, `ishtml`, `attachment`, `mailtype`, `context`, `dirty`) VALUES
  ('b2bBudgetNotify', 'test@example.com', '{config name=shopName}', 'Notify Budget Mail Subject', 'Notify Budget Mail Content', '', '0', '', '2', 'N;', '0')
;