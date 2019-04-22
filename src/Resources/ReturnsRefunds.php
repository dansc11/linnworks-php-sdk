<?php

namespace Linnworks\Resources;

class ReturnsRefunds extends BaseResource
{
    protected $pkOrderId;
    protected $bookedItems;
    protected $pkReturnId;
    protected $bookedReturnsExchangeItem;
    protected $fkOrderId;
    protected $pkRefundRowId;
    protected $from;
    protected $to;
    protected $dateType;
    protected $searchField;
    protected $exactMatch;
    protected $searchTerm;
    protected $pageNum;
    protected $numEntriesPerPage;
    protected $historyType;
    protected $sortColumn;
    protected $sortDirection;
}
