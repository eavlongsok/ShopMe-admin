<template>
    <div class="cursor-pointer fixed z-10" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10">
        <div class="w-full col-span-2">
            <img :src="product.product_img" class="w-full"/>
        </div>
        <div class="col-span-3">
            <div class="my-row">
                <div class="col-one">
                    ID:
                </div>
                <div class="col-two">
                    {{ product.product_id }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Product Name:
                </div>
                <div class="col-two">
                    {{ product.product_name }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Category:
                </div>
                <div class="col-two">
                    {{ product.category_name }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Condition:
                </div>
                <div class="col-two">
                    {{ product.product_condition === 1 ? 'New' : 'Used' }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Price:
                </div>
                <div class="col-two">
                    {{ formatToCurrency(product.price) }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Quantity:
                </div>
                <div class="col-two">
                    {{ product.quantity }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Sold:
                </div>
                <div class="col-two">
                    {{ product.product_sold }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Seller:
                </div>
                <div class="col-two">
                    {{ product.seller_first_name }} {{ product.seller_last_name }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Email:
                </div>
                <div class="col-two">
                    {{ product.email }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Description:
                </div>
                <div class="col-two">
                    {{ product.product_description }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Date Approved:
                </div>
                <div class="col-two">
                    {{ product.approved_at }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Approved By:
                </div>
                <div class="col-two">
                    {{ product.admin_first_name }} {{ product.admin_last_name }} (Admin ID: {{ product.approved_by }})
                </div>
            </div>


        </div>
    </div>
</template>

<script>
    export default {
        name: 'ApprovalInfo',
        props: ['product'],
        emits: ['backToMain'],
        methods: {
            formatToCurrency(amount){
                const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                });

                return formatter.format(amount);
            }
        }
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