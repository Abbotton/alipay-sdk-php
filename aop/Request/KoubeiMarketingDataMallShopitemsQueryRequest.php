<?php
/**
 * ALIPAY API: koubei.marketing.data.mall.shopitems.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class KoubeiMarketingDataMallShopitemsQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商圈门店以及门店下面优惠券商品信息
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
