<template>
    <div class="cursor-pointer" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10">
        <div class="w-72 h-auto col-span-2">
            <img src="iphone12.jpg" class="w-full"/>
            <div class="flex justify-evenly">
                <button class="rounded-xl text-xl p-2 border-2 border-black font-bold text-white mt-5 w-32 select-none" :class="{'bg-red-600 hover:bg-red-800': product.status === 1, 'bg-green-600 hover:bg-green-800': product.status === 0}" @click="$emit('changeStatus')">{{product.status === 1 ? 'Remove Temporarily' : 'Restore Product'}}</button>
                <button class="bg-red-600 rounded-xl text-xl p-2 border-2 border-black hover:bg-red-800 font-bold text-white mt-5 w-32 select-none" @click="$emit('backToMain')">Delete Permanently</button>
            </div>
        </div>
        <div class="col-span-3">
            <div class="my-row">
                <div class="col-one">
                    ID:
                </div>
                <div class="col-two">
                    {{ product.id }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Product Name:
                </div>
                <div class="col-two">
                    {{ product.name }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Category:
                </div>
                <div class="col-two">
                    {{ product.category }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Price:
                </div>
                <div class="col-two">
                    {{ product.price }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Seller:
                </div>
                <div class="col-two">
                    {{ product.seller }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Amount Sold:
                </div>
                <div class="col-two">
                    {{ product.amount_sold }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Date Added:
                </div>
                <div class="col-two">
                    {{ product.date_added }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Profile',
        props: ['product'],
        emits: ['backToMain', 'changeStatus']
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