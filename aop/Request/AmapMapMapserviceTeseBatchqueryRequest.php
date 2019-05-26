<?php
/**
 * ALIPAY API: amap.map.mapservice.tese.batchquery request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-05 17:25:01
 */

namespace Alipay\Request;

class AmapMapMapserviceTeseBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 高德默认配置
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
