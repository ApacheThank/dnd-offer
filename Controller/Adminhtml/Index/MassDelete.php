<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Dnd\Offer\Api\OfferRepositoryInterface;
use Dnd\Offer\Model\ResourceModel\Offer\Collection;
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CollectionFactory
     */
    private $offerCollectionFactory;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepository;

    public function __construct(
        Action\Context $context,
        Filter $filter,
        LoggerInterface $logger,
        CollectionFactory $offerCollectionFactory,
        OfferRepositoryInterface $offerRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->logger = $logger;
        $this->offerCollectionFactory = $offerCollectionFactory;
        $this->offerRepository = $offerRepository;
    }

    /**
     * Mass action execution
     *
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->filter->applySelectionOnTargetProvider();

        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->offerCollectionFactory->create());
        $deletedCookieGroups = 0;

        if ($collection->count() > 0) {
            try {
                foreach ($collection->getItems() as $offer) {
                    $this->offerRepository->delete($offer);
                    $deletedCookieGroups++;
                }

                $this->messageManager->addSuccessMessage(
                    __('%1 offer(s) has been successfully deleted', $deletedCookieGroups)
                );

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error has occurred'));
                $this->logger->critical($e);
            }
        }

        return $this->resultRedirectFactory->create()->setRefererUrl();
    }
}
