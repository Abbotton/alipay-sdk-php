<?php
/**
 * ALIPAY API: alipay.open.agent.signstatus.query request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-12 18:40:00
 */

namespace Alipay\Request;

class AlipayOpenAgentSignstatusQueryRequest extends AbstractAlipayRequest
{
    /**
     * isv查询商户某个产品的签约状态
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
