<template>
    <div class="mx-auto h-12 flex justify-center items-center gap-x-2 mt-14 text-[20px] font-bold" v-if="totalPages > 1">
        <button class="btn" @click="$emit('changePage', '-')"><img src="left-arrow.png" class="w-[15px] aspect-square" /></button>
        <button class="btn" v-for="i in formattedPageNumbers" @click="$emit('changePage', i)" :class="{'!bg-blue-500': page == i}">{{ i }}</button>
        <button class="btn" @click="$emit('changePage', '+')"><img src="right-arrow.png" class="w-[15px] aspect-square" /></button>
    </div>
</template>

<script>
    import {watch} from 'vue'
    export default {
        data() {
            return {
                formattedPageNumbers: []
            }
        },
        props: {
            totalPages: {
                type: Number,
                default: 1
            },
            page: {
                type: Number,
                default: 1
            }
        },
        emits: ['changePage'],
        watch: {
            page() {
                this.formatPageNumbers();
            }
        },
        methods: {
            async formatPageNumbers() {
                this.formattedPageNumbers = []
                let pageNumbers = [];

                if (this.totalPages <= 7) {
                    for (let i = 1; i <= this.totalPages; i++) pageNumbers.push(i.toString())
                }
                else {
                    pageNumbers.push('1')
                    if (this.page === 1) {
                        pageNumbers.push('2')
                        pageNumbers.push('3')
                    }
                    // if the distance from page to page 1 is less than 3, then we can display all pages from 1 to page + 1
                    else if (this.page - 1 <= 3) {
                        for (let i = 2; i <= this.page + 1; i++) pageNumbers.push(i.toString())
                    }
                    else{
                        pageNumbers.push('...')
                        if (this.totalPages != this.page)
                            for (let i = this.page - 1; i <= this.page + 1; i++) pageNumbers.push(i.toString())
                    }

                    if (this.totalPages == this.page) {
                        pageNumbers.push((this.totalPages - 2).toString())
                        pageNumbers.push((this.totalPages - 1).toString())
                        pageNumbers.push(this.totalPages.toString())
                    }
                    else if (this.totalPages - (this.page + 1)  <= 2) {
                        for (let i = this.page + 2; i <= this.totalPages; i++) {
                            pageNumbers.push(i.toString())
                        }
                    }

                    else {
                        pageNumbers.push('...')
                        pageNumbers.push(this.totalPages.toString())
                    }
                }
                this.formattedPageNumbers = pageNumbers;
            }
        },
        mounted() {
            this.formatPageNumbers();
        }
    }
</script>

<style scoped>
    .btn {
        @apply h-full aspect-square bg-white hover:bg-blue-200 flex justify-center items-center rounded-lg;
    }
</style>