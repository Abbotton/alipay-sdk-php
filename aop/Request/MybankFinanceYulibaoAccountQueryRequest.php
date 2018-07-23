<?php
/**
 * ALIPAY API: mybank.finance.yulibao.account.query request
 *
 * @author auto create
 * @since  1.0, 2016-11-18 16:07:46
 */

namespace Alipay\Request;

class MybankFinanceYulibaoAccountQueryRequest extends AbstractAlipayRequest
{

    /**
     * 余利宝账户和收益查询
     **/
    private $bizContent;

    private $apiParas = array();
    
    
    
    
    
    
    

    
    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParas["biz_content"] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }

    

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }




}
