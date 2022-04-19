<template>
    <div class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-50">

        <div @click="$emit('hide')" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
             class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">

                <div class="sm:flex sm:items-start">
                    <div class="my-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-xl leading-6 font-medium text-gray-900">
                            Create lesson
                        </h3>
                    </div>
                </div>

                <form class="w-full mb-3">
                    <div class="flex items-center border-b border-b-2 border-black py-2">
                        <input v-model="name"
                               class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                               type="text" placeholder="Name">
                    </div>
                    <div class="flex items-center border-b border-b-2 border-black py-2">
                        <input v-model="subject"
                               class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                               type="text" placeholder="Subject (optional)">
                    </div>
                    <div class="flex items-center border-b border-b-2 border-black py-2">
                        <input v-model="number"
                               class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                               type="text" placeholder="Number (optional)">
                    </div>
                    <div class="flex items-center border-b border-b-2 border-black py-2">
                        <input v-model="section"
                               class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                               type="text" placeholder="Section (optional)">
                    </div>
                </form>

                <errors v-if="errors" :errors="errors"></errors>

            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button @click="createLesson" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                          Create
                        </button>
                    </span>
                <span class="mt-3 flex w-full rounded-md sm:mt-0 sm:w-auto">
                        <button @click="$emit('hide')" type="button"
                                class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                          Cancel
                        </button>
                    </span>
            </div>

        </div>

    </div>
</template>

<script>
    import axios from 'axios';

    import Errors from '../Errors';

    export default {
        components: {
            Errors
        },
        data() {
            return {
                name: null,
                subject: null,
                number: null,
                section: null,

                errors: null,
            }
        },
        methods: {
            createLesson() {
                axios.post('/api/lessons', {
                    name: this.name,
                    subject: this.subject,
                    number: this.number,
                    section: this.section
                })
                    .then(response => {
                        this.$emit('refresh', response.data)
                    })
                    .then(() => {
                        this.$emit('hide')
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                    })

            }
        }
    }
</script>
