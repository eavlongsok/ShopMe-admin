<template>
    <div v-if="loading" class="w-full h-[90vh] flex justify-center items-center">
        <Loader :size="5" :thickness="0.5"/>
    </div>
    <div v-else-if="!loading" class="w-full h-[90vh] flex justify-center items-center">
        <div class="h-full flex flex-col gap-y-8 justify-center items-center">
            <img src="profilepic.png" class="w-3/5 rounded-[50%] aspect-square inline-block border-[1px] border-black shadow-lg"/>
            <p class="text-2xl font-bold">{{admin.first_name}} {{ admin.last_name }} (@{{ admin.username }})</p>
            <button class="w-4/5 p-3 mt-5 text-center rounded-lg border-2 border-gray-500 ml-5 text-xl hover:bg-gray-50" @click="$emit('changetab', -1)">Edit Account Information</button>
        </div>
        <!-- <div class="h-full flex flex-col indent-10 min-w-[25rem] justify-center text-xl gap-y-5">
            <p><span class="font-bold capitalize">Name: </span>{{ admin.first_name }} {{ admin.last_name }}</p>
            <p><span class="font-bold capitalize">Username: </span>{{admin.username}}</p>
        </div> -->
    </div>
</template>

<script>
    import Loader from './Loader.vue'
    export default {
        data() {
            return {
                loading: true,
                admin: {}
            }
        },
        components: {Loader},
        emits: ['changetab'],
        async mounted() {
            try {
                const response = await axios.get('/api/admin/info', {headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                }})
                this.admin = response.data
                console.log(this.admin)

            } catch(err) {
                console.log(err.response)
            }
            this.loading = false
        }
    }
</script>

<style scoped>

</style>