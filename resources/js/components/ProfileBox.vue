<template>
    <div class="relative cursor-pointer">
        <img src="profilepic.png" class="inline-block h-10 rounded-full outline outline-gray-500" alt="profile picture"/>
        <p class="inline-block text-xl text-white ml-6">{{ admin_name }}<img src="side-arrow.svg" height="20" width="20"/></p>
        <div class="dropdown-menu rounded-lg">
            <ul>
                <li class="dropdown-item hover:bg-gray-300" @click="$emit('adminprofile')">profile</li>
                <form ref="form" action="log-out" method="post">
                    <input type="hidden" name="_token" :value="csrf"/>
                    <li class="dropdown-item bg-red-600 text-white hover:bg-red-800" @click="submit()">log out</li>
                </form>

            </ul>
        </div>
    </div>
</template>

<script>

    export default {
        name: "ProfileBox",
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                admin_name: ''
            }
        },
        emits: ['adminprofile'],
        methods: {
            submit() {
                this.$refs.form.submit();
                localStorage.removeItem('admin_token')
            },
            async fetchAdminData() {
                try {
                    const response = await axios.get('api/admin/info', {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('admin_token')}`
                        }
                    })

                    this.admin_name = response.data.first_name + ' ' + response.data.last_name
                }
                catch (err) {
                    console.log(err.response.data)
                }
            }
        },
        async mounted() {
            await this.fetchAdminData()
        }
    }

</script>

<style scoped>
    .relative {
        padding: calc(7%) 0;
    }

    .dropdown-menu {
            @apply top-16 text-2xl text-center bg-white;
            line-height: 2.5;
            opacity: 0;
            position: absolute;
            width: 105%;
            box-shadow: 0 0 5px 5px rgba(0,0,0, .1);
            transform: translateY(-10px);
            transition: 200ms ease-in-out;
            pointer-events: none;
    }

    .relative:hover .dropdown-menu {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .dropdown-item {
        @apply capitalize border-b-4 border-blue-400;
    }

    img[src="side-arrow.svg"] {
        @apply inline-block ml-auto mr-4 ml-4;
    }
</style>