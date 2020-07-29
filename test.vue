<template>
    <div class="sidebar">
        <h1>TEST {{data}}</h1>
        <test2 :data="6784" />
    </div> 
</template>

<script>

    import test2 from "test2.vue"
    
    export default {
        props: {
            data: Number
        },
        data() {
            return {}
        },
        methods: {

        }
    }
</script>
