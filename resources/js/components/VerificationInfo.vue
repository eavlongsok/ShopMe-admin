<template>
    <div class="cursor-pointer fixed z-10" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-12 w-5/6 select-text pb-10">
        <div class="w-full col-span-2">
            <img :src="seller.img_url" class="w-full"/>
            <div class="flex justify-around">
                <button class="rounded-xl text-xl p-2 border-2 border-black font-bold text-white mt-5 w-32 select-none bg-red-600 hover:bg-red-700" @click="reject">Reject</button>
                <button class="rounded-xl text-xl p-2 border-2 border-black font-bold text-white mt-5 w-32 select-none" :class="{'bg-green-600 hover:bg-green-700': !isVerified, 'bg-red-600 hover:bg-red-700': isVerified}" @click="handleVerificationBtn">{{ isVerified ? 'Unverify' : 'Verify'}}</button>
            </div>
        </div>
        <div class="col-span-3">
            <div class="my-row">
                <div class="col-one">
                    Verification ID:
                </div>
                <div class="col-two">
                    {{seller.ver_id}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Seller ID:
                </div>
                <div class="col-two">
                    {{seller.seller_id}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    store name:
                </div>
                <div class="col-two">
                    {{seller.store_name}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    seller's name:
                </div>
                <div class="col-two">
                    {{seller.first_name}} {{ seller.last_name }}
                </div>
            </div>


            <div class="my-row">
                <div class="col-one">
                    email:
                </div>
                <div class="col-two">
                    {{seller.email}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    address:
                </div>
                <div class="col-two">
                    <p>Building Number: {{seller.building_number}}</p>
                    <p>Street Number: {{ seller.street_number }}</p>
                    <p>{{ seller.city }}, {{ seller.region_name }}</p>
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    zipcode:
                </div>
                <div class="col-two">
                    {{seller.zipcode}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    submission date:
                </div>
                <div class="col-two">
                    {{seller.created_at}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    business info:
                </div>
                <div class="col-two">
                    {{seller.business_info}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VerificationInfo',
        data() {
            return {
                isVerified: false
            }
        },
        props: ['seller'],
        emits: ['backToMain', 'reloadVerification'],
        methods: {
            async handleVerificationBtn(event) {
                const url = this.isVerified ? '/api/unverify/' : '/api/verify/'
                let params = new URLSearchParams();
                params.append('id', this.seller.ver_id)

                try {
                    const response = await axios.get(url, {
                        params,
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    })

                    console.log(response.data)
                }
                catch(err) {
                    console.log(err.response.data)
                }
                this.isVerified = !this.isVerified
            },

            async reject() {
                let confirmation = confirm('Are you sure you want to reject this verification?')

                if (!confirmation) return

                let params = new URLSearchParams();
                params.append('id', this.seller.ver_id)
                try {
                    const response = await axios.get('/api/rejectVerification', {
                        params,
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    })

                    console.log(response.data)
                    this.$emit('reloadVerification')

                }
                catch(err) {
                    console.log(err)
                }
            }
        }
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