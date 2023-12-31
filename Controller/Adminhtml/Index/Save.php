<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Dnd\Offer\Model\OfferRepository;
use Dnd\Offer\Model\OfferFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Dnd\Offer\Model\ImageUploader;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Psr\Log\LoggerInterface;

class Save extends \Magento\Backend\App\Action
{
    protected $messageManager;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    public function __construct(
        Context                $context,
        OfferRepository        $offerRepository,
        DataPersistorInterface $dataPersistor,
        OfferFactory           $offerFactory,
        ImageUploader          $imageUploader,
        LoggerInterface        $logger,
        FileSystem             $filesystem,
    )
    {
        parent::__construct($context);
        $this->offerRepository = $offerRepository;
        $this->dataPersistor = $dataPersistor;
        $this->offerFactory = $offerFactory;
        $this->imageUploader = $imageUploader;
        $this->logger = $logger;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);

    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed("Dnd_Offer::menu");
    }

    /**
     * Save action
     */
    public function execute()
    {
        $formData = $this->getRequest()->getPostValue();

        try {
            $data = $formData;

            $categoryIdsString = implode(',', $formData['category_ids']);
            $data['category_ids'] = $categoryIdsString;

            $model = isset($formData['id'])
                ? $this->offerRepository->get($formData['id'])
                : $this->offerFactory->create();

            $model->setData($data);

            $model = $this->imageData($model, $data);
            $this->offerRepository->save($model);
            $this->messageManager->addSuccessMessage(__('You saved the item.'));
            
            if ($this->getRequest()->getParam('back')) {
                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/edit',
                    ['id' => $model->getId(), '_current' => true]
                );
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $this->redirectIfError($formData);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error has occurred.'));
            $this->logger->critical($e);

            return $this->redirectIfError($formData);
        }

        return $this->resultRedirectFactory->create()->setPath('*/*');
    }

    /**
     * @param array $formData
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    private function redirectIfError($formData)
    {
        $this->dataPersistor->set('formData', $formData);

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = (int)$this->getRequest()->getParam('id')) {
            $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        } else {
            $resultRedirect->setPath('*/*/new');
        }

        return $resultRedirect;
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function imageData($model, $data)
    {
        $imageData = 'image_path';
        if (isset($data['image_path']) && count($data['image_path'])) {
            $imageData = $data['image_path'][0];
            if (isset($imageData['url']) && !isset($imageData['path'])){
                $imageData['tmp_name'] = $this->mediaDirectory->getAbsolutePath(\Dnd\Offer\Model\ImageUploader::BASE_PATH). $imageData['name'];
            } else {
                $imageData['tmp_name'] = $imageData['path'] . '/' . $imageData['file'];
            }
        }
        
        if ($model->getId()) {
            $pageData = $this->offerFactory->create();
            $pageData->load($model->getId());
            if (isset($data['image_path'][0]['name'])) {
                    $data['image_path'] = $this->imageUploader->saveMediaImage($imageData);
            } else {
                $data['image_path'] = '';
            }
        } else {
            if (isset($data['image_path'][0]['name'])) {
                $data['image_path'] = $this->imageUploader->saveMediaImage($imageData);
            }
        }
        $model->setData($data);
        return $model;
    }
}
