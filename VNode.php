<?php
class VNode {

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

    public function __construct($tag=null, $data=null, $children=null, $text=null, $elm=null, $context=null, $componentOptions=null) {
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