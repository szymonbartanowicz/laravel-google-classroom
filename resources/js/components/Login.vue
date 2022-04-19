<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full mb-64">
            <div class="text-center">
                <h2 class="text-3xl leading-9 font-extrabold text-gray-900 mb-2">
                    Sign in to your account
                </h2>
                <router-link to="register">
                    <a class="register-a font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150 text-sm leading-5">
                        Don't have an account yet?
                    </a>
                </router-link>
            </div>
            <ValidationObserver v-slot="{ invalid }">
                <form class="mt-8" autocomplete="off">
                    <input type="hidden" value="true"/>
                    <div class="rounded-md shadow-sm">
                        <div class="relative">
                            <validation-provider rules="required|email" v-slot="{ errors }">
                                <input :class="{'border-red-300 z-10 placeholder-red-300 text-red-900': errors.length}" v-model="email" aria-label="Email address" name="email" type="email" required
                                       class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none sm:text-sm sm:leading-5"
                                       placeholder="Email address"/>
                                <div v-if="errors.length" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none z-50">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>

                                <!--                                <span v-if="errors">{{ errors[0] }}</span>-->
                            </validation-provider>
                        </div>
                        <div class="-mt-px relative">
                            <validation-provider rules="required" v-slot="{ errors }">
                                <input :class="{'border-red-300 z-10 placeholder-red-300': errors.length}" v-model="password" aria-label="Password" name="password" type="password" required
                                       class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none sm:text-sm sm:leading-5"
                                       placeholder="Password"/>
                                <div v-if="errors.length" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none z-50">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <!--                                <span>{{ errors[0] }}</span>-->
                            </validation-provider>
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

                        <div class="text-sm leading-5">
                            <a href="#"
                               class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button :class="{ disabled: invalid }" :disabled="invalid" @click.prevent="login" type="submit"
                                class="mb-3 group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                              <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150"
                                         fill="currentColor" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"/>
                                    </svg>
                              </span>
                            Sign in
                        </button>
                    </div>
                </form>
            </ValidationObserver>
            <errors v-if="errors" :errors="errors"></errors>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver, extend} from 'vee-validate';
    import {required, email, max} from 'vee-validate/dist/rules';

    extend('required', {
        ...required,
        message: 'The {_field_} is required'
    });

    extend('email', {
        ...email,
        message: 'The {_field_} must be type of email'
    });

    extend('max', {
        ...max,
        params: ['max'],
        validate(value, {max}) {
            return value.length <= max;
        },
        message: 'Must be less than {max}'
    });

    import axios from 'axios';

    import Errors from './Errors';

    export default {
        components: {
            Errors,
            ValidationProvider,
            ValidationObserver,
        },
        data() {
            return {
                email: '',
                password: '',
                errors: null
            }
        },

        methods: {
            login() {
                axios.get('/sanctum/csrf-cookie')
                    .then(() => {
                        axios.post('/login', {
                            email: this.email,
                            password: this.password
                        })
                            .then(() => {
                                this.$router.push('/')
                            })
                            .catch(error => {
                                this.errors = error.response.data.errors
                            })
                    })
            }
        }
    }
</script>

<style>
    .register-a:hover {
        cursor: pointer;
    }

    .disabled {
        @apply bg-indigo-500 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed
    }
</style>