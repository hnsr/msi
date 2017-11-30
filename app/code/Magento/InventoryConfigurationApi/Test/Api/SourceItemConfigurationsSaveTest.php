<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryConfigurationApi\Test\Api;

use Magento\Framework\Webapi\Rest\Request;
use Magento\InventoryConfigurationApi\Api\Data\SourceItemConfigurationInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

class SourceItemConfigurationsSaveTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/inventory/source-item-configuration';
    const SERVICE_NAME_GET = 'inventoryConfigurationApiGetSourceItemConfigurationV1';
    const SERVICE_NAME_SAVE = 'inventoryConfigurationApiSourceItemConfigurationsSaveV1';

    /**
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/source_items.php
     */
    public function testSaveSourceItemConfiguration()
    {
        $sourceItemConfigurations = [
            [
                SourceItemConfigurationInterface::SOURCE_ID => 10,
                SourceItemConfigurationInterface::SKU => 'SKU-1',
                SourceItemConfigurationInterface::INVENTORY_NOTIFY_QTY => 2,
            ],
            [
                SourceItemConfigurationInterface::SOURCE_ID => 20,
                SourceItemConfigurationInterface::SKU => 'SKU-1',
                SourceItemConfigurationInterface::INVENTORY_NOTIFY_QTY => 1,
            ]
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME_SAVE,
                'operation' => self::SERVICE_NAME_SAVE . 'Execute',
            ],
        ];

        $this->_webApiCall($serviceInfo, ['sourceItemConfigurations' => $sourceItemConfigurations]);

        $sourceItemConfiguration = $this->getSourceItemConfiguration(10, 'SKU-1');
        self::assertNotEmpty($sourceItemConfiguration);
        self::assertEquals($sourceItemConfigurations[0], $sourceItemConfiguration);

        $sourceItemConfiguration = $this->getSourceItemConfiguration(20, 'SKU-1');
        self::assertNotEmpty($sourceItemConfiguration);
        self::assertEquals($sourceItemConfigurations[1], $sourceItemConfiguration);
    }

    /**
     * @param int $sourceId
     * @param string $sku
     * @return array|bool|float|int|string
     */
    private function getSourceItemConfiguration(int $sourceId, string $sku)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $sourceId . '/' . $sku,
                'httpMethod' => Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME_GET,
                'operation' => self::SERVICE_NAME_GET . 'get',
            ],
        ];

        return (TESTS_WEB_API_ADAPTER === self::ADAPTER_REST)
            ? $this->_webApiCall($serviceInfo)
            : $this->_webApiCall($serviceInfo, ['sourceId' => $sourceId, 'sku' => $sku]);
    }
}
