<?php
if(class_exists("Gc_MessageBar_Url_Filter")){
    return;
}
class Gc_MessageBar_Url_Filter 
implements 
    Gc_MessageBar_Configurable_Interface {
    protected $url_list = array();
    public function configure(array $options){
        $this->configure_field($options,"url_list");
        $this->prepare_list();
    }
    protected function prepare_list(){
        $prepared_list = array();
        foreach ($this->url_list as $url) {
            $prepared_list[] = $this->prepare_url($url,true);
        }
        $this->url_list = $prepared_list;
    }
    protected function prepare_url($url,$joker = false){
        $url_pieces = parse_url(rtrim($url,"/") );
        $url2 = '';
        if(isset($url_pieces['host'])){
            if(substr($url_pieces['host'], 0,4) != 'www.'){
                $url_pieces['host'] = 'www.'.$url_pieces['host'];
            }
            $url2 = $this->build_url($url_pieces,$joker);
        }
        return $url2;        
    }
    protected function configure_field(array $options,$field_name){
        if(isset($options[$field_name])){
            $this->$field_name = $options[$field_name];
        }
    }
    public function is_allowed($url){
        $url2 = $this->prepare_url($url);
        foreach ($this->url_list as $allowed_url) {
            if(substr($allowed_url,-1) == "*"){
                if(substr($url2,0,strlen($allowed_url)-1) == substr($allowed_url,0,-1)){
                    return true;
                }
            }elseif($url2 == $allowed_url){
                return true;
            }

        }
            return false;
        if(in_array($url2, $this->url_list)){
            return true;
        }
        return in_array($url, $this->url_list);
    }

    protected function build_url($url_pieces,$joker = false){
        $url2 = '';
       $url2 .= "http://";
        if(isset($url_pieces['host'])){
            $url2 .= $url_pieces['host'];
        }else{
            $url2 .= '';
        }
        if(isset($url_pieces['path'])){
            $url2 .= $url_pieces['path'];
        }else{
            $url2 .= '';
        }
        $url2 = rtrim($url2,"/");
        $url2 .= "/";
        if(isset($url_pieces['query'])){
            $url2 .= '?'.$url_pieces['query'];
        }else{
            $url2 .= '';
        }
        if(isset($url_pieces['fragment'])){
            $url2 .= $url_pieces['fragment'];
        }else{
            $url2 .= '';
        }
        if($joker and $star_pos = strpos($url2,"*")){
            return substr($url2,0, $star_pos+1);
        }
        return $url2;
    }


    public function set_url_list($list){
        $this->url_list = $list;
    }

    public function test(){
        /*
        $allowed = array(
            "http://www.testdomain.com/",
            "http://sub.testdomain.com/",
            "http://sub2.testdomain.com*",
            "http://www.testdomain.com/one",
            "http://www.testdomain.com/star*",
            "http://www.testdomain.com/two/?v=3*",
        );
        $this->set_url_list($allowed);
        $this->prepare_list();
        $test = array(
            array(
                "url" => "http://sub.testdomain.com/q",
                "result" => false
                ),
            array(
                "url" => "http://sub.testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "https://sub.testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "http://sub.testdomain.com",
                "result" => true
                ),
            array(
                "url" => "https://sub.testdomain.com",
                "result" => true
                ),

            array(
                "url" => "http://sub2.testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "http://sub2.testdomain.com",
                "result" => true
                ),
            array(
                "url" => "http://sub2.testdomain.com/dddd",
                "result" => true
                ),
            array(
                "url" => "http://sub2.testdomain.com/?asdasd=asdas",
                "result" => true
                ),




            array(
                "url" => "http://www.testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "http://www.testdomain.com",
                "result" => true
                ),
            array(
                "url" => "https://www.testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "https://www.testdomain.com",
                "result" => true
                ),
            array(
                "url" => "http://testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "http://testdomain.com",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com",
                "result" => true
                ),



            array(
                "url" => "http://www.testdomain.com/one/",
                "result" => true
                ),
            array(
                "url" => "http://www.testdomain.com/one",
                "result" => true
                ),
            array(
                "url" => "https://www.testdomain.com/one/",
                "result" => true
                ),
            array(
                "url" => "https://www.testdomain.com/one",
                "result" => true
                ),
            array(
                "url" => "http://testdomain.com/one/",
                "result" => true
                ),
            array(
                "url" => "http://testdomain.com/one",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/one/",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/one",
                "result" => true
                ),


            array(
                "url" => "http://www.testdomain.com/two/?v=3",
                "result" => true
                ),
            array(
                "url" => "http://www.testdomain.com/two?v=3",
                "result" => true
                ),
            array(
                "url" => "https://www.testdomain.com/two/?v=3",
                "result" => true
                ),
            array(
                "url" => "https://www.testdomain.com/two?v=3",
                "result" => true
                ),
            array(
                "url" => "http://testdomain.com/two/?v=3",
                "result" => true
                ),
            array(
                "url" => "http://testdomain.com/two?v=3",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/two/?v=3",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/two?v=3",
                "result" => true
                ),

            array(
                "url" => "https://testdomain.com/two?v=3&v=4",
                "result" => true
                ),

            array(
                "url" => "https://testdomain.com/star",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/star/",
                "result" => true
                ),
            array(
                "url" => "https://testdomain.com/starwars",
                "result" => true
                ),

            array(
                "url" => "https://testdomain.com/star/apple",
                "result" => true
                ),


        );
        var_dump($allowed);
        var_dump($this->url_list);
        foreach ($test as $value) {
            if( $value["result"] == $this->is_allowed($value["url"])){
                echo "OK - ".$value["url"]."</br>".PHP_EOL;
            } else{
                echo "FAILED - ".$value["url"]."</br>".PHP_EOL;
            }
        }
        die;
        */
    }

}