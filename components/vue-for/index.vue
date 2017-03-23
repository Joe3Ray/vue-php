<template>
    <div>
        <!-- 循环 -->
        <ul id="example-1">
            <li v-for="item in items">
                {{ item.message }}
            </li>
        </ul>
        <ul>
            <template v-for="item in items">
                <li>{{ item.message }}</li>
                <li class="divider"></li>
            </template>
        </ul>
        <ul id="repeat-object" class="demo">
            <li v-for="value in styleObject">
                {{ value }}
            </li>
        </ul>
        <div>
            <span v-for="n in 10">{{ n }}</span>
        </div>
        <vue-com v-for="item in items" :tplData="item.message" :key="item.message"></vue-com>
        <div v-for="item in items" :key="item.message"></div>
        <div v-for="item in items" v-if="item.message === 'Foo'">{{ item.message }}</div>
    </div>
</template>

<script>
    export default {};
</script>

<config>
    {
        "data": {
            "styleObject": {
                "color": "red",
                "fontSize": "13px"
            },
            "items": [
                {
                    "message": "Foo"
                },
                {
                    "message": "Bar"
                }
            ]
        },
        "components": {
            "vue-com": "components/vue-com"
        }
    }
</config>