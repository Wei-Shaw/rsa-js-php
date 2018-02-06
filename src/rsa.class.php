<?php
/**
 *   RSA-PHP 
 * 	 
 *   Author 空城 <694623056@qq.com>
 *   Copyright (C) 2018 空城
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class rsa
{
	// 公钥文件路径
	public static $pubkey = 'public.pem';
	// 私钥文件路径
	public static $prikey = 'private.pem';
	// js脚本文件路径
	public static $script = 'rsa_pubkey.js';
	// 加解密模式
 	// $model = 1 公钥加密，私钥解密：公开公钥，保存私钥
	// $model = 2 私钥加密，公钥解密：公开私钥，保存公钥
	public static $model = 1;

	/**
	 * 创建新的秘钥对
	 */
	public static function new()
	{
		try {
			$res = openssl_pkey_new();
			openssl_pkey_export($res, $pri);
			$pubkey= openssl_pkey_get_details($res);
			$pubkey = $pubkey['key'];
			if (!file_put_contents(self::$pubkey, $pubkey))
				throw new Exception('创建公钥失败');
			if (!file_put_contents(self::$prikey, $pri)) 
				throw new Exception('创建私钥失败');
			$script = 'var JSEncrypt_pubkey="'.str_replace("\n", '', $pubkey).'";';
			if (!file_put_contents(self::$script, $script)) 
				throw new Exception('创建脚本失败');
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		echo 'Success!';
		
	}

	/**
	 * RSA加密数据
	 * @param  string $data 加密数据
	 */
	public static function encrypt(string $data)
	{
		$file = self::$model === 1 ? self::$pubkey : self::$prikey;
		$func = self::$model === 1 ? 'openssl_public_encrypt' : 'openssl_private_encrypt';
		$key = file_get_contents($file);
		$func($data, $encrypt, $key);
		return $encrypt;
	}

	/**
	 * RSA解密数据
	 * @param  string $data 解密数据
	 */
	public static function decrypt(string $data)
	{
		$file = self::$model === 1 ? self::$prikey : self::$pubkey;
		$func = self::$model === 1 ? 'openssl_private_decrypt' : 'openssl_public_decrypt';
		$key = file_get_contents($file);
		$func($data, $decrypt, $key);
		return $decrypt;
	}

	/**
	 * 数据签名
	 * @param  string $data 待签名数据
	 */
	public static function sign(string $data)
	{
		$key = file_get_contents(self::$prikey);
		$pkeyid = openssl_pkey_get_private($key);
		openssl_sign($data, $signature, $pkeyid);
		openssl_free_key($pkeyid);
		return $signature;
	}

	/**
	 * 数据验签
	 * @param  string $data 未签名数据
	 * @param  string $sign 签名
	 */
	public static function verify($data, $sign)
	{
		$key = file_get_contents(self::$pubkey);
		$pubkey = openssl_pkey_get_public($key);
		$verify = openssl_verify($data, $sign, $pubkey);
		openssl_free_key($pubkey);
		return boolval($verify);
	}
}