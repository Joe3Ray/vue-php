
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
        return {"isActive": true,"classObj": {"isActive": 'true'},"activeClass": 'active',"errorClass": 'text-danger'};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- class -->
    <div class="static" v-bind:class="{ active: isActive }"></div>
    <div v-bind:class="classObj"></div>
    <div v-bind:class="[activeClass, errorClass]"></div>
</div>
`;

    module.exports = obj;
