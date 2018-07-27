<?php
/**
 * ALIPAY API: alipay.offline.market.shop.summary.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-09 13:39:45
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopSummaryBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 门店摘要信息批量查询接口
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
