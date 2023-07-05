<template>
    <div class="inline-block border-l-8 h-32 w-64 bg-white text-2xl capitalize pl-5 pt-5 hover-brightness-97 select-text rounded-lg shadow-xl" :style="{'border-color': color}">
        <p :style="{'color': color}" class="uppercase text-lg font-semibold tracking-tight leading-tight">{{title}}</p>
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
    .hover-brightness-97:hover {
        filter: brightness(0.97)
    }
</style>