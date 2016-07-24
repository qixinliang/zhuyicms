<?php

namespace app\components;

class WeixinDownloadImg {

    //从微信服务器端下载图片到本地服务器
    public function wxDownImg($media_id,$accessToken, $path) {
        //调用 多媒体文件下载接口
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$media_id;
        //用curl请求，返回文件资源和curl句柄的信息
        $info = $this->curl_request($url);
        
        echo "<pre>";
        print_r($info);exit;
        //文件类型
        $types = array('image/bmp' => '.bmp', 'image/gif' => '.gif', 'image/jpeg' => '.jpg', 'image/png' => '.png');
        //判断响应首部里的的content-type的值是否是这四种图片类型
        if (isset($types[$info['header']['content_type']])) {
            //文件的uri
            $path = $path . $types[$info['header']['content_type']];
        } else {
            return false;
        }

        //将资源写入文件里
        if ($this->saveFile($path, $info['body'])) {
            //将文件保存在本地目录
//            $imgPath = rtrim(base_url(), '/') . '/img' . date('Ymd') . '/' . md5($this->controller->wxId . $media_id) . $types[$info['header'['content_type']]];
//            if (!is_dir($imgPath)) {
//                if (mkdir($imgPath)) {
//                    if (false !== rename($path, $imgPath)) {
//                        return $imgPath;
//                    }
//                }
//            }
            return $path;
        }

        return false;
    }

    /**
     * curl请求资源
     * @param  string $url 请求url
     * @return array 
     */
    private function curl_request($url = '') {
        if ($url == '')
            return;
        $ch = curl_init();
        //这里返回响应报文时，只要body的内容，其他的都不要
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        //获取curl连接句柄的信息
        $httpInfo = curl_getinfo($ch);
        curl_close($ch);

        $info = array_merge(array($package), array($httpInfo));

        return $info;
    }

    /**
     * 将资源写入文件
     * @param  string 资源uri
     * @param  source 资源
     * @return boolean 
     */
    private function saveFile($path, $fileContent) {
        $fp = fopen($path, 'w');
        if (false !== $localFile) {
            if (false !== fwrite($fp, $fileContent)) {
                fclose($fp);
                return true;
            }
        }
        return false;
    }

}
