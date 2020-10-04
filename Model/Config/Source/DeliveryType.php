<?php
/**
 * Copyright ©  2020. Mantik Tech.
 * All rights reserved under CC BY-NC-SA 4.0 licence.
 * See LICENSE file for more details.
 * @link https://www.mantik.tech/
 */

declare(strict_types=1);

namespace Mantik\Bluemail\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class DeliveryType
 */
class DeliveryType implements OptionSourceInterface
{

    /**
     * Return options array
     *
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'SEND',
                'label' => __('Send')
            ],
            [
                'value' => 'PICKUP',
                'label' => __('Pickup')
            ]
        ];
    }
}
