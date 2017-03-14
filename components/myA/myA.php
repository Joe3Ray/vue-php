
    <?php
        include_once('Vue_Base.php');

        class Vue_myA extends Vue_Base {

            public $_d = array("a" => 123,"b" => false);
            public $options = array(
                "components" => array()
            );
            public $style = ".container {color: red;}.container div {color: green;}";
            
            public function _m0($ctx) {
                return $ctx->_c('div',array($ctx->_c('p',array($ctx->_v("abc")))));
            }
                

            public function render($ctx) {
                return $ctx->_c('div',array("staticClass"=>"container"),array($ctx->_m(0),$ctx->_v(" "),$ctx->_c('p',array("staticClass"=>"cpan","staticStyle"=>array("color" => ("blue")),"attrs"=>array("id"=>"yo")),array($ctx->_v($ctx->_s($ctx->_d["a"])))),$ctx->_v(" "),($ctx->_d["b"])?$ctx->_c('p',array($ctx->_v("haha"))):($ctx->_e()),$ctx->_v(" "),$ctx->_t("default",array($ctx->_v("my common slot"))),$ctx->_v(" "),$ctx->_c('p',array($ctx->_v($ctx->_s($ctx->_d["tplData"]))))),2);
            }
        }

        /*
        $instance = new Vue_myA();
        $virtualDom = $instance->render($instance);
        echo json_encode($virtualDom);
        */
