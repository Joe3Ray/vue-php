
    var _module1 = {
        exports: {}
    };
    (function (module, exports) {
        "use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    methods: {
        onSubmit: function onSubmit() {},
        greet: function greet() {}
    }
};
    })(_module1, _module1.exports);

    var obj = _module1.exports.default || _module1.exports;

    obj.data = function () {
        return {data: 'hello vue',a: 'hoho',b: 'haha',rawHtml: '<div>aladdin</div>',dynamicId: 'myid',someDynamicCondition: false,number: 2,ok: true,seen: false,url: 'http://baidu.com',isActive: true,classObj: {isActive: 'true'},activeClass: true,errorClass: true,activeColor: 'red',fontSize: 30,styleObject: {color: 'red',fontSize: '13px'},baseStyles: {color: 'red',fontSize: '13px'},overridingStyles: {color: 'green'},type: 'A',items: {0: {message: 'Foo'},1: {message: 'Bar'}},checked: false,picked: '',selected: '',toggle: ''};
    };
    obj.components = {myA: require('/Users/zhulei/Documents/demo/vue-php/components/myA/myA')};
    obj.template = `
<div>
    <!-- 插值 -->
    <!-- 普通差值 -->
    <p>{{ a }}</p>
    <!-- 单次渲染 -->
    <p v-once>{{ a }}</p>
    <!-- 纯html -->
    <div v-html="rawHtml"></div>
    <!-- 属性 -->
    <div v-bind:id="dynamicId"></div>
    <button v-bind:disabled="someDynamicCondition">Button</button>
    <!-- 表达式 -->
    <p>{{ number + 1 }}</p>
    <p v-bind:id="'my' + dynamicId">{{ ok ? 'YES' : 'NO' }}</p>
    <!-- 指令 -->
    <p v-if="seen">haha</p>
    <a v-bind:href="url"></a>
    <form v-on:submit.prevent="onSubmit"></form>
    <!-- 过滤器 -->
    <p>{{ 'ABC' | lower }}</p>
    <!-- v-bind / v-on 缩写 -->
    <a :href="url"></a>
    <a @click="onSubmit"></a>
    <!-- class -->
    <div class="static" v-bind:class="{ active: isActive }"></div>
    <div v-bind:class="classObj"></div>
    <div v-bind:class="[activeClass, errorClass]">
    <!-- style -->
    <div v-bind:style="{ color: activeColor, fontSize: fontSize + 'px' }"></div>
    <div v-bind:style="styleObject"></div>
    <div v-bind:style="[baseStyles, overridingStyles]"></div>
    <div v-bind:style="{ transform: 'translateX(10px)'}"></div>
    <!-- 条件判断 -->
    <h1 v-if="ok">Yes</h1>
    <div v-if="type === 'A'">
    A
    </div>
    <div v-else-if="type === 'B'">
    B
    </div>
    <div v-else-if="type === 'C'">
    C
    </div>
    <div v-else>
    Not A/B/C
    </div>
    <template v-if="ok">
        <h1>Title</h1>
        <p>Paragraph 1</p>
        <p>Paragraph 2</p>
    </template>
    <template v-if="type === 'username'">
        <label>Username</label>
        <input placeholder="Enter your username" key="username-input">
    </template>
    <template v-else>
        <label>Email</label>
        <input placeholder="Enter your email address" key="email-input">
    </template>
    <h1 v-show="ok">Hello!</h1>
    <!-- 循环 -->
    <ul id="example-1">
        <li v-for="item in items">
            {{ item.message }}
        </li>
    </ul>
    <ul>
        <template v-for="item in items">
            <li>{{ item.message }}</li>
            <li class="divider"></li>
        </template>
    </ul>
    <ul id="repeat-object" class="demo">
        <li v-for="value in styleObject">
            {{ value }}
        </li>
    </ul>
    <div>
        <span v-for="n in 10">{{ n }}</span>
    </div>
    <myA v-for="item in items" :tplData="item.message"></myA>
    <div v-for="item in items" :key="item.message"></div>
    <div v-for="item in items" v-if="item.message === 'Foo'">{{ item.message }}</div>
    <!-- 事件 -->
    <button v-on:click="counter += 1">增加 1</button>
    <button v-on:click="greet">Greet</button>
    <button v-on:click="greet('hi')">Say hi</button>
    <a v-on:click.stop="greet"></a>
    <input v-on:keyup.13="greet">
    <div @click.ctrl="greet">Do something</div>
    <!-- 表单 -->
    <input v-model="a" placeholder="edit me">
    <textarea v-model="a" placeholder="add multiple lines"></textarea>
    <input type="checkbox" id="checkbox" v-model="checked">
    <label for="checkbox">{{ checked }}</label>
    <div id="example-4" class="demo">
        <input type="radio" id="one" value="One" v-model="picked">
        <label for="one">One</label>
        <br>
        <input type="radio" id="two" value="Two" v-model="picked">
        <label for="two">Two</label>
        <br>
        <span>Picked: {{ picked }}</span>
    </div>
    <div id="example-5" class="demo">
        <select v-model="selected">
            <option>A</option>
            <option>B</option>
            <option>C</option>
        </select>
        <span>Selected: {{ selected }}</span>
    </div>
    <div id="example-6" class="demo">
        <select v-model="selected" multiple style="width: 50px">
            <option>A</option>
            <option>B</option>
            <option>C</option>
        </select>
        <br>
        <span>Selected: {{ selected }}</span>
    </div>
    <input type="checkbox" v-model="toggle" v-bind:true-value="a" v-bind:false-value="b">
    <select v-model="selected">
        <option v-bind:value="{ number: 123 }">123</option>
    </select>
    <input v-model.lazy="msg" >
    <!-- 组件 -->
    <myA :tplData="data" v-on:increment="greet" v-on:input="greet"></myA>
</div>
`;

    module.exports = obj;
