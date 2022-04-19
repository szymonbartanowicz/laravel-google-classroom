<template>
    <nav class="fixed w-full flex justify-between p-3 border-b items-center bg-white z-40">

        <div class="flex">

            <button @click="$emit('showSidebar')" class="focus:outline-none">
                <svg fill="#000000" viewBox="0 0 24 24" width="24px" height="24px">
                    <path d="M 0 2 L 0 4 L 24 4 L 24 2 Z M 0 11 L 0 13 L 24 13 L 24 11 Z M 0 20 L 0 22 L 24 22 L 24 20 Z"/>
                </svg>
            </button>

        </div>

        <div class="flex items-center">

            <div class="relative flex">

                <button v-click-outside="hideAddDropdown" @click="showAddDropdown = !showAddDropdown"
                        class="focus:outline-none h-8 w-8">
                    <svg fill="#000000"
                         viewBox="0 0 24 24" width="24px" height="24px">
                        <path fill-rule="evenodd"
                              d="M 11 2 L 11 11 L 2 11 L 2 13 L 11 13 L 11 22 L 13 22 L 13 13 L 22 13 L 22 11 L 13 11 L 13 2 Z"/>
                    </svg>
                </button>

                <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                >

                    <div v-if="showAddDropdown"
                         class="ls-dropdown origin-top-right absolute right-0 top-10 w-56 rounded-md shadow-lg">
                        <div class="rounded-md bg-white shadow-xs">
                            <div class="py-1">
                                <a @click="$emit('openJoinModal')"
                                   class="logout-a block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                    Join
                                </a>
                            </div>

                            <div class="border-t border-gray-100"></div>

                            <div class="py-1">
                                <a @click="$emit('openCreateModal')"
                                   class="logout-a block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                    Create
                                </a>
                            </div>
                        </div>
                    </div>

                </transition>

            </div>

            <div v-click-outside="hideNotificationsDropdown" class="relative flex">

                <button @click="getNotifications" class="mx-2 focus:outline-none flex items-center">
                <span class="inline-block relative">
                        <svg width="22px" height="25px" viewBox="0 0 22 25">
                            <path d="M 11 25 C 12.734375 25 14.140625 23.601562 14.140625 21.875 L 7.859375 21.875 C 7.859375 23.601562 9.265625 25 11 25 Z M 21.578125 17.691406 C 20.628906 16.675781 18.851562 15.152344 18.851562 10.15625 C 18.851562 6.363281 16.179688 3.324219 12.570312 2.582031 L 12.570312 1.5625 C 12.570312 0.699219 11.867188 0 11 0 C 10.132812 0 9.429688 0.699219 9.429688 1.5625 L 9.429688 2.578125 C 5.820312 3.324219 3.148438 6.363281 3.148438 10.15625 C 3.148438 15.152344 1.371094 16.675781 0.421875 17.691406 C 0.128906 18.003906 -0.00390625 18.382812 0 18.75 C 0.00390625 19.550781 0.636719 20.3125 1.578125 20.3125 L 20.421875 20.3125 C 21.363281 20.3125 21.996094 19.550781 22 18.75 C 22.003906 18.382812 21.871094 18.003906 21.578125 17.691406 Z M 21.578125 17.691406 "/>
                        </svg>
                    <span v-if="unreadNotificationsNumber"
                          class="origin-top-right text-xs absolute bottom-3 left-3 block h-4 w-4 rounded-full text-white bg-red-500">{{ unreadNotificationsNumber }}</span>
                </span>
                </button>

                <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                >

                    <div v-if="showNotificationsDropdown"
                         class="origin-top-right absolute top-9 right-0 w-56 rounded-md shadow-lg">
                        <div v-if="notifications" class="ls-dropdown rounded-md bg-white shadow-xs">
                            <div v-for="notification in notifications" class="py-1 border-t border-gray-100">
                                <a class="logout-a block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                    <span v-if="notification.data.type === 'newStudent'"><span class="font-bold">{{ notification.data.studentName }}</span>{{ notification.data.data }}</span>
                                    <span v-else-if="notification.data.type === 'newTask'">{{ notification.data.data }}<span
                                            class="font-bold"> {{ notification.data.lessonName }}</span></span>
                                    <span v-else-if="notification.data.type === 'newMention'"><span
                                            class="font-bold"> {{ notification.data.userName }}</span> {{ notification.data.data }}<span class="font-bold"> {{ notification.data.lessonName }}</span></span>
                                </a>
                            </div>

                        </div>

                        <div v-else class="ls-dropdown rounded-md bg-white shadow-xs">
                            <div class="py-1 border-t border-gray-100">
                                <a class="logout-a block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                    <span class="font-bold">No notifications for now.</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </transition>
            </div>

            <div v-click-outside="hideAvatarDropdown" class="relative">

                <button @click="showAvatarDropdown = !showAvatarDropdown"
                        class="focus:outline-none flex items-center">

                    <span class="inline-block relative">
                      <img class="h-8 w-8 ml-2 rounded-full" src="/images/default.jpg" alt=""/>
                        <span v-if="currentStatus" :class="currentStatus.class"
                              class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full text-white shadow-solid"></span>
                    </span>

                </button>

                <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                >

                    <div v-if="showAvatarDropdown"
                         class="ls-dropdown origin-top-right absolute right-0 top-10 w-56 rounded-md shadow-lg">
                        <div class="rounded-md bg-white shadow-xs">

                            <div class="py-1 relative">

                                <a @click="showStatusDropdown = true"
                                   class="flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">

                                    <span :class="currentStatus.class"
                                          class="block h-2.5 w-2.5 rounded-full mr-2"></span>

                                    {{ currentStatus.name }}

                                    <svg class="h-5 w-5 text-gray-400 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </a>

                            </div>


                            <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                            >

                                <div v-if="showStatusDropdown"
                                     class="origin-top-right top-0 right-56 absolute w-56 rounded-md shadow-lg">
                                    <div class="ls-dropdown rounded-md bg-white shadow-xs">

                                        <div v-for="status in statuses" class="border-t border-gray-100 py-1">
                                            <a @click="updateStatus(status.name)"
                                               class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                            <span :class="status.class"
                                                  class="block h-2.5 w-2.5 rounded-full mr-2"></span>
                                                {{ status.name }}
                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </transition>


                            <div class="border-t border-gray-100"></div>

                            <div class="py-1">
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                    {{ userName }}
                                </a>
                            </div>

                            <div class="border-t border-gray-100"></div>

                            <div class="py-1 relative">
                                <a @click.prevent="logout"
                                   class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                    Logout
                                </a>
                            </div>
                        </div>

                    </div>

                </transition>

            </div>

        </div>
    </nav>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                showAvatarDropdown: false,
                showAddDropdown: false,
                showStatusDropdown: false,
                showNotificationsDropdown: false,

                userName: null,
                currentStatus: null,
                statuses: {},

                unreadNotificationsNumber: null,
                notifications: null,
            }
        },
        methods: {
            logout() {
                axios.post('logout')
                    .then(() => {
                            this.$router.push('/login')
                        }
                    )
            },
            hideAvatarDropdown() {
                this.showAvatarDropdown = false
                this.showStatusDropdown = false
            },
            hideAddDropdown() {
                this.showAddDropdown = false
            },
            hideNotificationsDropdown() {
                this.showNotificationsDropdown = false
            },
            getStatuses() {
                axios.get('api/home/statuses').then(response => {
                    this.currentStatus = response.data.currentStatus
                    this.statuses = response.data.statuses
                })
            },
            getUser() {
                axios.get('api/home/user')
                    .then(response => {
                        this.userName = response.data.name
                    })
            },
            getUnreadNotificationsNumber() {
                axios.get('api/unreadNotifications')
                    .then(response => {
                        this.unreadNotificationsNumber = response.data
                    })
            },
            getNotifications() {
                if (!this.showNotificationsDropdown) {
                    if (!this.notifications) {
                        axios.get('api/notifications')
                            .then(response => {
                                this.notifications = response.data.notifications
                            }).then(() => {
                            if (this.unreadNotificationsNumber > 0) {
                                this.markNotificationsAsRead()
                            }
                        })
                    }
                    this.showNotificationsDropdown = true
                } else {
                    this.hideNotificationsDropdown()
                }
            },
            markNotificationsAsRead() {
                axios.post('api/markNotificationsAsRead')
                    .then(() => {
                        this.getUnreadNotificationsNumber()
                    })
            },
            updateStatus(status) {
                axios.put('api/updateStatus', {
                    name: status
                }).then(() => {
                    this.getStatuses()
                }).then(() => {
                    this.showStatusDropdown = false
                    this.showAvatarDropdown = false
                })
            }
        },
        created() {
            this.getUser()
            this.getStatuses()
            this.getUnreadNotificationsNumber()
        }
    }
</script>

<style>
    .ls-dropdown a:hover {
        cursor: pointer;
    }

    .ls-dropdown div:first-child {
        border: none
    }
</style>