<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref, defineProps, onMounted, onBeforeUnmount } from 'vue';

const isDropdownOpen = ref(false);
const isFolderDropdownOpen = ref(null);
const toggleDropdown = (id) => {
    isDropdownOpen.value = isDropdownOpen.value === id ? null : id;
    if (isDropdownOpen.value !== id) {
        isFolderDropdownOpen.value = null;
    }
};

const toggleFolderDropdown = (id) => {
    isFolderDropdownOpen.value = isFolderDropdownOpen.value === id ? null : id;
};

const handleClickOutside = (event) => {
    const dropdowns = document.querySelectorAll('.ri-more-2-fill, .ri-folder-add-line');
    let clickedInside = false;

    dropdowns.forEach(el => {
        if (el.contains(event.target)) {
            clickedInside = true;
        }
    });

    if (!clickedInside) {
        isDropdownOpen.value = null;
        isFolderDropdownOpen.value = null;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const props = defineProps({
    snippets: Array,
    folders: Array,
    category: Object,
});

const form = useForm({});

const toggleFavorite = (snippet) => {
    form.patch(`/snippets/${snippet.id}/favorite`, {
        preserveScroll: true,
        onSuccess: () => {
            snippet.is_favorite = !snippet.is_favorite;
        },
    });
};

const toggleDeletion = (snippet) => {
    form.delete(`/snippets/${snippet.id}/trash`, {
        preserveScroll: true,
    })
}

const addToFolder = (snippetId, folderId) => {
    const addForm = useForm({
        category_id: folderId,
    });

    addForm.post(`/snippets/${snippetId}/add-to-category`, {
        preserveScroll: true,
        onSuccess: () => {
            isFolderDropdownOpen.value = null;
            isDropdownOpen.value = null;
        },
    });
};


</script>
<template>

    <Head :title="`Snippets in ${category.name}`" />
    <h1 class="text-2xl font-bold mb-4">Snippets in "{{ category.name }}"</h1>


    <div class="relative w-64 mt-6">
        <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="search" placeholder="Search..."
            class="border border-gray-300 p-2 pl-10 w-full rounded-lg text-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
    </div>
    <div v-if="snippets.length === 0"
        class="flex flex-col items-center h-full text-gray-500 text-center p-0 mt-0 relative">

        <img src="/storage/app/public/images/no-data.jpg"
            class="w-100 absolute top-0 left-1/2 transform -translate-x-1/2" alt="No Data">
        <p class="mt-100">No snippets found.</p>
    </div>


    <div v-else>
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 mt-5">
            <div v-for="snippet in snippets" :key="snippet.id">
                <Link :href="route('snippets.show', snippet.id)"
                    class="shadow-sm hover:shadow-lg bg-gray-100 rounded-lg p-3 flex flex-col cursor-pointer">
                <div class="flex justify-between">
                    <h5 class="font-semibold truncate">{{ snippet.title }}</h5>
                    <div class="relative">
                        <i @click.prevent.stop="toggleDropdown(snippet.id)" class="ri-more-2-fill cursor-pointer"></i>
                        <div v-if="isDropdownOpen === snippet.id"
                            class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-2xl p-2 z-50">
                            <ul>
                                <Link :href="route('snippets.show', snippet.id)" >
                                <li
                                    class="p-2 text-xs flex items-center gap-2 rounded-xl hover:bg-gray-200 cursor-pointer">
                                    <i class="ri-pencil-line"></i> Edit
                                </li>
                                </Link>
                                <li @click.prevent.stop="toggleFavorite(snippet)"
                                    class="p-2 text-xs flex items-center gap-2 rounded-xl hover:bg-gray-200 cursor-pointer">
                                    <i :class="snippet.is_favorite === true ? 'ri-star-fill text-yellow-500' : 'ri-star-line'"></i>
                                    <span v-if="snippet.is_favorite === true">Remove from Favorites</span>
                                    <span v-else>Add to Favorites</span>
                                </li>

                                <li class="relative">
                                    <div @click.prevent.stop="toggleFolderDropdown(snippet.id)"
                                        class="p-2 text-xs flex items-center justify-between gap-2 rounded-xl hover:bg-gray-200 cursor-pointer">
                                        <div class="flex items-center gap-2">
                                            <i class="ri-folder-add-line"></i> Add to Folder
                                        </div>
                                        <i class="ri-arrow-right-s-line"></i>
                                    </div>

                                    <div v-if="isFolderDropdownOpen === snippet.id"
                                        class="absolute left-full top-0 ml-1 w-40 bg-white shadow-lg rounded-2xl p-2 z-50">
                                        <ul>
                                            <li v-for="folder in folders" :key="folder.id"
                                                @click.prevent.stop="addToFolder(snippet.id, folder.id)"
                                                :class="{'bg-blue-100': snippet.categories.some(c => c.id === folder.id)}"
                                                class="p-2 text-xs flex items-center gap-2 rounded-xl hover:bg-gray-200 cursor-pointer">
                                                <i class="ri-folder-line"></i> {{ folder.name }}
                                                <i v-if="snippet.categories.some(c => c.id === folder.id)" class="ri-check-line text-green-500 ml-auto"></i>
                                            </li>
                                            <li v-if="folders.length === 0" class="p-2 text-xs text-gray-500 italic">
                                                No folders available
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li @click.prevent.stop="toggleDeletion(snippet)"
                                    class="p-2 text-xs flex items-center gap-2 rounded-xl hover:bg-gray-200 cursor-pointer text-red-500">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-5 items-center min-h-[32px]">
                    <div class="flex gap-2 flex-wrap flex-grow">
                        <span v-for="tag in snippet.tags.slice(0, 2)" :key="tag.id"
                            class="text-sm bg-blue-200 px-2 py-1 rounded-lg">
                            #{{ tag.name }}
                        </span>
                        <span v-if="snippet.tags.length > 2" class="flex items-center text-gray-500 text-sm ">
                            +{{ snippet.tags.length - 2 }} more
                        </span>
                    </div>
                    <span class="text-gray-500 text-sm whitespace-nowrap">
                        {{ new Date(snippet.created_at).toLocaleDateString() }}
                    </span>
                </div>

                </Link>
            </div>
        </div>
    </div>
</template>


