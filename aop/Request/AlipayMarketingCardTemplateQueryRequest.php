<?php
/**
 * ALIPAY API: alipay.marketing.card.template.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 10:50:00
 */

namespace Alipay\Request;

class AlipayMarketingCardTemplateQueryRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡模板查询接口
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
