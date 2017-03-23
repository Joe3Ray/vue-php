
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
        return {"seen": false,"url": 'http://www.baidu.com'};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- 指令 -->
    <p v-if="seen">haha</p>
    <a v-bind:href="url"></a>
    <form v-on:submit.prevent="onSubmit"></form>
</div>
`;

    module.exports = obj;
