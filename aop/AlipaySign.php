<?php
/**
 * Created by PhpStorm.
 * User: jiehua
 * Date: 15/5/2
 * Time: 下午6:21
 */

namespace Alipay;

use Alipay\Exception\AlipayInvalidSignException;
use Alipay\Exception\AlipayBase64Exception;
use Alipay\Exception\AlipayOpenSslException;


class AlipaySign
{
    /**
     * 签名类型
     *
     * @var string
     */
    protected $type = 'RSA2';

    /**
     * 商户私钥（又称：小程序私钥，App私钥等）
     * 支持文件路径或私钥字符串，用于生成签名
     *
     * @var string
     */
    protected $appPrivateKey;

    /**
     * 支付宝公钥
     * 支持文件路径或公钥字符串，用于验证签名
     *
     * @var string
     */
    protected $alipayPublicKey;

    /**
     * 创建 AlipaySign 实例
     *
     * @param  string $signType
     * @param  string $appPrivateKey
     * @param  string $alipayPublicKey
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function create($appPrivateKey, $alipayPublicKey, $signType = 'RSA2')
    {
        $instance = new static();
        $typeAlgoMap = $instance->typeAlgoMap();
        if(!isset($typeAlgoMap[$signType])) {
            throw new \InvalidArgumentException('Unknown sign type: ' . $signType);
        }
        $instance->type = $signType;
        $instance->appPrivateKey = $instance->getKey($appPrivateKey, true);
        $instance->alipayPublicKey = $instance->getKey($alipayPublicKey, false);
        return $instance;
    }

    protected function __construct()
    {
    }

    public function __destruct()
    {
        @openssl_free_key($this->appPrivateKey);
        @openssl_free_key($this->alipayPublicKey);
    }

    /**
     * 签名（计算 Sign 值）
     *
     * @param string $data
     * @return void
     * @throws AlipayOpenSslException
     * @throws AlipayBase64Exception
     * @see https://docs.open.alipay.com/291/106118
     */
    public function generate($data)
    {
        $result = openssl_sign($data, $sign, $this->appPrivateKey, $this->getSignAlgo());
        if($result === false) {
            throw new AlipayOpenSslException(openssl_error_string());
        }
        $encodedSign = base64_encode($sign);
        if($encodedSign === false)
        {
            throw new AlipayBase64Exception($sign, true);
        }
        return $encodedSign;
    }

    public function generateByParams($params)
    {
        $data = $this->convertSignData($params);
        return $this->generate($data);
    }

    /**
     * 验签（验证 Sign 值）
     *
     * @param string $sign
     * @param string $data
     * @return void
     * @throws AlipayBase64Exception
     * @throws AlipayInvalidSignException
     * @throws AlipayOpenSslException
     * @see https://docs.open.alipay.com/200/106120
     */
    public function verify($sign, $data)
    {
        $decodedSign = base64_decode($sign, true);
        if($decodedSign === false) {
            throw new AlipayBase64Exception($sign, false);
        }
        $result = openssl_verify($data, $decodedSign, $this->alipayPublicKey, $this->getSignAlgo());
        switch($result)
        {
            case 1:
            break;
            case 0:
            throw new AlipayInvalidSignException($sign, $data);
            case -1:
            throw new AlipayOpenSslException(openssl_error_string());
        }
    }

    /**
     * 使用密钥字符串或路径加载密钥
     *
     * @param  string  $keyOrFilePath
     * @param  boolean $isPrivate
     * @return resource
     * @throws AlipayInvalidKeyException
     */
    protected function getKey($keyOrFilePath, $isPrivate = true)
    {
        if (file_exists($keyOrFilePath) && is_file($keyOrFilePath)) {
            $key = file_get_contents($keyOrFilePath);
        } else {
            $key = $keyOrFilePath;
        }
        if ($isPrivate) {
            $keyResource = openssl_pkey_get_private($key);
        } else {
            $keyResource = openssl_pkey_get_public($key);
        }
        if ($keyResource === false) {
            throw new AlipayInvalidKeyException('Invalid key: ' . $keyOrFilePath);
        }
        return $keyResource;
    }

    /**
     * 将数组转换为待签名数据
     *
     * @param array $params
     * @return void
     */
    protected function convertSignData($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        foreach ($params as $k => $v) {
            if (false === AlipayHelper::isEmpty($v) && "@" !== substr($v, 0, 1)) {
                $stringToBeSigned .= "&{$k}={$v}";
            }
        }
        $stringToBeSigned = substr($stringToBeSigned, 1);
        return $stringToBeSigned;
    }

    protected function typeAlgoMap()
    {
        return [
            'RSA' => OPENSSL_ALGO_SHA1,
            'RSA2' => OPENSSL_ALGO_SHA256,
        ];
    }

    public function getSignType()
    {
        return $this->type;
    }

    public function getSignAlgo()
    {
        return $this->typeAlgoMap()[$this->type];
    }
}
