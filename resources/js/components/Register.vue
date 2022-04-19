<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full mb-64">
            <div class="text-center">
                <h2 class="text-3xl leading-9 font-extrabold text-gray-900 mb-2">
                    Register
                </h2>
                <router-link to="login">
                    <a class="register-a font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150 text-sm leading-5">
                        Sign in to your account!
                    </a>
                </router-link>
            </div>
            <form class="mt-8">
                <input type="hidden" value="true"/>
                <div class="rounded-md shadow-sm">
                     <div>
                        <input v-model="name" type="text" required
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                               placeholder="Name"/>
                    </div>
                    <div>
                        <input v-model="email" type="email" required
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                               placeholder="Email address"/>
                    </div>

                    <div>
                        <input v-model="password" type="password" required
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                               placeholder="Password"/>
                    </div>
                    <div>
                        <input v-model="password_confirmation" type="password" required
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                               placeholder="Confirm password"/>
                    </div>


                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox"
                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"/>
                        <label for="remember_me" class="ml-2 block text-sm leading-5 text-gray-900">
                            Remember me
                        </label>
                    </div>

                </div>

                <div class="mt-6">
                    <button @click.prevent="register"
                            class="mb-3 group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                              <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150"
                                         fill="currentColor" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"/>
                                    </svg>
                              </span>
                        Register
                    </button>
                </div>
            </form>
            <errors v-if="errors" :errors="errors"></errors>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    import Errors from './Errors';

    export default {
        components: {
            Errors
        },
        data() {
            return {
                name: 'test1',
                email: 'test1@gmail.com',
                password: 'qwertyuiop',
                password_confirmation: 'qwertyuiop',
                errors: [],
            }
        },
        methods: {
            register() {
                axios.get('/sanctum/csrf-cookie')
                    .then(() => {
                        axios.post('/register', {
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        })
                            .then(() => {
                                    this.$router.push('/')
                                }
                            )
                            .catch(error => {
                                this.errors = error.response.data.errors
                            })
                    })
            }
        }
    }
</script>
