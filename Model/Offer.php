<?php
namespace Dnd\Offer\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Dnd\Offer\Api\Data\OfferDataInterface;
use Dnd\Offer\Model\ResourceModel;


class Offer extends AbstractModel implements IdentityInterface, OfferDataInterface
{
	const CACHE_TAG = 'offers';

	protected function _construct()
    {
        $this->_init(ResourceModel\Offer::class);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->_getData(OfferDataInterface::ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->setData(OfferDataInterface::ID, $id);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->_getData(OfferDataInterface::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->setData(OfferDataInterface::NAME, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->_getData(OfferDataInterface::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        $this->setData(OfferDataInterface::DESCRIPTION, $description);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImagePath()
    {
        return $this->_getData(OfferDataInterface::IMAGE_PATH);
    }

    /**
     * @inheritdoc
     */
    public function setImagePath($path)
    {
        $this->setData(OfferDataInterface::IMAGE_PATH, $path);

        return $this;
    }

	/**
     * @inheritdoc
     */
    public function getOfferUrl()
    {
        return $this->_getData(OfferDataInterface::OFFER_URL);
    }

    /**
     * @inheritdoc
     */
    public function setOfferUrl($url)
    {
        $this->setData(OfferDataInterface::OFFER_URL, $url);

        return $this;
    }

	/**
     * @inheritdoc
     */
    public function getDateStart()
    {
        return $this->_getData(OfferDataInterface::DATE_START);
    }

    /**
     * @inheritdoc
     */
    public function setDateStart($dateStart)
    {
        $this->setData(OfferDataInterface::DATE_START, $dateStart);

        return $this;
    }

	/**
     * @inheritdoc
     */
    public function getDateEnd()
    {
        return $this->_getData(OfferDataInterface::DATE_END);
    }

    /**
     * @inheritdoc
     */
    public function setDateEnd($dateEnd)
    {
        $this->setData(OfferDataInterface::DATE_END, $dateEnd);

        return $this;
    }

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}


    public function getCategoryIds()
    {
        return $this->_getData(OfferDataInterface::CATEGORY_IDS);
    }

    public function setCategoryIds($categoryIds)
    {
        $this->setData(OfferDataInterface::CATEGORY_IDS, $categoryIds);

        return $this;
    }
}
