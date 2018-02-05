<?php 
/**
 * 	 Javascript-PHP Test
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
$t_1 = microtime(true);
require_once 'src/rsa.class.php';
// 初始化参数
rsa::$prikey = 'src/key/private.pem';
rsa::$pubkey = 'src/key/public.pem';
rsa::$script = 'rsa_pubkey.js';
rsa::$model = 1;

if (php_sapi_name() === 'cli') {
	$action = $argv[1];
	$response = rsa::$action();
} else {
	$encrypted = base64_decode($_POST['encrypted']);
	$json['decrypted'] = rsa::decrypt($encrypted);
	$t_2 = microtime(true);
	$json['time'] = sprintf('%.4f', $t_2 - $t_1);


	echo json_encode($json);
}

