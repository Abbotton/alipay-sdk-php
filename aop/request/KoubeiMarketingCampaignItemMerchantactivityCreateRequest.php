<?php
/**
 * ALIPAY API: koubei.marketing.campaign.item.merchantactivity.create request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-06 17:40:00
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignItemMerchantactivityCreateRequest extends AbstractAlipayRequest
{
    /**
     * 商户创建商品代金券
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
