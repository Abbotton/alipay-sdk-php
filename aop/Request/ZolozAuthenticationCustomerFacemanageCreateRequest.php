<?php
/**
 * ALIPAY API: zoloz.authentication.customer.facemanage.create request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class ZolozAuthenticationCustomerFacemanageCreateRequest extends AbstractAlipayRequest
{
    /**
     * 热脸入库
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
