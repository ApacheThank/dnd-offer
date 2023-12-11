<?php
namespace Dnd\Offer\Ui\DataProvider\Offer\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{

      protected function _initSelect()
      {
          $this->addFilterToMap('id', 'main_table.id');
          $this->addFilterToMap('name', 'main_table.name');
          parent::_initSelect();
      }
}
