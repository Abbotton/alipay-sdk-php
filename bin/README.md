# Alipay Toolkit for PHP

## 生成私钥/公钥

由于支付宝官方提供的 [生成工具](https://docs.open.alipay.com/291/105971) 只支持 GUI，并且没有 Linux 版本；使用 OpenSSL 生成密钥简单快捷，两行命令即可搞定。

使用方法：`bin/genrsa`，即在当前目录生成密钥文件。