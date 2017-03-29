<template>
    <div class="vue-container">
        <!-- 普通差值 -->
        <p>{{ a }}</p>
        <!-- 单次渲染 -->
        <p v-once>{{ a }}</p>
        <!-- 纯html -->
        <div v-html="rawHtml"></div>
        <!-- 属性 -->
        <div v-bind:id="dynamicId"></div>
        <button v-bind:disabled="someDynamicCondition">Button</button>
        <!-- 表达式 -->
        <p>{{ number + 1 }}</p>
        <p v-bind:id="'my' + dynamicId">{{ ok ? 'YES' : 'NO' }}</p>
    </div>
</template>

<script>
    export default {};
</script>

<style>
    .vue-container {color: red;}
</style>

<config>
    {
        "data": {
            "a": "hoho",
            "rawHtml": "<div>vue</div>",
            "dynamicId": "myid",
            "someDynamicCondition": false,
            "number": 2,
            "ok": true
        }
    }
</config>