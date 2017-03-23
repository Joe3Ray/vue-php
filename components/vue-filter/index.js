
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
        return {};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- 过滤器 -->
    <p>{{ 'ABC' | lower }}</p>
</div>
`;

    module.exports = obj;
