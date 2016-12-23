<?php
/**
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2013 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA\Keys\PKCS1;
use phpseclib\Crypt\RSA\Keys\PKCS8;
use phpseclib\Crypt\RSA\Keys\PuTTY;
use phpseclib\Math\BigInteger;

class Unit_Crypt_RSA_LoadKeyTest extends PhpseclibTestCase
{
    public function testBadKey()
    {
        $rsa = new RSA();

        $key = 'zzzzzzzzzzzzzz';

        $this->assertFalse($rsa->load($key));
    }

    public function testPKCS1Key()
    {
        $rsa = new RSA();

        $key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp
wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5
1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh
3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2
pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX
GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il
AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF
L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k
X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl
U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ
37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0=
-----END RSA PRIVATE KEY-----';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPrivateKey());
    }

    public function testPKCS1SpacesKey()
    {
        $rsa = new RSA();

        $key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp
wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5
1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh
3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2
pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX
GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il
AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF
L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k
X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl
U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ
37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0=
-----END RSA PRIVATE KEY-----';
        $key = str_replace(array("\r", "\n", "\r\n"), ' ', $key);

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPrivateKey());
    }

    public function testPKCS1NoHeaderKey()
    {
        $rsa = new RSA();

        $key = 'MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp
wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5
1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh
3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2
pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX
GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il
AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF
L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k
X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl
U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ
37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0=';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPrivateKey());
    }

    public function testPKCS1NoWhitespaceNoHeaderKey()
    {
        $rsa = new RSA();

        $key = 'MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp' .
               'wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5' .
               '1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh' .
               '3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2' .
               'pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX' .
               'GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il' .
               'AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF' .
               'L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k' .
               'X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl' .
               'U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ' .
               '37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0=';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPrivateKey());
    }

    public function testRawPKCS1Key()
    {
        $rsa = new RSA();

        $key = 'MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp' .
               'wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5' .
               '1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh' .
               '3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2' .
               'pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX' .
               'GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il' .
               'AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF' .
               'L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k' .
               'X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl' .
               'U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ' .
               '37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0=';
        $key = base64_decode($key);

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPrivateKey());
    }

    public function testLoadPKCS8PrivateKey()
    {
        $rsa = new RSA();
        $rsa->setPassword('password');

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE6TAbBgkqhkiG9w0BBQMwDgQIcWWgZeQYPTcCAggABIIEyLoa5b3ktcPmy4VB
hHkpHzVSEsKJPmQTUaQvUwIp6+hYZeuOk78EPehrYJ/QezwJRdyBoD51oOxqWCE2
fZ5Wf6Mi/9NIuPyqQccP2ouErcMAcDLaAx9C0Ot37yoG0S6hOZgaxqwnCdGYKHgS
7cYUv40kLOJmTOJlHJbatfXHocrHcHkCBJ1q8wApA1KVQIZsqmyBUBuwbrfFwpC9
d/R674XxCWJpXvU63VNZRFYUvd7YEWCrdSeleb99p0Vn1kxI5463PXurgs/7GPiO
SLSdX44DESP9l7lXenC4gbuT8P0xQRDzGrB5l9HHoV3KMXFODWTMnLcp1nuhA0OT
fPS2yzT9zJgqHiVKWgcUUJ5uDelVfnsmDhnh428p0GBFbniH07qREC9kq78UqQNI
Kybp4jQ4sPs64zdYm/VyLWtAYz8QNAKHLcnPwmTPr/XlJmox8rlQhuSQTK8E+lDr
TOKpydrijN3lF+pgyUuUj6Ha8TLMcOOwqcrpBig4SGYoB56gjAO0yTE9uCPdBakj
yxi3ksn51ErigGM2pGMNcVdwkpJ/x+DEBBO0auy3t9xqM6LK8pwNcOT1EWO+16zY
79LVSavc49t+XxMc3Xasz/G5xQgD1FBp6pEnsg5JhTTG/ih6Y/DQD8z3prjC3qKc
rpL4NA9KBI/IF1iIXlrfmN/zCKbBuEOEGqwcHBDHPySZbhL2XLSpGcK/NBl1bo1Z
G+2nUTauoC67Qb0+fnzTcvOiMNAbHMiqkirs4anHX33MKL2gR/3dp8ca9hhWWXZz
Mkk2FK9sC/ord9F6mTtvTiOSDzpiEhb94uTxXqBhIbsrGXCUUd0QQN5s2dmW2MfS
M35KeSv2rwDGzC1+Qf3MhHGIZDqoQwuZEzM5yHHafCatAbZd2sjaFWegg0r2ca7a
eZkZFj3ZuDYXJFnL82guOASh7rElWO2Ys7ncXAKnaV3WkkF+JDv/CUHr+Q/h6Ae5
qEvgubTCVSYHzRP37XJItlcdywTIcTY+t6jymmyEBJ66LmUoD47gt/vDUSbhT6Oa
GlcZ+MZGlUnPOSq4YknOgwKH8izboY4UgVCrmXvlaZYQhZemNDkVbpYVDf+s6cPf
tJwVoZf+qf2SsRTUsI10isoIzCyGw2ie8kmipdP434Z/99uVU3zxD6raNDlyp33q
FWMgpr2JU6NVAla7N51g7Jk8VjIIn7SvCYyWkmvv4kLB1UHl3NFqYb9YuIZUaDyt
j/NMcKMLLOaEorRZ2N2mDNoihMxMf8J3J9APnzUigAtaalGKNOrd2Fom5OVADePv
Tb5sg1uVQzfcpFrjIlLVh+2cekX0JM84phbMpHmm5vCjjfYvUvcMy0clCf0x3jz6
LZf5Fzc8xbZmpse5OnOrsDLCNh+SlcYOzsagSZq4TgvSeI9Tr4lv48dLJHCCcYKL
eymS9nhlCFuuHbi7zI7edcI49wKUW1Sj+kvKq3LMIEkMlgzqGKA6JqSVxHP51VH5
FqV4aKq70H6dNJ43bLVRPhtF5Bip5P7k/6KIsGTPUd54PHey+DuWRjitfheL0G2w
GF/qoZyC1mbqdtyyeWgHtVbJVUORmpbNnXOII9duEqBUNDiO9VSZNn/8h/VsYeAB
xryZaRDVmtMuf/OZBQ==
-----END ENCRYPTED PRIVATE KEY-----';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPrivateKey());
    }

    public function testSavePKCS8PrivateKey()
    {
        $rsa = new RSA();

        $key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp
wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5
1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh
3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2
pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX
GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il
AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF
L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k
X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl
U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ
37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0=
-----END RSA PRIVATE KEY-----';
        $rsa->setPassword('password');

        $this->assertTrue($rsa->load($key));

        $key = $rsa->getPrivateKey('PKCS8');
        $this->assertInternalType('string', $key);

        $this->assertTrue($rsa->load($key));
    }

    public function testPubKey1()
    {
        $rsa = new RSA();

        $key = '-----BEGIN RSA PUBLIC KEY-----
MIIBCgKCAQEA61BjmfXGEvWmegnBGSuS+rU9soUg2FnODva32D1AqhwdziwHINFa
D1MVlcrYG6XRKfkcxnaXGfFDWHLEvNBSEVCgJjtHAGZIm5GL/KA86KDp/CwDFMSw
luowcXwDwoyinmeOY9eKyh6aY72xJh7noLBBq1N0bWi1e2i+83txOCg4yV2oVXhB
o8pYEJ8LT3el6Smxol3C1oFMVdwPgc0vTl25XucMcG/ALE/KNY6pqC2AQ6R2ERlV
gPiUWOPatVkt7+Bs3h5Ramxh7XjBOXeulmCpGSynXNcpZ/06+vofGi/2MlpQZNhH
Ao8eayMp6FcvNucIpUndo1X8dKMv3Y26ZQIDAQAB
-----END RSA PUBLIC KEY-----';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPublicKey());
        $this->assertFalse($rsa->getPrivateKey());
    }

    public function testPubKey2()
    {
        $rsa = new RSA();

        $key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA61BjmfXGEvWmegnBGSuS
+rU9soUg2FnODva32D1AqhwdziwHINFaD1MVlcrYG6XRKfkcxnaXGfFDWHLEvNBS
EVCgJjtHAGZIm5GL/KA86KDp/CwDFMSwluowcXwDwoyinmeOY9eKyh6aY72xJh7n
oLBBq1N0bWi1e2i+83txOCg4yV2oVXhBo8pYEJ8LT3el6Smxol3C1oFMVdwPgc0v
Tl25XucMcG/ALE/KNY6pqC2AQ6R2ERlVgPiUWOPatVkt7+Bs3h5Ramxh7XjBOXeu
lmCpGSynXNcpZ/06+vofGi/2MlpQZNhHAo8eayMp6FcvNucIpUndo1X8dKMv3Y26
ZQIDAQAB
-----END PUBLIC KEY-----';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPublicKey());
        $this->assertFalse($rsa->getPrivateKey());
    }

    public function testSSHPubKey()
    {
        $rsa = new RSA();

        $key = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4e' .
               'CZ0FPqri0cb2JZfXJ/DgYSF6vUpwmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMS' .
               'GkVb1/3j+skZ6UtW+5u09lHNsj6tQ51s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZw== ' .
               'phpseclib-generated-key';

        $this->assertTrue($rsa->load($key));
        $this->assertInternalType('string', $rsa->getPublicKey());
        $this->assertFalse($rsa->getPrivateKey());
    }

    public function testSSHPubKeyFingerprint()
    {
        $rsa = new RSA();

        $key = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQD9K+ebJRMN10kGanhi6kDz6EYFqZttZWZh0'.
              'YoEbIbbere9N2Yvfc7oIoCTHYowhXND9WSJaIs1E4bx0085CZnofWaqf4NbZTzAh18iZup08ec'.
              'COB5gJVS1efpgVSviDF2L7jxMsBVoOBfqsmA8m0RwDDVezyWvw4y+STSuVzu2jI8EfwN7ZFGC6'.
              'Yo8m/Z94qIGzqPYGKJLuCeidB0TnUE0ZtzOJTiOc/WoTm/NOpCdfQZEJggd1MOTi+QUnqRu4Wu'.
              'b6wYtY/q/WtUFr3nK+x0lgOtokhnJfRR/6fnmC1CztPnIT4BWK81VGKWONAxuhMyQ5XChyu6S9'.
              'mWG5tUlUI/5';

        $this->assertTrue($rsa->load($key));
        $this->assertSame($rsa->getPublicKeyFingerprint('md5'), 'bd:2c:2f:31:b9:ef:b8:f8:ad:fc:40:a6:94:4f:28:82');
        $this->assertSame($rsa->getPublicKeyFingerprint('sha256'), 'N9sV2uSNZEe8TITODku0pRI27l+Zk0IY0TrRTw3ozwM');
    }

    public function testSetPrivate()
    {
        $rsa = new RSA();

        $key = '-----BEGIN RSA PUBLIC KEY-----
MIIBCgKCAQEA61BjmfXGEvWmegnBGSuS+rU9soUg2FnODva32D1AqhwdziwHINFa
D1MVlcrYG6XRKfkcxnaXGfFDWHLEvNBSEVCgJjtHAGZIm5GL/KA86KDp/CwDFMSw
luowcXwDwoyinmeOY9eKyh6aY72xJh7noLBBq1N0bWi1e2i+83txOCg4yV2oVXhB
o8pYEJ8LT3el6Smxol3C1oFMVdwPgc0vTl25XucMcG/ALE/KNY6pqC2AQ6R2ERlV
gPiUWOPatVkt7+Bs3h5Ramxh7XjBOXeulmCpGSynXNcpZ/06+vofGi/2MlpQZNhH
Ao8eayMp6FcvNucIpUndo1X8dKMv3Y26ZQIDAQAB
-----END RSA PUBLIC KEY-----';

        $this->assertTrue($rsa->load($key));
        $this->assertTrue($rsa->setPrivateKey());
        $this->assertGreaterThanOrEqual(1, strlen("$rsa"));
        $this->assertFalse($rsa->getPublicKey());
    }

    /**
     * make phpseclib generated XML keys be unsigned. this may need to be reverted
     * if it is later learned that XML keys are, in fact, supposed to be signed
     * @group github468
     */
    public function testUnsignedXML()
    {
        $rsa = new RSA();

        $key = '<RSAKeyValue>
  <Modulus>v5OxcEgxPUfa701NpxnScCmlRkbwSGBiTWobHkIWZEB+AlRTHaVoZg/D8l6YzR7VdQidG6gF+nuUMjY75dBXgY/XcyVq0Hccf1jTfgARuNuq4GGG3hnCJVi2QsOgcf9R7TeXn+p1RKIhjQoWCiEQeEBTotNbJhcabNcPGSEJw+s=</Modulus>
  <Exponent>AQAB</Exponent>
</RSAKeyValue>';

        $rsa->load($key);
        $rsa->setPublicKey();
        $newkey = $rsa->getPublicKey('XML');

        $this->assertSame(strtolower(preg_replace('#\s#', '', $key)), strtolower(preg_replace('#\s#', '', $newkey)));
    }

    /**
     * @group github468
     */
    public function testSignedPKCS1()
    {
        $rsa = new RSA();

        $key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/k7FwSDE9R9rvTU2nGdJwKaVG
RvBIYGJNahseQhZkQH4CVFMdpWhmD8PyXpjNHtV1CJ0bqAX6e5QyNjvl0FeBj9dz
JWrQdxx/WNN+ABG426rgYYbeGcIlWLZCw6Bx/1HtN5ef6nVEoiGNChYKIRB4QFOi
01smFxps1w8ZIQnD6wIDAQAB
-----END PUBLIC KEY-----';

        $rsa->load($key);
        $rsa->setPublicKey();
        $newkey = $rsa->getPublicKey();

        $this->assertSame(preg_replace('#\s#', '', $key), preg_replace('#\s#', '', $newkey));
    }

    /**
     * @group github861
     */
    public function testPKCS8Only()
    {
        $rsa = new RSA();

        $key = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKB0yPMAbUHKqJxP
5sjG9AOrQSAYNDc34NsnZ1tsi7fZ9lHlBaKZ6gjm2U9q+/qCKv2BuGINxWo2CMJp
DHNY0QTt7hThr3B4U62z1CWWGnfLhFtHKH6jNYYOGc4x0jgT88uSrKFvUOLhjkjW
bURmJMpN+OjLJuZQZ7uwoqtT3IEDAgMBAAECgYBaElS/fEzYst/Fp2DA8lYGPTs4
vf2JxbdWrp7phlxEH3mTbUGljkr/Jj90wnSiojFpz0jm2h4oyh5Oq9OOaJwkCYcu
2lcHJvFlhR2XEJpd1bHHcvDwZHdUjSpnO8kvwQtjuTnho2ntRzAA4wIJVSd7Tynj
0IFEKmzhSKIvIIeN8QJBANLa10R1vs+YqpLdpAuc6Z9GYhHuh1TysBPw2xNtw3Xf
tGPx4/53eQ0RwiHdw9Opgt8CBHErD6KzziflfxUrIXkCQQDCz4t01qYWT43kxS6k
TcnZb/obho6akGc8C1hSxFIIGUa9hAhMpY2W6GXeGpv5TZtEJZIJE1VHTLvcLSGm
ILNbAkEAgq9mWqULxYket3Yt1ZDEb5Zk9C49rJXaMhHHBoyyZ51mJcfngnE0Erid
9PWJCOf4GBYdALMqtrHwpWOlV05rKQJAd6Tz50w1MRqm8MvRe4Ny5qIJH4Kibncl
kBD/q8V7BBJSCe7fEgPTU81jUudQx+pL46yXZg+DnoiYD/9/3QHUZQJBAMBiKiZ7
qMnD/pkHR/NFcYSYShUJS0cHyryVl7/eCclsQlZTRdnVTtKF9xPGTQC8fK0G7BDN
Z2sKniRCcDT1ZP4=
-----END PRIVATE KEY-----';

        $result = $rsa->load($key, 'PKCS8');

        $this->assertTrue($result);
    }

    public function testPKCS1EncryptionChange()
    {
        $rsa = new RSA();

        $key = 'PuTTY-User-Key-File-2: ssh-rsa
Encryption: none
Comment: phpseclib-generated-key
Public-Lines: 4
AAAAB3NzaC1yc2EAAAADAQABAAAAgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4
eCZ0FPqri0cb2JZfXJ/DgYSF6vUpwmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RK
NUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ51s1SPrCBkedbNf0Tp0GbMJDy
R4e9T04ZZw==
Private-Lines: 8
AAAAgBYo5KOevqhsjfDNEVcmkQF8/vsU6hwS4d7ceFYDLa0PlhIAo4aE8KNtyjAQ
LiRkmJ0ZqAWTN5TH0ynryJAInTxMb2AnZuXWKt106C5JC7+S9qSCFThTAxvihEpw
BVe5dnPnJ80TFtPm+n/JkdQic2bsVSy+kNNn7y4uef5m0mMRAAAAQQDeAw6fiIQX
GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJ
rmfPwIGm63ilAAAAQQDEIvkdBvZtCvgHKitwxab+EQ/YxnNE5XvfIXjWE+xEL2br
oquF470c9Mm6jf/2zmn6yobE6UUvQ0O3hKSiyOAbAAAAQBGoiuSoSjafUhV7i1cE
Gpb88h5NBYZzWXGZ37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ
4p0=
Private-MAC: 03e2cb74e1d67652fbad063d2ed0478f31bdf256
';
        $key = preg_replace('#(?<!\r)\n#', "\r\n", $key);
        $this->assertTrue($rsa->load($key));

        $rsa->setPrivateKeyFormat('PKCS1');

        PKCS1::setEncryptionAlgorithm('AES-256-CBC');
        $rsa->setPassword('demo');

        $encryptedKey = (string) $rsa;

        $this->assertRegExp('#AES-256-CBC#', $encryptedKey);

        $rsa = new RSA();
        $rsa->setPassword('demo');
        $this->assertTrue($rsa->load($encryptedKey));
        $rsa->setPassword();
        $rsa->setPrivateKeyFormat('PuTTY');
        $key2 = (string) $rsa;

        $this->assertSame($key, $key2);
    }

    public function testRawKey()
    {
        $rsa = new RSA();

        $key = array(
            'e' => new BigInteger('10001', 16),
            'n' => new BigInteger('aa18aba43b50deef38598faf87d2ab634e4571c130a9bca7b878267414faab8b471bd8965f5c9fc3' .
                              '818485eaf529c26246f3055064a8de19c8c338be5496cbaeb059dc0b358143b44a35449eb2641131' .
                              '21a455bd7fde3fac919e94b56fb9bb4f651cdb23ead439d6cd523eb08191e75b35fd13a7419b3090' .
                              'f24787bd4f4e1967', 16)
        );
        $this->assertTrue($rsa->load($key));
        $rsa->setPublicKeyFormat('raw');
        $this->assertEmpty("$rsa");
    }

    public function testRawComment()
    {
        $key = 'PuTTY-User-Key-File-2: ssh-rsa
Encryption: aes256-cbc
Comment: phpseclib-generated-key
Public-Lines: 4
AAAAB3NzaC1yc2EAAAADAQABAAAAgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4
eCZ0FPqri0cb2JZfXJ/DgYSF6vUpwmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RK
NUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ51s1SPrCBkedbNf0Tp0GbMJDy
R4e9T04ZZw==
Private-Lines: 8
llx04QMegql0/nE5RvcJSrGrodxt6ytuv/JX2caeZBUyQwQc2WBNYagLHyHPM9jI
9OUWz59FLhjFXZMDNMoUXxVmjwQpOAaVPYNxxFM9AF6/NXFji64K7huD9n4A+kLn
sHwMLWPR5a/tZA0r05DZNz9ULA3mQu7Hz4EQ8ifu3uTPJuTmL51x6RmudYKysb20
fM8VzC3ukvzzRh0pujUVTr/yQdmciASVFnZlt4xQy+ZEOVUAOfwjd//AFfXTvk6x
7A45rNlU/uicHwLgoY1APvRHCFxw7F+uVW5L4mSX7NNzqBKkZ+1qpQTAfQvIfEIb
444+CXsgIyOpqt6VxJH2u6elAtE1wau3YaFR8Alm8m97rFYzRi3oDP5NZYkTCWSV
EOpSeghXSs7IilJu8I6/sB1w5dakdeBSFkIynrlFXkO0uUw+QJJWjxY8SypzgIuP
DzduF6XsQrCyo6dnIpGQCQ==
Private-MAC: 35134b7434bf828b21404099861d455e660e8740';
        $raw = PuTTY::load($key, 'password');
        $this->assertArrayHasKey('comment', $raw);
        $this->assertEquals($raw['comment'], 'phpseclib-generated-key');

        $rsa = new RSA();
        $rsa->load($raw);
        $this->assertGreaterThanOrEqual(1, strlen("$rsa"));
    }

    public function testPrivateMSBlob()
    {
        $key = 'BwIAAACkAABSU0EyAAQAAAEAAQAnh6FFs6kYe/gmb9dzqsQKmtjFE9mxNAe9mEU3OwOEEfyI' .
               'wkAx0/8dwh12fuP4wzNbdZAq4mmqCE6Lo8wTNNIJVNYEhKq5chHg1+hPDgfETFgtEO54JZSg' .
               '3cBZWEV/Tq3LHEX8CaLvHZxMEfFXbTfliFYMLoJ+YK1mpg9GYcmbrVmMAKSoOgETkkiJJzYm' .
               'XftO3KOveBtvkAzjHxxSS1yP/Ba10BzeIleH96SbTuQtQRLXwRykdX9uazK+YsiSud9/PyLb' .
               'gy5TI+o28OHq5P+0y5+a9IaAQ/92UwlrkHUYfhN/xTVlUIxKlTEdUQTIf+iHif8d4ABb3OdY' .
               'JXZOW6fGeUP10jMyvbnrEoPDsYy9qfNk++0/8UP2NeO1IATszuZYg1nEXOW/5jmUxMCdiFyd' .
               'p9ES211kpEZ4XcvjGaDlaQ+bLWj05i2m/9aHYcBrfcxxvlMa/9ZvrX4DfPWeydUDDDQ4+ntp' .
               'T50BunSvmyf7cUk76Bf2sPgLXUQFoufEQ5g1Qo/v1uyhWBJzh6OSUO/DDXN/s8ec/tN05RQQ' .
               'FZQ0na+v0hOCrV9IuRqtBuj4WAj1I/A1JjwyyP9Y/6yWFPM6EcS/6lyPy30lJPoULh7G29zk' .
               'n7NVdTEkDtthdDjtX7Qhgd9qWvm5ADlmnvsS9A5m7ToOgQyOxtJoSlLitLbf/09LRycl/cdI' .
               'zoMOCEdPe3DQcyEKqUPsghAq+DKw3uZpXwHzwTdfqlHSWAnHDggFKV1HZuWc1c4rV4k4b513TqE=';

        $plaintext = 'zzz';

        $privKey = new RSA();
        $privKey->load($key);

        $this->assertSame($privKey->getLoadedFormat(), 'MSBLOB');

        $this->assertGreaterThanOrEqual(1, strlen("$privKey"));

        $pubKey = new RSA();
        $pubKey->load($privKey->getPublicKey('msblob'));

        $this->assertGreaterThanOrEqual(1, strlen("$pubKey"));

        $ciphertext = $pubKey->encrypt($plaintext);

        $this->assertSame($privKey->decrypt($ciphertext), $plaintext);
    }

    public function testNakedOpenSSHKey()
    {
        $key = 'AAAAB3NzaC1yc2EAAAABIwAAAIEA/NcGSQFZ0ZgN1EbDusV6LLwLnQjs05ljKcVVP7Z6aKIJUyhUDHE30uJa5XfwPPBsZ3L3Q7S0yycVcuuHjdauugmpn9xx+gyoYs7UiV5G5rvxNcA/Tc+MofGhAMiTmNicorNAs5mv6fRoVbkpIONRXPz6WK0kjx/X04EV42Vm9Qk=';

        $rsa = new RSA();
        $rsa->load($key);

        $this->assertSame($rsa->getLoadedFormat(), 'OpenSSH');

        $this->assertGreaterThanOrEqual(1, strlen("$rsa"));
    }

    public function testPuttyPublicKey()
    {
        $key = '---- BEGIN SSH2 PUBLIC KEY ----
Comment: "rsa-key-20151023"
AAAAB3NzaC1yc2EAAAABJQAAAIEAhC/CSqJ+8vgeQ4H7fJru29h/McqAC9zdGzw0
9QsifLQ7s5MvXCavhjUPYIfV0KsdLQydNPLJcbKpXmpVD9azo61zLXwsYr8d1eHr
C/EwUYl8b0fAwEsEF3myb+ryzgA9ihY08Zs9NZdmt1Maa+I7lQcLX9F/65YdcAch
ILaEujU=
---- END SSH2 PUBLIC KEY ----';

        $rsa = new RSA();
        $rsa->load($key);

        $this->assertSame($rsa->getLoadedFormat(), 'PuTTY');

        $this->assertGreaterThanOrEqual(1, strlen("$rsa"));
    }

    /**
     * @group github960
     */
    public function testSetLoad()
    {
        $key = 'PuTTY-User-Key-File-2: ssh-rsa
Encryption: aes256-cbc
Comment: phpseclib-generated-key
Public-Lines: 4
AAAAB3NzaC1yc2EAAAADAQABAAAAgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4
eCZ0FPqri0cb2JZfXJ/DgYSF6vUpwmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RK
NUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ51s1SPrCBkedbNf0Tp0GbMJDy
R4e9T04ZZw==
Private-Lines: 8
llx04QMegql0/nE5RvcJSrGrodxt6ytuv/JX2caeZBUyQwQc2WBNYagLHyHPM9jI
9OUWz59FLhjFXZMDNMoUXxVmjwQpOAaVPYNxxFM9AF6/NXFji64K7huD9n4A+kLn
sHwMLWPR5a/tZA0r05DZNz9ULA3mQu7Hz4EQ8ifu3uTPJuTmL51x6RmudYKysb20
fM8VzC3ukvzzRh0pujUVTr/yQdmciASVFnZlt4xQy+ZEOVUAOfwjd//AFfXTvk6x
7A45rNlU/uicHwLgoY1APvRHCFxw7F+uVW5L4mSX7NNzqBKkZ+1qpQTAfQvIfEIb
444+CXsgIyOpqt6VxJH2u6elAtE1wau3YaFR8Alm8m97rFYzRi3oDP5NZYkTCWSV
EOpSeghXSs7IilJu8I6/sB1w5dakdeBSFkIynrlFXkO0uUw+QJJWjxY8SypzgIuP
DzduF6XsQrCyo6dnIpGQCQ==
Private-MAC: 35134b7434bf828b21404099861d455e660e8740';

        $rsa = new RSA();
        $rsa->setPrivateKey($key);
        $rsa->load($key);

        $rsa = new RSA();
        $rsa->load($key);
        $rsa->setPrivateKey();
        $rsa->load($rsa);
    }

    /**
     * @group github980
     */
    public function testZeroComponents()
    {
        $key = '-----BEGIN RSA PRIVATE KEY-----
MIGaAgEAAkEAt5yrcHAAjhglnCEn6yecMWPeUXcMyo0+itXrLlkpcKIIyqPw546b
GThhlb1ppX1ySX/OUA4jSakHekNP5eWPawIBAAJAW6/aVD05qbsZHMvZuS2Aa5Fp
NNj0BDlf38hOtkhDzz/hkYb+EBYLLvldhgsD0OvRNy8yhz7EjaUqLCB0juIN4QIB
AAIBAAIBAAIBAAIBAA==
-----END RSA PRIVATE KEY-----';

        $rsa = new RSA();
        $rsa->load($key);
        $rsa->setHash('md5');
        $rsa->setMGFHash('md5');

        $rsa->sign('zzzz', RSA::PADDING_PKCS1);
    }

    public function pkcs8tester($key, $pass)
    {
        $rsa = new RSA();
        $rsa->setPassword($pass);
        $rsa->load($key);
        $r = PKCS8::load($key, $pass);
        PKCS8::setEncryptionAlgorithm($r['meta']['algorithm']);
        if (isset($r['meta']['cipher'])) {
            PKCS8::setEncryptionScheme($r['meta']['cipher']);
        }
        if (isset($r['meta']['prf'])) {
            PKCS8::setPRF($r['meta']['prf']);
        }
        $newkey = "$rsa";

        $r2 = PKCS8::load($newkey, $pass);
        $this->assertSame($r['meta']['algorithm'], $r2['meta']['algorithm']);
        if (isset($r['meta']['cipher']) || isset($r2['meta']['cipher'])) {
            $this->assertSame($r['meta']['cipher'], $r2['meta']['cipher']);
        }
        if (isset($r['meta']['prf']) || isset($r2['meta']['prf'])) {
            $this->assertSame($r['meta']['prf'], $r2['meta']['prf']);
        }

        $rsa2 = new RSA();
        $rsa2->setPassword($pass);
        $rsa2->load($newkey);

        // comparing $key to $newkey won't work since phpseclib randomly generates IV's and salt's
        // so we'll strip the encryption

        $rsa->setPassword();
        $rsa2->setPassword();
        $this->assertSame("$rsa", "$rsa2");
    }

    public function testPKCS8AES256CBC()
    {
        // openssl pkcs8 -in private.pem -topk8 -v2 aes-256-cbc -v2prf hmacWithSHA256 -out enckey.pem

        // EncryptionAlgorithm: id-PBES2
        // EncryptionScheme:    aes256-CBC-PAD
        // PRF:                 id-hmacWithSHA256 (default)

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFLTBXBgkqhkiG9w0BBQ0wSjApBgkqhkiG9w0BBQwwHAQIIU53ox17kUkCAggA
MAwGCCqGSIb3DQIJBQAwHQYJYIZIAWUDBAEqBBATi8ZER9juR41S2c35WXTQBIIE
0K98r5dQq/OxbwA2CH0ENs9Jw2qjvW0uGkH8DdO8XvCJohMrIU8FABxw/50Af5Ew
Nq4FJIYz90LjZzlI7kf97TDMZKw2K3AleymwmfMcKer5pZ6jqdxLGXFztdj3Fm/S
P+NcVjEZFSEH1MNDEPhiPSIUAf1yQcLwKAzHH0JTnZBOFSBbGxTZLYfvD2angVNL
xTivLYJGdr1cUrAuZQcM3JGQEvCA5qAC7oRhdVgGyJrl8xXY3mVlaXMsW8A+Q7xj
NyH7lJUFEF3YPMbpWr8zblCQYgGByM++yOfYQXno50AgWdYjPO88pPzKcCe4x6WV
qKlvqTYZqb1HgZurTd3BS/e6GWRgnRt8W87nuNcyJasud92Z0FhSGwIirlE89gUW
EinbY8m6+sL9VZZ5+t66TROtpj1Ohj8t3W+01oLDCtdSTGwLuq9XUsEyuYZSqUN9
0F43U8pOykNbChi1S8vfFdwf7U1R+hgoF0MRNDwh3hRfSS0zPUnCGb6hDZrOZB9C
e3xbfXiujVlfhRc7r2qbZHAwqNLcccC98oLfbEIUdBXn6M7GfFIwiuNiS48rehp0
dA9+CiWJBq+7b/lRdcgQJxjwUpxtMXr/812Bky4dDoMDs32cmMghH2sgUvht0imy
ZhA3IvSCAV1wVoQLqUuPXLMskcKsNCTbL9AYEpJm612dm43btXec2vtjCc4ajpCg
wICLE2V1jwzWw0girrT/IMt8QUd3fkJZkEAbmFHwuZptFnreRCidZjfQqYhWfyqJ
nGW+cc7G1bGwxt32fC5eu23hBTJERmRlvkhC+v2WKhYXcKyOKQn5/I4eaEZauDn3
wmg3f4h/PPuQgqv/vspOai9a5HhPRNyeIjXsk3hxHepEgV+kVSU50BpchSSzBuhK
71F3nOMTyJ/XXxaZrLLtpo3CcXmI0/JuNG7pjDS++Vx/BQFs8xxDfxRs0Um7RlT1
piGZGDn9zHNpbspHkAeoQmlplbmjtCClojhfBj4HbXTtlYmDgwKHul4YIni6kgCr
G+WduGXLeyxmH976vvJasD4wyttL2CZTHLR7Elp+yl0xjXMlj/iP4WYozJAmGifq
xjLWMsZ0gaBtAoOFrvcgOueE7+E+NdbIHzU4u5FTbz0DLCvrsZeKwpOPEsMw0LVG
T6rNsBzMY3XyBtV1FdXwmuOcWha62Ezr/RRrfvRPRImy/xVVKOrOQ/KbyELkjroh
UAEPs7s+89Ovc7P30IfS0Xzlhz2aSRflZarOIqu1JtjTYZ0XWLTWoQT2fjZdnMDV
qFrbTPdXezqTAAzk3rnkkghgamTVQ7Y8D+BIGHIc4+oVT2jxzSjBQC7szmudanGQ
hfGLyO+vwLg4r1lanzSULtqfwTZMarjYGxLqpQp8cIjJfzvLI3psRDFyuWCdIbEs
y3VKgoNsa+PmyimGSa7x2cw6ayTx9wlOhPzaBwqMhHxr4qJwS2ohDONeRfnPr34+
oVD7mnCBLB14qiZcpQv+qPGvd/Q/tA5SBNbZhPuWtjqvy7/K+1FQX6xvx1kl7p9W
l0Q99rwqECl8y+CiKEXdItkCTA/vgxblSt465Mbdic7cbcP6wAMSGmpryrmZomm/
mKVKf5kPx2aR2W2KAcgw3TJIu1QX7N+l3kFrf9Owtz1a
-----END ENCRYPTED PRIVATE KEY-----';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS8RC2CBC()
    {
        // openssl pkcs8 -in private.pem -topk8 -v2 rc2 -out enckey.pem

        // EncryptionAlgorithm: id-PBES2
        // EncryptionScheme:    rc2CBC
        // PRF:                 id-hmacWithSHA1

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFFjBIBgkqhkiG9w0BBQ0wOzAeBgkqhkiG9w0BBQwwEQQI/V5Qw9+hKt4CAggA
AgEQMBkGCCqGSIb3DQMCMA0CAToECPxrtS4U+IIBBIIEyKQyYpJ8tfWXVvitxaPq
+gtrVVWd/ukjwZ+jQY3g/ZjZNWQPq5XbuoP5F3u5g4V+RoXzAIkdwyiveEv+XssV
DJVHfNiL6VcdxhFJ1rmt2uq9vFW/x9UHDqAWsnytn46NFRWUqgKzkYWMDqU71IC/
wyq7UzjTtqdLzaCxkTWYst1o+Iu7VXapFVcscPYyGshLVyZ0x/etc/09LOC4bIpk
3Qzf+f+adrNxW0mbD3SyDfVadvS8mApsd7bJR320iEKd4CmW0sNAzKkm2ya2aUIi
Hrk3DEgr4rPmpn3BVfZ6pg+yRu+MOxBhl+8yfA+E8kXfe/F7BiMkJQcJTOfLRLfH
TXipyb4f8oa+gmwwWK0jfCuxoxiOTA1CBCjZoTvdSuFYVTdblysQO3BivvSQgbmD
oHntb7HEoZ6yB49u/LrrowUQNH+XihBcototyLCmC5K+x8N5cZsp+yaLJekDHlQs
ATVMeKCbPjYaS4g48lDyC1VbtNtJc/zN5gOUB0PM80iB02iZegYyeW5+WWzY+Lgu
lpWLH7PdpqL5KtoH6SJKD6Szl8dKJLYzpHI2esckpp9YsDtX2z/VkUFKTd0PeeNh
WefX0q8A47NBeBLFEZqmzPrL6IyaPnnPCUsvqk6MEA1DgsmY3DFd8nEYhzJIAwoy
Rw1mCqwL0uukQPqFGByU9YRHyhJd5aAPyF1xSLfUQUJb9xn+wyN57xoamFePPWMi
UXdESZWX+rjA0ChfEtL9AzXcfO9PBS1p/2JkVxUt/UPfI9SgQn92kLo0LRi/iRLk
H4zjnkaDy65ZY15bzyK+EvJ+VZ+P24QI7X12f1m+rkssMekHWHf5/SitUpW26ZFe
M6vXyz3RlXxow+0WcsPob19n/vbgeJQPTfMY0zPS0iCRIggC/liWMEOzP/R1jCYi
q8TEaUi1Ztx3Gp4Y8Vcf33a/YsxKoUsQlFFtyE6KE3ZEI03E6cMiX21nWULKrk9l
+8Tq4T1a8I4goVa+e4CYBYwMAY9fdfUJ/p1EnrG6Ynj06a2Zx0IK/dF5w0b/5TeL
PMyafb4FHkpkyYYFlktQdKIqGjjtmKUr56/7vumVHUyItf5nSuM8lLps8to2MLkE
MAolD+X4FIGs/1Z5NlUb5AlNVNRY1c7tf+YSXI21PlkBpaRSAvN9/2fmGnxWSvAa
BEGR0JA4zMPrCSpxrBQpOrZPh/cD9YXNu+N9P4dtf57smCviKTJg8eMl9NYu4vtc
FygdqPKuhJM2WI5Qdrqjbf4NQ1mngSxXNKrcNmC/m60JPNKHC7dM2ynbN8QyZqEE
EzSdL0Z3YQGnuwr+4zKHHsNnO4nRJfUowWks4Gvi2HIyy3DBVqqyEPxDDGEpcqs+
8GNKTGBg1PCVg+I9Xjxio4tBuwLDo6Y8Ef8SphN/0DC9svaQRfEOY3/9WB+fDnrq
SUSNZNWetkCd247WHwl+JvJDXCuzGJ2+JG5DXuEdCq2EhEVNUWPuotXTPvI+0wsP
Kq23uvzS53ZArQnxlqgwyXQ06jzc+J4AiNtl3uIw8D6LrRyaDsOsKQCEh7qjkqTc
khzefbnNRDL5PIJnTfM7vSQ4nUzdAxs/7YzX6GMx1DaCtBANbUVUoIE+3oKdqpGV
9AmO2phYWCBefw==
-----END ENCRYPTED PRIVATE KEY-----';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS83DES()
    {
        // openssl pkcs8 -in private.pem -topk8 -v1 PBE-SHA1-3DES -out enckey.pem

        // EncryptionAlgorithm: pbeWithSHAAnd3-KeyTripleDES-CBC

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE6jAcBgoqhkiG9w0BDAEDMA4ECHwurC0qxNK7AgIIAASCBMjRJefQ1oLo/pml
Zw1qTE2NMgSNdP6z3vEap0qMMs/EXO37GDuHGla/yvvBIZbBXPVoQwd7K9QfU1NP
JCBtNBTD9Pl7a4fJIlrf2dN5SCP8lu+nFa5ZyCiBvUtxfdROnhXfkhs0kqOowLaq
1mw+Di9FPSA3ZkdJTPpAyMNPMlYpQII2ex+j6NmRB8t7O121o6ynbmDj0Rh87dv9
VtRhO9sonTy160Mv2HPMLMliXMwFUvpEH9XNE+K6V6DnoMc7I9jmEnqLAzmjsKv/
AOwuX8t8cPboeGOu++/0h3879+OVcnkXGMW0aAT+3sX4oMgEVREHDwkn2IGsrIuV
SerUKg8WSoyhRNb9j7uJAlvU6bCrivcOujjNasdWKG47ojeiySFUkKu9JBohQ+vI
mrlkqZv+FMwEKgApPhCKbQYqLuKl/kp6lNBXmhcusuxsGCnaw7/Wa+Y6p+Gz6UL5
caFpDm6FX+Snvi5/6sUpMKL9LPAAZZVRpKj9JWcidEoXa5rINIMtKyVpl+GEQmac
9lCdFq+5zO2r94af9AKRUIqTquyCkcy2s2mzNq2IIv2atibnb2HQex0/EhLFxMC/
UZbl61YaSBxrH52LY4SNOUy+ppCsP4z0ojTdci9Yc3BnMqzSPD3FMQPmGpWWRGOq
Jdj2/B76Q7rYZjIdrh6UbSROrgTNgmbeASxfDSHHmmcZyIUtMzBC45bmz3ra/FO0
eb+6srXOmdIG368/xRdo9o1R/cNw9BHgGu5R/Bx+AxhK8DuhL7rMYLVn3Ukl2qrt
0koCtDbPxq2YgF8VYXz4WNRCmLuUroty99WVOM7BvM3XyfSP5iLynoRr8B7Rju5K
t5o/OJUrNqSNjtzYd3PZEXqi7ShWkYp5BACzBfSxkGfL6bBMfoM8Yd3dBLrplRu8
WpVbJSA3DbJwAyGhKP3dQmmhBH9nJEppSK0iQPCruAyyZJT6kEmPhcNuYq8CyWe4
+l9Hs5qIHMrkEq1BcLYiQFBLVLXR6eHf3J8fAMmz8I74TWNM2u3FZUcDoIwqHmHG
zDwYZ9h1tkijvyvbH5RkMWRb7WAB2b9Q9ZsPR0naQbqHmO73Uypu/pfugx+cmOPr
AiYOQHnwSCDcaTHJnO33L7KhgA+RfIRoigJXgeYzlWmjW/U6SRK8RTvda4lxPsOo
/bXTZOoUA8qTDKT02n20h0Ab6kLDApSigGQlYI8Jhfre/PGFWfrLxPpy4ED53sPg
xY1d6tIa18yQCAznC3k6Q9OK02bGaFTcGnTPg88PBgyUFuqljKGrG9rpm4uTPzwa
1vzKv05oYjK9xyzy6LkIPYHyp6R2tedVO34pa52LNCO3/DaLnwiDfMRW5SFu0J0E
P7/viLPTwwR6zdAAlt8hsT6apBYlLzVqGRy9nbN60ZS3Q8lIe6xR47koWAJvnHuS
uBx9xP3JWcs+Cnis85wODY1qxXa0Yx58kUVmoyyiBOWHcwsi82YtgwAAjhR8K1wy
gRJR72XIKmdZ/RINp7f2dN7xswy8iU2m3vBxgc1AH2/8knlGLebNS7/RJwW1KXsb
pp/6vHRPSla5cxzsF9NmYHmSAUpk1t+Mo+YjjoT63bVC4xNzkXdft4l4QyUQQXU2
aENeUJKn2r8X3Tpz92U=
-----END ENCRYPTED PRIVATE KEY-----
';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS82DES()
    {
        // openssl pkcs8 -in private.pem -topk8 -v1 PBE-SHA1-2DES -out enckey.pem

        // EncryptionAlgorithm: pbeWithSHAAnd2-KeyTripleDES-CBC

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE6jAcBgoqhkiG9w0BDAEEMA4ECGT21lf3Tl4dAgIIAASCBMgJmnyaQktoSk8u
JyUAaB4ZgGWW22BX1xRA7en0sNj4PhBxj1DEXGKNUBx3k6u6Cd7JUupxGfeag5aw
fi0bWNgEw7YSITmZKaKO5Ee4shQrEDucaH6KEGYV6YspNys5dD817hmd4Yc31q2e
Ig5k3rIpP72Yy9Si0FXmKDE8/GmCYckdIQVUCGZD9nLugvqEC0adludfMAwzCHUY
68jeyKiPJGTFSmE0wPD5EaWknn3U0eRcmKwZPtFpWEAEv5GTm1h6Y5q+P2X2Qsia
Neoa4jjnSEww85zbno/k0KdoRIuSEM8qOHdNuU6XNTCGKxEkgBLkY+vjRjOCi+K4
fzJSAPxaYATGX+W8EWegz3yhwiFujjDPkO9nfeoyks/saFbP9uT8aesZUn4/rIw0
KyW7lYW0TUyBxfIXg1DEsKcmSrrb0WrFLN/MnjO8Y1bAY63KgKgpFZk+7H/7eCmD
2Egj6o2LXTEPkxwYyeR41k64LM5RFF5qs4wS0Gfo1oTc6lSbuZNFHSgsXkb+CXFL
JZ3CuaYFY5Ldfm+1HsrZ9s3GmNAnog6WABXIcz9aULUyJfLr+oZaQR7TC5KpM5Xy
dyztlsN43D9UZKdz93zW2V3LxbzbOWTrcd9dB1GwrPvWIy/0/dqFOvpcr9k/4S1T
AJ6pja4x19EQLj2DUvO7JQEy2Rlam+SI/ARQTc0W0dJ8x7FboHZDxUQCRDih5Qw3
s/xoGflLUYYtAR5hfgjbWuvG3Told4IYlBn2vvVu7UxXQekUOaZLePqucAP3sTDC
pK7JK+OT223FNU5NieGS4hh+jxZQnLuuyxWQaTCJM9isYPqJYsWT9X+c44ixOgLJ
unYtg+8Lck3On6wiDUPWTLCGJvjb53NhPSovTjNBW2Q7YszXXjeO5svwwxtKHabx
vCDsG0zdNdwIgupqynbtcuUhsmIsJKBu5c+9i8P21rNF0DZjOkv8mThRA6YQLce8
mLTcnpLsvCGNehVEStD6pr+CtGsQEEtH3bPc2ZBrpxtz1EHmrI7H3kX1gjbD7bsT
XWzaxsId+8pmqnAcMRzU3mRv4Fe+387X2irG4OxR/6cFMk3+yfpKJLSsNh6HAVRX
xzYwVz2WP7RM0KuLh7auAcI1mHk+0xAvDi7s3ggy3SzLzQq9p+EEFVGVSYuVFLbi
TtlY6HQ3b1Z6KgntoPj79YuOmri6/8w7nBkKt09faYLUf9wZWHLL9/LjZqoJxPfl
lX5Ss4+MDV1aG9aJoTT53d8Gn8ApWK+XFToFg2InYZzZqBnKP8DHPG8D2Gh/MZlA
Yt4hPDNLf733zm1zJTWo0TF6+4AwZp7XUKTg+pM1CZJDOTbJlEA+cXY3BxOq10bl
JPJmV/JFINqSeBLN8V2Ong8Q0Dt4uabSmlOUz19SXpimBrO8ztxaqigMFIKMbLXX
uIVAoxG7KLPuv44yK3Fjsg6OtwZnrWqea02b1qwFFrIKoqmQ8FNFBMYqcHU2IkSq
gJqSylfqcre5Y1DOSlcjGa7aP4C8AyB5qOG7LZ/CLAePKqgAHtMd/K40Zgku36Ir
9OAcUXy/H5PFJIleEyjvLLBE0VWs0TVBXi/FiIqwvAYNOFqXl/gtRcZ0kVex/QeN
rcONqwmUGJOjrfhUyJA=
-----END ENCRYPTED PRIVATE KEY-----
';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS8RC2()
    {
        // openssl pkcs8 -in private.pem -topk8 -v1 PBE-SHA1-RC2-128 -out enckey.pem

        // EncryptionAlgorithm: pbeWithSHAAnd128BitRC2-CBC

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE6jAcBgoqhkiG9w0BDAEFMA4ECAdC2l5rzAQeAgIIAASCBMjuEQDNkvSX6ylp
WsgQZUSvPdNpdlG084oLmhTV0z4pZeLB0YCyKCM7GMVQ0tsprRW0ky86ulbY3W5Q
86WNHXYtVIFXEmIjN1syRG5Pq3RZ4Ba6wf36Gc/1713p6GjcPxLZ+JOw1xBEm1rh
1nI9b43PzbKmczs+6IvRO5b9MjKNkBeNzH9kh4b3zsEW/IFgYaz8zip/zRu4hCSW
ORhnRYFvbI22E84g4/SB1WS34nR/flyZBXT4P87s7bwXEOsXAGnEeVF38znV3awD
V8rry2e1drRmlhfNhvDroQrkv7O9X2ee91I9gahPKpXtAlGXBgRb8qjVHeI8Ea3K
Ty2zcWnjdE7/jt6pO07+B+FlHNdKySlFdKTHEmJ1x+O5Ui4JaGjI2UML0yGHoYFU
wGH/1DYJ4+R9d97BYJ/yp9+JAQAjpG7UUt3jFgNx0CAbP7d438l06z2EE87EqIEa
3Y0ZG1Q5PWE60hPJsvUELdgzcUiKkVCxhOPhwmbSlQpEYXZRBWv0RAJzey6yPMQ+
L/TkMDpgTNUk9x+n3ehnRuA/tlthxXN/ViDthPO7ovSVCsKsUq6lXotO+3hHLGs0
u8ZyVKHNEqGso7PfAFsjcJq2C46mQNME4HPOWm5J+TFf/vvwqdYKCiF3arV0hUtW
x9lyPR2PvZM1ik9jXi6lc5hPegcwmx9/j5yc4/3rQNiwe4dtUpL5JLKAxecKBLgr
atyVnAs69JYaaUT9aKRDzYzCRjo0jIQ9/lgJl2DqVRF9aYnknrVWRIjyKbfKjw7N
w0yfXlVRw46YuJ72PSHpr7HcfzL1EWzmKcAEPDH/UCpIaoeNTwxeEQSltOM0D0Su
ZzyAQP8sWSXSwdtD5YD7iiUjDN4UMDuIwAEMLN9231/RjvKuLAys0oNRQfHkkiCo
9rt/VUP8be98UTyKu7Bx7JUEW6VVnYM+Y274MLQk6TcjZyXgbKwhHFJyAjcBAFQp
5kkYES0kk+57HeBImcB0a5qBor/uAnlsCV690roUlBtkVhBOkTjVi7w/uZsSjIWr
MBndNHTFqqnkbm8xOOoSH6vS32c1KE8FrGpmkPGc6wziX9Ja/MkuLDXrBIlnP4Yj
aCf0sVMSR1/LoHIGGaGXmTzs0VTR8Z5EyW5uvvCy6dWCWnTKEWmTTS+zqW1RYVBZ
n/P2ovj2Kl4rhQuSpfOE9xBFWsgPAD6T2FJzfvu0/D3Sw5pI/RT4NnQ77oJSs+jN
lV33FeceRoJqjcP6YMAiRX4RmkTeD5Hgy0YRLrfQ4PKwAQG82uIj3yqXWveexwb0
Cpm5XxzgCMWGBRvIvM+yByf0SP7fIWYHbzsEWJkN5btF3tMc6i12q7AJ0/UFMQLt
KNiMg+dLWP18cySU8OysXqPq1JKHDU1NMg2Xf9o2c35eOktLfcO9axS4oAAz4bGN
hTB8rk+MWnHfSlWvMPMzlJyndXv/WxfojujLogDTOHd4/q4KyoOwJY3H44eeW79u
sS7pDbjKMl8A15BLMLx01DaOYk7EiHFnGIpY2V2+2Xm7vQu9+8fHSf8whuCRVmBY
Drhy2HTF1veKrQ6IrIyQicmzTtW6moSnNg69SpuzKTegYyCRsSHDIL3WxMoopVwr
Pu3ed6UvXDfotj3v8rE=
-----END ENCRYPTED PRIVATE KEY-----';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS8RC240()
    {
        // openssl pkcs8 -in private.pem -topk8 -v1 PBE-SHA1-RC2-40 -out enckey.pem

        // EncryptionAlgorithm: pbeWithSHAAnd40BitRC2-CBC

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE6jAcBgoqhkiG9w0BDAEGMA4ECHzZ2FqUJyiCAgIIAASCBMiKVNJiU/1UCaoq
V6VSX33gL8CjQqhzDEUlXhHoSYx7IWAJIx7C1DDgeLDfJ//cCMlBPbIOr6knohFQ
KegbsulIYm2DXUQvEoivh02F4An2RLkP0JSMl5CmYTbiu/nJic2jdin+vaKWhRA8
Kznk5wEuhz4t6Oo4Hp3k3+0sd6YqLbmdcFCiYSXE52WN271VvUXqwz3TosUgOUVH
YNzlER1xzNBFgcUyrfiyA+t5NaSDfpYncD64zXFz2KgkjcpfPBBMRnQ2I4mperbe
3uUt8ZFCVeRiROWtx9WQgCVDDWztlrYzfEo/lFtNKjdseiYDj1/1gG6S2x5/S5dP
LzEndNYiVEB2q9XrocrdKVumr7EqNe7C/AMNxuyfAoU2QV5SRyDDSRa1muYwHBa7
x2XIThMS2tQLdN9bjJGcT653DKoq0Qwf1uMAOdHuqBLNXOpZw8PG8d3xmVHCyB4u
rr5E48L2DmD0TwY3YBjfb3KCw/r/CvT1/cWkCpO90aNmSS4JMuOBiFlKaERMvXcw
Ffo0ErZWwlgDN40hQO7xySxI3Paz6/QbxXunVnFQkTclkcQG63K6nWO9fMtgxRaZ
ZGsv/jdWUZ6fBvOvW09zLvF2ZXKhTbfwU47C+2TefvENVz4rTAJcGgtF1wiFv1Lt
0XT2FeJ6/jVGhk6745cHgOhsxqamOTuQ/y948ViMmR037INHouazD8dWHkjOY45d
hy0DjRGIiig94/r+b7YZn81QUkk0HddyH4zi18f5Lx+ExiLDKaLqLv57PQ3ZCeBy
v6Hoq/5tpZWCdXkLIAHx/a7ltiJQlyRUr8QcKPcGfr/qvIcYsUocHZ3iwkhxCnZ9
77E2f/Owf8VaS3x4g5V6RYNlkhuqVixLq/3QyphykcqDK2g4PnWfq8prGY97jHXN
+LZdwwV8LJkkoxCG1aehPlvtpGYGS75aeU1iFbqfke+gF2JG4LJZQsl1dAoL8R+X
ZdILuN+182CpwwptK4QmCvSWXk/ZJtYK9e6OtE1keLM4oQW122fxwyVkEnnAR2/f
Qc2U3WLk/UZSuhcHExxreWP4kiN8cYgSpw+k2uk7Xuw2kelu6hv6UzB4EtL8Xy4O
kK7y6EjCkRJVqQoZ3vVny5408edc7Kh6bz0P5G4LIaCpvrcCYOJv2f57/lr8dMM6
XcoZ0YIXATdUzKzhXKNFiib7SzBwFRRD+jMwzLnNeUhss8IUVl5LYwD6sOWbhdep
LtUO2eXa74i6pa7sz1PLLWDZQ64f9fVPX7EEBy2LBVP3iLqLd2x/OHw+s7pTPT0G
EgRpW0+IYBZGQjGN9s1VXXyhTm3RL03KeYls7aHmmcVqDqvozarqplN/kAhSGPmD
N99FlSrIfihCEZlXO4iP2LRkJaFy11mU0ZvIMkDJ51fNO8PWypca4Rgi4azMCaiR
HW/dtSBH2N8St0G80c2gUKXRmmJWFqebUIk4000VrpDcZlg0INC2unY0NwRFDe2a
55/NR7TO9GdhWYDWsZedRatUFul5DWznncIfrXAD3T32gUR3I9zc62OFVsH2Dve3
oMZUabvnj3g1sQqiRgJyIe5aVZljgXMh0cdWjcUi34vOVBU8yOU921/jSinJWyH7
GxyNlS3BiKQ22CLvbjc=
-----END ENCRYPTED PRIVATE KEY-----';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS8RC4()
    {
        // openssl pkcs8 -in private.pem -topk8 -v1 PBE-SHA1-RC4-128 -out enckey.pem

        // EncryptionAlgorithm: pbeWithSHAAnd128BitRC4

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE5DAcBgoqhkiG9w0BDAEBMA4ECKQOMmQxsqe0AgIIAASCBMJ86iav9sz/nsgC
KrPC1m7fPDuJF1BCPd6Yu+b+D4++4htITNBujK+Ur0xgQnfs7+Et8/cz521KR1zp
kalr56rqvPu9X45l86f/PYv6D+3660jxAadk1bZ9Y7nXzjXsFlZljEi/oLYSAKwh
rn88ZNBM7hwoEtEZJXOK7yZlcpfLuNyRJhfxRp893yeG3SHDN+SAKzqbjrGtPnJY
2X0Y/KidhYAYLi3onhxkq6I9aEI54oBZUiKLHhRD5/ASx8EeSPK2Ydj20PfDIRIk
t75Tlqn5eLC124xdO0rm/vrczIrzo+JaqLq8dO0T7PGrR7hv9OyFwM6ssfzl2MyT
Si4Yv3gBk6dUQ4lySj6XfscjEPwnUSjO3SMwAV0uBoBxDyeKg+58sT0e4Ow7k+6U
SFoqa2m+gBAjXzL8SaGfvjfy0ViBtgLycGrK80dp7k0L5pJAZou7WCPWlP+5+kIl
IprSGD1luOm1olQBSaQO+GkhQlMg4jK7cMKM2bRWyT2ibq8KZujlhWlcqXbbaqSh
nJdadTfAsaCf3/hK1fiFwwSyFbiPjIE1H+WS+JMcg826S7FzoZ3BzcEVbiHRsBXy
PS95ZM3v/HWOejEO44NEqnrwjyqBlSJXOK2WLOUWWlf6t8pdQEjA1xUfJARXqv16
rQEXq2ZTGBOGeorwKLeUNgMQS7SfVP56Mmi23A3QQk7JNPXfkcrQscHu3mzesYKA
+ckJwDsyjnTwYWFXfDxfReKVA17YSV160oKCPhO7jIeiHO8azw7RKaaIaueKe5QU
boKWPAKeEfsDrSxtEYxy6hQ/45LYB+gUlAauaFlT4d0qMWQzt05Zs4ugUtx8SuI3
hWB80fi8XEJajti/3JIg0+cDEmv9XtYQXpaFKR/gTHl1ReSscY/rNyiUc7t1dBsn
kAwMhk/7p/0MZdEpG0e3qQ9Fs2pELlShzORobM0HWd41d2BkW54W/TJ9ERJhMU9Q
NJ5KZDukkCdTIgvnPKJ/50byYVGtt8VBXDzMQsm5ex9yDkEmBLtc14z7UaGd7FCK
xmbcVfkf+h5GPuJqXiZv9RsOfV0eVXlNx7jQ8Pq3FM5EiL8Wtj1XB3+cpFkPREoC
lA9enCZNdjXPB4SSz4kF+UwWdaNS77SGXDq4NQRT/cu4mce+1VPjepEc1WzLw5m+
aaHtJJCdLhaUJmYfaPGz4kg2CSdFCDjzDLOQCOwGtqAY6667ZOFb2VCukQR2aSfK
XJF4Br7UsKhtlZvRDZGLSdxS/6IPe3KgzInP+27kLpv5UcolD3GfuS9WZhfa7tlB
37n5nyGJCgVHufWRrYdKPI32Dn4R312/k+6X9zR1G4FlYzbuA+g2Q5CX8n5e/9jm
1WjCv8ppB3p3BjIv8iEAyfPShwe4uk2ohry+nY/pq7qYl3E7Y6vS1MOmRJT5jvBA
oCWQITjRu/d0xocYp6agkMEBgkyiqzLEW8PV2bziRZVGsYvC/4ky1MVERFO9jiSk
3L6Xllp1yB+Mw9y12bUhDAZNAqpkNtL0CJbLKh6fQ7x6l4d0t/QqpuKXPvF5l0wr
+Fb131STrh7fkiTT1glrra1UhJzz/KVOR+TG32GOSI0hOTqu4/gDQ/vUV0gh0SJw
OvndKFWbSnE=
-----END ENCRYPTED PRIVATE KEY-----';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }

    public function testPKCS8RC440()
    {
        // openssl pkcs8 -in private.pem -topk8 -v1 PBE-SHA1-RC4-40 -out enckey.pem

        // EncryptionAlgorithm: pbeWithSHAAnd40BitRC4

        $key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE5DAcBgoqhkiG9w0BDAECMA4ECNrUHnZnezluAgIIAASCBMKm0lEML1hIdzcu
pglVZSwG8JyigX3xgHwnN4oAz6HYTNeatkV3xqGP23DP84e9En7mQTRxuQ9Tk8sN
NoEK2VsjYmrJtsbyTH21M0vPlAvFv/sIgxVYcF7jv58jHhLNnibPtDeMXZDjd4uq
vdppAGRxIr5z8XZ8ruAlsAB/xzcz1bIK8CwWH9NBOWLDe195/OYoUrgf9h+U2s8e
1rbq+U/woJZHD7+3RuPmtWTtrneY/NVTiU03OUUleys88eCSqZrQzR0faNxdVzTg
mhSrtkqwho9xxUFY6KqLOTF4BQufD+ZSNt9LN3EaFdvGcI1QWWDH11ne98oz3GUa
GCGhaADTOAAOdvXyv+6YRfj1VvtisUeiGjttFmaUHGOmHCCYoyVkbhsRq4QnbbCv
641ogRbuISBHwr+mzqjwTZXC5Laxsrn5EnCZ309vohq7l+g3M1Y9nzR8hOsiqTFu
7PPj1jYM8znYkVx/me+xnpB/d3Ot86K6NszbTaWk9cHr4qfkF+pu2kUYQ/26CUBE
y7DxYmhXnOBRGUTvQebrMoSK8hOaw0uWEQtYp3gLOS1hituL965m2qRbP/ysDP85
DAorOSbKDEMHYy7UP3xh743FErEOoY83GtugnJgjrTlJ/5NyS1KFr5QUsQD/N/Zw
bIkjdFT5mjWVaotHzpNc1IigpAPbNpe4J1E1E2nB8YE5ckSEVseUJ+ypgWSvJxmU
G68YvidODClnBekff8sRDCNN4dekQgnNEMbAWgHRWtMERvXS/9xfJZiqiq+7WvIE
Xvu1Qq5zG3+mESNX9AVLngv5btD2m6QFEqOLG9JKQWp61J3c2lG/kdtWBjsXiWoi
zvkA4u9ZxUzX3s3T2aHozg9O4+0ti947l7wSIxbxLYA0d1M7cQoeKAuRnpwzfCZ9
gpQ9VG9acDhU9LCxcZBHfuKROeI7D7wL//MJp/ue26uhOZY7Z0gbFIfjeSPW+HD0
fRGA849/1aKIsRarKg2YleqsXO04E3J/lpTt1gjy3aGE25Arq6qo+4DRsUIIWeS+
QwzdDeqy1zs8BIPxa51U/jvbqxCvqXsMw4la0txkSwymMvc6U+QpJgm2KqSDCs8W
+QYIz4SYlADLgl+MVDGd9IB/PN8AIZ0Lr7QqKKBIrfyegO/gjCkHCdNIh1Q/Bzbf
rq8AYwbxHnp2Jn2MAzw9s13ncENpZqCDHkhmd89hJc1B4f8rv5KhDsIVb85XQJek
pdpqugcYjxohSBEa9yzp0JDRa97Btir7D4+9HG2NUullFgXvbqKvlKPj+ORUDxJd
DMGC2Uov1koiVBvvahtmr8eTBNdA48cA7l/c5t8UsGbjrwpqLZLTJ1FHjnVKybuu
soPwPAxr3WBE4Ien7WqPj+GTeLWMb9//kpi5grguv3Db6rdH2Y4PT9Fi4UBxd+6N
LqB1rPkt4AQtQwda1ccixYXIFfWSJ6+XEyp6/wsW05DZAiu3R4o/T9Z59KPGlbf0
aaEAW+FZ9jYa6sDBlMwCN2TEmnBFkytJYe8+B5UxkEAIn3g/Vr9R4t4YDCSE2ugs
q6YJC1bQ8jHojcWTs47zcefCXhOkKOg3oxzYIQe9Ikdmf70JxIo+bS92O2vrkV0p
OFLPBrLe4Hw=
-----END ENCRYPTED PRIVATE KEY-----';
        $pass = 'asdf';

        $this->pkcs8tester($key, $pass);
    }
}
