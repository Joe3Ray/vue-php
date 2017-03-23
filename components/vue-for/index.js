
    var _module1 = {
        exports: {}
    };
    (function (module, exports) {
        "use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = {};
    })(_module1, _module1.exports);

    var obj = _module1.exports.default || _module1.exports;

    obj.data = function () {
        return {"styleObject": {"color": 'red',"fontSize": '13px'},"items": [{"message": 'Foo'},{"message": 'Bar'}]};
    };
    obj.components = {"vue-com": require('/Users/zhulei/Documents/demo/vue-php/components/vue-com')};
    obj.template = `
<div>
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
    <vue-com v-for="item in items" :tplData="item.message" :key="item.message"></vue-com>
    <div v-for="item in items" :key="item.message"></div>
    <div v-for="item in items" v-if="item.message === 'Foo'">{{ item.message }}</div>
</div>
`;

    module.exports = obj;
