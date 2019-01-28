<?php
namespace Asm\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart as CustomerCart;

class Clearcart extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @param Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param CustomerCart $cart
     */
    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        CustomerCart $cart
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;

        parent::__construct($context);
    }

    public function execute()
    {
        $this->deleteQuoteItems();

        // $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();
        // foreach ($allItems as $item) {
        //     $itemId = $item->getItemId();
        //     $this->cart->removeItem($itemId)->save();
        // }

        // $message = __(
        //     'You deleted all item from shopping cart.'
        // );
        // $this->messageManager->addSuccessMessage($message);

        // $response = [
        //     'success' => true,
        // ];

        // $this->getResponse()->representJson(
        //     $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
        // );
    }

     public function deleteQuoteItems(){
        $checkoutSession = $this->getCheckoutSession();
        $allItems = $checkoutSession->getQuote()->getAllVisibleItems();//returns all teh items in session
        foreach ($allItems as $item) {
            $itemId = $item->getItemId();//item id of particular item
            //$quoteItem=$this->getItemModel()->load($itemId);//load particular item which you want to delete by his item id
            $this->cart->removeItem($itemId)->save();
        }
        $message = __(
                'You deleted all item from shopping cart.'
            );
            $this->messageManager->addSuccessMessage($message);

            $response = [
                'success' => true,
            ];

            $this->getResponse()->representJson(
                $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
        );
    }

    public function getCheckoutSession(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();//instance of object manager 
        $checkoutSession = $objectManager->get('Magento\Checkout\Model\Session');//checkout session
        return $checkoutSession;
        //echo "<pre>"; print_r($checkoutSession->getData()); die();
    }
}