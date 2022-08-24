---
layout: default
title: Audit Log
github_link: shopware-enterprise/b2b-suite/technical/audit-log.md
indexed: true
menu_title: Audit Log
menu_order: 10
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="alert alert-info">
You can download a plugin showcasing the topic <a href="{{ site.url }}/exampleplugins/B2bAuditLog.zip">here</a>. 
</div>

<div class="toc-list"></div>

## Description

The B2B-Suite provides a general audit log which can be implemented in every component. The audit log component
can save different log types, author information like firstname, lastname and email, and provides a one to many 
association index. The database structure is described in the graphic below:

![image](/assets/img/b2b/audit_log_structure.svg)

As you can see, the database structure is very flat. In the `b2b_audit_log` table we save a log type and a serialized 
AuditLogValueEntity. All required author information is saved in the `b2b_audit_log_author` table.   

The `b2b_audit_log_index` saves all association data between an audit log and affected entities. As an example, if you
  change an order position it would be nice to show this information in the main order view. 

## A simple Example
In this example we will increase the quantity of an order position. To create an audit log you can use the following 
snippet:

```php
$auditLogValue = new AuditLogValueEntity();
$auditLogValue->comment = 'We neeed more items';
$auditLogValue->newValue = 15;
$auditLogValue->oldValue = 10;

$auditLog = new AuditLogEntity();
$auditLog->logValue = $auditLogValue->toDatabaseString();
$auditLog->logType = 'changeOrderPosition';

$orderReferenceIndex = new AuditLogIndexEntity();
$orderReferenceIndex->referenceId = 10;
$orderReferenceIndex->referenceTable = 's_order';

$orderDetailReferenceIndex = new AuditLogIndexEntity();
$orderDetailReferenceIndex->referenceId = 20;
$orderDetailReferenceIndex->referenceTable = 's_order_details';

$indexGroup = [
    $orderReferenceIndex,
    $orderDetailReferenceIndex,
];

$this->auditLogService
    ->createAuditLog($auditLog, $identity, $indexGroup);

```

With the following snippet you can get all available audit logs: 

```php
$auditLogSearchStruct = new AuditLogSearchStruct();
$auditLogs = $this->auditLogService
    ->fetchList('s_order', 10, $auditLogSearchStruct);

```

If you want to access only audit logs from a specific order position look at the example below:
 
 ```php
 $auditLogSearchStruct = new AuditLogSearchStruct();
 $auditLogs = $this->auditLogService
     ->fetchList('s_order_details', 20, $auditLogSearchStruct);
 ```  
