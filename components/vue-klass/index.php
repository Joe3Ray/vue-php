
    <?php
        include_once('Vue_Base.php');

        class Vue_Klass extends Vue_Base {

            public $_d = array("isActive" => true,"classObj" => array("isActive" => true),"activeClass" => "active","errorClass" => "text-danger");
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('div',array("staticClass"=>"static","class"=>array("active" => $ctx->_d["isActive"]))),$ctx->_v(" "),$ctx->_c('div',array("class"=>$ctx->_d["classObj"])),$ctx->_v(" "),$ctx->_c('div',array("class"=>array($ctx->_d["activeClass"],$ctx->_d["errorClass"])))));
            }
        }
