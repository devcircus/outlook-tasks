<template>
    <div>
        <modal-component name="addRuleModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenAddRuleModal($event)">
            <add-rule-modal :category="addRuleModal.category" :type="addRuleModal.type" />
        </modal-component>
        <modal-component name="editRuleModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenEditRuleModal($event)">
            <edit-rule-modal :rule="editRuleModal.rule" />
        </modal-component>
        <modal-component name="deleteCategoryDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDeleteCategoryDialog($event)">
            <dialog-modal :title="deleteCategoryDialog.title" :text="deleteCategoryDialog.text" :buttons="deleteCategoryDialog.buttons" />
        </modal-component>
        <modal-component name="restoreCategoryDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenRestoreCategoryDialog($event)">
            <dialog-modal :title="restoreCategoryDialog.title" :text="restoreCategoryDialog.text" :buttons="restoreCategoryDialog.buttons" />
        </modal-component>
    </div>
</template>

<script>
import DialogModal from '@/Modals/DialogModal';
import AddRuleModal from '@/Modals/AddRuleModal';
import EditRuleModal from '@/Modals/EditRuleModal';

export default {
    components: {
        DialogModal,
        AddRuleModal,
        EditRuleModal,
    },
    data () {
        return {
            addRuleModal: {
                category: null,
                type: null,
            },
            editRuleModal: {
                rule: null,
            },
            deleteCategoryDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            restoreCategoryDialog: {
                title: null,
                text: null,
                buttons: [],
            },
        }
    },
    methods: {
        beforeOpenAddRuleModal (event) {
            this.addRuleModal.category = event.params.category;
            this.addRuleModal.type = event.params.type;
        },
        beforeOpenEditRuleModal (event) {
            this.editRuleModal.rule = event.params.rule;
        },
        beforeOpenDeleteCategoryDialog (event) {
            this.deleteCategoryDialog.title = event.params.title;
            this.deleteCategoryDialog.text = event.params.text;
            this.deleteCategoryDialog.buttons = event.params.buttons;
        },
        beforeOpenRestoreCategoryDialog (event) {
            this.restoreCategoryDialog.title = event.params.title;
            this.restoreCategoryDialog.text = event.params.text;
            this.restoreCategoryDialog.buttons = event.params.buttons;
        },
    },
}
</script>
