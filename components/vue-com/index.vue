<template>
    <div class="container">
        <div><p>abc</p></div>
        <p class="cpan" style="color: blue;" id="yo">{{a}}</p>
        <p v-if="b">haha</p>
        <slot>my default slot</slot>
        <slot name="header">my header slot</slot>
        <slot name="item" v-for="item in items" :text="item">my scoped slot</slot>
        <p>{{ tplData }}</p>
    </div>
</template>

<style>
    .container {color: red;}
    .container div {color: green;}
</style>

<script>
    export default {
        props: ['tplData']
    };
</script>

<config>
    {
        "data": {
            "a": 123,
            "b": false,
            "items": [
                123,
                456
            ]
        }
    }
</config>