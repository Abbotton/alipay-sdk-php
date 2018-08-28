<?php

namespace Alipay\Signer;

use Alipay\AlipayHelper;
use Alipay\Exception\AlipayBase64Exception;
use Alipay\Exception\AlipayInvalidSignException;
use Alipay\Exception\AlipayOpenSslException;

abstract class AlipaySigner
{
    /**
     * 签名（计算 Sign 值）
     *
     * @param string   $data
     * @param resource $privateKey
     *
     * @throws AlipayOpenSslException
     * @throws AlipayBase64Exception
     *
     * @return string
     *
     * @see https://docs.open.alipay.com/291/106118
     */
    public function generate($data, $privateKey)
    {
        $result = openssl_sign($data, $sign, $privateKey, $this->getSignAlgo());
        if ($result === false) {
            throw new AlipayOpenSslException();
        }
        $encodedSign = base64_encode($sign);
        if ($encodedSign === false) {
            throw new AlipayBase64Exception($sign, true);
        }

        return $encodedSign;
    }

    /**
     * 将参数数组签名（计算 Sign 值）
     *
     * @param array    $params
     * @param resource $privateKey
     *
     * @return string
     *
     * @see self::generate
     */
    public function generateByParams($params, $privateKey)
    {
        $data = $this->convertSignData($params);

        return $this->generate($data, $privateKey);
    }

    /**
     * 验签（验证 Sign 值）
     *
     * @param string   $sign
     * @param string   $data
     * @param resource $publicKey
     *
     * @throws AlipayBase64Exception
     * @throws AlipayInvalidSignException
     * @throws AlipayOpenSslException
     *
     * @return void
     *
     * @see https://docs.open.alipay.com/200/106120
     */
    public function verify($sign, $data, $publicKey)
    {
        $decodedSign = base64_decode($sign, true);
        if ($decodedSign === false) {
            throw new AlipayBase64Exception($sign, false);
        }
        $result = openssl_verify($data, $decodedSign, $publicKey, $this->getSignAlgo());
        switch ($result) {
            case 1:
                break;
            case 0:
                throw new AlipayInvalidSignException($sign, $data);
            case -1:
                // no break
            default:
                throw new AlipayOpenSslException();
        }
    }

    /**
     * 异步通知验签（验证 Sign 值）
     *
     * @param array    $params
     * @param resource $publicKey
     *
     * @return void
     *
     * @see self::verify
     * @see https://docs.open.alipay.com/200/106120#s1
     */
    public function verifyByParams($params, $publicKey)
    {
        $sign = $params['sign'];
        $signType = $params['sign_type'];
        if ($signType !== $this->getSignType()) {
            throw new \InvalidArgumentException("Sign type didn't match, expect {$this->getSignType()}, {$signType} given");
        }
        unset($params['sign'], $params['sign_type']);

        $data = $this->convertSignData($params);
        $this->verify($sign, $data, $publicKey);
    }

    /**
     * 将数组转换为待签名数据
     *
     * @param array $params
     *
     * @return string
     */
    protected function convertSignData($params)
    {
        ksort($params);
        $stringToBeSigned = '';
        foreach ($params as $k => $v) {
            $v = @(string) $v;
            if (AlipayHelper::isEmpty($v) || $v[0] === '@') {
                continue;
            }
            $stringToBeSigned .= "&{$k}={$v}";
        }
        $stringToBeSigned = substr($stringToBeSigned, 1);

        return $stringToBeSigned;
    }

    abstract public function getSignType();

    abstract public function getSignAlgo();
}
