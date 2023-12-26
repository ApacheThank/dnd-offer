<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Dnd\Offer\Model\ImageUploader;

class Upload extends \Magento\Backend\App\Action
{
    public function __construct(
        Context       $context,
        ImageUploader $imageUploader
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed("Dnd_Offer::menu");
    }
    
    public function execute()
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result = $this->imageUploader->saveFileToTmpDir('image_path');
        return $jsonResult->setData($result);
    }
}
