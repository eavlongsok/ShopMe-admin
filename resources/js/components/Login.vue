<template>
    <div class="h-screen w-screen bg-white">
        <div class="mx-auto w-1/4">
            <img src="logo-blue.svg" class="mx-auto" />
            <div class="h-[28rem] w-full shadow-lg border-2 border-gray-200">
                <h1 class="text-center text-4xl mt-5 font-bold">Sign In</h1>
                <div class="text-lg mt-5 w-5/6 mx-auto">
                    <input type="hidden" name="_token" :value="csrf"/>
                    <label for="email" class="block my-1">Enter your email:</label>

                    <input ref="email" type="email" v-model="email" name="email" class="input-box"/>

                    <div v-if="errors !== null && errors.hasOwnProperty('email')" class="text-red-600 text-sm">
                        <span>{{ errors.email[0] }}</span>
                    </div>

                    <label for="password" class="block my-1 mt-4" name="password">Enter your password:</label>

                    <input ref="password" type="password" v-model="password" name="password" class="input-box"/>

                    <div v-if="errors !== null && errors.hasOwnProperty('password')" class="text-red-600 text-sm">
                        <span>{{ errors.password[0] }}</span>
                    </div>

                    <span class="cursor-pointer hover:font-bold leading-loose" @click="showPassword()" ref="toggle">Show password</span>

                    <br/>

                    <div class="text-md">
                        <input name='remember' type="checkbox" class="mt-4 scale-150" @change="remember = !remember" value=1 />
                        <label for='remember' class="ml-3">Remember me</label>
                    </div>

                    <button @click="handleSubmit" class="border-2 border-gray-500 w-full rounded-md block bg-blue-500 text-white leading-loose text-xl mt-5 hover:bg-blue-600">Sign in</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Login',
        data() {
            return {
                email: '',
                password: '',
                remember: false,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                errors: null,
                credentialError: null
            }
        },
        methods: {
            showPassword() {
                let target = this.$refs.password
                let textBox = this.$refs.toggle

                if (target.type === 'password') {
                    target.type = 'text'
                    textBox.innerText = 'Hide password'
                }
                else {
                    target.type = 'password'
                    textBox.innerText = 'Show password'
                }
            },
            async handleSubmit() {
                try {
                    const req = await axios.post('/log-in', {email: this.email, password: this.password, remember: this.remember})

                    if (req.data?.success) {
                        window.location.href = '/'
                    }
                }
                catch (err) {
                    if (err.response.status === 422) {
                        this.errors = err.response.data.errors
                    }
                    else if (err.response.status === 401) {
                        this.credentialErrors = err.response.data.errors
                        console.log(this.credentialErrors)
                    }
                    else {
                        this.errors = 'Something went wrong. Please try again later.'
                    }

                }
            },
        },
        mounted() {
            this.$refs.email.focus()
        }
    }
</script>

<style scoped>
    .input-box {
        @apply border-2 border-gray-500 focus:outline-blue-500 py-0.5 px-3 w-full rounded-md leading-relaxed focus:shadow-lg focus:outline text-blue-900 font-bold;
    }
</style>