<template>
    <div>
        <modal-component name="addRuleModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenAddRuleModal($event)">
            <add-rule-modal :category="addRuleModal.category" :type="addRuleModal.type" />
        </modal-component>
        <modal-component name="editRuleModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenEditRuleModal($event)">
            <edit-rule-modal :rule="editRuleModal.rule" />
        </modal-component>
        <modal-component name="deleteCategoryDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="deleteCategoryDialog.title" :text="deleteCategoryDialog.text" :buttons="deleteCategoryDialog.buttons" />
        </modal-component>
        <modal-component name="restoreCategoryDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="restoreCategoryDialog.title" :text="restoreCategoryDialog.text" :buttons="restoreCategoryDialog.buttons" />
        </modal-component>
        <modal-component name="deleteEmailDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="deleteEmailDialog.title" :text="deleteEmailDialog.text" :buttons="deleteEmailDialog.buttons" />
        </modal-component>
        <modal-component name="restoreEmailDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="restoreEmailDialog.title" :text="restoreEmailDialog.text" :buttons="restoreEmailDialog.buttons" />
        </modal-component>
        <modal-component name="deleteAllEmailDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="deleteAllEmailDialog.title" :text="deleteAllEmailDialog.text" :buttons="deleteAllEmailDialog.buttons" />
        </modal-component>
        <modal-component name="deleteTaskDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="deleteTaskDialog.title" :text="deleteTaskDialog.text" :buttons="deleteTaskDialog.buttons" />
        </modal-component>
        <modal-component name="restoreTaskDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="restoreTaskDialog.title" :text="restoreTaskDialog.text" :buttons="restoreTaskDialog.buttons" />
        </modal-component>
        <modal-component name="deleteUserDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="deleteUserDialog.title" :text="deleteUserDialog.text" :buttons="deleteUserDialog.buttons" />
        </modal-component>
        <modal-component name="restoreUserDialog" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="restoreUserDialog.title" :text="restoreUserDialog.text" :buttons="restoreUserDialog.buttons" />
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
            deleteEmailDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            restoreEmailDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            deleteAllEmailDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            deleteTaskDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            restoreTaskDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            deleteUserDialog: {
                title: null,
                text: null,
                buttons: [],
            },
            restoreUserDialog: {
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
        beforeOpenDialog (event) {
            this[event.name].title = event.params.title;
            this[event.name].text = event.params.text;
            this[event.name].buttons = event.params.buttons;
        },
    },
}
</script>
