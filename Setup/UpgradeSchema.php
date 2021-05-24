<?php
namespace ShopForBuild\CustomVendors\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('catalog_product_entity'),
                'internal_sku',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'LENGTH' =>64,
                    'nullable' => true,
                    'comment' => 'Vendor SKU'
                ]
            );

        }

        $setup->endSetup();
	}
}
