<?php

namespace Linnworks\Resources;

class PurchaseOrder extends BaseResource
{
    protected $searchParameter;
    protected $createParameters;
    protected $updateParameter;
    protected $addItemParameter;
    protected $updateItemParameter;
    protected $deleteItemParameter;
    protected $purchaseOrder;
    protected $pkPurchaseId;
    protected $Note;
    protected $pkPurchaseOrderNoteId;
    protected $changeStatusParameter;
    protected $deliverItemParameter;
    protected $auditLog;
}
