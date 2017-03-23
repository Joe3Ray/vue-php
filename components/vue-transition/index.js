
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
        return {"items": [{"message": 'Foo'},{"message": 'Bar'}]};
    };
    obj.components = {"vue-com": require('/Users/zhulei/Documents/demo/vue-php/components/vue-com')};
    obj.template = `
<div>
    <!-- 过渡效果 -->
    <transition>
        <table v-if="items.length > 0">
            <tr>
                <td></td>
            </tr>
        </table>
        <p v-else>Sorry, no items found.</p>
    </transition>
    <transition name="component-fade" mode="out-in">
        <component v-bind:is="'vue-com'"></component>
    </transition>
    <div>
        <transition-group name="list" tag="p">
            <span v-for="item in items" :key="item.message">{{item.message}}</span>
        </transition-group>
    </div>
    <div>
        <transition-group name="list-a">
            <span v-for="item in items" :key="item.message + 'aa'">{{item.message}}</span>
        </transition-group>
    </div>
</div>
`;

    module.exports = obj;
