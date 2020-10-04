<?php
/**
 * Copyright ©  2020. Mantik Tech.  All rights reserved under CC BY-NC-SA 4.0 licence.
 * See LICENSE file for more details.
 * @link https://www.mantik.tech/
 */

declare(strict_types=1);

namespace Mantik\Bluemail\Helper;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    protected $configHelper;

    protected $storeManager;
    protected $productFactory;
    /**
     * @param Context $context
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Context $context,
        Config $config,
        StoreManagerInterface $storeManager,
        ProductFactory $productFactory
    ) {
        parent::__construct($context);

        $this->configHelper = $config;
        $this->storeManager = $storeManager;
        $this->productFactory = $productFactory;
    }

    public function getPackages($items)
    {
        $package=[];

        foreach ($items as $item) {
            $height = 0;
            $width = 0;
            $depth = 0;

            $product= empty($item->getProduct()) ? $this->productFactory->create()->load($item->getProductId()) : $item->getProduct();

            if ($this->configHelper->getSizeHeightAttributeId()) {
                $height = $product->getResource()->getAttributeRawValue($product->getId(), $this->configHelper->getSizeHeightAttributeId(), $this->storeManager->getStore()->getId());
            }
            if ($this->configHelper->getSizeHeightAttributeId()) {
                $width = $product->getResource()->getAttributeRawValue($product->getId(), $this->configHelper->getSizeWidthAttributeId(), $this->storeManager->getStore()->getId());
            }
            if ($this->configHelper->getSizeHeightAttributeId()) {
                $depth = $product->getResource()->getAttributeRawValue($product->getId(), $this->configHelper->getSizeDepthAttributeId(), $this->storeManager->getStore()->getId());
            }
            $package[]=[
                "weight"=> $this->weightToKg($item->getWeight()),
                "weightUnit"=> 'KG',
                "sizeHeight"=> $height,
                "sizeWidth"=> $width,
                "sizeDepth"=> $depth,
                "declaredValue"=> $item->getPrice(),
                "quantity" => $item->getQty()
            ];
        }
        return $package;
    }

    public function getDestination($order)
    {
        $street = $order->getShippingAddress()->getStreet();
        return [
            'destName' => $order->getShippingAddress()->getName(),
            'destCode' => $order->getShippingAddress()->getCustomerTaxVat() ? $order->getShippingAddress()->getTaxVat() : $order->getCustomerTaxvat(),
            'destCodeType' => 'DNI',
            'destEmail' => $order->getShippingAddress()->getEmail(),
            'destStreetName' => $street[0],
            'destStreetNumber' => isset($street[1]) ? $street[1] : '',
            'destBuildingFloor' => isset($street[2]) ? $street[2] : '',
            'destZip' => $order->getShippingAddress()->getPostCode(),
            'destTown' => $order->getShippingAddress()->getCity(),
            'destDepartmentId' => 610, //TODO: esperando lo de tiber
            'destCountryId' => Config::DEFAULT_COUNTRY,
            'destPhone' => $order->getShippingAddress()->getTelephone()
        ];
    }
    public function weightToKg($weight)
    {
        switch ($this->configHelper->getWeightUnit()) {
            case 'lb':
                $weight = $weight*.454;
        }
        return $weight;
    }
}