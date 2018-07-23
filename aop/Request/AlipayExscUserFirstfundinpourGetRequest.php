<?php
/**
 * ALIPAY API: alipay.exsc.user.firstfundinpour.get request
 *
 * @author auto create
 * @since  1.0, 2017-12-07 16:30:37
 */
namespace Alipay\Request;

class AlipayExscUserFirstfundinpourGetRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝 cif的accountNo 格式：支付宝userId+0156
     **/
    private $alipayId;
    private $apiParas = array();
    public function setAlipayId($alipayId)
    {
        $this->alipayId = $alipayId;
        $this->apiParas["alipay_id"] = $alipayId;
    }
    public function getAlipayId()
    {
        return $this->alipayId;
    }
}
