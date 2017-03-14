var child = require('child_process');
var colors = require('colors');

var phpResult;
child.exec('php index.php myB', function (err, stdout, stderr) {
    if (!err) {
        phpResult = stdout.trim();
        console.log('phpResult : ', phpResult);
    }
});

global.Vue = require('vue');
Vue.filter('lower', function (value) {
    return value.toLowerCase();
});
var app = new Vue({
    template: '<myB />',
    components: {
        myB: require('./components/myB/myB')
    }
});

var renderer = require('./vue-server-renderer/build').createRenderer();

var jsResult;
renderer.renderToString(app, function (err, html) {
    if (!err) {
        jsResult = html.trim();
        console.log('jsResult  : ', jsResult);
    }
});

var t = setInterval(function () {
    if (phpResult && jsResult) {
        if (phpResult == jsResult) {
            console.log('结果一致'.green);
        }
        else {
            console.error('结果不一致'.red);
        }
        clearInterval(t);
    }
}, 500);