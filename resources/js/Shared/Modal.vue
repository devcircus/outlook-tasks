<template>
    <div>
        <modal-component name="deleteCategoryDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDeleteCategoryDialog($event)">
            <dialog-modal :title="deleteCategoryDialog.title" :text="deleteCategoryDialog.text" :buttons="deleteCategoryDialog.buttons" />
        </modal-component>
    </div>
</template>

<script>
import DialogModal from '@/Modals/DialogModal';

export default {
    components: {
        DialogModal,
    },
    data () {
        return {
            deleteCategoryDialog: {
                title: null,
                text: null,
                buttons: [],
            },
        }
    },
    methods: {
        beforeOpenDeleteCategoryDialog (event) {
            this.deleteCategoryDialog.title = event.params.title;
            this.deleteCategoryDialog.text = event.params.text;
            this.deleteCategoryDialog.buttons = event.params.buttons;
        },
    },
}
</script>
