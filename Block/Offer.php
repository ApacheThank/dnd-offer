<?php 

namespace Dnd\Offer\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Mageants\PartFinder\Model\PartFinderUniversalProductsFactory;
use Magento\Store\Model\StoreManagerInterface;


class Offer extends \Magento\Framework\View\Element\Template
{
    public function __construct( 
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManagerInterface,
        CollectionFactory $collectionFactory,
        array $data = []
    )
	{
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->storeManager = $storeManagerInterface;
        $this->offerCollection = $collectionFactory;
    }


    public function getItems()
    {   
        return $this->offerCollection->create();;
    }

    public function getImageUrl($path)
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
        return $mediaUrl;
    }
}