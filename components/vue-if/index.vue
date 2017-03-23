<template>
    <div>
        <!-- 条件判断 -->
        <h1 v-if="ok">Yes</h1>
        <div v-if="type === 'A'">
        A
        </div>
        <div v-else-if="type === 'B'">
        B
        </div>
        <div v-else-if="type === 'C'">
        C
        </div>
        <div v-else>
        Not A/B/C
        </div>
        <template v-if="ok">
            <h1>Title</h1>
            <p>Paragraph 1</p>
            <p>Paragraph 2</p>
        </template>
        <template v-if="type === 'username'">
            <label>Username</label>
            <input placeholder="Enter your username" key="username-input">
        </template>
        <template v-else>
            <label>Email</label>
            <input placeholder="Enter your email address" key="email-input">
        </template>
        <h1 v-show="ok">Hello!</h1>
    </div>
</template>

<script>
    export default {};
</script>

<config>
    {
        "data": {
            "ok": true,
            "type": "B"
        }
    }
</config>