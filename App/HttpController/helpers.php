<?php
/*
 * @Author: your name
 * @Date: 2021-06-22 08:43:20
 * @LastEditTime: 2021-06-23 01:12:03
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /opt/easyAdmin/App/HttpController/helpers.php
 */

if (!function_exists('generate_salt')) {
    /**
     * Return localized route name.
     *
     * @param string $routeName
     * @return string
     */
    function generate_salt():string
    {
        $str = '';
        // 使用随机方式生成一个四位字符
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        for ($i = 0; $i < 4; $i++) {
            $str .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $str;
    }
}


if (!function_exists('generate_hash_password')) {
    /**
     * @description: 
     * @param {String} $password
     * @param {String} $salt
     * @return {*}
     */   
    function generate_hash_password(String $password, String $salt):string
    {
        return md5(sha1($password) . $salt);
    }
}