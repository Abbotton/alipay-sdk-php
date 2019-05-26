<?php
/**
 * ALIPAY API: alipay.marketing.exchangevoucher.template.create request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class AlipayMarketingExchangevoucherTemplateCreateRequest extends AbstractAlipayRequest
{
    /**
     * 兑换券模板创建接口
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
