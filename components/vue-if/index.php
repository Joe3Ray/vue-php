
    <?php
        include_once('Vue_Base.php');

        class Vue_If extends Vue_Base {

            public $_d = array("ok" => true,"type" => "B");
            public $options = array(
                "components" => array()
            );
            public $style = "";
            

            public function render($ctx) {
                return $ctx->_c('div',array(($ctx->_d["ok"])?$ctx->_c('h1',array($ctx->_v("Yes"))):($ctx->_e()),$ctx->_v(" "),(($ctx->_d["type"]===("A")))?$ctx->_c('div',array($ctx->_v("\n    A\n    "))):((($ctx->_d["type"]===("B")))?$ctx->_c('div',array($ctx->_v("\n    B\n    "))):((($ctx->_d["type"]===("C")))?$ctx->_c('div',array($ctx->_v("\n    C\n    "))):($ctx->_c('div',array($ctx->_v("\n    Not A/B/C\n    ")))))),$ctx->_v(" "),($ctx->_d["ok"])?array($ctx->_c('h1',array($ctx->_v("Title"))),$ctx->_v(" "),$ctx->_c('p',array($ctx->_v("Paragraph 1"))),$ctx->_v(" "),$ctx->_c('p',array($ctx->_v("Paragraph 2")))):($ctx->_e()),$ctx->_v(" "),(($ctx->_d["type"]===("username")))?array($ctx->_c('label',array($ctx->_v("Username"))),$ctx->_v(" "),$ctx->_c('input',array("key"=>"username-input","attrs"=>array("placeholder"=>"Enter your username")))):(array($ctx->_c('label',array($ctx->_v("Email"))),$ctx->_v(" "),$ctx->_c('input',array("key"=>"email-input","attrs"=>array("placeholder"=>"Enter your email address"))))),$ctx->_v(" "),$ctx->_c('h1',array("directives"=>array(array("name"=>"show","rawName"=>"v-show","value"=>($ctx->_d["ok"]),"expression"=>"ok"))),array($ctx->_v("Hello!")))),2);
            }
        }
