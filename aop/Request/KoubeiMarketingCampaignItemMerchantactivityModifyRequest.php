<?php
/**
 * ALIPAY API: koubei.marketing.campaign.item.merchantactivity.modify request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-06 17:37:41
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignItemMerchantactivityModifyRequest extends AbstractAlipayRequest
{
    /**
     * 商户修改商品代金券
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
