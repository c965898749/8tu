<?php
$config = array (
		//签名方式,默认为RSA2(RSA2048)
		'sign_type' => "RSA2",

		//支付宝公钥
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsUzYuvjjUb7KSVFDt4CcXkwb1r/XwBsdZql7RGo6OIsIgOt5eab876rj4ktstf+5UwzzJqIx3gcwjAnfm8+KO90hPc2RKU5MX6lxgJV7AvKmq4kIIIc84xAesSU3RRrRCM+kx2v9stCe5NTqKf/JIsCFS6QsXMbV97g3vBvZBIa1zHPakX3S7PaaqT/nfb1LvzeVKdq/NDT4R94w4wEzfnW0Z/7n7JhubTwY7y8a+nT1YiJr+KgsaDH2sq6hB318cIbXCYeS0dmfniEEI/rlGme2H7piB0Kn2LK4lySlkbRBKxn8y3oPFoXjb3fIzpGIkijfELWQTSdFBGlPRUZ+vQIDAQAB",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAvFKUdnbELmU/eb3QLGQfXOckGeUHCWD79TQKWJCT7Y703qnc/aRVKKBNSqPr5hKoQI2FF2RO1VXr5XH9XvsiHpIBoT4tAaR/Kfa5DTBDbXO0kDO9KixbAfFG8Dz4SG7ZF8Jxl4thjXScCMhNiZ5DIUiBxK1XQ2E1Z68iUfROWcxG3ky9hSEX4VJLonyVW3ajidjOOosfyejih18j4NWoRAi+OJWkFG8OgKNAFogVXHVgp3OVzLXKsfej0Rvlh4GQquzRKIZOhoHOI/hHDxxGUt6KDHTKJ8Zjq3HtZ6gAsNwTpUOA5H1lIo09s7qSkEEwcPd75lo39/sDsnln9Wr1iQIDAQABAoIBAHVIfLt2ZeF7HtgD1ZT/2eRy8zHJR+OYafIgsdzMVcRaBrhxU3cHlB2UD+7PPcfwkKZ7PIr/5nTIaPxhTGmNv4cIaUlxPnhKaQPKax8CksukhqxT/Jg6PYdtKz/MyNfbaQp6B2JY5K1DFTYHAPsW/DnLPT4usiz9alQjZx1hKDaVh4KtbMu+6bF5sgnhw0q+y30zTdUAnYCLifYF5LZtyfEMIUN44ZIN0t/gIhiTk7Vdr3howRgw6l531wtT4CKfPa8QSSQsMxqq3GTeCOnB+LV3Gf03sMWVY1a5/tUKvGX1oS5aYNjWRdxxjEm8UzIfKMlWq5LpnVAB16SlHZqrN30CgYEA3N3yFHYoBjqVxk+QCN+RRMryWVPjaQu9ybuF4jUyDsNomHW9JzFoB8LzP5IeP51tfgEMz5qySSCoOeJNxnEvPyEs9ha+JWS3kteHIwJd5zobBBA/sXuOR1zjsL9quagUEj/H2kSXubzXxAyIsD0GvoYgqc8mxyLBWiR2RmjPbisCgYEA2kdf7RK88namuL8AaWgYFe6FJKilM6i3l08zunvPc9n3Ze89ScTONPZ1CYakYL6wi8K03cKoLE0F/pOlPH6TZaBET5Q8PRWvUTWIfl1SyAv51nsLwaAYEP23pHys43o+yZ4pUhe+4+/NKsDWits+vfVa0dra2cm/AvZB/VDqhRsCgYAsglGhi/oe3zmFoEz/bMZinZ9fHweqCKKzf6XIYz32OazfZIK83jj/r2rDudd8rGX/SYjiYWNiV0FvgMp5nh+OUko0QXsKIBTac7KY/IYd7di55ehgBO00NmTwHnMMfPGmh9Hni4Ej/glhScFV+sZcKL12WveOk9NxLf3jzIVYxwKBgQDNFF42GXLYw02lKh8y6ZAnmvARHzoHrS7AxLMvNIAWsuVUKlCE7Jlo/V2803nBQ6gPx6Gy8N6csMk/BG0sxyepRciok/d81NDhFdDmGLxcI5RaRUUoaEf0Psy5iEHPf91aBHWCIblB4t36my93SsJKKylnHZ6dHIWQPNqHK+0LnQKBgBZ2H46wDDksk5t1uQVftJAgz8lbc/oAWgfsynrS9cGc0aCI22Lzz0/M1sZ26mQfODDkfN4AG44W+sLQTn5wyaP15oI3SufcH8sizRaODccTJteMZdpaOsOfQH69tT2Gzni3/6uTpWowOHrwxiShO/d6xGrEipBSBDUVgYFXkoYA",

		//编码格式
		'charset' => "UTF-8",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//应用ID
		'app_id' => "2021001187627815",

		//支付通知地址
		'notify_url' => "http://admin.yimem.com:59754/alipay2/notify_url.php",

		//最大查询重试次数
		'MaxQueryRetry' => "10",

		//查询间隔
		'QueryDuration' => "3"
);