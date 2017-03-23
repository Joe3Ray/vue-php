
    <?php
        include_once('Vue_Base.php');

        class Vue_Dir extends Vue_Base {

            public $_d = array("seen" => false,"url" => "http://www.baidu.com");
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array(($ctx->_d["seen"])?$ctx->_c('p',array($ctx->_v("haha"))):($ctx->_e()),$ctx->_v(" "),$ctx->_c('a',array("attrs"=>array("href"=>$ctx->_d["url"]))),$ctx->_v(" "),$ctx->_c('form',array("on"=>array("submit"=> 'function')))));
            }
        }
