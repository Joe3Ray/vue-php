
    <?php
        include_once('Vue_Base.php');

        class Vue_Transition extends Vue_Base {

            public $_d = array("items" => array("0" => array("message" => "Foo"),"1" => array("message" => "Bar")));
            public $options = array(
                "components" => array("vue-com" => "components/vue-com")
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array($ctx->_c('transition',array((($ctx->_len($ctx->_d["items"])>(0)))?$ctx->_c('table',array($ctx->_c('tr',array($ctx->_c('td'))))):($ctx->_c('p',array($ctx->_v("Sorry, no items found.")))))),$ctx->_v(" "),$ctx->_c('transition',array("attrs"=>array("name"=>"component-fade","mode"=>"out-in")),array($ctx->_c(("vue-com"),array("tag"=>"component"))),1),$ctx->_v(" "),$ctx->_c('div',array($ctx->_c('transition-group',array("attrs"=>array("name"=>"list","tag"=>"p")),$ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return $ctx->_c('span',array("key"=>item.message),array($ctx->_v($ctx->_s($ctx->_d["item"]["message"]))));}))),1),$ctx->_v(" "),$ctx->_c('div',array($ctx->_c('transition-group',array("attrs"=>array("name"=>"list-a")),$ctx->_l($ctx->_d["items"],"item",null,null,function($ctx){return $ctx->_c('span',array("key"=>item.message + 'aa'),array($ctx->_v($ctx->_s($ctx->_d["item"]["message"]))));}))),1)),1);
            }
        }
