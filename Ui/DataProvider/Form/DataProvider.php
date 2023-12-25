<?php

namespace Dnd\Offer\Ui\DataProvider\Form;

use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{

    /**
     * @var \Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory
     */
    protected $collection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var array
     */
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $offerCollectionFactory,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $offerCollectionFactory->create();
        $this->storeManager = $storeManager;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }


    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $data = $item->getData();
            $data['category_ids'] = explode(',', $data['category_ids']);
            $result = $data;
            $this->loadedData[$item->getId()] = $result;

            if ($item->getImagePath()) {
                $m['image_path'][0]['name'] = $this->getImageName($item->getImagePath());
                $m['image_path'][0]['file'] = $this->getImageName($item->getImagePath());
                $m['image_path'][0]['url'] = $this->getMediaUrl($item->getImagePath());
                $m['image_path'][0]['type'] = 'image';
                $m['image_path'][0]['path'] = $this->mediaDirectory->getAbsolutePath(\Dnd\Offer\Model\ImageUploader::BASE_PATH);

                $fullData = $this->loadedData;
                $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $m);
            }
        }
        return $this->loadedData;
    }

    public function getMediaUrl($path = '')
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
        return $mediaUrl;
    }

    public function getImageName($path)
    {
        $imgName = str_ireplace(\Dnd\Offer\Model\ImageUploader::BASE_PATH, '', $path);
        return $imgName;
    }
}
