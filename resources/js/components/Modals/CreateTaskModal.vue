<template>
    <div>
        <div class="w-full h-16 absolute z-50 border-b flex items-center justify-between">
            <button @click="$emit('hide')" type="button"
                    class="p-3 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                <svg class="h-10 w-10" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <span class="inline-flex rounded-md shadow-sm">
                <button @click="createTask" type="button"
                        class="mr-3 inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                    Create
                </button>
            </span>
        </div>

        <div class="pt-16 grid grid-cols-4 w-full lg:h-full absolute z-40 bg-white">
            <div class="mb-24 col-span-4 lg:col-span-3 py-5">

                <form class="px-16 pt-5">
                    <div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input v-model="title" class="form-input block w-full sm:text-sm sm:leading-5"
                                   placeholder="Title"/>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea v-model="instructions" class="form-input block w-full sm:text-sm sm:leading-5"
                                      placeholder="Instructions (optional)"></textarea>
                        </div>
                    </div>

                    <errors class="mt-2" v-if="errors" :errors="mainErrors"></errors>

                </form>

            </div>

            <div class="px-16 lg:p-5 col-span-4 lg:col-span-1 h-auto">
                <form class="w-auto">
                    <div class="relative" v-click-outside="hide">
                        <label
                                class="block text-sm font-medium leading-5 text-gray-700">For:</label>
                        <div @click.prevent="showStudentsDropdown = !showStudentsDropdown"
                             class="mt-1 relative rounded-md shadow-sm">
                            <input readonly :value="pickedStudentsNames"
                                   class="form-input block w-full pr-10 sm:text-sm sm:leading-5"/>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="-mr-1 ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <div v-if="showStudentsDropdown" @click="showStudentsDropdown = true"
                             class="origin-top-right absolute left mt-2 w-56 rounded-md shadow-lg z-40">
                            <div class="pb-3 rounded-md bg-white shadow-xs">

                                <div class="p-3 flex items-start" :class="students ? 'border-b' : ''">
                                    <div class="absolute flex items-center h-5">
                                        <input @click="checkAll" v-model="allStudents" id="all-students"
                                               type="checkbox"
                                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"/>
                                    </div>
                                    <div class="pl-7 text-sm leading-5">
                                        <label class="font-medium text-gray-700">All students</label>
                                    </div>
                                </div>

                                <div v-if="students" v-for="student in students"
                                     class="p-3 pb-0 flex items-start">
                                    <div class="absolute flex items-center h-5">
                                        <input v-model="pickedStudents"
                                               @change="updateCheckall"
                                               :value="student" :id="student.id" type="checkbox"
                                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"/>
                                    </div>
                                    <div class="pl-7 text-sm leading-5">
                                        <label class="font-medium text-gray-700">{{ student.name }}</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label for="points"
                               class="block text-sm font-medium leading-5 text-gray-700">Points</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input v-model="points" id="points"
                                   class="form-input block w-full sm:text-sm sm:leading-5"/>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="deadline"
                               class="block text-sm font-medium leading-5 text-gray-700">Deadline</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="date" required v-model="deadline" id="deadline"
                                   class="form-input block w-full sm:text-sm sm:leading-5"/>
                        </div>
                    </div>

                    <errors class="mt-2" v-if="errors" :errors="sideErrors"></errors>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import moment from 'moment'

    import Errors from '../Errors'

    export default {
        props: ['id'],
        components: {
            Errors
        },
        data() {
            return {
                showStudentsDropdown: false,

                students: [],

                title: null,
                instructions: null,
                pickedStudents: [],
                allStudents: true,
                points: 100,
                deadline: moment().format('YYYY-MM-DD'),

                errors: null
            }
        },
        methods: {
            getStudents() {
                axios.get('/api/lessons/' + this.id + '/people')
                    .then(response => {
                        this.students = response.data.people
                    })
            },
            checkAll() {

                this.allStudents = !this.allStudents;
                this.pickedStudents = [];
                if (this.allStudents) {
                    for (var key in this.students) {
                        this.pickedStudents.push(this.students[key]);
                    }
                }
            },
            updateCheckall() {
                if (this.pickedStudents.length === this.students.length) {
                    this.allStudents = true;
                } else {
                    this.allStudents = false;
                }
            },
            createTask() {
                axios.post('/api/tasks', {
                    lesson_id: this.id,
                    title: this.title,
                    instructions: this.instructions,
                    points: this.points,
                    deadline: this.deadline,
                    pickedStudents: this.pickedStudents,
                    allStudents: this.allStudents
                }).then(response => {
                    this.$emit('refresh', response.data)
                    this.$emit('hide')
                })
                    .catch(error => {
                        this.errors = error.response.data.errors
                    })
            },
            hide() {
                this.showStudentsDropdown = false
            },
        },
        computed: {
            pickedStudentsNames() {
                if (this.allStudents) {
                    this.pickedStudents = this.students
                    return 'All students'
                } else {
                    return this.pickedStudents.map(value => value.name).join(', ')
                }
            },
            mainErrors() {
                if (this.errors.title || this.errors.instructions) {
                    return {
                        title: this.errors.title,
                        instructions: this.errors.instructions,
                    }
                }
            },
            sideErrors() {
                if (this.errors.pickedStudents || this.errors.points || this.errors.deadline) {
                    return {
                        pickedStudents: this.errors.pickedStudents,
                        points: this.errors.points,
                        deadline: this.errors.deadline
                    }
                }
            }
        },
        created() {
            this.getStudents()
        }
    }
</script>
