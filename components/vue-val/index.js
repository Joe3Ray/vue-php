
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
        return {"a": 'hoho',"rawHtml": '<div>vue</div>',"dynamicId": 'myid',"someDynamicCondition": false,"number": 2,"ok": true};
    };
    obj.components = {};
    obj.template = `
<div>
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
</div>
`;

    module.exports = obj;
