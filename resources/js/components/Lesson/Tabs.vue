<template>
    <div class="fixed-top border-b border-gray-200">
        <nav class="-mb-px flex justify-center">
            <router-link
                    class="whitespace-no-wrap py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300"
                    :to="{ name: 'LessonHome', params: {id: id} }" exact>
                Wall
            </router-link>
            <router-link
                    class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300"
                    :to="{ name: 'LessonTasks', params: {id: id} }">
                Tasks
            </router-link>
            <router-link
                    class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300"
                    :to="{ name: 'LessonPeople', params: {id: id} }">
                People
            </router-link>
        </nav>
    </div>
</template>

<script>
    export default {
        props: ['id']
    }
</script>

<style>
    .active {
        @apply border-indigo-500 text-indigo-600 !important
    }

    .active:focus {
        @apply outline-none text-indigo-800 border-indigo-700 !important
    }
</style>