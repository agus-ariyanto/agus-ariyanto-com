<?php
class EtCurl{
    protected $baseurl="http://icndbpi1.iconpln.co.id:8000/mmods/"; // production
    //protected $baseurl="http://icndbapdi1.iconpln.co.id:8020/mmods/"; // development
    //protected $baseurl="http://10.14.23.48:8020/mmods/"; //dev
    //protected $baseurl="http://icndbapdi1.iconpln.co.id:8020/imosy/"; // development
    //protected $baseurl="http://icndbapqi1.iconpln.co.id:8010/imosy/";
    //protected $baseurl="http://icndbpi1.iconpln.co.id:8000/imosy/"; // production

    protected $_url='';
    protected $_usragent='Olive';


    function setUserAgent($usr){
        $this->_usragent=$usr;
    }
    function sendGet($url=''){
        $this->_url=$this->baseurl.$url;
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->_url,
            CURLOPT_USERAGENT =>$this->_usragent,

        ));

        /* Send the request & save response to $resp */
        $resp = curl_exec($curl);
        //$rescode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        /* Close request to clear up some resources */
        curl_close($curl);
        /* http response code harus 2xx */
        //if($rescode <300 && $rescode>199) return $resp;
        return $resp;
    }

    function getContent($url){
        ini_set("allow_url_fopen", 1);
        $this->_url=$this->baseurl.$url;
        return file_get_contents($this->_url);
    }
    function sendPost($url='',$post){
        $this->_url=$this->baseurl.$url;
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->_url,
            CURLOPT_USERAGENT =>$this->_usragent,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post
        ));

        /* Send the request & save response to $resp */
        $resp = curl_exec($curl);
        //$rescode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        /* Close request to clear up some resources */
        curl_close($curl);

        /* http response code harus 2xx */
        //if ($rescode <300 && $rescode>199) return $resp;

        return $resp;
    }

    function setBaseUrl($baseurl){
        //if(strr_pos($baseurl)!=='/') $baseurl=$baseurl.'/';
        $this->baseurl=$baseurl;
    }

    function getUrl(){
        return $this->_url;
    }
    function fromXML($url='',$post=null){
        if(!empty($post)) $data=$this->sendPost($url,$post);
        else $data=$this->sendGet($url);
        return new SimpleXMLElement($data);
    }
    protected function remove_utf8_bom($text){
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }
    function fromJSON($url='',$post=null){
        if(!empty($post)){
            $data=$this->sendPost($url,$post);
        }else {
            $data=$this->sendGet($url);
        }

        if(empty($data)) return 0;

        $data= $this->remove_utf8_bom($data);
        /* convert white space menjadi spasi tunggal */
        $data= preg_replace('/\s+/', ' ', trim($data));
        $data= preg_replace('/[^0-9A-Za-z\.\,\"\}\{\:]/', ' ', trim($data));
            /* hack untuk error dengan coma */
            $data= str_replace(array(', }',',}'),'}',$data);
            return json_decode($data,true);
        }
    }
