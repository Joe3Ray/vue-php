<?php
$name = $argv[1];
$cls = 'Vue_' . $name;
$filePath = __DIR__ . '/components/' . $name . '/' . $name . '.php';
if ($argv[2]) {
    $filePath = $argv[2];
}
include_once($filePath);
$instance = new $cls();
$virtualDom = $instance->render($instance);

function isUnaryTag($tag) {
    $arr = array(
        'area','base','br','col','embed','frame','hr','img','input','isindex','keygen','link','meta','param','source','track','wbr'
    );

    return in_array(strtolower($tag), $arr);
}

// 是否索引数组
function isPlainArray($arr) {
    if (is_array($arr)) {
        $keys = array_keys($arr);
        return $keys === array_keys($keys);
    }
    return false;
}

$html = '';
$css = array();

// 根据virtualdom结构渲染html
function getRenderCnt($virtualDom, $isRoot=false) {
    global $html;
    global $css;
    $style = $virtualDom['context']->style;
    if ($style) {
        $css[] = $style;
    }
    if ($virtualDom['tag']) {
        if ($isRoot) {
            if (!isset($virtualDom['data']) || !is_array($virtualDom['data'])) {
                $virtualDom['data'] = array();
            }
            if (!isset($virtualDom['data']['attrs']) || !is_array($virtualDom['data']['attrs'])) {
                $virtualDom['data']['attrs'] = array();
            }
            $virtualDom['data']['attrs']['server-rendered'] = 'true';
        }
        $html .= renderStartingTag($virtualDom);

        $children = $virtualDom['children'];
        if ($children && count($children) > 0) {
            foreach ($children as $v) {
                getRenderCnt($v);
            }
        }

        $tag = $virtualDom['tag'];
        if (!isUnaryTag($tag)) {
            $html .= '</' . $tag . '>';
        }
    }
    elseif ($virtualDom['isComment']) {
        $html .= "<!--" . $virtualDom['text'] . "-->";
    }
    else {
        $html .= $virtualDom['raw'] ? $virtualDom['text'] : htmlspecialchars($virtualDom['text'], ENT_QUOTES);
    }
}

// VNode工厂
function generateVNode($tag=null, $data=null, $children=null, $text=null, $elm=null, $context=null, $componentOptions=null) {
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

// 处理vnode的domProps属性
function renderDomProps(&$data, &$children) {
    $props = $data['domProps'];
    if (is_array($props) && count($props) > 0) {
        $attrs = $data['attrs'];
        foreach ($props as $k => $v) {
            if ($k === 'innerHTML') {
                $child = generateVNode(null, null, null, $v);
                $child['raw'] = true;
                $children = array($child);
            }
            elseif ($k === 'textContent') {
                $child = generateVNode(null, null, null, $v);
                $children = array($child);
            }
            else {
                $propsToAttrMap = array(
                    'acceptCharset' => 'accept-charset',
                    'className' => 'class',
                    'htmlFor' => 'for',
                    'httpEquiv' => 'http-equiv'
                );
                $attr = $propsToAttrMap[$k];
                if (!$attr) {
                    $attr = strtolower($k);
                }
                if (isValidAttr($attr) && !($attrs && $attrs[$attr])) {
                    if (!$attrs) {
                        $data['attrs'] = array();
                    }
                    $data['attrs'][$attr] = $v;
                }
            }
        }
    }
}

// 是否合法属性
function isValidAttr($attr) {
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

// 是否是布尔值的属性
function isBooleanAttr($attr) {
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

// 是否可枚举值的属性
function isEnumeratedAttr($attr) {
    $allAttrs = array(
        'contenteditable','draggable','spellcheck'
    );

    if (in_array($attr, $allAttrs)) {
        return true;
    }
    return false;
}

// 值是否是false或者跟null
function isFalsyAttrValue($val) {
    if ($val === null || $val === false) {
        return true;
    }
    return false;
}

// 渲染标签头
function renderStartingTag(&$virtualDom) {
    renderDomProps($virtualDom['data'], $virtualDom['children']);
    $markup = '<' . $virtualDom['tag'];
    $data = $virtualDom['data'];

    // 处理attrs
    if (isset($data['attrs']) && count($data['attrs']) > 0) {
        foreach ($data['attrs'] as $k => $v) {
            if (isBooleanAttr($k)) {
                if (!isFalsyAttrValue($v)) {
                    $markup .= ' ' . $k . '="' . $k . '"';
                }
            }
            elseif (isEnumeratedAttr($k)) {
                $markup .= ' ' . $k . '="' . ((isFalsyAttrValue($v) || $v === 'false') ? 'false' : 'true') . '"';
            }
            else {
                /**
                 * php中字符串连接跟js的处理不一样
                 * 针对不同类型的数据，我们在php中模拟js数据类型的字符串连接
                 */
                // 模拟js中的数组
                if (isPlainArray($v)) {
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
    if ($data['staticClass']) {
        array_push($cls, $data['staticClass']);
    }
    $cls = array_merge($cls, handleClass($data['class']));
    if (count($cls) > 0) {
        $markup .= ' class="' . join(' ', $cls) . '"';
    }

    // 处理directives
    if ($data['directives']) {
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
    if ($data['staticStyle']) {
        $style = handleArrayKey($data['staticStyle']);
    }
    if ($data['style']) {
        $style = array_merge($style, handleStyle($data['style']));
    }
    if (count($style) > 0) {
        $markup .= ' style="';
        foreach ($style as $k => $v) {
            $markup .= $k . ':' . $v . ';';
        }
        $markup = substr($markup, 0, -1);
        $markup .= '"';
    }

    $markup .= '>';

    return $markup;
}

// 处理class的数据结构
function handleClass($arr) {
    $ret = array();
    if (is_array($arr)) {
        if (isPlainArray($arr)) {
            foreach ($arr as $k => $v) {
                if (is_string($v)) {
                    array_push($ret, $v);
                }
                else {
                    $ret2 = handleClass($v);
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

    return $ret;
}

// 处理style的数据结构
function handleStyle($arr) {
    $ret = array();
    if (is_array($arr)) {
        if (isPlainArray($arr)) {
            foreach ($arr as $v) {
                if (is_array($v)) {
                    $v = handleArrayKey($v);
                }
                $ret = array_merge($ret, $v);
            }
        }
        else {
            $ret = handleArrayKey($arr);
        }
    }
    return $ret;
}

// 把数组的key处理成连字符形式
function handleArrayKey($arr) {
    $ret = array();
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $k = inCamelize($k);
            $ret[$k] = $v;
        }
    }

    return $ret;
}

// 反camelize化
function inCamelize($str) {
    preg_match_all('/([A-Z])/', $str, $match);
    foreach ($match[0] as $i => $e) {
        $str = str_replace($e, '-' . strtolower($match[1][$i]), $str);
    }
    return $str;
}

getRenderCnt($virtualDom, true);

$css = array_unique($css);

echo $html;