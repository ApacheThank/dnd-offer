<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Dnd\Offer\Model\OfferRepository;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;

    public function __construct(
        Context $context,
        OfferRepository $offerRepository
    ) {
        parent::__construct($context);
        $this->offerRepository = $offerRepository;
    }

    /**
     * Edit action
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $title = __('New Offer');

        if ($id) {
            $model = $this->offerRepository->get($id);
            $title = __('Edit Offer: %1', $model->getName());
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Dnd_Offer::menu');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
