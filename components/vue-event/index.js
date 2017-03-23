
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
        greet: function greet() {}
    }
};
    })(_module1, _module1.exports);

    var obj = _module1.exports.default || _module1.exports;

    obj.data = function () {
        return {"counter": 0};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- 事件 -->
    <button v-on:click="counter += 1">增加 1</button>
    <button v-on:click="greet">Greet</button>
    <button v-on:click="greet('hi')">Say hi</button>
    <a v-on:click.stop="greet"></a>
    <input v-on:keyup.13="greet">
    <div @click.ctrl="greet">Do something</div>
</div>
`;

    module.exports = obj;
