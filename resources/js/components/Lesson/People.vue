<template>
    <div>
        <tabs :id="id"></tabs>

        <div v-if="people.creator" class="max-w-3xl mx-auto py-12 px-12">

            <div class="my-2 bg-white shadow overflow-hidden sm:rounded-md">
                <ul>
                    <li>
                        <a href="#"
                           class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex items-center px-4 py-4 sm:px-6">
                                <div class="min-w-0 flex-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full ml-2"
                                             src="/images/default.jpg"
                                        />
                                    </div>
                                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                        <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                                            {{ people.creator[0]  .name }}
                                        </div>
                                    </div>
                                </div>
                                <span class="mr-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium leading-4 bg-yellow-100 text-yellow-800">
                                    <svg class="h-4 w-4 mr-2" viewBox="0 0 640 512"><path fill="currentColor" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"></path></svg>
                                    creator
                                </span>
                                <div>
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul>
                    <li v-for="user in people.people">
                        <a href="#"
                           class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex items-center px-4 py-4 sm:px-6">
                                <div class="min-w-0 flex-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full ml-2"
                                             src="/images/default.jpg"
                                        />
                                    </div>
                                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                        <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                                            {{ user.name }}
                                        </div>
                                    </div>
                                </div>
                                <span :class="user.pivot.role === 'teacher' ?  'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'" class="mr-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4">
                                        {{ user.pivot.role }}
                                </span>
                                <div>
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</template>

<script>
    import axios from 'axios'

    import Tabs from './Tabs'


    export default {
        components: {
            Tabs
        },
        data() {
            return {
                id: this.$route.params.id,

                people: {}
            }
        },
        methods: {
            getPeople() {
                axios.get('/api/lessons/' + this.id + '/people')
                    .then(response => {
                        if (response.data === 404) {
                            this.$router.push('/')
                        } else {
                            this.people = response.data
                        }
                    })
            }
        },
        created() {
            this.getPeople();
        }

    }
</script>

<style>
    li:first-child {
        border: none;
    }

    li {
        @apply border-t border-gray-200
    }
</style>