<template>
    <div id="viewProducts" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{product.name}}</h4>
        </div>
            <div class="modal-body" v-if="show == true">
                <p>Manufactured: {{product.manufactured_at}}</p>
                <img :src="image_url(product)" class="image">
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" @click="$emit('viewProductModalClosed')">Close</button>
        </div>
        </div>

    </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
            },
            product: {
                type: Object,
                reqiured: true,
                default: () => ({})
            }
        },
        watch: {
            show(show) {
                if(show)$("#viewProducts").modal()
            }
        },
        methods: {
            image_url(product) {
                return `/api/products/${product.id}/image?${Math.ceil(Math.random() * 10000)}`;
            }
        }
    }
</script>

<style scoped>
.image {
    width: 100%;
    height: auto;
}
</style>
