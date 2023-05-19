<template>
    <div class="cursor-pointer" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10">
        <div class="w-full col-span-2">
            <img src="bingchilling.jpeg" class="w-full"/>
            <button class="block bg-green-600 rounded-xl text-xl p-2 border-2 border-black hover:bg-green-700 font-bold text-white mt-5 w-32 ml-auto select-none" @click="approve">Approve</button>
        </div>
        <div class="col-span-3">
            <div class="my-row">
                <div class="col-one">
                    ID:
                </div>
                <div class="col-two">
                    {{seller.ID}}
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
                    name:
                </div>
                <div class="col-two">
                    {{seller.name}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    id card:
                </div>
                <div class="col-two">
                    {{seller.id_card}}
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
                    {{seller.address}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    submission date:
                </div>
                <div class="col-two">
                    {{seller.submission_date}}
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
        props: ['seller'],
        emits: ['backToMain'],
        methods: {
            approve() {
                event.target.innerText = 'Approved'
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