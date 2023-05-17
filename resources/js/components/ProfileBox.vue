<template>
    <div class="relative cursor-pointer">
        <img src="profilepic.png" class="inline-block h-10 rounded-full outline outline-gray-500" alt="profile picture"/>
        <p class="inline-block text-xl text-white ml-6">Eav Long Sok <img src="side-arrow.svg" height="20" width="20"/></p>
        <div class="dropdown-menu rounded-lg">
            <ul>
                <li class="dropdown-item hover:bg-gray-300">profile</li>
                <li class="dropdown-item bg-red-600 text-white hover:bg-red-800" @click="$emit('logout')">log out</li>
            </ul>
        </div>
    </div>
</template>

<script>

    export default {
        name: "ProfileBox",
        emits: ['logout']
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