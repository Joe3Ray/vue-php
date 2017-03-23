
    <?php
        include_once('Vue_Base.php');

        class Vue_Shorthand extends Vue_Base {

            public $_d = array("url" => "http://www.baidu.com");
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('a',array("attrs"=>array("href"=>$ctx->_d["url"]))),$ctx->_v(" "),$ctx->_c('a',array("on"=>array("click"=> 'function')))));
            }
        }
