<?php
/**
 * A simple class describing virtual dom
 */
class VNode {
    /**
     * tag name
     * @var string
     */
    public $tag;
    public $data;
    public $children;
    public $text;
    public $elm;
    public $ns = null;
    public $context;
    public $functionalContext = null;
    public $key = null;
    public $componentOptions;
    public $child = null;
    public $parent = null;
    public $raw = false;
    public $isStatic = false;
    public $isRootInsert = true;
    public $isComment = false;
    public $isCloned = false;
    public $isOnce = false;
    // flag: 是否应该调过当前节点，直接渲染子节点
    public $skipToChildren = false;
    // flag: 是否是组件
    public $isComponent = false;

    public function __construct($tag=null, $data=array(), $children=null, $text=null, $elm=null, $context=null, $componentOptions=null) {
        $this->tag = $tag;
        $this->data = $data;
        $this->children = $children;
        $this->text = $text;
        $this->elm = $elm;
        $this->context = $context;
        if (is_array($data) && isset($data['key'])) {
            $this->key = $data['key'];
        }
        $this->componentOptions = $componentOptions;
    }
}

/**
 * class Vue_Base
 * Vue基类
 * 所有Vue组件都继承自Vue_Base
 */
class Vue_Base {

    public function getTagNamespace($tag) {
        return false;
    }

    public function isReservedTag($tag) {
        return false;
    }

    public function parsePlatformTagName($tag) {
        return $tag;
    }

    // 是否数字或字符串
    public function isPrimitive($val) {
        if (is_numeric($val) || is_string($val)) {
            return true;
        }
        return false;
    }

    // 组件目录
    private $componentsDir = array('./');

    // 设置组件目录
    public function setComponentsDir($dirs) {
        if (isset($dirs)) {
            if (is_string($dirs)) {
                array_unshift($this->componentsDir, $dirs);
            }
            elseif ($this->isPlainArray($dirs)) {
                foreach($dirs as $dir) {
                    if (is_string($dir)) {
                        array_unshift($this->componentsDir, $dir);
                    }
                }
            }
        }
    }

    // VNode工厂
    public function generateVNode($tag=null, $data=array(), $children=null, $text=null, $elm=null, $context=null, $componentOptions=null) {
        $node = new VNode($tag, $data, $children, $text, $elm, $context, $componentOptions);
        return $node;
    }

    // 是否索引数组
    public function isPlainArray($arr) {
        if (is_array($arr)) {
            $keys = array_keys($arr);
            return $keys === array_keys($keys);
        }
        return false;
    }

    // 创建一个空的虚拟dom
    public function createEmptyVNode() {
        $node = $this->generateVNode();
        $node->text = '';
        $node->isComment = true;
        return $node;
    }

    // 创建文本虚拟dom
    public function createTextVNode($text) {
        return $this->generateVNode(null, null, null, (string)$text);
    }

    public function normalizeArrayChildren($children, $nestedIndex='') {
        $res = array();
        for ($i = 0; $i < count($children); $i++) {
            $c = $children[$i];
            if ($c == null || is_bool($c)) {
                continue;
            }
            if (count($res) > 1) {
                $last = $res[count($res) - 1];
            }
            // nested
            if ($this->isPlainArray($c)) {
                $res = array_merge($res, $this->normalizeArrayChildren($c, $nestedIndex . '_' . $i));
            }
            elseif ($this->isPrimitive($c)) {
                if (isset($last) && isset($last->text)) {
                    $last->text .= (string)$c;
                }
                elseif ($c !== '') {
                    array_push($res, $this->createTextVNode($c));
                }
            }
            else {
                if ($c->text && isset($last) && $last->text) {
                    $res[count($res) - 1] = $this->createTextVNode($last->text . $c->text);
                }
                else {
                    if ($c->tag && $c->key == null && $nestedIndex != null) {
                        $c->key = '__vlist' . $nestedIndex . '_' . $i . '__';
                    }
                    array_push($res, $c);
                }
            }
        }
        return $res;
    } 

    // 处理$children
    public function normalizeChildren($children) {
        return $this->isPrimitive($children) ? array($this->createTextVNode($children)) : ($this->isPlainArray($children) ? $this->normalizeArrayChildren($children) : null);
    }

    public function simpleNormalizeChildren($children) {
        for ($i = 0; $i < count($children); $i++) {
            if ($this->isPlainArray($children)) {
                return array_merge(array(), $children);
            }
        }
        return $children;
    }

    // 驼峰化
    public function camelize($str) {
        preg_match_all('/-(\w)/', $str, $match);
        foreach ($match[0] as $i => $e) {
            $str = str_replace($e, strtoupper($match[1][$i]), $str);
        }
        return $str;
    }

    // Pascal化
    public function capitalize($str) {
        $arr = str_split($str);
        $arr[0] = strtoupper($arr[0]);
        return join('', $arr);
    }

    // 获取Vue实例相关信息
    public function resolveAsset($id) {
        if (!is_string($id)) {
            return;
        }
        if (isset($this->options['components']) && isset($this->options['components'][$id])) {
            foreach ($this->componentsDir as $dir) {
                $path = $dir . '/' . $id . '/index.php';
                $path = str_replace('//', '/', $path);
                if (file_exists($path)) {
                    return $path;
                }
            }
        }
    }

    public function applyNS($vnode, $ns) {
        $vnode['ns'] = $ns;
        if ($vnode['ns'] === 'foreignObject') {
            return;
        }
        if (is_array($vnode['children'])) {
            for ($i = 0; $i < count($vnode['children']); $i++) {
                $child = $vnode['children'][$i];
                if ($child['tag'] && !$child['ns']) {
                    $this->applyNS($child, $ns);
                }
            }
        }
    }

    // 数据混合
    public function extendData($to, $from) {
        foreach ($from as $k => $v) {
            $to[$k] = $v;
        }
        return $to;
    }

    // 创建元素
    public function createElement($context, $tag, $data, $children, $normalizationType=1, $alwaysNormalize=null) {
        if ($this->isPlainArray($data) || $this->isPrimitive($data)) {
            $normalizationType = $children;
            $children = $data;
            $data = null;
        }
        if ($alwaysNormalize) {
            $normalizationType = 2;
        }

        if (!$tag) {
            return $this->createEmptyVNode();
        }

        if ($normalizationType === 2) {
            $children = $this->normalizeChildren($children);
        }
        elseif ($normalizationType === 1) {
            $children = $this->simpleNormalizeChildren($children);
        }
        $vnode = null;
        $ns = null;
        if (is_string($tag)) {
            $Ctor = null;
            $ns = $this->getTagNamespace($tag);
            if ($this->isReservedTag($tag)) {
                $vnode = $this->generateVNode($this->parsePlatformTagName($tag), $data, $children, null, null, $context);
            }
            // 针对 keep-alive / transition 这2个内置组件
            // 直接取子节点
            elseif ($tag === 'keep-alive' || $tag === 'transition') {
                $vnode = $this->generateVNode(null, null, $children);
                $vnode->skipToChildren = true;
            }
            // 针对transition-group
            elseif ($tag === 'transition-group') {
                $tag = 'span';
                if (is_array($data) && is_array($data['attrs'])) {
                    if (isset($data['attrs']['tag'])) {
                        $tag = $data['attrs']['tag'];
                        unset($data['attrs']['tag']);
                    }
                    $innerAttrs = array(
                        'name','appear','css','mode','type','enter-class','leave-class',
                        'enter-to-class','leave-to-class','enter-active-class','leave-active-class',
                        'appear-class','appear-active-class','appear-to-class'
                    );
                    foreach ($data['attrs'] as $k => $v) {
                        if (in_array($k, $innerAttrs)) {
                            unset($data['attrs'][$k]);
                        }
                    }
                }
                $vnode = $this->generateVNode($tag, $data, $children, null, null, $context);

            }
            // 根据attrs.tag（默认span）设置当前节点
            elseif ($Ctor = $this->resolveAsset($tag)) {
                $vnode = $this->createComponent($Ctor, $data, $context, $children, $tag);
            }
            else {
                $vnode = $this->generateVNode($tag, $data, $children, null, null, $context);
            }
        }
        /*
        else {
            $vnode = $this->createComponent($tag, $data, $context, $children);
        }
        */
        if ($vnode) {
            if ($ns) {
                $this->applyNS($vnode, $ns);
            }
            return $vnode;
        }
        else {
            return $this->createEmptyVNode();
        }
    }

    public function handleClsName($tag) {
        $str = str_replace('-', '_', $tag);
        $arr = explode('_', $str);
        $res = array();
        foreach ($arr as $v) {
            $v[0] = strtoupper($v[0]);
            $res[] = $v;
        }
        $str = join('_', $res);
        return $str;
    }

    // 创建组件
    public function createComponent($Ctor, $data, $context, $children, $tag) {
        if (!$Ctor) {
            return;
        }

        $vnode = $this->generateVNode($tag, $data);
        $vnode->isComponent = true;

        include_once($Ctor);
        $cls = $this->handleClsName($tag);
        $component = new $cls();
        // 处理slot
        if ($children && count($children) > 0) {
            foreach ($children as $child) {
                $slotName = 'default';
                if ($child->data && $child->data['slot']) {
                    $slotName = $child->data['slot'];
                }
                if (!isset($component->slots[$slotName]) || !is_array($component->slots[$slotName])) {
                    $component->slots[$slotName] = array();
                }
                array_push($component->slots[$slotName], $child);
            }
        }

        // 处理scopedSlots
        if (isset($data['scopedSlots']) && is_array($data['scopedSlots'])) {
            $component->scopedSlots = $data['scopedSlots'];
        }

        // 处理props
        $props = array();
        if (isset($data['attrs'])) {
            $props = $data['attrs'];
        }
        foreach ($props as $k => $v) {
            $component->_d[$k] = $v;
        }
        $vnode->children =  array($component->_render($component));
        return $vnode;
    }
    public function _f_lower($args) {
        $val = $args[0];
        return strtolower($val);
    }

    public function defaultFilter($args) {
        $val = $args[0];
        if ($this->isPrimitive($val)) {
            return $val;
        }
        else {
            return '';
        }
    }
    public $_d = array();

    public $options = array();

    public $scopedSlots = array();

    public $slots = array();

    public function __construct() {

    }

    // call static render function
    public function _m($index) {
        $methodName = "_m" . $index;
        if (method_exists($this, $methodName)) {
            return $this->$methodName($this);
        }
        return null;
    }

    // create element
    public function _c($a=null, $b=null, $c=null, $d=null) {
        if (isset($b['scopedSlots'])) {
            $this->scopedSlots = $b['scopedSlots'];
        }
        
        return $this->createElement($this, $a, $b, $c, $d, false);
    }

    // create text node
    public function _v($str) {
        return $this->createTextVNode($str);
    }

    // to string
    public function _s($val) {
        if ($this->isPrimitive($val)) {
            return (string)$val;
        }
        elseif ($val === true) {
            return 'true';
        }
        elseif ($val === false) {
            return 'false';
        }
        elseif (is_array($val)) {
            return json_encode($val);
        }
        return '';
    }

    // call filter function
    public function _f($id, $args) {
        $filterId = '_f_' . $id;
        if (method_exists($this, $filterId)) {
            $ret = $this->$filterId($args);
        }
        else {
            $ret = $this->defaultFilter($args);
        }
        return $ret;
    }

    // loop the array and call the function
    public function _l($arr, $alias, $iterator1, $iterator2, $fn) {
        if (!$arr) {
            return;
        }
        $ret = array();
        if (isset($this->_d[$alias])) {
            $originAlias = $this->_d[$alias];
        }
        if (isset($this->_d[$iterator1])) {
            $originIterator1 = $this->_d[$iterator1];
        }
        if (isset($this->_d[$iterator2])) {
            $originIterator2 = $this->_d[$iterator2];
        }
        if (is_string($arr)) {
            $arr = str_split($arr);
        }
        elseif (is_numeric($arr)) {
            $tmp = array();
            for ($i = 0; $i < $arr; $i++) {
                $tmp[$i] = $i + 1;
            }
            $arr = $tmp;
        }
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $this->_d[$iterator1] = $k;
                $this->_d[$alias] = $v;
                array_push($ret, $fn($this));
            }
        }
        if (isset($originAlias)) {
            $this->_d[$alias] = $originAlias;
        }
        if (isset($originIterator1)) {
            $this->_d[$iterator1] = $originIterator1;
        }
        if (isset($originIterator2)) {
            $this->_d[$iterator2] = $originIterator2;
        }
        return $ret;
    }

    // render slot
    public function _t($name, $fallback, $props=null, $bindObject=null) {
        if (isset($this->scopedSlots[$name])) {
            $scopedSlotsFn = $this->scopedSlots[$name];
        }
        if (isset($scopedSlotsFn) && is_array($scopedSlotsFn) && count($scopedSlotsFn) > 1) {
            if (!is_array($props)) {
                $props = array();
            }
            if (is_array($bindObject)) {
                $props = $this->extendData($props, $bindObject);
            }
            $propName = $scopedSlotsFn[0];
            if (isset($this->_d[$propName])) {
                $originData = $this->_d[$propName];
            }
            $this->_d[$propName] = $props;
            $res = $scopedSlotsFn[1]($this);
            if (isset($originData)) {
                $this->_d[$propName] = $originData;
            }
        }
        elseif (isset($this->slots[$name])) {
            $res = $this->slots[$name];
        }
        return isset($res) ? $res : $fallback;
    }

    // create empty node
    public function _e() {
        return $this->createEmptyVNode();
    }

    // number add or string concat
    public function _a($a, $b) {
        if (!is_string($a) && is_numeric($a) && !is_string($b) && is_numeric($b)) {
            return $a + $b;
        }
        return $a . $b;
    }

    // loose equal
    public function _q($a, $b) {
        if (is_array($a) && is_array($b)) {
            if (json_encode($a) === json_encode($b)) {
                return true;
            }
        }
        elseif (!is_array($a) && !is_array($b)) {
            if ((string)$a === (string)$b) {
                return true;
            }
        }
        return false;
    }

    // loose indexOf
    public function _i($arr, $val) {
        foreach($arr as $i => $v) {
            if ($this->_q($v, $val)) {
                return $i;
            }
        }
        return -1;
    }

    // to number
    public function _n($val) {
        if (is_numeric($val)) {
            return $val;
        }
        elseif (is_string($val)) {
            $arr = str_split($val);
            $ret = array();
            $hasDot = false;
            foreach ($arr as $v) {
                preg_match("/\d|\./", $v, $match);
                if (count($match) > 0 && ($v != '.' || !$hasDot)) {
                    $ret[] = $v;
                    if ($v === '.') {
                        $hasDot = true;
                    }
                }
                else {
                    break;
                }
            }
            $ret = join('', $ret);
            return floatval($ret);
        }
        return $val;
    }

    // get length
    public function _len($val) {
        if (is_string($val)) {
            return strlen($val);
        }
        elseif ($this->isPlainArray($val)) {
            return count($val);
        }
        else {
            return $val['length'];
        }
    }

    public function isUnaryTag($tag) {
        $arr = array(
            'area','base','br','col','embed','frame','hr','img','input','isindex','keygen','link','meta','param','source','track','wbr'
        );
        return in_array(strtolower($tag), $arr);
    }

    // html
    private $html = '';

    // css
    private $css = array();

    // _render
    public function _render($ctx) {}

    // render
    public function render($data = null) {
        if (isset($data) && is_array($data)) {
            $this->_d = array_merge_recursive($data, $this->_d);
        }
        $vnode = $this->_render($this);
        if ($vnode instanceof VNode) {
            $this->getRenderCnt($vnode, true);
        }
        return array(
            'html' => $this->html,
            'css' => join('', array_unique($this->css))
        );
    }

    public function getRenderCnt($virtualDom, $isRoot=false) {
        if (isset($virtualDom->context->style)) {
            $this->css[] = $virtualDom->context->style;
        }
        if ($virtualDom->tag) {
            if ($virtualDom->isComponent) {
                $children = $virtualDom->children[0];
                // 合并staticClass
                $staticClass = array();
                if (isset($children->data['staticClass'])){
                    array_push($staticClass, $children->data['staticClass']);
                }
                if (isset($virtualDom->data['staticClass'])) {
                    array_push($staticClass, $virtualDom->data['staticClass']);
                }
                $staticClass = join(' ', $staticClass);
                if ($staticClass != '') {
                    $children->data['staticClass'] = $staticClass;
                }
                // 合并class
                $klass = array();
                if (isset($children->data['class'])) {
                    $klass = $this->handleClass($children->data['class']);
                }
                if (isset($virtualDom->data['class'])) {
                    $klass = array_merge($klass, $this->handleClass($virtualDom->data['class']));
                }
                if (count($klass) > 0) {
                    $children->data['class'] = $klass;
                }
                $virtualDom = $children;
            }
            if ($isRoot) {
                if (!isset($virtualDom->data) || !is_array($virtualDom->data)) {
                    $virtualDom->data = array();
                }
                if (!isset($virtualDom->data['attrs']) || !is_array($virtualDom->data['attrs'])) {
                    $virtualDom->data['attrs'] = array();
                }
                $virtualDom->data['attrs']['server-rendered'] = 'true';
            }
            $this->html .= $this->renderStartingTag($virtualDom);

            $children = $virtualDom->children;
            if (is_array($children) && count($children) > 0) {
                foreach ($children as $v) {
                    $this->getRenderCnt($v);
                }
            }

            $tag = $virtualDom->tag;
            if (!$this->isUnaryTag($tag)) {
                $this->html .= '</' . $tag . '>';
            }
        }
        elseif ($virtualDom->skipToChildren) {
            $children = $virtualDom->children;
            if (is_array($children) && count($children) > 0) {
                foreach ($children as $v) {
                    $this->getRenderCnt($v);
                }
            }
        }
        elseif ($virtualDom->isComment) {
            $this->html .= "<!--" . $virtualDom->text . "-->";
        }
        else {
            $this->html .= $virtualDom->raw ? $virtualDom->text : htmlspecialchars($virtualDom->text, ENT_QUOTES);
        }
    }

    // 是否是布尔值的属性
    public function isBooleanAttr($attr) {
        $allAttrs = array(
            'allowfullscreen','async','autofocus','autoplay','checked','compact','controls','declare',
            'default','defaultchecked','defaultmuted','defaultselected','defer','disabled',
            'enabled','formnovalidate','hidden','indeterminate','inert','ismap','itemscope','loop','multiple',
            'muted','nohref','noresize','noshade','novalidate','nowrap','open','pauseonexit','readonly',
            'required','reversed','scoped','seamless','selected','sortable','translate',
            'truespeed','typemustmatch','visible'
        );

        if (in_array($attr, $allAttrs)) {
            return true;
        }
        return false;
    }

    // 值是否是false或者跟null
    public function isFalsyAttrValue($val) {
        if ($val === null || $val === false) {
            return true;
        }
        return false;
    }

    // 是否可枚举值的属性
    public function isEnumeratedAttr($attr) {
        $allAttrs = array(
            'contenteditable','draggable','spellcheck'
        );

        if (in_array($attr, $allAttrs)) {
            return true;
        }
        return false;
    }

    // 反camelize化
    public function inCamelize($str) {
        preg_match_all('/([A-Z])/', $str, $match);
        foreach ($match[0] as $i => $e) {
            $str = str_replace($e, '-' . strtolower($match[1][$i]), $str);
        }
        return $str;
    }

    // 处理class的数据结构
    public function handleClass($arr) {
        $ret = array();
        if (is_array($arr)) {
            if ($this->isPlainArray($arr)) {
                foreach ($arr as $k => $v) {
                    if (is_string($v)) {
                        array_push($ret, $v);
                    }
                    else {
                        $ret2 = $this->handleClass($v);
                        $ret = array_merge($ret, $ret2);
                    }
                }
            }
            else {
                foreach ($arr as $k => $v) {
                    if ($v) {
                        array_push($ret, $k);
                    }
                }
            }
        }
        elseif (is_string($arr)) {
            array_push($ret, $arr);
        }

        return $ret;
    }

    // 把数组的key处理成连字符形式
    public function handleArrayKey($arr) {
        $ret = array();
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $k = $this->inCamelize($k);
                $ret[$k] = $v;
            }
        }

        return $ret;
    }

    // 处理style的数据结构
    public function handleStyle($arr) {
        $ret = array();
        if (is_array($arr)) {
            if ($this->isPlainArray($arr)) {
                foreach ($arr as $v) {
                    if (is_array($v)) {
                        $v = $this->handleArrayKey($v);
                    }
                    $ret = array_merge($ret, $v);
                }
            }
            else {
                $ret = $this->handleArrayKey($arr);
            }
        }
        return $ret;
    }

    public function renderStartingTag(&$virtualDom) {
        $this->renderDomProps($virtualDom->data, $virtualDom->children);
        $markup = '<' . $virtualDom->tag;
        $data = $virtualDom->data;

        // 处理attrs
        if (isset($data['attrs']) && count($data['attrs']) > 0) {
            foreach ($data['attrs'] as $k => $v) {
                if ($this->isBooleanAttr($k)) {
                    if (!$this->isFalsyAttrValue($v)) {
                        $markup .= ' ' . $k . '="' . $v . '"';
                    }
                }
                elseif ($this->isEnumeratedAttr($k)) {
                    $markup .= ' ' . $k . '="' . (($this->isFalsyAttrValue($v) || $v === 'false') ? 'false' : 'true') . '"';
                }
                else {
                    /**
                    * php中字符串连接跟js的处理不一样
                    * 针对不同类型的数据，我们在php中模拟js数据类型的字符串连接
                    */
                    // 模拟js中的数组
                    if ($this->isPlainArray($v)) {
                        $v = join(',', $v);
                    }
                    // 模拟js中的对象
                    elseif (is_array($v)) {
                        $v = '[object Object]';
                    }
                    // 模拟js中的true
                    elseif ($v === true) {
                        $v = 'true';
                    }
                    // 模拟js中的false
                    elseif ($v === false) {
                        $v = 'false';
                    }
                    // 模拟js中的null
                    elseif ($v === null) {
                        $v = 'null';
                    }

                    $markup .= ' ' . $k . '="' . $v . '"';
                }
            }
        }

        // 处理class
        $cls = array();
        if (isset($data['staticClass'])) {
            array_push($cls, $data['staticClass']);
        }
        if (isset($data['class'])) {
            $cls = array_merge($cls, $this->handleClass($data['class']));
        }
        if (count($cls) > 0) {
            $markup .= ' class="' . join(' ', $cls) . '"';
        }

        // 处理directives
        if (isset($data['directives'])) {
            $dirs = $data['directives'];
            foreach ($dirs as $dir) {
                if ($dir['name'] === 'show' && !$dir['value']) {
                    if (!$data['staticStyle']) {
                        $data['staticStyle'] = array();
                    }
                    $data['staticStyle']['display'] = 'none';
                }
            }
        }

        // 处理style
        $style = array();
        if (isset($data['staticStyle'])) {
            $style = $this->handleArrayKey($data['staticStyle']);
        }
        if (isset($data['style'])) {
            $style = array_merge($style, $this->handleStyle($data['style']));
        }
        if (count($style) > 0) {
            $markup .= ' style="';
            foreach ($style as $k => $v) {
                $markup .= $k . ':' . $v . ';';
            }
            //$markup = substr($markup, 0, -1);
            $markup .= '"';
        }

        $markup .= '>';

        return $markup;
    }

    // 是否合法属性
    public function isValidAttr($attr) {
        $allAttrs = array(
            'accept','accept-charset','accesskey','action','align','alt','async','autocomplete',
            'autofocus','autoplay','autosave','bgcolor','border','buffered','challenge','charset',
            'checked','cite','class','code','codebase','color','cols','colspan','content','http-equiv',
            'name','contenteditable','contextmenu','controls','coords','data','datetime','default',
            'defer','dir','dirname','disabled','download','draggable','dropzone','enctype','method','for',
            'form','formaction','headers','height','hidden','high','href','hreflang','http-equiv',
            'icon','id','ismap','itemprop','keytype','kind','label','lang','language','list','loop','low',
            'manifest','max','maxlength','media','method','GET','POST','min','multiple','email','file',
            'muted','name','novalidate','open','optimum','pattern','ping','placeholder','poster',
            'preload','radiogroup','readonly','rel','required','reversed','rows','rowspan','sandbox',
            'scope','scoped','seamless','selected','shape','size','type','text','password','sizes','span',
            'spellcheck','src','srcdoc','srclang','srcset','start','step','style','summary','tabindex',
            'target','title','type','usemap','value','width','wrap'
        );

        if (in_array($attr, $allAttrs) || strpos($attr, 'data-') === 0 || strpos($attr, 'aria-') === 0) {
            return true;
        }
        return false;
    }

    // 处理vnode的domProps属性
    public function renderDomProps(&$data, &$children) {
        if (isset($data['domProps'])) {
            $props = $data['domProps'];
        }
        if (isset($props) && is_array($props) && count($props) > 0) {
            if (isset($data['attrs'])) {
                $attrs = $data['attrs'];
            }
            foreach ($props as $k => $v) {
                if ($k === 'innerHTML') {
                    $child = $this->generateVNode(null, null, null, $v);
                    $child->raw = true;
                    $children = array($child);
                }
                elseif ($k === 'textContent') {
                    $child = $this->generateVNode(null, null, null, $v);
                    $children = array($child);
                }
                else {
                    $propsToAttrMap = array(
                        'acceptCharset' => 'accept-charset',
                        'className' => 'class',
                        'htmlFor' => 'for',
                        'httpEquiv' => 'http-equiv'
                    );
                    if (isset($propsToAttrMap[$k])) {
                        $attr = $propsToAttrMap[$k];
                    }
                    if (!isset($attr)) {
                        $attr = strtolower($k);
                    }
                    if (isset($attr) && $this->isValidAttr($attr) && !(isset($attrs) && isset($attrs[$attr]))) {
                        if (!isset($attrs)) {
                            $data['attrs'] = array();
                        }
                        $data['attrs'][$attr] = $v;
                    }
                }
            }
        }
    }
}
