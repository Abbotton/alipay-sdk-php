<?php
/**
 * ALIPAY API: alipay.open.public.comptest.create request
 *
 * @author auto create
 *
 * @since 1.0, 2019-03-13 18:25:00
 */

namespace Alipay\Request;

class AlipayOpenPublicComptestCreateRequest extends AbstractAlipayRequest
{
    /**
     * 文档发布验证
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
