# RSA-JS-PHP
> 前后端交互时为了保证信息安全可使用RSA方式加密信息，在数据量大的时候可采用AES+RSA结合方式。[DEMO演示地址](https://api.wm07.cn/test/rsa-js-php/rsa.html)
## 一点历史
1976年以前，所有的加密方法都是同一种模式：  
（1）甲方选择某一种加密规则，对信息进行加密；  
（2）乙方使用同一种规则，对信息进行解密。由于加密和解密使用同样规则（简称"密钥"），这被称为"对称加密算法"（Symmetric-key algorithm）。  
这种加密模式有一个最大弱点：甲方必须把加密规则告诉乙方，否则无法解密。保存和传递密钥，就成了最头疼的问题。  
1977年，三位数学家Rivest、Shamir 和 Adleman 设计了一种算法，可以实现非对称加密。这种算法用他们三个人的名字命名，叫做RSA算法。从那时直到现在，RSA算法一直是最广为使用的"非对称加密算法"。毫不夸张地说，只要有计算机网络的地方，就有RSA算法。
## 算法原理
- RSA算法的主要原理是利用了数论中质数的巧妙关系即[欧拉定理](https://baike.baidu.com/item/%E6%AC%A7%E6%8B%89%E5%AE%9A%E7%90%86/891345?fr=aladdin)，要实现RSA算法需找到三个具有特定关系的值，在此命名为n、e、d；
- 假设有两个值互为质数的正整数p和q,(为了便于运算演示，取的值比较小，通常情况下是取的非常大的值，值越大破解的难度越大)，p=5 q=17 即 p和q的乘积为n=5x17=85； 
- 计算得出n的[欧拉函数](https://baike.baidu.com/item/%E6%AC%A7%E6%8B%89%E5%87%BD%E6%95%B0/1944850?fr=aladdin)φ(n)=(p-1)(q-1)=64，在区间(1,64)中随机选择一个数e=13,，需保证e和φ(n)为互质关系；
- 计算e对于φ(n)的[模反元素](https://baike.baidu.com/item/%E6%A8%A1%E5%8F%8D%E5%85%83%E7%B4%A0/20417595?fr=aladdin)d，得出d=5；至此，已经得到了三个具有特定关系的值 n=85 e=13 d=5，设公钥为(n,e)，私钥即为(n,d)；
- 假设用户发送数字3到服务端，通过RSA加密的过程为：m=(3^13) mod 85=63，mod为求模，得到密文63；
- 服务端收到密文m=63，解密过程为 s=(63^5) mod 85=3，最终得出原文为3；
- 加解密关系为 m=(s^e) mod n，s=(m^d) mod n；私钥与公钥可互相使用，只需要保护一个不被泄露即可；
- 于是私钥泄露就意味着RSA加密失去意义；
## 使用方式
> 请确保PHP的openssl扩展开启，且保证php在环境变量中，如果是windows需添加环境变量：名 OPENSSL_CONF，值 D:\http\php\extras\ssl\openssl.cnf(根据openssl.cnf目录而定)；
- 生成新的公私钥文件在项目根目录命令行运行：
```bash
php rsa.php new
```
- 保护好私钥，确保私钥不被暴露在web可访问目录下；
- 将生成的rsa_pubkey.js引入到web项目中，具体请运行DEMO演示即可；
- rsa.class.php的使用：
```php
// 初始化参数，设置公钥与私钥的路径
rsa::$prikey = 'src/key/private.pem';
rsa::$pubkey = 'src/key/public.pem';

// JavaScript脚本生成位置，用于重新生成公私钥
rsa::$script = 'rsa_pubkey.js';

// $model = 1 公钥加密，私钥解密：公开公钥，保存私钥
// $model = 2 私钥加密，公钥解密：公开私钥，保存公钥
rsa::$model = 1;

// RSA加密
rsa::encrypt($data);

// RSA解密
rsa::decrypt($data);

// RSA签名
rsa::sign($data);

// RSA验签
rsa::verify($data, $sign);
```
>Javascript加密来源于开源项目[jsencrypt](https://github.com/travist/jsencrypt)