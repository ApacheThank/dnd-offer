<?php

namespace Dnd\Offer\Model\ResourceModel\Offer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dnd\Offer\Model\Offer;
use Dnd\Offer\Model\ResourceModel\Offer as OfferResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventObject = 'offers_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Offer::class, OfferResource::class);
    }

}
