<?php
/**
 * ALIPAY API: zhima.customer.auth.mutualview.apply request
 *
 * @author auto create
 *
 * @since 1.0, 2018-09-03 20:55:00
 */

namespace Alipay\Request;

class ZhimaCustomerAuthMutualviewApplyRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻信用互查申请
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
