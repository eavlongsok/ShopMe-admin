<template>
    <div class="cursor-pointer fixed z-10" @click="$emit('backToMain', removed)">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10">
        <div class="w-full col-span-2">
            <img :src="product.product_img" class="w-full"/>
            <div class="flex justify-evenly">
                <button class="rounded-xl text-xl p-2 border-2 border-black font-bold text-white mt-5 w-32 select-none" :class="{'bg-red-600 hover:bg-red-800': product.banned_by === null, 'bg-green-600 hover:bg-green-700': product.banned_by !== null}" @click="handleBan()">{{product.banned_by === null ? 'Ban' : 'Unban'}}</button>
                <button class="bg-red-600 rounded-xl text-xl p-2 border-2 border-black hover:bg-red-800 font-bold text-white mt-5 w-32 select-none" @click="removeProduct(); removed = true">Delete Product</button>
            </div>
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
                    Status:
                </div>
                <div class="col-two">
                    {{ product.banned_by === null ? 'Active' : 'Banned' }}
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
                    Price:
                </div>
                <div class="col-two">
                    {{ formattedPrice }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Seller:
                </div>
                <div class="col-two">
                    {{ product.seller_first_name }} {{ product.seller_last_name }} (S{{ product.seller_id }})
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Store name:
                </div>
                <div class="col-two">
                    {{product.store_name}}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Amount Sold:
                </div>
                <div class="col-two">
                    {{ product.product_sold }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    In stock:
                </div>
                <div class="col-two">
                    {{ product.quantity }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Approved By:
                </div>
                <div class="col-two">
                    {{ product.admin_first_name }} {{ product.admin_last_name }} (Admin ID: {{ product.admin_id }})
                </div>
            </div>

            <div class="my-row" v-if="product.hasOwnProperty('ban_admin_first_name')">
                <div class="col-one">
                    Banned By:
                </div>
                <div class="col-two" v-if="product.banned_by !== null">
                    {{ product.ban_admin_first_name }} {{ product.ban_admin_last_name }} (Admin ID: {{ product.banned_by }})
                </div>
                <div class="col-two" v-else>None</div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Requested at:
                </div>
                <div class="col-two">
                    {{ product.created_at }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Approved at:
                </div>
                <div class="col-two">
                    {{ product.approved_at }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Updated at:
                </div>
                <div class="col-two">
                    {{ product.updated_at }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    description:
                </div>
                <div class="col-two">
                    {{ product.product_description }}
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: 'Profile',
        data() {
            return {
                removed: false
            }
        },
        props: ['product'],
        emits: ['backToMain', 'changeStatus'],
        methods: {
            formatToCurrency(amount){
                const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                });
                return formatter.format(amount);
            },
            async handleBan() {
                const route = this.product.banned_by === null ? '/api/ban/product' : '/api/unban/product';

                let params = new URLSearchParams();
                params.append('id', this.product.product_id);

                try {
                    const response = await axios.get(route, {
                        params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    })

                    if (response.data?.success) {
                        this.$emit('changeStatus', response.data.banned_by)
                    }

                } catch(err) {
                    console.log(err.response.data)
                    alert('Something went wrong. Please try again later.')
                }
            },
            async removeProduct() {
                let params = new URLSearchParams()
                params.append('id', this.product.product_id)

                try {
                    const respones = await axios.get('/api/remove/product', {
                        params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    })
                }
                catch(err) {
                    alert('Something went wrong. Please try again later')
                    console.log(err.response.data)
                }
            }
        },
        computed: {
            formattedPrice() {
                return this.formatToCurrency(this.product.price);
            }
        }
    }
</script>

<style scoped>
    .col-one {
        @apply text-gray-800 font-bold capitalize justify-self-end;
    }

    .col-two {
        @apply col-span-2 break-words;
    }

    .my-row {
        @apply grid grid-cols-3 gap-8 justify-between text-xl my-2;
    }
</style>