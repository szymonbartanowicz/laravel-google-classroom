<template>
    <div v-if="errors">
        <span v-for="error in computedErrors" class="block text-red-600 text-sm font-light">{{ error }}</span>
    </div>
</template>

<script>
    export default {
        props: ['errors'],
        computed: {
            computedErrors() {
                let errors = Object.values(this.errors)
                errors = errors.flat()
                return errors
            }
        }
    }
</script>