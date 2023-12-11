<?php

namespace Dnd\Offer\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class NewAction extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $result->forward('edit');
    }
}
