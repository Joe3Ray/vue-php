
    <?php
        include_once('Vue_Base.php');

        class Vue_Style extends Vue_Base {

            public $_d = array("activeColor" => "red","fontSize" => 30,"styleObject" => array("color" => "red","fontSize" => "13px"),"baseStyles" => array("color" => "red","fontSize" => "13px"),"overridingStyles" => array("color" => "green"));
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('div',array("style"=>(array("color" => $ctx->_d["activeColor"],"fontSize" => ($ctx->_d["fontSize"] . "px"))))),$ctx->_v(" "),$ctx->_c('div',array("style"=>($ctx->_d["styleObject"]))),$ctx->_v(" "),$ctx->_c('div',array("style"=>(array($ctx->_d["baseStyles"],$ctx->_d["overridingStyles"])))),$ctx->_v(" "),$ctx->_c('div',array("style"=>(array("transform" => ("translateX(10px)")))))));
            }
        }
