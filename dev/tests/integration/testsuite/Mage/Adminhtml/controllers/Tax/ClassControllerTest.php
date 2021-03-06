<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @magentoAppArea adminhtml
 */
class Mage_Adminhtml_Tax_ClassControllerTest extends Mage_Backend_Utility_Controller
{
    /**
     * @dataProvider ajaxSaveActionDataProvider
     * @magentoDbIsolation enabled
     *
     * @param array $postData
     * @param array $expectedData
     */
    public function testAjaxSaveAction($postData, $expectedData)
    {
        $this->getRequest()->setPost($postData);

        $this->dispatch('backend/admin/tax_class/ajaxSave');

        $jsonBody = $this->getResponse()->getBody();
        $result = Mage::helper('Mage_Core_Helper_Data')->jsonDecode($jsonBody);

        $this->assertArrayHasKey('class_id', $result);

        $classId = $result['class_id'];
        /** @var $rate Mage_Tax_Model_Class */
        $class = Mage::getModel('Mage_Tax_Model_Class')->load($classId, 'class_id');
        $this->assertEquals($expectedData['class_name'], $class->getClassName());
    }

    /**
     * @return array
     */
    public function ajaxSaveActionDataProvider()
    {
        return array(
            array(
                array(
                    'class_type' => 'CUSTOMER',
                    'class_name' => 'Class Name'
                ),
                array(
                    'class_name' => 'Class Name'
                )
            ),
            array(
                array(
                    'class_type' => 'PRODUCT',
                    'class_name' => '11111<22222'
                ),
                array(
                    'class_name' => '11111&lt;22222'
                )
            ),
            array(
                array(
                    'class_type' => 'CUSTOMER',
                    'class_name' => '   12<>sa&df    '
                ),
                array(
                    'class_name' => '12&lt;&gt;sa&amp;df'
                )
            ),
        );
    }
}
