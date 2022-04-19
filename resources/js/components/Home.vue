<template>
    <div>
        <top-nav
                @openCreateModal="showCreateModal = true"
                @openJoinModal="showJoinModal = true"
                @showSidebar="showSidebar = true">
        </top-nav>

        <sidebar
                v-if="showSidebar"
                :show="showSidebar"
                @hide="showSidebar = false">
        </sidebar>

        <transition
                enter-active-class="ease-out duration-200"
                enter-class="opacity-0 transform translate-y-4 scale-150"
                enter-to-class="opacity-100 transform translate-y-0 scale-100"
                leave-active-class="ease-in duration-200"
                leave-class="opacity-100 transform scale-100"
                leave-to-class="opacity-0 transform scale-150"
        >

            <create-lesson-modal
                    v-if="showCreateModal"
                    :show="showCreateModal"
                    @hide="showCreateModal = false"
                    @refresh="setCreatedLesson">
            </create-lesson-modal>


            <join-lesson-modal
                    v-if="showJoinModal"
                    @hide="showJoinModal = false"
                    @refresh="setJoinedLesson">
            </join-lesson-modal>

        </transition>

        <lessons
                :refreshJoinedLessons="refreshJoinedLessons"
                :refreshCreatedLessons="refreshCreatedLessons"
                @refreshCreatedLessonsToFalse="refreshCreatedLessons = false"
                @refreshJoinedLessonsToFalse="refreshJoinedLessons = false"
                :lesson="lesson">
        </lessons>

        <alert
                v-if="showAlert"
                @hide="showAlert = false"
                :message="message">
        </alert>
    </div>
</template>

<script>
    import TopNav from './TopNav';
    import CreateLessonModal from './Modals/CreateLessonModal';
    import JoinLessonModal from './Modals/JoinLessonModal';
    import Lessons from './Lessons.vue';
    import Alert from './Alert';
    import Sidebar from './Sidebar';

    export default {
        components: {
            TopNav,
            CreateLessonModal,
            JoinLessonModal,
            Lessons,
            Alert,
            Sidebar,
        },

        data() {
            return {
                showCreateModal: false,
                showJoinModal: false,

                showSidebar: false,

                refreshCreatedLessons: false,
                refreshJoinedLessons: false,

                lesson: null,

                message: null,
                showAlert: false,
            }
        },
        methods: {
            setCreatedLesson(data) {
                this.lesson = data
                this.refreshCreatedLessons = true
                this.showAlert = true
                this.message = 'Successfully created lesson'
            },
            setJoinedLesson(data) {
                this.message = 'Successfully joined to the lesson'
                this.lesson = data
                this.showAlert = true
                this.refreshJoinedLessons = true
            }
        }
    }
</script>