<?php
/**
 * ALIPAY API: alipay.mobile.beacon.device.query request
 *
 * @author auto create
 * @since  1.0, 2017-02-28 11:12:47
 */
namespace Alipay\Request;

class AlipayMobileBeaconDeviceQueryRequest extends AbstractAlipayRequest
{
    /**
     * 设备的UUID
     **/
    private $bizContent;
    private $apiParas = array();
    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParas["biz_content"] = $bizContent;
    }
    public function getBizContent()
    {
        return $this->bizContent;
    }
}
