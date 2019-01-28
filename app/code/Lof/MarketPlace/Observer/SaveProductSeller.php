<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://www.landofcoder.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Landofcoder
 * @package    Lof_MarketPlace
 * @copyright  Copyright (c) 2014 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */
namespace Lof\MarketPlace\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveProductSeller implements ObserverInterface
{
    /**
     * Catalog data
     *
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogData;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;


    protected $sellerproduct;

    /**
     * @param \Magento\Catalog\Helper\Data $catalogData
     */
    public function __construct(
        \Lof\MarketPlace\Model\SellerProduct $sellerproduct,
        \Magento\Framework\App\ResourceConnection $resource
        )
    {
        $this->sellerproduct = $sellerproduct;
        $this->_resource = $resource;
    }

    /**
     * Checking whether the using static urls in WYSIWYG allowed event
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test1234.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('Lof\MarketPlace\Observer\SaveProductSeller');
        $connection = $this->_resource->getConnection();
        $table_name = $this->_resource->getTableName('lof_marketplace_product');
        $productController = $observer->getController();
        $productId = $productController->getRequest()->getParam('id');
        $data = $productController->getRequest()->getPostValue();

        if(isset($data['product']['seller_id']) && $data['product']['seller_id'] > 0 && $productId){
            $status = $data['product']['approval'];
            $product_name = $data['product']['name'];
            $productSellers = $data['product']['seller_id'];
            $product_sku = $data['product']['sku'];
            $product_price = $data['product']['price'];
            if(!is_array($productSellers)){
                $productSellers = array();
                $productSellers[] = (int)$data['product']['seller_id'];
            }
            $sellerproduct = $this->sellerproduct->getCollection()->addFieldToFilter('product_id',$productId)->getFirstItem();

            if(count($sellerproduct->getData()) >0) {
                foreach ($productSellers as $k => $v) {
                $connection->query('UPDATE ' . $table_name . ' SET sku =  "'.$product_sku.'",price =  "'.$product_price.'",  product_name = "'.$product_name.'", status = '.$status.' WHERE seller_id = '.$v.' AND product_id = '.(int)$productId);
                }
            } else {
                $connection->query('DELETE FROM ' . $table_name . ' WHERE product_id =  ' . (int)$productId . ' ');
                foreach ($productSellers as $k => $v) {
                    $connection->query('INSERT INTO ' . $table_name . ' (seller_id,product_id,status,product_name,price,sku) VALUES ( ' . $v . ', ' . (int)$productId . ', '.$status.', "'.$product_name.'", "'.$product_price.'", "'.$product_sku.'")');
                }
            }

        }
    }
}
