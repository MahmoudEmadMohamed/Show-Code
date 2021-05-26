<?php


namespace ShopForBuild\CustomVendors\Model\Api;

use Magento\Framework\App\ResourceConnection ;
use Magento\Customer\Model\Session;
use Magento\Sales\Model\Order;

class CustomPlaceOrder
{

    /**
     * Database connection Object
     * @var \Magento\Framework\App\ResourceConnection $connection
    */
    protected $_Connection;

    /**
     * Customer Session Object
     * @var \Magento\Customer\Model\Session $Session
     */
    protected $_Customer;

    /**
     * Order Object
     * @var \Magento\Sales\Model\Order $order
     */
    protected $_Order;

    public function __construct(
        ResourceConnection $connection,
        Session $customer,
        Order $order
    )
    {
        $this->_Connection = $connection->getConnection('mega_dev');
        $this->_Customer = $customer;
        $this->_Order = $order;
    }

    /**
     * @api
     * Decrease Vendor's Balance if payment for login Customer
     * @return String
     */
    public function afterPlaceOrderCustomer()
    {
        $_CustomerId =  $this->_Customer->getCustomer()->getId();
        $_CustomerEmail =  $this->_Customer->getCustomer()->getEmail();

        return $this->getData($_CustomerEmail);
    }

    /**
     * @api
     * Decrease Vendor's Balance if payment for guest
     * @param string $email
     * @return string
     */
    public function afterPlaceOrderGuest($email)
    {
        $balance = $this->getData($email);
        return $balance;
    }

    /**
     * Load Order Data using Customer Email
     * @param string $email
    */
    protected function getData($email)
    {
//        $order = $this->_Order->getCollection()
//            ->addAttributeToFilter('customer_email', $email)->getLastItem();
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $order3 = $objectManager->create('Magento\Sales\Model\Order')
            ->getCollection()
            ->addAttributeToFilter('customer_email', $email)->getLastItem();
        $orderId = $order3->getId();
        $orderItems = $order3->getAllItems();

        foreach ($orderItems as $item) {
            if($orderItems[0] === $item)continue;

            $vendor_id=   $item->getVendorId();

            //fetch whole payment information
            $paymentMethod =$order3->getPayment()->getData();

            if($paymentMethod['additional_information']['method_title'] == 'Cash On Delivery')
            {
                if($item->getIsVirtual())continue;
                return $this->updateVendorBalance($vendor_id, $orderId);
            }

        }
        return ["Error"];
    }

    /**
     * Update Database tables
     * @param int $vendorId
     * @param int $order_id
     * @return int
    */
    protected function updateVendorBalance($vendorId, $order_id)
    {
        try {
            // $table is table name
            $table = $this->_Connection->getTableName('ves_vendor_sales_order');
            $query = "SELECT * FROM `" . $table . "` WHERE vendor_id = '$vendorId' AND order_id = '$order_id' ";
            $result = $this->_Connection->fetchAll($query);

            $table = $this->_Connection->getTableName('ves_store_credit');
            $query2 = "SELECT * FROM `" . $table . "` WHERE customer_id = '$vendorId'";
            $result2 = $this->_Connection->fetchAll($query2);

            $cost =  $result[0]['total_due'] ;
            $balance = $result2[0]['credit'];

            $newBalance = $balance - $cost ;


            $query3 = "UPDATE `" . $table . "` SET credit =$newBalance  WHERE customer_id = '$vendorId'";
            $result3 = $this->_Connection->query($query3);
            return $newBalance;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
