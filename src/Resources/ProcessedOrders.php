<?php

namespace Linnworks\Resources;

class ProcessedOrders extends BaseResource
{
    protected $pkOrderId;
    protected $refundItems;
    protected $isManualRefund;
    protected $includeBookings;
    protected $includeRefundLink;
    protected $from;
    protected $to;
    protected $dateType;
    protected $searchField;
    protected $exactMatch;
    protected $searchTerm;
    protected $pageNum;
    protected $numEntriesPerPage;
    protected $sortColumn;
    protected $sortDirection;
    protected $categoryName;
    protected $pkItemId;
    protected $newName;
    protected $pkOrderID;
    protected $noteText;
    protected $isInternal;
    protected $returnitems;
    protected $returnLocation;
    protected $channelReason;
    protected $channelSubReason;
    protected $category;
    protected $reason;
    protected $isReturnBooking;
    protected $ignoredValidation;
    protected $pkOrderNoteId;
    protected $exchangeItems;
    protected $despatchLocation;
    protected $isBooking;
    protected $resendItems;
    protected $additionalCost;
}
