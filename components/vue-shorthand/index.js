
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
        onSubmit: function onSubmit() {}
    }
};
    })(_module1, _module1.exports);

    var obj = _module1.exports.default || _module1.exports;

    obj.data = function () {
        return {"url": 'http://www.baidu.com'};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- v-bind / v-on 缩写 -->
    <a :href="url"></a>
    <a @click="onSubmit"></a>
</div>
`;

    module.exports = obj;
