<?php
/**
 * ALIPAY API: zoloz.authentication.customer.smilepay.initialize request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class ZolozAuthenticationCustomerSmilepayInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 人脸初始化唤起zim
     **/
    private $bizContent;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }
}
