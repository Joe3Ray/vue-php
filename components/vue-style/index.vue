<template>
    <div>
        <!-- style -->
        <div v-bind:style="{ color: activeColor, fontSize: fontSize + 'px' }"></div>
        <div v-bind:style="styleObject"></div>
        <div v-bind:style="[baseStyles, overridingStyles]"></div>
        <div v-bind:style="{ transform: 'translateX(10px)'}"></div>
    </div>
</template>

<script>
    export default {};
</script>

<config>
    {
        "data": {
            "activeColor": "red",
            "fontSize": 30,
            "styleObject": {
                "color": "red",
                "fontSize": "13px"
            },
            "baseStyles": {
                "color": "red",
                "fontSize": "13px"
            },
            "overridingStyles": {
                "color": "green"
            }
        }
    }
</config>