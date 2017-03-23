<template>
    <div>
        <!-- 过渡效果 -->
        <transition>
            <table v-if="items.length > 0">
                <tr>
                    <td></td>
                </tr>
            </table>
            <p v-else>Sorry, no items found.</p>
        </transition>
        <transition name="component-fade" mode="out-in">
            <component v-bind:is="'vue-com'"></component>
        </transition>
        <div>
            <transition-group name="list" tag="p">
                <span v-for="item in items" :key="item.message">{{item.message}}</span>
            </transiton-group>
        </div>
        <div>
            <transition-group name="list-a">
                <span v-for="item in items" :key="item.message + 'aa'">{{item.message}}</span>
            </transiton-group>
        </div>
    </div>
</template>

<script>
    export default {};
</script>

<config>
    {
        "data": {
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