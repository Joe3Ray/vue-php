<template>
    <div>
        <!-- 事件 -->
        <button v-on:click="counter += 1">增加 1</button>
        <button v-on:click="greet">Greet</button>
        <button v-on:click="greet('hi')">Say hi</button>
        <a v-on:click.stop="greet"></a>
        <input v-on:keyup.13="greet">
        <div @click.ctrl="greet">Do something</div>
    </div>
</template>

<script>
    export default {
        methods: {
            greet: function () {}
        }
    };
</script>

<config>
    {
        "data": {
            "counter": 0
        }
    }
</config>