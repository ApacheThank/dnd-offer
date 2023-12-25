<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Cookie Consent (GDPR) for Magento 2
 */

namespace Dnd\Offer\Api\Data;

interface OfferDataInterface
{
    public const ID = 'id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const IMAGE_PATH = 'image_path';
    public const OFFER_URL = 'offer_url';
    public const CATEGORY_IDS = 'category_ids';
    public const DATE_START = 'date_start';
    public const DATE_END = 'date_end';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     *
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getImagePath();

    /**
     * @param string $path
     *
     */
    public function setImagePath($path);

    /**
     * @return string
     */
    public function getOfferUrl();

    /**
     * @param string $url
     *
     */
    public function setOfferUrl($url);

    /**
     * @return string
     */
    public function getDateStart();

    /**
     * @param string $dateStart
     */
    public function setDateStart($dateStart);

    /**
     * @return string
     */
    public function getDateEnd();

    /**
     * @param string $dateEnd
     */
    public function setDateEnd($dateEnd);

    /**
     * @return string
     */
    public function getCategoryIds();

    /**
     * @param string $ids
     */
    public function setCategoryIds($ids);

}
