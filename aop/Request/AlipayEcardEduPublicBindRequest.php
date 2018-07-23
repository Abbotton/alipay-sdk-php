<?php
/**
 * ALIPAY API: alipay.ecard.edu.public.bind request
 *
 * @author auto create
 * @since  1.0, 2014-06-12 17:16:41
 */
namespace Alipay\Request;

class AlipayEcardEduPublicBindRequest extends AbstractAlipayRequest
{
    /**
     * 机构编码
     **/
    private $agentCode;
    /**
     * 公众账号协议Id
     **/
    private $agreementId;
    /**
     * 支付宝userId
     **/
    private $alipayUserId;
    /**
     * 一卡通姓名
     **/
    private $cardName;
    /**
     * 一卡通卡号
     **/
    private $cardNo;
    /**
     * 公众账号id
     **/
    private $publicId;
    private $apiParas = array();
    public function setAgentCode($agentCode)
    {
        $this->agentCode = $agentCode;
        $this->apiParas["agent_code"] = $agentCode;
    }
    public function getAgentCode()
    {
        return $this->agentCode;
    }
    public function setAgreementId($agreementId)
    {
        $this->agreementId = $agreementId;
        $this->apiParas["agreement_id"] = $agreementId;
    }
    public function getAgreementId()
    {
        return $this->agreementId;
    }
    public function setAlipayUserId($alipayUserId)
    {
        $this->alipayUserId = $alipayUserId;
        $this->apiParas["alipay_user_id"] = $alipayUserId;
    }
    public function getAlipayUserId()
    {
        return $this->alipayUserId;
    }
    public function setCardName($cardName)
    {
        $this->cardName = $cardName;
        $this->apiParas["card_name"] = $cardName;
    }
    public function getCardName()
    {
        return $this->cardName;
    }
    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
        $this->apiParas["card_no"] = $cardNo;
    }
    public function getCardNo()
    {
        return $this->cardNo;
    }
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
        $this->apiParas["public_id"] = $publicId;
    }
    public function getPublicId()
    {
        return $this->publicId;
    }
}
