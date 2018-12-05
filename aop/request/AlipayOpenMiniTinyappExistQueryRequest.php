<?php
/**
 * ALIPAY API: alipay.open.mini.tinyapp.exist.query request
 *
 * @author auto create
 *
 * @since 1.0, 2018-07-24 16:05:00
 */

namespace Alipay\Request;

class AlipayOpenMiniTinyappExistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询是否创建过小程序
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
