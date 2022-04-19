<template>
    <div :class="background" class="flex flex-col rounded-lg shadow-lg overflow-hidden">
        <div class="flex-shrink-0 h-48 w-full">
        </div>
        <div class="flex-1 bg-white p-6 flex flex-col justify-between">
            <div class="flex-1">
                <a class="block">
                    <router-link :to="{ name: 'LessonHome', params: {id: lesson.id} }">
                        <h3 class="mt-2 text-xl leading-7 font-semibold text-gray-900">
                            {{ lesson.name }}
                        </h3>
                    </router-link>
                    <p class="mt-3 text-base leading-6 text-gray-500">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto accusantium
                        praesentium eius, ut atque fuga culpa, similique sequi cum eos quis dolorum.
                    </p>
                </a>
            </div>
            <div class="mt-6 flex items-center">
                <div class="flex-shrink-0">
                    <a href="#">
                        <img class="h-10 w-10 rounded-full"
                             src="/images/default.jpg"
                             alt=""/>
                    </a>
                </div>
                <div class="ml-3">
                    <p class="text-sm leading-5 font-medium text-gray-900">
                        <a href="#" class="hover:underline">
                            {{ lesson.user.name }}
                        </a>
                    </p>
                    <div class="flex text-sm leading-5 text-gray-500">
                        <span>
                            {{ createdOn(lesson.created_at) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['lesson', 'background'],

        methods: {
            createdOn(date) {
                return moment(date).fromNow()
            },
        }
    }
</script>