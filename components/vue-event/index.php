
    <?php
        include_once('Vue_Base.php');

        class Vue_Event extends Vue_Base {

            public $_d = array("counter" => 0);
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('button',array("on"=>array("click"=> 'function')),array($ctx->_v("增加 1"))),$ctx->_v(" "),$ctx->_c('button',array("on"=>array("click"=> 'function')),array($ctx->_v("Greet"))),$ctx->_v(" "),$ctx->_c('button',array("on"=>array("click"=> 'function')),array($ctx->_v("Say hi"))),$ctx->_v(" "),$ctx->_c('a',array("on"=>array("click"=> 'function'))),$ctx->_v(" "),$ctx->_c('input',array("on"=>array("keyup"=> 'function'))),$ctx->_v(" "),$ctx->_c('div',array("on"=>array("click"=> 'function')),array($ctx->_v("Do something")))));
            }
        }
