<?php
namespace Craft;

class AmCommerceModel extends BaseModel
{
    // Element types
    const OrderElementType = 'AmCommerce_Order';

    // Order statuses
    const StatusCancelled = 'cancelled';
    const StatusFinished = 'finished';
    const StatusPaid  = 'paid';
    const StatusPending = 'pending';
}