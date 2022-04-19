<template>
    <div>
        <tabs :id="id"></tabs>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 text-white">

                <div :class="lesson.background" class="max-w-3xl mx-auto rounded-lg h-48 p-6">

                    <div class="flex justify-between items-center">

                        <h1 class="text-3xl font-bold mb-2">{{ lesson.name }}</h1>

                        <router-link class="flex"
                                     :to="{ name: 'LessonPeople', params: {id: id} }">
                            <svg fill="#fff" viewBox="0 0 24 24" width="24" height="24">
                                <path d="M9 12A5 5 0 1 1 9 2a5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2zm1-5a1 1 0 0 1 0-2 5 5 0 0 1 5 5v2a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3zm-2-4a1 1 0 0 1 0-2 3 3 0 0 0 0-6 1 1 0 0 1 0-2 5 5 0 0 1 0 10z"/>
                            </svg>
                            <p class="ml-1">{{ numberOfStudents }}</p>
                        </router-link>

                    </div>

                    <h3 class="text-sm font-medium"><span class="font-light">Lesson key: </span>{{ lesson.key }}</h3>

                </div>
            </div>

            <div>
                <div class="relative rounded-md shadow-sm">
                    <input id="body"
                           v-model="body"
                           @keyup.down="next"
                           @keyup.up="previous"
                           @keyup="processMentions"
                           @keydown.up.prevent=""
                           @keydown.enter="addMention(namesToDisplay[currentItem].computedName)"
                           @keyup.left="trim"
                           @keyup.right="trim"
                           @keyup.delete="currentItem = 0"
                           autocomplete="off"
                           class="form-input block w-full pr-10 sm:text-sm sm:leading-5 outline-none"
                           placeholder="Write something to your class...">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button @click="createPost" class="focus:outline-none">
                            <svg :class="body ? 'text-indigo-600' : ''"
                                 class="h-5 w-5 text-gray-400 hover:text-indigo-600" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path d="M2 3v18l20-9L2 3zm2 11l9-2-9-2V6.09L17.13 12 4 17.91V14z"></path>
                            </svg>
                        </button>
                    </div>


                    <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                    >
                        <div v-if="namesToDisplay.length"
                             class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg">
                            <div class="rounded-md bg-white shadow-xs">
                                <div class="py-1">
                                    <a v-for="(item, key, index) in namesToDisplay" :key="key"
                                       @click="addMention(item.computedName)"
                                       :class="key === currentItem ? 'bg-gray-100' : ''"
                                       class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 cursor-pointer">{{
                                        item.computedName }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </transition>

                </div>

            </div>

            <div v-if="posts" class="bg-white shadow overflow-hidden sm:rounded-md mt-4">
                <ul>
                    <li v-for="post in posts">
                        <a href="#"
                           class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex items-center px-4 py-2 sm:px-6">
                                <div class="min-w-0 flex-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" src="/images/default.jpg" alt=""/>
                                    </div>
                                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                        <div>
                                            <div class="text-sm leading-5 font-medium text-indigo-600 truncate">{{
                                                post.user.name }}
                                            </div>
                                            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                                <span class="truncate">{{ createdOn(post.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-html="styleMention(post.body)" class="px-6 py-2"></div>
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

    export default {
        components: {
            Tabs
        },
        data() {
            return {
                id: this.$route.params.id,

                lesson: {},
                numberOfStudents: null,

                people: null,
                posts: null,

                body: '',
                namesToDisplay: [],
                currentItem: 0,
            }
        },

        methods: {
            processMentions() {
                if (this.body.length) {
                    this.namesToDisplay = []
                    let bodyWords = this.body.split(' ')
                    let lastItem = bodyWords[bodyWords.length - 1]
                    if (lastItem.charAt(0) === '@') {
                        for (let i = 0; i < this.computedPeople.length; i++) {
                            if (this.computedPeople[i]['computedName'].indexOf(lastItem.substring(1)) > -1) {
                                this.namesToDisplay.unshift(this.computedPeople[i])
                            }
                        }
                    }
                    if (this.currentItem > this.namesToDisplay.length - 1) {
                        this.currentItem = 0
                    }
                } else {
                    this.namesToDisplay = []
                }
            },
            trim() {
                this.body = this.body.trim()
            },
            next() {
                if (this.namesToDisplay.length) {
                    if (this.currentItem === this.namesToDisplay.length - 1) {
                        this.currentItem = 0
                    } else {
                        this.currentItem++
                    }
                    this.namesToDisplay = []
                }
            },
            previous() {
                if (this.namesToDisplay.length) {
                    if (this.currentItem === 0) {
                        this.currentItem = this.namesToDisplay.length - 1
                    } else {
                        this.currentItem--
                    }
                    this.namesToDisplay = []
                }
            },
            addMention(computedName) {
                if (this.namesToDisplay.length) {
                    let bodyWords = this.body.split(' ')
                    let end = bodyWords[bodyWords.length - 1].substring(1).length
                    this.body = this.body.substring(0, this.body.length - end)
                    this.body += computedName + ' '
                    this.namesToDisplay = []
                    this.currentItem = 0
                }
            },
            styleMention(value) {
                let words = value.split(' ')
                let wordsToDisplay = '<span>'
                let names = this.computedPeople.map(person => (
                    person.computedName
                ))
                for (let i = 0; i < words.length; i++) {
                    if (words[i].charAt(0) === '@' && names.includes(words[i].substring(1))) {
                        wordsToDisplay += '<span class="mention">' + words[i] + '</span> '
                    } else {
                        wordsToDisplay += '<span>' + words[i] + '</span> '
                    }
                }
                return wordsToDisplay
            },
            async getLesson() {
                // axios.get('/api/lessons/' + this.id)
                //     .then(response => {
                //         if (response.data === 404) {
                //             this.$router.push('/')
                //         } else {
                //             this.lesson = response.data.lesson
                //             this.numberOfStudents = response.data.numberOfStudents
                //             this.people = response.data.people
                //         }
                //     })
                let response = await axios.get('/api/lessons/' + this.id)
                if (response.data === 404) {
                    this.$router.push('/')
                } else {
                    this.lesson = response.data.lesson
                    this.numberOfStudents = response.data.numberOfStudents
                    this.people = response.data.people
                }
            },
            async getPosts() {
                // axios.get('/api/lessons/' + this.id + '/posts')
                //     .then(response => {
                //         this.posts = response.data
                //     })
                let response = await axios.get('/api/lessons/' + this.id + '/posts')
                this.posts = response.data
            },
            createPost() {
                axios.post('/api/posts', {
                    lesson_id: this.id,
                    body: this.body,
                    computedUsers: this.computedPeople
                }).then(response => {
                    this.posts.unshift(response.data);
                    this.body = null
                    this.namesToDisplay = []
                })
                    .catch(error => {
                        this.errors = error.response.data.errors
                    })
            },
            createdOn(date) {
                return moment(date).fromNow()
            }
        }
        ,
        computed: {
            computedPeople() {
                return this.people.map(person => ({
                        computedName: person.name.replace(/\s+/g, '_'),
                        id: person.id,
                        name: person.name
                    }
                ))
            }
        }
        ,
        created() {
            this.getLesson()
            this.getPosts()
        }
        ,
    }
</script>

<style>
    .mention {
        @apply inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-purple-100 text-purple-800
    }
</style>