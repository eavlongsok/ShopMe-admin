<template>
    <div class="cursor-pointer" @click="$emit('backToMain')">
        <img src="back-arrow.png" width="16" class="inline-block">
        <span class="text-lg align-middle ml-2 underline hover:font-bold">Back</span>
    </div>
    <div class="grid grid-cols-5 gap-6 mx-auto mt-5 w-5/6 select-text pb-10">
        <div class="w-full col-span-2">
            <img :src="product.img_url" class="w-full h-[400px] aspect-square"/>
            <button @click="handleApproval(product.product_id)" class="block bg-green-600 rounded-xl text-xl p-2 border-2 border-black hover:bg-green-700 font-bold text-white mt-5 w-32 ml-auto select-none">{{ product.is_approved ? "Approved!" : "Approve" }}</button>
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
                    Seller:
                </div>
                <div class="col-two">
                    {{ product.first_name }} {{ product.last_name }}
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
                    Quantity:
                </div>
                <div class="col-two">
                    {{ product.quantity }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Price:
                </div>
                <div class="col-two">
                    ${{ product.price }}
                </div>
            </div>

            <div class="my-row">
                <div class="col-one">
                    Product Codiditon:
                </div>
                <div class="col-two">
                    {{ product.product_condition ? 'New' : 'Used' }}
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
                    Submission Date:
                </div>
                <div class="col-two">
                    {{ product.created_at }}
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                approved: false,
            }
        },
        name: 'ApprovalInfo',
        props: ['product'],
        emits: ['backToMain', 'toggleApprove'],
        methods: {
            async handleApproval(id) {
                let route;
                if (this.product.is_approved) {
                    route = "/api/unapproveProduct/?id=" + id
                }
                else {
                    route = "/api/approveProduct/?id=" + id
                }
                console.log(route)
                try {
                    const response = await axios.get(route, {
                        headers: {
                            "Authorization": "Bearer " + localStorage.getItem("admin_token")
                        }
                    })
                    if (response.status == 200) {
                        this.approved = true
                        console.log(response.data)
                        this.$emit('toggleApprove', this.product.product_id)
                    }
                }
                catch (err) {
                    console.log(err.response.data)
                }
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