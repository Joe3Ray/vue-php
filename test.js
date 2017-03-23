var expect = require('chai').expect;

global.Vue = require('vue');
Vue.filter('lower', function (value) {
    return value.toLowerCase();
});

var renderer = require('vue-server-renderer/build').createRenderer();

function jsRenderer(app) {
    return new Promise((resolve, reject) => {
        renderer.renderToString(app, (err, html) => {
            if (err) {
                reject('js render error');
            }
            else {
                html = html.trim();
                resolve(html);
            }
        });
    });
}

var child = require('child_process');

function phpRenderer(name) {
    return new Promise((resolve, reject) => {
        child.exec('php index.php ' + name, (err, stdout, stderr) => {
            if (!err && !stderr) {
                var html = stdout.trim();
                resolve(html);
            }
            else {
                reject('php render error');
            }
        });
    });
}

function generateApp(name) {
    var opt = {
        template: '<' + name + ' />',
        components: {}
    };
    opt.components[name] = require('./components/' + name);
    var app = new Vue(opt);
    return app;
}

describe('插值: vue-val', function () {
    it('vue-php对插值的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-val');
        return Promise.all([jsRenderer(app), phpRenderer('vue-val')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('指令: vue-dir', function () {
    it('vue-php对指令的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-dir');
        return Promise.all([jsRenderer(app), phpRenderer('vue-dir')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('过滤器: vue-filter', function () {
    it('vue-php对过滤器的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-filter');
        return Promise.all([jsRenderer(app), phpRenderer('vue-filter')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('v-bind/v-on简写: vue-shorthand', function () {
    it('vue-php对v-bind/v-on简写的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-shorthand');
        return Promise.all([jsRenderer(app), phpRenderer('vue-shorthand')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('css类: vue-klass', function () {
    it('vue-php对css类的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-klass');
        return Promise.all([jsRenderer(app), phpRenderer('vue-klass')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('内联style: vue-style', function () {
    it('vue-php对内联style的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-style');
        return Promise.all([jsRenderer(app), phpRenderer('vue-style')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('条件判断: vue-if', function () {
    it('vue-php对条件判断的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-if');
        return Promise.all([jsRenderer(app), phpRenderer('vue-if')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('循环: vue-for', function () {
    it('vue-php对循环的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-for');
        return Promise.all([jsRenderer(app), phpRenderer('vue-for')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('事件: vue-event', function () {
    it('vue-php对事件的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-event');
        return Promise.all([jsRenderer(app), phpRenderer('vue-event')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('表单: vue-form', function () {
    it('vue-php对表单的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-form');
        return Promise.all([jsRenderer(app), phpRenderer('vue-form')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('组件: vue-com', function () {
    it('vue-php对组件的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-com-test');
        return Promise.all([jsRenderer(app), phpRenderer('vue-com-test')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});

describe('过渡动画: vue-transition', function () {
    it('vue-php对过渡动画的渲染结果与vue-server-renderer一致', function () {
        var app = generateApp('vue-transition');
        return Promise.all([jsRenderer(app), phpRenderer('vue-transition')]).then(([res1, res2]) => {
            expect(res1).to.equal(res2);
        })
    });
});