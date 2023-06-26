<template>
    <div class="cursor-pointer fixed" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10 mb-36">
        <div class="w-full col-span-2">
            <img src="bingchilling.jpeg" class="w-full"/>
            <button class="block bg-red-600 rounded-xl text-xl p-2 border-2 border-black hover:bg-red-800 font-bold text-white mt-5 w-32 ml-auto select-none" @click="handleClick">{{user.status == 1 ? 'Ban' : 'Unban'}}</button>
        </div>
        <div class="col-span-3">
            <div class="my-row">
                <div class="col-one">
                    ID:
                </div>
                <div class="col-two">
                    {{userType === 1 ? user.buyer_id : user.seller_id}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Status:
                </div>
                <div class="col-two">
                    {{user.status == 0 ? 'Banned' : 'Active'}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2 && user.ver_id !== null">
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
                    {{ userType === 1 ? user.first_name + '' + user.last_name : user.seller_first_name + ' ' + user.seller_last_name }}
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
                    {{user.date_of_birth}}
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

            <div class="my-row" v-if="userType === 2 && user.ver_id !== null">
                <div class="col-one">
                    verified at:
                </div>
                <div class="col-two">
                    {{user.verified_at}}
                </div>
            </div>

            <div class="my-row" v-if="userType === 2 && user.ver_id !== null">
                <div class="col-one">
                    verified by:
                </div>
                <div class="col-two">
                    {{user.admin_first_name}} {{ user.admin_last_name }} (ID: {{ user.verified_by }})
                </div>
            </div>

            <!-- <div class="my-row" v-if="userType === 1">
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
            </div> -->

            <div class="my-row" v-if="user.address_id !== null">
                <div class="col-one">
                    address:
                </div>
                <div class="col-two">
                    <p>Building Number: {{user.building_number}}</p>
                    <p>Street Number: {{ user.street_number }}</p>
                    <p>{{ user.city }}, {{ user.region_name }}</p>
                </div>
            </div>

            <div class="my-row" v-if="userType === 2 && user.ver_id !== null">
                <div class="col-one">
                    description:
                </div>
                <div class="col-two">
                    {{user.business_info}}
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: 'Profile',
        props: ['userType', 'user'],
        emits: ['backToMain', 'toggleBan'],
        methods: {
            async ban() {
                const route = "api/ban/" + (this.userType == 1 ? 'buyer' : 'seller')
                const params = new URLSearchParams();
                params.append('id', (this.userType == 1 ? this.user.buyer_id : this.user.seller_id));

                try {
                    const response = await axios.get(route, {
                        params: params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    });
                    this.user.status = 0;
                }
                catch (err) {
                    console.log(err);
                }
            },
            async unban() {
                const route = "api/unban/" + (this.userType == 1 ? 'buyer' : 'seller')
                const params = new URLSearchParams();
                params.append('id', (this.userType == 1 ? this.user.buyer_id : this.user.seller_id));

                try {
                    const response = await axios.get(route, {
                        params: params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    });
                    this.user.status = 1;
                }
                catch (err) {
                    console.log(err);
                }
            },
            handleClick() {
                if (this.user.status == 1) this.ban(); else this.unban();
                this.$emit('toggleBan', this.userType, this.userType === 1 ? this.user.buyer_id : this.user.seller_id);
            }
        },
        // mounted() {
        //     console.log(this.user);
        // }
    }
</script>

<style scoped>
    .col-one {
        @apply text-gray-800 font-bold capitalize justify-self-end text-end;
    }

    .col-two {
        @apply col-span-2;
    }

    .my-row {
        @apply grid grid-cols-3 gap-8 justify-between text-xl my-2;
    }
</style>