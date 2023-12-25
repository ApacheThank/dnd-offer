<?php

namespace Dnd\Offer\Test\Unit\Model;

use Dnd\Offer\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ImageUploaderTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var Database|\PHPUnit\Framework\MockObject\MockObject
     */
    private $coreFileStorageDatabaseMock;

    /**
     * @var WriteInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mediaDirectoryMock;

    /**
     * @var UploaderFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $uploaderFactoryMock;

    /**
     * @var StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    /**
     * @var LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $loggerMock;

    protected function setUp(): void
    {
        $this->coreFileStorageDatabaseMock = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mediaDirectoryMock = $this->getMockBuilder(WriteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->uploaderFactoryMock = $this->getMockBuilder(UploaderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filesystemMock->expects($this->once())
            ->method('getDirectoryWrite')
            ->with(DirectoryList::MEDIA)
            ->willReturn($this->mediaDirectoryMock);

        $this->imageUploader = new ImageUploader(
            $this->coreFileStorageDatabaseMock,
            $filesystemMock,
            $this->uploaderFactoryMock,
            $this->storeManagerMock,
            $this->loggerMock
        );
    }

    public function testMoveFileFromTmp()
    {
        $imageName = 'test.jpg';
        $baseTmpPath = 'tmp/imageUploader/images/';
        $basePath = 'dnd-offer/images/';
        $baseImagePath = $basePath . $imageName;
        $baseTmpImagePath = $baseTmpPath . $imageName;

        $this->coreFileStorageDatabaseMock->expects($this->once())
            ->method('copyFile')
            ->with($baseTmpImagePath, $baseImagePath);

        $this->mediaDirectoryMock->expects($this->once())
            ->method('renameFile')
            ->with($baseTmpImagePath, $baseImagePath);

        $this->imageUploader->moveFileFromTmp($imageName);
    }

    public function testGetFilePath()
    {
        $path = 'dnd-offer/images/';
        $imageName = 'test.jpg';
        $expectedResult = 'dnd-offer/images/test.jpg';

        $result = $this->imageUploader->getFilePath($path, $imageName);

        $this->assertEquals($expectedResult, $result);
    }

    // public function testSaveMediaImage()
    // {
    //     $imageData = 'test.jpg';
    //     $basePath = 'dnd-offer/images/';
    //     $expectedResult = 'dnd-offer/images/test.jpg';

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('create')
    //         ->with(['fileId' => $imageData])
    //         ->willReturnSelf();

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('setAllowedExtensions')
    //         ->with(['jpg', 'jpeg', 'png', 'gif']);

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('setAllowRenameFiles')
    //         ->with(true);

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('setAllowCreateFolders')
    //         ->with(true);

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('setFilesDispersion')
    //         ->with(false);

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('validateFile');

    //     $this->mediaDirectoryMock->expects($this->once())
    //         ->method('getAbsolutePath')
    //         ->with($basePath)
    //         ->willReturn('/var/www/html/magento/pub/media/dnd-offer/images/');

    //     $this->uploaderFactoryMock->expects($this->once())
    //         ->method('save')
    //         ->with('/var/www/html/magento/pub/media/dnd-offer/images/')
    //         ->willReturn(['file' => 'test.jpg']);

    //     $result = $this->imageUploader->saveMediaImage($imageData);

    //     $this->assertEquals($expectedResult, $result);
    // }
}