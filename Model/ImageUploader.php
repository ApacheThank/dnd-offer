<?php

namespace Dnd\Offer\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;

class ImageUploader
{

    const BASE_TMP_PATH = "tmp/imageUploader/images/";
    const BASE_PATH = "dnd-offer/images/";
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    /**
     * @var string
     */
    public $baseTmpPath;
    /**
     * @var string
     */
    public $basePath;
    /**
     * @var string[]
     */
    public $allowedExtensions;
    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    private $coreFileStorageDatabase;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ImageUploader constructor.
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem                      $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory   $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Psr\Log\LoggerInterface                           $logger
    )
    {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = self::BASE_TMP_PATH;
        $this->basePath = self::BASE_PATH;
        $this->allowedExtensions = self::ALLOWED_EXTENSIONS;
    }

    /**
     * @param $imageName
     * @return mixed
     * @throws LocalizedException
     */
    public function moveFileFromTmp($imageName)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();
        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);
        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
        return $imageName;
    }

    /**
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * @param $baseTmpPath
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param $path
     * @param $imageName
     * @return string
     */
    public function getFilePath($path, $imageName):string
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * @param $fileId
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function saveFileToTmpDir($image_path)
    {
        try {
            $fileUploader = $this->uploaderFactory->create(['fileId' => $image_path]);
            $fileUploader->setAllowedExtensions($this->getAllowedExtensions());
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);
            $fileUploader->setFilesDispersion(false);
            $fileUploader->validateFile();
            $result = $fileUploader->save($this->mediaDirectory->getAbsolutePath(self::BASE_TMP_PATH));
            $result['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . self::BASE_TMP_PATH . ltrim(str_replace('\\', '/', $result['file']), '/'); 
        } catch (LocalizedException $e) {
            $result = ['errorcode' => 0, 'error' => $e->getMessage()];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $result = ['errorcode' => 0, 'error' => __('An error occurred, please try again later.')];
        }
        return $result;
    }

    /**
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param $allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    public function saveMediaImage($imageData)
    {
        try {
            $fileUploader = $this->uploaderFactory->create(['fileId' => $imageData]);
            $fileUploader->setAllowedExtensions($this->getAllowedExtensions());
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);
            $fileUploader->validateFile();
            $info = $fileUploader->save($this->mediaDirectory->getAbsolutePath(self::BASE_PATH));
            return $this->mediaDirectory->getRelativePath(self::BASE_PATH) . $info['file'];
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the image.')
            );
            $this->logger->critical($e);
        }
    }
}
