<?php
/**
 * ALIPAY API: alipay.trust.user.report.get request
 *
 * @author auto create
 * @since  1.0, 2018-02-02 17:40:14
 */
namespace Alipay\Request;

class AlipayTrustUserReportGetRequest extends AbstractAlipayRequest
{
    /**
     * 指定该接口在商户端的使用场景。具体枚举值在样例代码中给出
     **/
    private $scene;
    /**
     * FN_S（金融简版）
     **/
    private $type;
    private $apiParas = array();
    public function setScene($scene)
    {
        $this->scene = $scene;
        $this->apiParas["scene"] = $scene;
    }
    public function getScene()
    {
        return $this->scene;
    }
    public function setType($type)
    {
        $this->type = $type;
        $this->apiParas["type"] = $type;
    }
    public function getType()
    {
        return $this->type;
    }
}
