<template>
    <div>
        <!-- class -->
        <div class="static" v-bind:class="{ active: isActive }"></div>
        <div v-bind:class="classObj"></div>
        <div v-bind:class="[activeClass, errorClass]"></div>
    </div>
</template>

<script>
    export default {};
</script>

<config>
    {
        "data": {
            "isActive": true,
            "classObj": {
                "isActive": true
            },
            "activeClass": "active",
            "errorClass": "text-danger"
        }
    }
</config>