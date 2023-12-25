<?php

namespace Dnd\Offer\Test\Unit\Controller\Adminhtml\Index;

use Dnd\Offer\Controller\Adminhtml\Index\Save;
use Dnd\Offer\Model\OfferRepository;
use Dnd\Offer\Model\OfferFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Dnd\Offer\Model\ImageUploader;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{
    private $context;
    private $offerRepository;
    private $dataPersistor;
    private $offerFactory;
    private $imageUploader;
    private $logger;
    private $save;

    protected function setUp(): void
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->offerRepository = $this->getMockBuilder(OfferRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->dataPersistor = $this->getMockBuilder(DataPersistorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->offerFactory = $this->getMockBuilder(OfferFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->imageUploader = $this->getMockBuilder(ImageUploader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->save = new Save(
            $this->context,
            $this->offerRepository,
            $this->dataPersistor,
            $this->offerFactory,
            $this->imageUploader,
            $this->logger
        );
    }

    public function testExecute()
    {
        $formData = [
            'id' => 1,
            'name' => 'Test Offer',
            'category_ids' => [1, 2, 3],
            'image_path' => [
                [
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => '/tmp/php/php6hst32',
                    'error' => 0,
                    'size' => 98174,
                    'path' => '/tmp',
                    'file' => 'php6hst32',
                ],
            ],
        ];

        $model = $this->getMockBuilder(OfferFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $model->expects($this->once())
            ->method('setData')
            ->with($formData);

        $this->offerFactory->expects($this->once())
            ->method('create')
            ->willReturn($model);

        $this->offerRepository->expects($this->once())
            ->method('save')
            ->with($model);

        $this->dataPersistor->expects($this->never())
            ->method('set');

        $this->imageUploader->expects($this->once())
            ->method('saveMediaImage')
            ->willReturn('test.jpg');

        $this->assertInstanceOf(
            \Magento\Framework\Controller\Result\Redirect::class,
            $this->save->execute()
        );
    }

    public function testExecuteWithException()
    {
        $formData = [
            'id' => 1,
            'name' => 'Test Offer',
            'category_ids' => [1, 2, 3],
            'image_path' => [
                [
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => '/tmp/php/php6hst32',
                    'error' => 0,
                    'size' => 98174,
                    'path' => '/tmp',
                    'file' => 'php6hst32',
                ],
            ],
        ];

        $exception = new \Exception('An error has occurred.');

        $this->offerFactory->expects($this->once())
            ->method('create')
            ->willThrowException($exception);

        $this->logger->expects($this->once())
            ->method('critical')
            ->with($exception);

        $this->messageManager = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->save->messageManager = $this->messageManager;

        $this->messageManager->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('An error has occurred.'));

        $this->assertInstanceOf(
            \Magento\Framework\Controller\Result\Redirect::class,
            $this->save->execute()
        );
    }
}