<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /** @var PageFactory */
    private $pageFactory;

    public function __construct(
        Context     $context,
        PageFactory $rawFactory
    )
    {
        $this->pageFactory = $rawFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        // $resultPage->setActiveMenu('Dnd_Offer::menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Offers list'));

        return $resultPage;
    }
}
