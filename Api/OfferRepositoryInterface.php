<?php

namespace Dnd\Offer\Api;

use Dnd\Offer\Api\Data\OfferDataInterface;

interface OfferRepositoryInterface
{
	/**
     * @param OfferDataInterface $offer
     */
    public function save(OfferDataInterface $offer);

    /**
     * @param int $id
     */
    public function get(int $id);

    /**
     * @param OfferDataInterface $offer
     */
    public function delete(OfferDataInterface $offer);

    /**
     * @param int $id
     */
    public function deleteById(int $id);
}