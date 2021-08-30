## 🔑 生成私钥/公钥

由于支付宝官方提供的 [生成工具](https://docs.open.alipay.com/291/105971) 只支持 GUI，并且没有 Linux 版本；使用 OpenSSL 生成密钥简单快捷，两行命令即可搞定。

使用方法：`bin/genrsa`，即在当前目录生成密钥文件。

## ✍🏻 处理支付宝公钥

由于支付宝官方提供的公钥只有一行，且头尾没有包裹 `BEGIN` / `END`，所以 OpenSSL 不能正确识别。使用此工具可自动将密钥处理为正确、规范的 PEM 格式。

使用方法：
```shell
# 公钥
bin/wrap-key public <密钥>
# 私钥
bin/wrap-key private <密钥>
```