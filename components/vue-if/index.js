
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
        return {"ok": true,"type": 'B'};
    };
    obj.components = {};
    obj.template = `
<div>
    <!-- 条件判断 -->
    <h1 v-if="ok">Yes</h1>
    <div v-if="type === 'A'">
    A
    </div>
    <div v-else-if="type === 'B'">
    B
    </div>
    <div v-else-if="type === 'C'">
    C
    </div>
    <div v-else>
    Not A/B/C
    </div>
    <template v-if="ok">
        <h1>Title</h1>
        <p>Paragraph 1</p>
        <p>Paragraph 2</p>
    </template>
    <template v-if="type === 'username'">
        <label>Username</label>
        <input placeholder="Enter your username" key="username-input">
    </template>
    <template v-else>
        <label>Email</label>
        <input placeholder="Enter your email address" key="email-input">
    </template>
    <h1 v-show="ok">Hello!</h1>
</div>
`;

    module.exports = obj;
