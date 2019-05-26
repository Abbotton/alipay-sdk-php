<?php
/**
 * ALIPAY API: aft.aifin.fireeye.ocr.image.query request
 *
 * @author auto create
 *
 * @since 1.0, 2019-01-07 20:51:15
 */

namespace Alipay\Request;

class AftAifinFireeyeOcrImageQueryRequest extends AbstractAlipayRequest
{
    /**
     * OCR火眼识别
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
