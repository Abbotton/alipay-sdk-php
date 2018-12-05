<?php
/**
 * ALIPAY API: koubei.marketing.campaign.item.merchantactivity.close request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-06 17:40:00
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignItemMerchantactivityCloseRequest extends AbstractAlipayRequest
{
    /**
     * 商户下架代金券
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
