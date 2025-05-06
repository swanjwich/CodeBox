<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const props = defineProps(['isExpanded']);
const emit = defineEmits(['toggle']);

const showFolders = ref(false);
const toggleFolders = () => {
    showFolders.value = !showFolders.value;
};
const folders = ref([]);

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const newFolder = ref(null);
const addFolder = () => {
    if (!newFolder.value) {
        newFolder.value = { name: "", isEditing: true };
    }
};

const saveFolder = () => {
    if (newFolder.value && newFolder.value.name.trim() !== "") {
        axios.post(route('category.store'), {
            name: newFolder.value.name
        })
        .then(response => {
            folders.value.push(newFolder.value.name);
            newFolder.value = null;
        })
        .catch(error => {
            console.error('There was an error saving the category!', error);
        });
    }
};


</script>

<template>
    <aside :class="isExpanded ? 'w-60' : 'w-20'"
        class="bg-[#e9e5e6] h-screen fixed left-0 top-0 p-5 flex flex-col border-0">
        <div :class="isExpanded ? 'justify-start' : 'justify-center'" class="flex items-center ms-1 mb-5">
            <img src="/storage/app/public/images/logo.png" alt="Logo" class="h-8 w-8" />
            <h1 v-if="isExpanded" class="ms-2 font-bold text-lg">CodeBox</h1>
        </div>


        <ul class="text-2xl text-gray-500 space-y-1 mt-5">
            <Link :href="route('new')" :class="[
                isExpanded ? 'justify-start px-2' : 'justify-center',
                $page.component === 'NewSnippet' ? 'bg-gray-300 text-[#091C2A]' : 'text-gray-500'
            ]" class="sidebar-link group">
            <i class="ri-add-box-fill"></i>
            <span v-if="isExpanded" class="ml-3 text-base">New Snippet</span>
            <span v-else class="tooltip group-hover:block">
                New Snippet
            </span>
            </Link>

            <Link :href="route('all')" :class="[
                isExpanded ? 'justify-start px-2' : 'justify-center',
                $page.component === 'AllSnippets' ? 'bg-gray-300 text-[#091C2A]' : 'text-gray-500'
            ]" class="sidebar-link group">
            <i class="ri-file-code-fill"></i>
            <span v-if="isExpanded" class="ml-3 text-base">All Snippets</span>
            <span v-else class="tooltip group-hover:block">
                All Snippets
            </span>
            </Link>

            <Link :href="route('favorites')" :class="[
                isExpanded ? 'justify-start px-2' : 'justify-center',
                $page.component === 'Favorites' ? 'bg-gray-300 text-[#091C2A]' : 'text-gray-500'
            ]" class="sidebar-link group">
            <i class="ri-star-fill"></i>
            <span v-if="isExpanded" class="ml-3 text-base">Favorites</span>
            <span v-else class="tooltip group-hover:block">
                Favorites
            </span>
            </Link>

            <Link :href="route('trash')" :class="[
                isExpanded ? 'justify-start px-2' : 'justify-center',
                $page.component === 'Trash' ? 'bg-gray-300 text-[#091C2A]' : 'text-gray-500'
            ]" class="sidebar-link group"> <i class="ri-delete-bin-7-fill"></i>
            <span v-if="isExpanded" class="ml-3 text-base">Trash</span>
            <span v-else class="tooltip group-hover:block">
                Trash
            </span>
            </Link>
        </ul>

        <hr class="mt-4 text-gray-500" />

        <ul class="text-2xl text-gray-500 space-y-3 mt-3">
            <li @click="toggleFolders" :class="isExpanded ? 'justify-start px-2' : 'justify-center'"
                class="sidebar-link group">
                <i class="ri-folders-fill"></i>
                <span v-if="isExpanded" class="ml-3 text-base">Folders</span>
                <span v-else class="tooltip group-hover:block">
                    Folders
                </span>

                <i :class="isExpanded ? '' : 'hidden'" @click.stop="addFolder"
                    class="ri-folder-add-fill ml-auto cursor-pointer hover:text-blue-500 rounded-lg">
                </i>
            </li>
            <li @click="toggleFolders" :class="isExpanded ? 'hidden' : 'justify-center'" class="sidebar-link group">
                <i :class="isExpanded ? 'hidden' : ''" @click.stop="addFolder"
                    class="ri-folder-add-fill cursor-pointer hover:text-blue-500 rounded-lg">
                </i>
                <span v-if="!isExpanded" class="tooltip group-hover:block">
                    Add Folder
                </span>
            </li>

            <transition name="fade">
                <ul v-if="showFolders" class="space-y-2 overflow-y-auto"
                    :class="isExpanded ? 'ml-6' : 'flex flex-col items-center'">
                    <li v-if="newFolder" class="flex items-center py-1">
                        <i class="ri-folder-fill"></i>
                        <input v-model="newFolder.name" @keyup.enter="saveFolder"
                            class=" w-30 ml-3 border-b border-gray-500 outline-none text-sm "
                            placeholder="Enter folder name..." autofocus>
                    </li>
                    <li v-for="folder in folders" :key="folder"
                        :class="isExpanded ? 'justify-start px-3' : 'justify-center'"
                        class="group relative flex items-center cursor-pointer rounded-lg py-1 hover:bg-gray-300 transition-all duration-300">
                        <i class="ri-folder-fill"></i>
                        <span v-if="isExpanded" class="ml-3 text-base">{{ folder.name }}</span>
                        <span v-else class="tooltip group-hover:block">
                            {{ folder.name }}
                        </span>
                    </li>

                </ul>
            </transition>
        </ul>


        <div :class="isExpanded ? 'justify-start' : 'justify-center'" class="mt-auto flex relative">
            <div @click="toggleDropdown" :class="isExpanded ? 'justify-start' : 'justify-center'"
                class="group h-10 relative flex cursor-pointer rounded-lg">
                <img src="/storage/app/public/images/profile.jpg" alt="Profile" class="rounded-full object-cover" />
                <span v-if="isExpanded" class="ml-3 text-sm font-bold flex items-center truncate">{{
                    $page.props.auth.user.name }}</span>
                <span v-else class="tooltip group-hover:block">{{ $page.props.auth.user.name }}</span>
            </div>

            <div v-if="isExpanded" @click="emit('toggle')" class="cursor-pointer ms-3 p-2 rounded-lg hover:bg-gray-300">
                <i class="ri-side-bar-line text-xl"></i>
            </div>

            <div v-if="isDropdownOpen" class="absolute left-0 bottom-12 w-48 bg-white shadow-lg rounded-2xl p-2 z-50">
                <ul>
                    <li class="p-2 rounded-2xl hover:bg-gray-200 cursor-pointer">Profile</li>
                    <li class="p-2 rounded-2xl hover:bg-gray-200 cursor-pointer">Settings</li>
                    <li>
                        <Link :href="route('logout')" method="post" as="button" type="button"
                            class="p-2 w-full text-left rounded-2xl hover:bg-gray-200 cursor-pointer text-red-500">
                        Logout
                        </Link>
                    </li>
                </ul>
            </div>
        </div>

        <div @click="emit('toggle')" :class="isExpanded ? 'hidden' : ''"
            class="mt-3 relative flex justify-center cursor-pointer rounded-lg py-1 hover:bg-gray-300">
            <i class="ri-side-bar-line text-xl"></i>
        </div>

    </aside>
</template>
