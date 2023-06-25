<template>
    <input v-if="userType == 1" v-model="queryForBuyer" type="text" name="query" class="h-9 w-72 focus:outline-none rounded focus:bg-gray-100 placeholder-gray-900 search" placeholder="Search" @keypress="$event => {
        if ($event.key == 'Enter') {
            $refs.searchbtn.click();
        }}
    "/>
    <input v-if="userType == 2" v-model="queryForSeller" type="text" name="query" class="h-9 w-72 focus:outline-none rounded focus:bg-gray-100 placeholder-gray-900 search" placeholder="Search" @keypress="$event => {
        if ($event.key == 'Enter') {
            $refs.searchbtn.click();
        }}
    "/>
    <input v-if="userType == 0" v-model="queryInGeneral" type="text" name="query" class="h-9 w-72 focus:outline-none rounded focus:bg-gray-100 placeholder-gray-900 search" placeholder="Search" @keypress="$event => {
        if ($event.key == 'Enter') {
            $refs.searchbtn.click();
        }}
    "/>
    <button ref="searchbtn" class="bg-green-500 ml-5 w-20 h-10 text-md tracking-wide rounded-2xl text-gray-800 hover:bg-green-600 hover:text-gray-900 font-semibold" @click="search">Search</button>
</template>

<script>
    export default {
        data() {
            return {
                queryForBuyer: '',
                queryForSeller: '',
                queryInGeneral: ''
            }
        },
        props: ['userType'],
        name: 'SearchBox',
        emits: ['search'],
        methods: {
            search() {
                if (this.userType == 1) {
                    this.$emit('search', this.queryForBuyer)
                }
                else if (this.userType == 2) {
                    this.$emit('search', this.queryForSeller)
                }
                else if (this.userType == 0) {
                    this.$emit('search', this.queryInGeneral)
                }
            }
        }
    }
</script>

<style scoped>
    .search {
        background-image: url('/searchicon.svg');
        background-repeat: no-repeat;
        background-size: 25px 25px;
        background-position: 2% 50%;
        padding-left: 35px;
    }
</style>