<?php

namespace Dnd\Offer\Model;

use Magento\Framework\Controller\Result\JsonFactory;
use Dnd\Offer\Api\OfferRepositoryInterface;
use Dnd\Offer\Api\Data\OfferDataInterface;
use Dnd\Offer\Model\OfferFactory;
use Dnd\Offer\Model\ResourceModel\Offer as OfferResource;
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;

class OfferRepository implements OfferRepositoryInterface
{
	public function __construct(
        CollectionFactory $offerCollectionFactory,
        OfferResource $offerResource,
        OfferFactory $offerFactory,
		    JsonFactory $jsonFactory
    ) {
        $this->offerCollection   = $offerCollectionFactory;
        $this->offerResource   = $offerResource;
        $this->offerFactory   = $offerFactory;
        $this->jsonFactory   = $jsonFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(OfferDataInterface $offer)
    {
		    $this->offerResource->save($offer);
        return $offer;

    }

    /**
     * @inheritdoc
     */
    public function get(int $offerId)
    {
		    $offer = $this->offerFactory->create();
        $this->offerResource->load($offer, $offerId);
        return $offer;  
    }

    /**
     * @inheritdoc
     */
    public function delete(OfferDataInterface $offer)
    {
		$this->offerResource->delete($offer);

    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $offerId)
    {
		$offer = $this->offerFactory->create();
		$this->offerResource->delete($offer,$offerId);
		return true;
    }

}
