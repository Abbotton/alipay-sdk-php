<?php
/**
 * ALIPAY API: koubei.marketing.campaign.crowd.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:17:50
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignCrowdDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户人群组删除接口
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
