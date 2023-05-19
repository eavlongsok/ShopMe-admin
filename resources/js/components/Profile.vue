<template>
    <div class="cursor-pointer" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10">
        <div class="w-full col-span-2">
            <img src="bingchilling.jpeg" class="w-full"/>
            <button class="block bg-red-600 rounded-xl text-xl p-2 border-2 border-black hover:bg-red-800 font-bold text-white mt-5 w-32 ml-auto select-none" @click="$emit('toggleBan')">{{user.banned ? 'Unban' : 'Ban'}}</button>
        </div>
        <div class="col-span-3">
            <div class="my-row">
                <div class="col-one">
                    ID:
                </div>
                <div class="col-two">
                    {{user.ID}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Status:
                </div>
                <div class="col-two">
                    {{user.banned ? 'Banned' : 'Active'}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2">
                <div class="col-one">
                    store name:
                </div>
                <div class="col-two">
                    {{user.store_name}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    name:
                </div>
                <div class="col-two">
                    {{user.name}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    email:
                </div>
                <div class="col-two">
                    {{user.email}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    date of birth:
                </div>
                <div class="col-two">
                    {{user.dob}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    joined at:
                </div>
                <div class="col-two">
                    {{user.created_at}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2">
                <div class="col-one">
                    verified at:
                </div>
                <div class="col-two">
                    {{user.verified_at}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2">
                <div class="col-one">
                    verified by:
                </div>
                <div class="col-two">
                    {{user.verified_by}} (AD{{ user.verifier_ID }})
                </div>
            </div>

            <div class="my-row" v-if="userType === 1">
                <div class="col-one">
                    {{user.addresses.length > 1 ? 'addresses' : 'address'}}:
                </div>
                <div class="col-two">
                    <span v-if="user.addresses.length === 0">Not Available</span>
                    <span v-else-if="user.addresses.length === 1">{{ user.addresses[0] }}</span>
                    <ul v-else class="list-disc">
                        <li v-for="address in user.addresses">{{ address }}</li>
                    </ul>
                </div>
            </div>

            <div class="my-row" v-if="userType === 2">
                <div class="col-one">
                    business address:
                </div>
                <div class="col-two">
                    {{user.business_address}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2">
                <div class="col-one">
                    business info:
                </div>
                <div class="col-two">
                    {{user.business_info}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 1">
                <div class="col-one">
                    orders:
                </div>
                <div class="col-two">
                    {{user.orders}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2">
                <div class="col-one">
                    recent sales:
                </div>
                <div class="col-two">
                    {{user.recent_sales}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Profile',
        props: ['userType', 'user'],
        emits: ['backToMain']
    }
</script>

<style scoped>
    .col-one {
        @apply text-gray-800 font-bold capitalize justify-self-end;
    }

    .col-two {
        @apply col-span-2;
    }

    .my-row {
        @apply grid grid-cols-3 gap-8 justify-between text-xl my-2;
    }
</style>