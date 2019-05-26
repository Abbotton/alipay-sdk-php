<?php
/**
 * ALIPAY API: alipay.user.twostage.common.use request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-16 14:40:00
 */

namespace Alipay\Request;

class AlipayUserTwostageCommonUseRequest extends AbstractAlipayRequest
{
    /**
     * 通用当面付二阶段接口
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
