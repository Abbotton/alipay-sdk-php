<?php
/**
 * ALIPAY API: alipay.commerce.data.custommetric.sync request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-22 18:10:00
 */

namespace Alipay\Request;

class AlipayCommerceDataCustommetricSyncRequest extends AbstractAlipayRequest
{
    /**
     * 商户自主监控自定义指标数据上报接口
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
