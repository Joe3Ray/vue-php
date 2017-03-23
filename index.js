var vuetojs = require('vue-to-js');
var path = require('path');
var fs = require('fs');

var compName = process.argv[2];
var compPath = path.resolve(__dirname, 'components', compName, 'index.vue');
var blocks = vuetojs.getBlocks(compPath);

var config;
try {
    config = JSON.parse(blocks.config[0].code);
}
catch (e) {
    config = {};
}

var configData = config.data ? jsObjToPhpArr(config.data) : 'array()';
var configComponents = config.components ? jsObjToPhpArr(config.components) : 'array()';

function jsObjToPhpArr(obj) {
    var ret = 'array(';
    var keys = Object.keys(obj);
    keys.forEach(function (e, i) {
        var val = obj[e];
        ret += '"' + e + '" => ';
        if (typeof val === 'string') {
            ret += '"' + val + '"';
        }
        else if (typeof val === 'object') {
            ret += jsObjToPhpArr(val);
        }
        else {
            ret += val;
        }
        if (i + 1 < keys.length) {
            ret += ',';
        }
    });
    ret += ')';
    return ret;
}

function jsObjToStr(obj, fn) {
    fn = fn || function (str) {return "'" + str + "'";};
    var ret = '';
    if (Array.isArray(obj)) {
        ret = '[';
        obj.forEach(function (e, i) {
            if (typeof e === 'object') {
                ret += jsObjToStr(e);
            }
            else {
                ret += fn(e);
            }
            if (i + 1 < obj.length) {
                ret += ',';
            }
        });
        ret += ']';
    }
    else {
        ret = '{';
        var keys = Object.keys(obj);
        keys.forEach(function (e, i) {
            var val = obj[e];
            ret += '"' + e + '": ';
            if (typeof val === 'object') {
                ret += jsObjToStr(val);
            }
            else {
                ret += fn(val);
            }
            if (i + 1 < keys.length) {
                ret += ',';
            }
        });
        ret += '}';
    }
    return ret;
}

var template = blocks.template.code;
var phpCompiler = require('./vue-template-php-compiler/build');
var phpInfo = phpCompiler.compile(template);

var styleCode = blocks.styles.map(function (e, i) {
    return e.code;
}).join('').replace(/\n/g, '');

var phpRender = phpInfo.render;
var phpStaticRender = phpInfo.staticRenderFns;

var className = compName.replace(/-/g, '_');
className = className.split('_').map(function (e, i) {
    var chars = e.split('');
    chars[0] = chars[0].toUpperCase();
    return chars.join('');
}).join('_');

var phpCode = `
    <?php
        include_once('Vue_Base.php');

        class ${className} extends Vue_Base {

            public $_d = ${configData};
            public $options = array(
                "components" => ${configComponents}
            );
            public $style = "${styleCode}";
            ${phpStaticRender.map(function (fn, i) {
                return `
            public function _m${i}($ctx) {
                ${fn}
            }
                `;
            }).join('\n')}

            public function render($ctx) {
                ${phpRender}
            }
        }
`;

var phpPath = path.resolve(__dirname, 'components', compName, 'index.php');
fs.writeFileSync(phpPath, phpCode);

var transform = require('babel-core').transform;

function getScript(code) {
    var result = transform(code, {
        plugins: ['transform-es2015-modules-commonjs'],
        presets: ['env']
    });

    return result.code;
}

var jsData = config.data ? jsObjToStr(config.data, function (str) {
    if (typeof str === 'string') {
        return "'" + str + "'";
    }
    else {
        return str;
    }
}) : '{}';
var jsComponents = config.components ? jsObjToStr(config.components, function (str) {
    var filepath = path.resolve(__dirname, str);
    return 'require(\'' + filepath + '\')';
}) : '{}';
var jsCode = `
    var _module1 = {
        exports: {}
    };
    (function (module, exports) {
        ${getScript(blocks.script.code)}
    })(_module1, _module1.exports);

    var obj = _module1.exports.default || _module1.exports;

    obj.data = function () {
        return ${jsData};
    };
    obj.components = ${jsComponents};
    obj.template = \`${template}\`;

    module.exports = obj;
`;

var jsPath = path.resolve(__dirname, 'components', compName, 'index.js');
fs.writeFileSync(jsPath, jsCode);