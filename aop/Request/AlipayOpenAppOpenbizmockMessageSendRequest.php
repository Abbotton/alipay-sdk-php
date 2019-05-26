<?php
/**
 * ALIPAY API: alipay.open.app.openbizmock.message.send request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-11 18:35:00
 */

namespace Alipay\Request;

class AlipayOpenAppOpenbizmockMessageSendRequest extends AbstractAlipayRequest
{
    /**
     *  模拟业务系统上行消息接口
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
