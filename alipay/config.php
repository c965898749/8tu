<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2019070660554562",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEAtzdm3iB5DsmAkvj+bwURewiLTZQ3g2kYXwDW6ogOrXx9QmhJ3cSRNKL/Ys9KLpvW2cZS5Ms/TB/nYDlBTbT9DQ15hKnM9CB9pbu4pRVpnuSepRcQyhXSJOYRhpOpsC6HeTUIWJ8YLBHQIRkyXZ7qtz3T6GeYUbxJhNWDpPy1r1Js6xuY1w6qGNbX51GfpEbAvI82vQKhn0ED8HAaVpzL1yI87g1SZe5EgQOPRQX3O5otg2wQdTb53ud8g4H2960NHM4zv1iCEM15gDGXJTt6FZ1uNGNltN4mcTTj8UD44yfu9aPC/MDgCWjcfemcT1Fo8Zbr6UKkzmuWgwTOlw6u5QIDAQABAoIBAFD/WCXJO1uTL5lKJMmV9wVqYJNafDRHSPPvHhQvNDX0PDgYfNdi6ELZ8IeRVp1GXfSG3eyyAfi4fgSnKzycT+buVxOUMbyXhJS5acbpsXkCXzRi/xQoE3AmmgbhzvnJiDDIhjkPlJMfCOEpD4AeGmwKFF3BNrCjyejEr99HrValHQ3r/bKHETGuU+kghr4Mmm8z5XiuI5lVFJf+I4Gq12345EYzbL8RCTzZC3WlybJe9KdPs3PQQ8Qb3l/TWnkozwNSPPW8mXhwL8yF2fNq4jeX4IzmBbezSKuoEzPBbalCr5qp6diwu9Tu/1QxEFHKu9365lyKesbFvLXoUoLaYD0CgYEA5pfs2i3fKiyCuqqShagal+9TROxv4GQU+99d+zU2QD4kDaQanRfIQmC3lpAhWFFqOvZ86QeMAJxNYNH8oOVfUzEfGVoiaSBpI7/LTU6WQQxL5d/rDL1sIhgrhEhSKp99G+tXVFtJ4KsuuRbUMDf6Jl6IDZkqIFuH8Ek2ayCavxMCgYEAy2crNrwoeknDPu7dIHKhlUNUqUbF+32F3+zI0vnw+8GOVEjqvSEkCQTk+D/3cCC1E5FOBrayx4dmnhzcVv6meLzaGsICSEoxfdneEkSFK3FVNPvVQCq6eein+k5H2W9/qaHs5RQK4wIP76ZPLWuHK7yEjLFMyYiltv/TR6VhgScCgYEAqFRlSUvNKfs+H0ffGASEDT9emOTEMpi8nLUM5RGOHc1/Aho2d1DiFlqGCjoCrXcZRhujSXUB5Xw0HCaN9WIbeR5VpmsezYEkXz1mCnQAyVFRomgYLL+mTSk5syIcIRM2AAiHQoQ1ZPVxcRnSIbTrPdEqHksd7msCzAyV1hQCAtMCgYB9SAhX6EDOQYyewKU2jSR1OUl2Ef9zzXad28w+FnCVwDwYMelToIv/eiJFvbB8QckGSmNSw0kOmJleHcyhUKvchgWYoZKiUAB30a90dPvJLD8dKVfJ9Adzexerlneut3xcUT4GQvgJpoWGSFtQUICrMaw6tCHlp+LZ+mx6HqUV1QKBgQDMWweAtfqZK5quBIdIKSzehRoif3nDzg820WZiQ0DkVdBaFA/evTdlcOiJVPsON56a3uZdqA+R3hZeS/3G0r34orakSVa7ZR4IC22ei2Gm2tjivc5gwm/3gaSs0u/eD/YDdP9kOondgBdJuPrty1+Wqxeb8H5fVrVzQxxyoUNk7Q==",
		
		//异步通知地址
		'notify_url' => "http://工程公网访问地址/alipay/notify_url.php",
		
		//同步跳转
		'return_url' => "http://工程公网访问地址/alipay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgK12345jWRg/5ZB6CNXMLvOIASwFekb77hgKbhC+VRVm0omFFNqZ528OzlvyOndx6GAGm+C7QJfjsXvyIm6rh4X8PL59eXGA8woPDZ4WZnKqASJGOV4SzRct1AsQ2jHHWnqXkjsr4VWknOYD3R6ORSSTvNJ4gdypc55E3dqZL5tKkwc59Gkh/n2Lcfcaeufd0xPoZFrsAEGjtSjnkDJqv7rMyct82luHGLdMCCEl6cqUsjpG9tU+tcjGY86yPeITj0HXFfOWCw5yOJQBQk6t9Nubi6Kr1qMKeh8evQqKXSRQGP6+aJVAk91eo8NChdUUYVJ8yIe7a2wXXUDTPCzdAKe2yZ3MwIDAQAB",
		
	
);