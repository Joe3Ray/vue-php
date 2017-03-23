
    <?php
        include_once('Vue_Base.php');

        class Vue_For extends Vue_Base {

            public $_d = array("styleObject" => array("color" => "red","fontSize" => "13px"),"items" => array("0" => array("message" => "Foo"),"1" => array("message" => "Bar")));
            public $options = array(
                "components" => array("vue-com" => "components/vue-com")
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('ul',array("attrs"=>array("id"=>"example-1")),$ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return $ctx->_c('li',array($ctx->_v("\n            ".$ctx->_s($ctx->_d["item"]["message"])."\n        ")));})),$ctx->_v(" "),$ctx->_c('ul',array($ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return array($ctx->_c('li',array($ctx->_v($ctx->_s($ctx->_d["item"]["message"])))),$ctx->_v(" "),$ctx->_c('li',array("staticClass"=>"divider")));})),2),$ctx->_v(" "),$ctx->_c('ul',array("staticClass"=>"demo","attrs"=>array("id"=>"repeat-object")),$ctx->_l($ctx->_d["styleObject"],"value",null,null,function($ctx){return $ctx->_c('li',array($ctx->_v("\n            ".$ctx->_s($ctx->_d["value"])."\n        ")));})),$ctx->_v(" "),$ctx->_c('div',$ctx->_l((10),"n",null,null,function($ctx){return $ctx->_c('span',array($ctx->_v($ctx->_s($ctx->_d["n"]))));})),$ctx->_v(" "),$ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return $ctx->_c('vue-com',array("key"=>item.message,"attrs"=>array("tplData"=>$ctx->_d["item"]["message"])));}),$ctx->_v(" "),$ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return $ctx->_c('div',array("key"=>item.message));}),$ctx->_v(" "),$ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return (($ctx->_d["item"]["message"]===("Foo")))?$ctx->_c('div',array($ctx->_v($ctx->_s($ctx->_d["item"]["message"])))):($ctx->_e());})),2);
            }
        }
