
    <?php
        include_once('Vue_Base.php');

        class Vue_Val extends Vue_Base {

            public $_d = array("a" => "hoho","rawHtml" => "<div>vue</div>","dynamicId" => "myid","someDynamicCondition" => false,"number" => 2,"ok" => true);
            public $options = array(
                "components" => array()
            );
            public $style = "";
            
            public function _m0($ctx) {
                return $ctx->_c('p',array($ctx->_v($ctx->_s($ctx->_d["a"]))));
            }
                

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('p',array($ctx->_v($ctx->_s($ctx->_d["a"])))),$ctx->_v(" "),$ctx->_m(0),$ctx->_v(" "),$ctx->_c('div',array("domProps"=>array("innerHTML"=>$ctx->_s($ctx->_d["rawHtml"])))),$ctx->_v(" "),$ctx->_c('div',array("attrs"=>array("id"=>$ctx->_d["dynamicId"]))),$ctx->_v(" "),$ctx->_c('button',array("attrs"=>array("disabled"=>$ctx->_d["someDynamicCondition"])),array($ctx->_v("Button"))),$ctx->_v(" "),$ctx->_c('p',array($ctx->_v($ctx->_s($ctx->_a($ctx->_d["number"], (1)))))),$ctx->_v(" "),$ctx->_c('p',array("attrs"=>array("id"=>("my" . $ctx->_d["dynamicId"]))),array($ctx->_v($ctx->_s(($ctx->_d["ok"]?("YES"):(("NO")))))))));
            }
        }
