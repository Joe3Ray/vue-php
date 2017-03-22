<template>
    <div>
        <!-- 插值 -->
        <!-- 普通差值 -->
        <p>{{ a }}</p>
    </div>
</template>

<script>
    export default {
        methods: {
            onSubmit: function () {},
            greet: function () {}
        }
    };
</script>

<config>
    {
        "data": {
            "data": "hello vue",
            "a": "hoho",
            "b": "haha",
            "rawHtml": "<div>aladdin</div>",
            "dynamicId": "myid",
            "someDynamicCondition": false,
            "number": 2,
            "ok": true,
            "seen": false,
            "url": "http://baidu.com",
            "isActive": true,
            "classObj": {
                "isActive": true
            },
            "activeClass": true,
            "errorClass": true,
            "activeColor": "red",
            "fontSize": 30,
            "styleObject": {
                "color": "red",
                "fontSize": "13px"
            },
            "baseStyles": {
                "color": "red",
                "fontSize": "13px"
            },
            "overridingStyles": {
                "color": "green"
            },
            "type": "A",
            "items": [
                {
                    "message": "Foo"
                },
                {
                    "message": "Bar"
                }
            ],
            "checked": false,
            "picked": "",
            "selected": "",
            "toggle": "",
            "msg": ""
        },
        "components": {
            "myA": "components/myA/myA"
        }
    }
</config>