var fs = require('fs');
var path = require('path');
var child = require('child_process');
var colors = require('colors');

var componentsPath = path.resolve(__dirname, 'components');
var components = fs.readdirSync(componentsPath);

components.forEach(function (e, i) {
    child.exec('node index.js ' + e, (err, stdout, stderr) => {
        if (err || stderr) {
            console.log((err || stderr).red);
        }
        else {
            console.log(('组件 ' + e + ' 编译成功').green);
        }
    });
});