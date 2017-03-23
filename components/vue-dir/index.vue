<template>
    <div>
        <!-- 指令 -->
        <p v-if="seen">haha</p>
        <a v-bind:href="url"></a>
        <form v-on:submit.prevent="onSubmit"></form>
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
            "seen": false,
            "url": "http://www.baidu.com"
        }
    }
</config>