
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
        return {"data": 'hello vue',"number": 2};
    };
    obj.components = {"vue-com": require('/Users/zhulei/Documents/demo/vue-php/components/vue-com')};
    obj.template = `
<div>
    <!-- 组件 -->
    <vue-com :tplData="data" ref="profile">
        <p>content from parent</p>
        <p slot="header">header from parent</p>
        <template slot="item" scope="props">
            <span>{{ props.text }}</span>
        </template>
    </vue-com>
    <component v-bind:is="'vue-com'" :tplData="number"></component>
    <keep-alive>
        <component v-bind:is="'vue-com'" :tplData="number"></component>
    </keep-alive>
</div>
`;

    module.exports = obj;
