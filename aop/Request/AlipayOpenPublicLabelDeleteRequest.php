<?php
/**
 * ALIPAY API: alipay.open.public.label.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:58:30
 */

namespace Alipay\Request;

class AlipayOpenPublicLabelDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 公众号标签管理-删除标签
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
