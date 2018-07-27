<?php
/**
 * ALIPAY API: alipay.open.app.notify.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 16:11:37
 */

namespace Alipay\Request;

class AlipayOpenAppNotifyVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 验证通知
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
