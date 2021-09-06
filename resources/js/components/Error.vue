<template>
    <div class="alert alert-danger">
        <p><strong>{{data.message}}</strong></p>
        <span v-for="errorType, typeIndex in data.errors" v-bind:key="typeIndex">
            <p v-for="error, index in errorType" v-bind:key="index">{{error}}</p>
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type: Object,
                reqiured: true,
                default: () => ({})
            }
        },
    }
</script>
