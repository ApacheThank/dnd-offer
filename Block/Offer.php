<?php

namespace Dnd\Offer\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;


class Offer extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        Context               $context,
        Registry              $registry,
        DateTime              $dateTime,
        StoreManagerInterface $storeManagerInterface,
        CollectionFactory     $collectionFactory,
        array                 $data = []
    )
    {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->dateTime = $dateTime;
        $this->storeManager = $storeManagerInterface;
        $this->offerCollection = $collectionFactory;
    }


    public function getItems()
    {
        $currentDate = $this->dateTime->gmtDate();
        $offerCollection = $this->offerCollection->create();
        $offerCollection->addFieldToFilter('category_ids', ['finset' => $this->getCurrentCategoryId()]);
        $offerCollection->addFieldToFilter('date_start', ['lteq' => $currentDate]);
        $offerCollection->addFieldToFilter('date_end', ['gteq' => $currentDate]);
        return $offerCollection;
    }

    public function getImageUrl($path)
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
        return $mediaUrl;
    }

    public function getCurrentCategoryId()
    {
        $currentCategory = $this->registry->registry('current_category');
        return $currentCategory ? $currentCategory->getId() : null;
    }
}
