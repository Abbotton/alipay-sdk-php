<?php
/**
 * ALIPAY API: alipay.open.app.lingbalingliu.query request
 *
 * @author auto create
 *
 * @since 1.0, 2018-08-16 12:05:01
 */

namespace Alipay\Request;

class AlipayOpenAppLingbalingliuQueryRequest extends AbstractAlipayRequest
{
    /**
     * yufayanzheng
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
