<template>
    <div>
        <!-- v-bind / v-on 缩写 -->
        <a :href="url"></a>
        <a @click="onSubmit"></a>
    </div>
</template>

<script>
    export default {
        methods: {
            onSubmit: function () {}
        }
    };
</script>

<config>
    {
        "data": {
            "url": "http://www.baidu.com"
        }
    }
</config>