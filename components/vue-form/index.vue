<template>
    <div>
        <!-- 表单 -->
        <input v-model="a" placeholder="edit me">
        <textarea v-model="a" placeholder="add multiple lines"></textarea>
        <input type="checkbox" id="checkbox" v-model="checked">
        <label for="checkbox">{{ checked }}</label>
        <div id="example-4" class="demo">
            <input type="radio" id="one" value="One" v-model="picked">
            <label for="one">One</label>
            <br>
            <input type="radio" id="two" value="Two" v-model="picked">
            <label for="two">Two</label>
            <br>
            <span>Picked: {{ picked }}</span>
        </div>
        <div id="example-5" class="demo">
            <select v-model="selected">
                <option>A</option>
                <option>B</option>
                <option>C</option>
            </select>
            <span>Selected: {{ selected }}</span>
        </div>
        <div id="example-6" class="demo">
            <select v-model="selected" multiple style="width: 50px">
                <option>A</option>
                <option>B</option>
                <option>C</option>
            </select>
            <br>
            <span>Selected: {{ selected }}</span>
        </div>
        <input type="checkbox" v-model="toggle" v-bind:true-value="a" v-bind:false-value="b">
        <select v-model="selected">
            <option v-bind:value="{ number: 123 }">123</option>
        </select>
        <input v-model.lazy="msg" >
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
            "a": "hoho",
            "b": "haha",
            "checked": false,
            "picked": "",
            "selected": "",
            "toggle": "",
            "msg": ""
        }
    }
</config>