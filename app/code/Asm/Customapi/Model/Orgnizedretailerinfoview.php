<?php
namespace Asm\Customapi\Model;
use Asm\Customapi\Api\OrgnizedretailerinfoInterface;
use Lof\MarketPlace\Model\ResourceModel\Group\CollectionFactory;
 
class Orgnizedretailerinfoview implements OrgnizedretailerinfoInterface
{
    /**
     * Returns greeting message to user
     *
     * @api
     * @param string $name Users name.
     * @return string Greeting message with users name.
     */
    protected $request;
    protected $_sellerCollection;
    protected $_productCollectionFactory;
    protected $groupRepository;

    public function __construct(
       \Magento\Framework\App\RequestInterface $request,
       \Lof\MarketPlace\Model\Seller $sellerCollection,
       \Lof\MarketPlace\Model\SellerProduct $sellerProductCollection,
       \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
       \Asm\Geolocation\Helper\Data $helperData,
       \Magento\Quote\Model\QuoteFactory $quoteFactory,
       CollectionFactory $collectionFactory
    ) {
       $this->request = $request;
       $this->_sellerCollection = $sellerCollection;
       $this->helperData = $helperData;
       $this->_sellerProductCollection = $sellerProductCollection;
       $this->quoteFactory = $quoteFactory;
       $this->_productCollectionFactory = $productCollectionFactory;
       $this->groupRepository = $collectionFactory;
    }

    public function orgnizedretailerinfo() 
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('\Magento\Framework\Webapi\Rest\Request');
        $post = $request->getBodyParams();
        $sellerNew = array();
        if($post){
             // Get Orgnized Retailer deatils
            $sellerCollectionDetails = $this->_sellerCollection->getCollection()->addFieldToFilter('seller_id', array('in' => $post['seller_id']));
            $sellerData = array();
            $sellerCount = count($sellerCollectionDetails->getData());  
            $groups = $this->groupRepository->create();
            if($sellerCount){
            foreach($sellerCollectionDetails as $sellcoll):
                foreach ($groups as $group) {
                    if($group->getId() == $sellcoll->getGroupId()){
                        $groupName = $group->getName();
                    }
                }
                $sellerData = $sellcoll->getData();
		 //Set kirana landline
                if ($sellerData['contact_number']) {
                   if(preg_match( '/(\d{2})(\d{4})(\d{4})$/', $sellerData['contact_number'],  $matches ) )
                   {
                        $result = '0'.$matches[1] . '-' .$matches[2] . '-' . $matches[3];
                        $sellerData['contact_number'] = $result;
                   }
                }

		//Set kirana landline
		if ($sellerData['telephone']) {
   		   if(preg_match( '/(\d{2})(\d{4})(\d{4})$/', $sellerData['telephone'],  $matches ) )
    		   {
        		$result = '0'.$matches[1] . '-' .$matches[2] . '-' . $matches[3];
		        $sellerData['telephone'] = $result;
    		   }
		}
		
		//Set kirana fax
                    if ($sellerData['kirana_fixed_line']) {
                        if(preg_match( '/(\d{2})(\d{4})(\d{4})$/', $sellerData['kirana_fixed_line'],  $matches ) )
                        {
                           $result = '0'.$matches[1] . '-' .$matches[2] . '-' . $matches[3];
                           $sellerData['kirana_fixed_line'] = $result;
                        }
                    }

                $sellerData['group_name'] = $groupName;
                $sellerNew[] = $sellerData;
            endforeach;
            }else{
                $sellerNew = array("status" => "Success","message" => "Orgnized retailer or kirana data not found");
            }
        }else
        {
            $sellerNew = array("status" => "Error","message" => "Id must requried");
        }
       $data = $sellerNew;
        return $data;
    }
   
}
