<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.parkinglotinfo.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-28 10:33:48
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingParkinglotinfoQueryRequest extends AbstractAlipayRequest
{
    /**
     * 停车场信息查询
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
