<?php
/**
 * ALIPAY API: koubei.marketing.campaign.item.merchantactivity.query request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-06 17:37:42
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignItemMerchantactivityQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户查询商品代金券详情
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
