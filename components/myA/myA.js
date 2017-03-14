
    var _module1 = {
        exports: {}
    };
    (function (module, exports) {
        'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['tplData']
};
    })(_module1, _module1.exports);

    var obj = _module1.exports.default || _module1.exports;

    obj.data = function () {
        return {a: 123,b: false};
    };
    obj.components = {};
    obj.template = `
<div class="container">
    <div><p>abc</p></div>
    <p class="cpan" style="color: blue;" id="yo">{{a}}</p>
    <p v-if="b">haha</p>
    <slot>my common slot</slot>
    <p>{{ tplData }}</p>
</div>
`;

    module.exports = obj;
