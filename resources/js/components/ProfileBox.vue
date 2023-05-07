<template>
    <div class="relative">
        <img src="profilepic.png" class="inline-block h-10 rounded-full outline outline-gray-500" alt="profile picture"/>
        <p class="inline-block text-xl text-white ml-6">Eav Long Sok</p>
        <div class="dropdown-menu border-2 border-t-0 border-gray-400">
            <ul>
                <li class="dropdown-item">profile</li>
                <li class="dropdown-item">log out</li>
            </ul>
        </div>
    </div>
</template>

<script>
    import LogoutButton from './LogoutButton.vue';

    export default {
        name: "ProfileBox",
        components: {
            LogoutButton
        }
    }

</script>

<style scoped>
    .relative {
        padding: calc(7%) 0;
    }

    .dropdown-menu {
            @apply top-16 text-2xl text-center bg-white;
            display: none;
            position: absolute;
            width: 105%;
    }

    .relative:hover .dropdown-menu, .dropdown-menu:hover {
        display: block;

    }

    .dropdown-menu:hover {
        display: block;
    }

    .dropdown-item {
        @apply capitalize border-b-4 border-blue-400 cursor-pointer;
    }

    .dropdown-item:hover {
        @apply bg-gray-200;
    }
</style>