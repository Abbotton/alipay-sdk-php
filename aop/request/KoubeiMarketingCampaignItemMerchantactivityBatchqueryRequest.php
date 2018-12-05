<?php
/**
 * ALIPAY API: koubei.marketing.campaign.item.merchantactivity.batchquery request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-06 17:37:37
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignItemMerchantactivityBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户查询商品代金券列表
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
