<?php
namespace Dnd\Offer\Ui\DataProvider\Form;
 
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{

    /**
     * @var array
     */
    protected $loadedData;
    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $offerCollectionFactory,
        DataPersistorInterface $dataPersistorInterface,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []    
    ) {
        $this->collection = $offerCollectionFactory->create();
        $this->dataPersistor = $dataPersistorInterface;
        $this->storeManager = $storeManager;
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

        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $uploaderFactory = $objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');

        foreach ($items as $item) {
            $data = $item->getData();
            $data['category_ids'] = explode(',', $data['category_ids']);
            $result = $data;
            $this->loadedData[$item->getId()] = $result;

            if ($item->getImagePath()) {
                // $result = $fileUploader->get($this->getMediaUrl($item->getImagePath()));
                // var_dump($this->getMediaUrl($item->getImagePath()));die;
                $m['image_path'][0]['name'] = $this->getImageName($item->getImagePath());
                $m['image_path'][0]['url'] = $this->getMediaUrl($item->getImagePath());
                $m['image_path'][0]['type'] = 'image/jpeg';
                $m['image_path'][0]['previewType'] = 'image';
                // $m['image_path'][0]['tmp_name'] = 'image';
                // $fileUploader = $uploaderFactory->create(['fileId' => $m['image_path'][0]]);

                // var_dump($this->getMediaUrl($item->getImagePath()));
                // die;
                // $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
                // $fileUploader->setAllowRenameFiles(true);
                // $fileUploader->setAllowCreateFolders(true);
                // $fileUploader->setFilesDispersion(false);
                // $fileUploader->validateFile();
                
                $fullData = $this->loadedData;
                $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $m);
                // var_dump($this->loadedData[$item->getId()]);die;
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
        $imgName = str_ireplace(\Dnd\Offer\Model\ImageUploader::BASE_PATH,'',$path);
        return $imgName;
    }
}