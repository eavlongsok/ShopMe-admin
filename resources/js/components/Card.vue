<template>
    <div class="inline-block border-l-8 h-32 w-72 bg-white text-2xl capitalize pl-5 pt-5 hover-brightness-98" :style="{'border-color': color}">
        <p :style="{'color': color}" class="uppercase text-lg font-semibold">{{title}}</p>
        <p class="mt-2 font-semibold">{{value}}</p>
    </div>
</template>

<script>
    export default {
        name: 'Card',
        props: ['title', 'value', 'color']
    }
</script>

<style scoped>

</style>