<?php

/**
 * class Vue_Base
 * Vue基类
 * 所有Vue组件都继承自Vue_Base
 */
class Vue_Base {
    public static function getTagNamespace($tag) {
        return false;
    }

    public static function isReservedTag($tag) {
        return false;
    }

    public static function parsePlatformTagName($tag) {
        return $tag;
    }

    // 是否数字或字符串
    public static function isPrimitive($val) {
        if (is_numeric($val) || is_string($val)) {
            return true;
        }
        return false;
    }

    // VNode工厂
    public static function generateVNode($tag=null, $data=null, $children=null, $text=null, $elm=null, $context=null, $componentOptions=null) {
        $node = array();
        $node['tag'] = $tag;
        $node['data'] = $data;
        $node['children'] = $children;
        $node['text'] = $text;
        $node['elm'] = $elm;
        $node['ns'] = null;
        $node['context'] = $context;
        $node['functionalContext'] = null;
        $node['key'] = null;
        if (is_array($data) && isset($data['key'])) {
            $node['key'] = $data['key'];
        }
        $node['componentOptions'] = $componentOptions;
        $node['child'] = null;
        $node['parent'] = null;
        $node['raw'] = false;
        $node['isStatic'] = false;
        $node['isRootInsert'] = true;
        $node['isComment'] = false;
        $node['isCloned'] = false;
        $node['isOnce'] = false;
        return $node;
    }

    // 是否索引数组
    public static function isPlainArray($arr) {
        if (is_array($arr)) {
            $keys = array_keys($arr);
            return $keys === array_keys($keys);
        }
        return false;
    }

    // 创建一个空的虚拟dom
    public static function createEmptyVNode() {
        $node = self::generateVNode();
        $node['text'] = '';
        $node['isComment'] = true;
        return $node;
    }

    // 创建文本虚拟dom
    public static function createTextVNode($text) {
        return self::generateVNode(null, null, null, (string)$text);
    }

    public static function normalizeArrayChildren($children, $nestedIndex='') {
        $res = array();
        for ($i = 0; $i < count($children); $i++) {
            $c = $children[$i];
            if ($c == null || is_bool($c)) {
                continue;
            }
            $last = $res[count($res) - 1];
            // nested
            if (self::isPlainArray($c)) {
                $res = array_merge($res, self::normalizeArrayChildren($c, $nestedIndex . '_' . $i));
            }
            elseif (self::isPrimitive($c)) {
                if ($last && $last['text']) {
                    $last['text'] .= (string)$c;
                }
                elseif ($c !== '') {
                    array_push($res, self::createTextVNode($c));
                }
            }
            else {
                if ($c['text'] && $last && $last['text']) {
                    $res[count($res) - 1] = self::createTextVNode($last['text'] . $c['text']);
                }
                else {
                    if ($c['tag'] && $c['key'] == null && $nestedIndex != null) {
                        $c['key'] = '__vlist' . $nestedIndex . '_' . $i . '__';
                    }
                    array_push($res, $c);
                }
            }
        }
        return $res;
    } 

    // 处理$children
    public static function normalizeChildren($children) {
        return self::isPrimitive($children) ? array(self::createTextVNode($children)) : (self::isPlainArray($children) ? self::normalizeArrayChildren($children) : null);
    }

    public static function simpleNormalizeChildren($children) {
        for ($i = 0; $i < count($children); $i++) {
            if (self::isPlainArray($children)) {
                return array_merge(array(), $children);
            }
        }
        return $children;
    }

    // 驼峰化
    public static function camelize($str) {
        preg_match_all('/-(\w)/', $str, $match);
        foreach ($match[0] as $i => $e) {
            $str = str_replace($e, strtoupper($match[1][$i]), $str);
        }
        return $str;
    }

    // Pascal化
    public static function capitalize($str) {
        $arr = str_split($str);
        $arr[0] = strtoupper($arr[0]);
        return join('', $arr);
    }

    // 获取Vue实例相关信息
    public static function resolveAsset($options, $type, $id) {
        if (!is_string($id)) {
            return;
        }
        $assets = $options[$type];
        if (isset($assets[$id])) {
            return $assets[$id];
        }
        $camelizeId = self::camelize($id);
        if (isset($assets[$camelizeId])) {
            return $assets[$camelizeId];
        }
        $pascalId = self::capitalize($id);
        if (isset($assets[$pascalId])) {
            return $assets[$pascalId];
        }
        $lowerId = strtolower($id);
        if ($lowerId === 'keep-alive' || $lowerId === 'transition' || $lowerId === 'transition-group') {
            return $lowerId;
        }
    }

    public static function applyNS($vnode, $ns) {
        $vnode['ns'] = $ns;
        if ($vnode['ns'] === 'foreignObject') {
            return;
        }
        if (is_array($vnode['children'])) {
            for ($i = 0; $i < count($vnode['children']); $i++) {
                $child = $vnode['children'][$i];
                if ($child['tag'] && !$child['ns']) {
                    self::applyNS($child, $ns);
                }
            }
        }
    }

    // 创建元素
    public static function createElement($context, $tag, $data, $children, $normalizationType=1, $alwaysNormalize=null) {
        if (self::isPlainArray($data) || self::isPrimitive($data)) {
            $normalizationType = $children;
            $children = $data;
            $data = null;
        }
        if ($alwaysNormalize) {
            $normalizationType = 2;
        }

        if (!$tag) {
            return self::createEmptyVNode();
        }

        if ($normalizationType === 2) {
            $children = self::normalizeChildren($children);
        }
        elseif ($normalizationType === 1) {
            $children = self::simpleNormalizeChildren($children);
        }
        if ($context && $children) {
            $context->slots = array();
            foreach($children as $child) {
                if ($child['data'] && $child['data']['slot']) {
                    $slotName = $child['data']['slot'];
                    $context->slots[$slotName] = $child;
                }
            }
        }
        $vnode = null;
        $ns = null;
        if (is_string($tag)) {
            $Ctor = null;
            $ns = self::getTagNamespace($tag);
            if (self::isReservedTag($tag)) {
                $vnode = self::generateVNode(self::parsePlatformTagName($tag), $data, $children, null, null, $context);
            }
            elseif ($Ctor = self::resolveAsset($context->options, 'components', $tag)) {
                $vnode = self::createComponent($Ctor, $data, $context, $children, $tag);
            }
            else {
                $vnode = self::generateVNode($tag, $data, $children, null, null, $context);
            }
        }
        /*
        else {
            $vnode = self::createComponent($tag, $data, $context, $children);
        }
        */
        if ($vnode) {
            if ($ns) {
                self::applyNS($vnode, $ns);
            }
            return $vnode;
        }
        else {
            return self::createEmptyVNode();
        }
    }

    // 创建组件
    public static function createComponent($Ctor, $data, $context, $children, $tag) {
        if (!$Ctor) {
            return;
        }
        include_once(__DIR__ . '/' . $Ctor . '.php');
        $cls = 'Vue_' . $tag;
        $component = new $cls();
        $props = $data['attrs'];
        if (!$props) {
            $props = array();
        }
        foreach ($props as $k => $v) {
            $component->_d[$k] = $v;
        }
        return $component->render($component);
    }
    public function _f_lower($args) {
        $val = $args[0];
        return strtolower($val);
    }

    public function defaultFilter($args) {
        $val = $args[0];
        if (self::isPrimitive($val)) {
            return $val;
        }
        else {
            return '';
        }
    }
    public $_d = array();

    public $options = array();

    public $scopedSlots = array();

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
        if ($b['scopedSlots']) {
            $this->scopedSlots = $b['scopedSlots'];
        }
        
        return self::createElement($this, $a, $b, $c, $d, false);
    }

    // create text node
    public function _v($str) {
        return self::createTextVNode($str);
    }

    // to string
    public function _s($val) {
        if (self::isPrimitive($val)) {
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
        $originAlias = $this->_d[$alias];
        $originIterator1 = $this->_d[$iterator1];
        $originIterator2 = $this->_d[$iterator2];
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
        $this->_d[$alias] = $originAlias;
        $this->_d[$iterator1] = $originIterator1;
        $this->_d[$iterator2] = $originIterator2;
        return $ret;
    }

    // render slot
    public function _t($name, $fallback/*, $props, $bindObject*/) {
        $scopedSlotsFn = $this->scopedSlots[$name];
        if ($scopedSlotsFn && count($scopedSlotsFn) > 1) {
            $res = $scopedSlotsFn[1]($this);
        }
        else {
            $res = $this->slots[$name];
        }
        return $res ? $res : $fallback;
    }

    // create empty node
    public function _e() {
        return self::createEmptyVNode();
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
        $n = floatval($val);
        return ($n || $n === 0) ? $n : $val;
    }
}