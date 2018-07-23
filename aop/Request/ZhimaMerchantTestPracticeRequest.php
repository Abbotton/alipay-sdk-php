<?php
/**
 * ALIPAY API: zhima.merchant.test.practice request
 *
 * @author auto create
 * @since  1.0, 2016-03-30 10:16:33
 */
namespace Alipay\Request;

class ZhimaMerchantTestPracticeRequest extends AbstractAlipayRequest
{
    /**
     * asd
     **/
    private $bizContent;
    private $apiParas = array();
    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParas["biz_content"] = $bizContent;
    }
    public function getBizContent()
    {
        return $this->bizContent;
    }
}
