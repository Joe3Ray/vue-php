
    <?php
        include_once('Vue_Base.php');

        class Vue_Filter extends Vue_Base {

            public $_d = array();
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('p',array($ctx->_v($ctx->_s($ctx->_f("lower",array(("ABC")))))))));
            }
        }
