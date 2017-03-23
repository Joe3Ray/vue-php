
    <?php
        include_once('Vue_Base.php');

        class Vue_Com_Test extends Vue_Base {

            public $_d = array("data" => "hello vue","number" => 2);
            public $options = array(
                "components" => array("vue-com" => "components/vue-com")
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('vue-com',array("ref"=>"profile","attrs"=>array("tplData"=>$ctx->_d["data"]),"scopedSlots"=>array("item"=>array("props",function ($ctx){return array($ctx->_c('span',array($ctx->_v($ctx->_s($ctx->_d["props"]["text"])))));}))),array($ctx->_c('p',array($ctx->_v("content from parent"))),$ctx->_v(" "),$ctx->_c('p',array("slot"=>"header"),array($ctx->_v("header from parent"))))),$ctx->_v(" "),$ctx->_c(("vue-com"),array("tag"=>"component","attrs"=>array("tplData"=>$ctx->_d["number"]))),$ctx->_v(" "),$ctx->_c('keep-alive',array($ctx->_c(("vue-com"),array("tag"=>"component","attrs"=>array("tplData"=>$ctx->_d["number"])))),1)),1);
            }
        }
