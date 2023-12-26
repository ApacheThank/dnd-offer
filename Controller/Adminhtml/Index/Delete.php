<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Dnd\Offer\Api\OfferRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepository;

    public function __construct(
        Action\Context $context,
        LoggerInterface $logger,
        OfferRepositoryInterface $offerRepository
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
    }

    /**
     * Delete action
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');


        if ($id) {
            try {
                $this->offerRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the offer.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete offer right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
            }
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
