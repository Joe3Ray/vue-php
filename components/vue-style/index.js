
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
        return {"activeColor": 'red',"fontSize": 30,"styleObject": {"color": 'red',"fontSize": '13px'},"baseStyles": {"color": 'red',"fontSize": '13px'},"overridingStyles": {"color": 'green'}};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- style -->
    <div v-bind:style="{ color: activeColor, fontSize: fontSize + 'px' }"></div>
    <div v-bind:style="styleObject"></div>
    <div v-bind:style="[baseStyles, overridingStyles]"></div>
    <div v-bind:style="{ transform: 'translateX(10px)'}"></div>
</div>
`;

    module.exports = obj;
