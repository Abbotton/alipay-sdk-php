<?php
/**
 * ALIPAY API: alipay.open.operation.openbizmock.biz.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-17 16:40:01
 */

namespace Alipay\Request;

class AlipayOpenOperationOpenbizmockBizQueryRequest extends AbstractAlipayRequest
{
    /**
     * 开放基础业务模拟查询接口
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
