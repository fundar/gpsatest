<?php
if(!class_exists("Gc_MessageBar_Metrix_Helper")){
    return;
}


class Gc_MessageBar_Metrix_Helper{
    protected $endpoint_url;
    protected $metrix_code;
    protected $click_id;
    public function configure($data){
        if(isset($data['endpoint_url'])){
            $this->endpoint_url = $data['endpoint_url'];
        }

        if(isset($data['metrix_code'])){
            $this->metrix_code = $data['metrix_code'];
        }

        if(isset($data['click_id'])){
            $this->click_id = $data['click_id'];
        }


    }

    public function render(){
        $urlPrefix = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        ?>
        <script type="text/javascript">
        //<![CDATA[
        try {
           var MXTracker = new MX.tracker();
           MXTracker.setOption('base_url', '<?php echo $urlPrefix.$this->endpoint_url ?>');
           MXTracker.setId('<?php echo $this->metrix_code->get_value();?>');
           MXTracker.trackPageView();
        } catch(err) {}
        //]]>
        </script>
        <?php

    }

}
