<?php
/**
 * ALIPAY API: aft.finsecure.riskplus.security.policy.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class AftFinsecureRiskplusSecurityPolicyQueryRequest extends AbstractAlipayRequest
{
    /**
     * 策略咨询服务输出
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
