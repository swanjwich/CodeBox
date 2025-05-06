<script setup>
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, defineProps } from 'vue';

const isDropdownOpen = ref(false);
const toggleDropdown = (id) => {
    isDropdownOpen.value = isDropdownOpen.value === id ? null : id;
};

const props = defineProps({
    snippets: Array,
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
</script>
<template>

    <Head title="Favorites" />
    <h1 class="text-2xl font-bold">Favorites</h1>

    <div class="relative w-64 mt-6">
        <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="search" placeholder="Search..."
            class="border border-gray-300 p-2 pl-10 w-full rounded-lg text-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
    </div>
    <div v-if="snippets.length === 0"
        class="flex flex-col items-center h-full text-gray-500 text-center p-0 mt-0 relative">

        <img src="/storage/app/public/images/no-data.jpg"
            class="w-100 absolute top-0 left-1/2 transform -translate-x-1/2" alt="No Data">
        <p class="mt-100">No favorites found.</p>
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
                                <li
                                    class="p-2 text-xs flex items-center gap-2 rounded-xl hover:bg-gray-200 cursor-pointer">
                                    <i class="ri-pencil-line"></i> Edit
                                </li>
                                <li @click.prevent.stop="toggleFavorite(snippet)"
                                    class="p-2 text-xs flex items-center gap-2 rounded-xl hover:bg-gray-200 cursor-pointer">
                                    <i :class="snippet.is_favorite ? 'ri-star-fill text-yellow-500' : 'ri-star-line'"
                                        class="cursor-pointer"></i>
                                    {{ snippet.is_favorite ? 'Remove from Favorites' : 'Add to Favorites' }}
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
