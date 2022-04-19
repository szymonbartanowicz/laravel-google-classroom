<template>
    <div>
        <transition
                enter-active-class="ease-out duration-500"
                enter-class="transform scale-90"
                enter-to-class="transform scale-100"
        >

            <modal
                    v-if="showModal"
                    @hide="showModal = false"
                    :id="id"
                    @refresh="refreshTasks">
            </modal>

        </transition>

        <tabs :id="id"></tabs>
        <div v-if="!showModal" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto py-3">
                <button v-if="isTeacher" @click="showModal = true" type="button"
                        :disabled="people ? false : true" :class="people ? '' : 'opacity-50 cursor-not-allowed'"
                        class="inline-flex items-center pr-6 pl-3 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                    <svg fill="#ffffff"
                         viewBox="0 0 24 24" width="24px" height="24px">
                        <path fill-rule="evenodd"
                              d="M 11 2 L 11 11 L 2 11 L 2 13 L 11 13 L 11 22 L 13 22 L 13 13 L 22 13 L 22 11 L 13 11 L 13 2 Z"/>
                    </svg>
                    <span class="ml-2">Create</span>
                </button>

                <div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">

                    <transition
                            enter-active-class="transform ease-out duration-300 transition"
                            enter-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                            leave-active-class="transition ease-in duration-100"
                            leave-class="opacity-100"
                            leave-to-class="opacity-0">

                        <message
                                v-if="showMessage && isTeacher && !people"
                                :show="showMessage"
                                @hide="showMessage = false">
                        </message>

                    </transition>

                </div>

            </div>

            <div v-if="tasks" class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul>
                    <li v-for="task in tasks" class="border-t border-gray-200">
                        <a href="#"
                           class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                                        {{ task.title }}
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span v-if="closed(task.deadline)"
                                              class="mr-2 bg-red-100 text-red-800 px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            closed
                                        </span>

                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="[task.access === 'public' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800']">
                                          {{ task.access }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span>
                                            Deadline
                                            <time datetime="2020-01-07">{{ closingOn(task.deadline) }}</time>
                                        </span>
                                    </div>
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
    import moment from 'moment'

    import Tabs from './Tabs'
    import Modal from '../Modals/CreateTaskModal'
    import Message from '../Messages/CanNotCreateTaskMessage'

    export default {
        components: {
            Tabs,
            Modal,
            Message
        },
        data() {
            return {
                id: this.$route.params.id,

                tasks: null,

                showModal: false,
                showMessage: true,

                isTeacher: null,

                people: null,
            }
        },
        methods: {
            getTasks() {
                axios.get('/api/lessons/' + this.id + '/tasks')
                    .then(response => {
                        if (response.data === 404) {
                            this.$router.push('/')
                        } else {
                            this.tasks = response.data
                        }
                    })
            },
            closingOn(date) {
                return moment(date).fromNow()
            },
            closed(date) {
                return moment(date).isBefore(moment())
            },
            refreshTasks(data) {
                this.tasks.unshift(data)
            },
            checkIfIsTeacher() {
                axios.get('api/isTeacher', {
                    params: {
                        id: this.id
                    }
                }).then(response => {
                    this.isTeacher = response.data
                })
            },
            getPeople() {
                axios.get('/api/lessons/' + this.id + '/people')
                    .then(response => {
                        if (response.data === 404) {
                            this.$router.push('/')
                        } else {
                            this.people = response.data.people
                        }
                    })
            },
        },
        created() {
            this.getTasks()
            this.checkIfIsTeacher()
            this.getPeople()
        }
    }
</script>