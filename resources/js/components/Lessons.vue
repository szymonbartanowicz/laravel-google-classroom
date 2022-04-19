<template>
    <div class="min-h-screen relative pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div v-if="lessons.createdLessons" class="relative max-w-7xl mx-auto mb-16">

            <div>
                <h2 class="text-3xl leading-9 tracking-tight font-extrabold text-gray-900 sm:text-4xl sm:leading-10">
                    Lessons you created
                </h2>
            </div>

            <div class="mt-12 grid gap-5 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">


                <lesson v-for="lesson in lessons.createdLessons" :lesson="lesson"
                        :key="lesson.id" :background="lesson.background"></lesson>

            </div>
        </div>

        <div v-if="lessons.joinedLessons" class="relative max-w-7xl mx-auto">

            <div>
                <h2 class="text-3xl leading-9 tracking-tight font-extrabold text-gray-900 sm:text-4xl sm:leading-10">
                    Lessons you joined
                </h2>
            </div>

            <div class="mt-12 grid gap-5 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">


                <lesson v-for="lesson in lessons.joinedLessons" :lesson="lesson"
                        :key="lesson.id" :background="lesson.background"></lesson>

            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Lesson from './Lesson';

    export default {
        components: {
            Lesson
        },

        props: ['refreshCreatedLessons', 'refreshJoinedLessons', 'lesson'],

        data() {
            return {
                lessons: {},
            }
        },

        methods: {
            getLessons() {
                axios.get('/api/lessons')
                    .then(response => {
                        this.lessons = response.data
                    })
            },
        },

        created() {
            this.getLessons()
        },

        watch: {
            refreshCreatedLessons: function () {
                if (this.refreshCreatedLessons === true) {
                    if (this.lessons.createdLessons === null) {
                        this.lessons.createdLessons = []
                    }
                    this.lessons.createdLessons.unshift(this.lesson)
                }
                this.$emit('refreshCreatedLessonsToFalse')
            },
            refreshJoinedLessons: function () {
                if (this.refreshJoinedLessons === true) {
                    if (this.lessons.joinedLessons === null) {
                        this.lessons.joinedLessons = []
                    }
                    this.lessons.joinedLessons.unshift(this.lesson)
                }
                this.$emit('refreshJoinedLessonsToFalse')
            }
        }

    }
</script>
