<?php
/**
 * Copyright ©  2020. Mantik Tech.  All rights reserved under CC BY-NC-SA 4.0 licence.
 * See LICENSE file for more details.
 * @link https://www.mantik.tech/
 */

namespace Mantik\Bluemail\Block\Adminhtml\Region;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

/**
 * Class FormContainer
 * @package Mantik\Bluemail\Block\Adminhtml\Region
 */
class Edit extends Container
{
    protected $regionCollectionFactory;

    public function __construct(
        Context $context,
        array $data = [],
        RegionCollectionFactory $regionCollectionFactory
    ) {
        $this->regionCollectionFactory = $regionCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Initialize form.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Mantik_Bluemail';
        $this->_controller = 'adminhtml_region';

        parent::_construct();

        $this->removeButton('reset');
        $this->removeButton('delete');

        if (!$this->hasArRegion()) {
            $this->addButton(
                'import',
                [
                    'label' => __('Import Regions'),
                    'class' => 'delete',
                    'onclick' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getImportUrl() . '\', {data: {}})'
                ]
            );
        }
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('adminhtml/system_config/edit', ['section' => 'carriers']) . '"#carriers_bluemail-link';
    }

    /**
     * Get URL for import button
     *
     * @return string
     */
    public function getImportUrl()
    {
        return $this->getUrl('bluemail/region/import');
    }

    private function hasArRegion()
    {
        $regionCollection = $this->regionCollectionFactory->create();
        $regionCollection->addFieldToFilter(
            'country_id',
            'AR'
        ); //TODO maybe make it an option, at least some class constant?
        return $regionCollection->count() > 0;
    }
}
