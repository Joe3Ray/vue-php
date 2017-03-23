<template>
    <div>
        <!-- 组件 -->
        <vue-com :tplData="data" ref="profile">
            <p>content from parent</p>
            <p slot="header">header from parent</p>
            <template slot="item" scope="props">
                <span>{{ props.text }}</span>
            </template>
        </vue-com>
        <component v-bind:is="'vue-com'" :tplData="number"></component>
        <keep-alive>
            <component v-bind:is="'vue-com'" :tplData="number"></component>
        </keep-alive>
    </div>
</template>

<script>
    export default {};
</script>

<config>
    {
        "data": {
            "data": "hello vue",
            "number": 2
        },
        "components": {
            "vue-com": "../vue-com"
        }
    }
</config>