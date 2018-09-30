## 🔑 生成私钥/公钥

由于支付宝官方提供的 [生成工具](https://docs.open.alipay.com/291/105971) 只支持 GUI，并且没有 Linux 版本；使用 OpenSSL 生成密钥简单快捷，两行命令即可搞定。

使用方法：`bin/genrsa`，即在当前目录生成密钥文件。

## ✍🏻 处理支付宝公钥

由于支付宝官方提供的公钥只有一行，且头尾没有包裹 `BEGIN` / `END`，所以 OpenSSL 不能正确识别。使用此工具可自动将密钥处理为正确、规范的 PEM 格式。

使用方法：`bin/wrap-key <密钥>`。

## 📦 打包 Requests

由于支付宝官方给出的 Requests 实在太多（700+），而部分平台（微擎等）发布时必须带上完整 `vendor` 文件夹，零散文件太多可能导致更新超时，所以出此下策 —— 把所有 Requests 打包成一个 PHAR 文件。

使用方法：`bin/phar-requests`，即在当前目录生成 `requests.phar` 文件。

删除 `aop/Requests` 目录后，修改 `composer.json` 的 `autoload` 部分，新增 `files` 小节：

```json
"autoload": {
    "files": [
        "requests.phar"
    ]
}
```

随后执行 `composer dumpautoload` 重新生成自动加载文件即可。

## ⚔️ <del>杀进程残留</del>

更新：`0.24.1-beta.1` 版本已修复，参见 [更新日志](https://docs.alipay.com/mini/ide/changelog#0241-beta1-20180916)。

支付宝小程序开发者工具 `0.20.1` 版本，在 macOS 平台有进程残留，占用 8999+ 端口。

目前已经反馈官方，[讨论帖](https://openclub.alipay.com/read.php?tid=8250&fid=65)，客服答复新版会修复。

临时解决方案，使用 `bin/kill-ide` 即可杀死全部进程残留。