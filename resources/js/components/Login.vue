<template>
    <div class="h-screen w-screen bg-white">
        <div class="mx-auto w-1/4">
            <img src="logo-blue.svg" class="mx-auto" />
            <div class="h-96 w-full shadow-lg border-2 border-gray-200">
                <h1 class="text-center text-4xl mt-5 font-bold">Sign In</h1>
                <form class="text-lg mt-5 w-5/6 mx-auto" method="post">
                    <label for="email" class="block my-1">Enter your email:</label>

                    <input ref="email" type="email" name="email" class="input-box"/>

                    <label for="password" class="block my-1 mt-4" name="password">Enter your password:</label>

                    <input ref="password" type="password" class="input-box"/>

                    <br/>
                    <span class="cursor-pointer hover:font-bold leading-loose" @click="showPassword()" ref="toggle">Show password</span>

                    <button type="submit" class="border-2 border-gray-500 w-full rounded-md block bg-blue-500 text-white leading-loose text-xl mt-5 hover:bg-blue-600">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Login',
        methods: {
            showPassword() {
                console.log("hi")
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
            }
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