<template>
    <div>
        <modal-component name="addRuleModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenAddRuleModal($event)">
            <add-rule-modal :category="addRuleModal.category" :type="addRuleModal.type" />
        </modal-component>
        <modal-component name="editRuleModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenEditRuleModal($event)">
            <edit-rule-modal :rule="editRuleModal.rule" />
        </modal-component>
        <modal-component name="emailTaskModal" width="80%" :max-width="400" :adaptive="true" :scrollable="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenEmailTaskModal($event)">
            <email-task-modal :task="emailTaskModal.task" />
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
        <modal-component name="dialogModal" width="80%" :max-width="400" :adaptive="true" :delay="250" height="auto" class="text-center center" @before-open="beforeOpenDialog($event)">
            <dialog-modal :title="dialog.title" :text="dialog.text" :buttons="dialog.buttons" />
        </modal-component>
    </div>
</template>

<script>
import DialogModal from '@/Modals/DialogModal';
import AddRuleModal from '@/Modals/AddRuleModal';
import EditRuleModal from '@/Modals/EditRuleModal';
import EmailTaskModal from '@/Modals/EmailTaskModal';

export default {
    components: {
        DialogModal,
        AddRuleModal,
        EditRuleModal,
        EmailTaskModal,
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
            emailTaskModal: {
                task: null,
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
            dialog: {
                title: null,
                text: null,
                buttons: [],
            },
        }
    },
    methods: {
        beforeOpenDialog (event) {
            this.dialog.title = event.params.title;
            this.dialog.text = event.params.text;
            this.dialog.buttons = event.params.buttons;
        },
        beforeOpenAddRuleModal (event) {
            this.addRuleModal.category = event.params.category;
            this.addRuleModal.type = event.params.type;
        },
        beforeOpenEditRuleModal (event) {
            this.editRuleModal.rule = event.params.rule;
        },
        beforeOpenEmailTaskModal (event) {
            this.emailTaskModal.task = event.params.task;
        },
    },
}
</script>
