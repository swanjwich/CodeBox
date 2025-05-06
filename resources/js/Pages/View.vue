<script setup>
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    snippet: Object,
});

// State for edit mode
const isEditing = ref(false);

// Form state
const form = useForm({
    title: props.snippet.title,
    description: props.snippet.description || "",
    content: props.snippet.content,
    tags: props.snippet.tags || [],
    tagsString: props.snippet.tags ? props.snippet.tags.map(tag => tag.name).join(", ") : "",
});

// Toggle edit mode
const toggleEditMode = () => {
    isEditing.value = !isEditing.value;
};

// submit form
const submit = () => {
    form.patch(route("snippets.update", props.snippet.id), {
        preserveScroll: true,
        onSuccess: ({props}) => {
            isEditing.value = false;
            form.tags = props.snippet.tags; // Update the form tags from the fresh data
            form.tagsString = props.snippet.tags.map(tag => tag.name).join(", ");
        },
        onError: () => form.reset(),
    });
};


</script>

<template>

    <Head title="View Snippet" />
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <Link :href="route('all')"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <span>All Snippets</span>
                </Link>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                        {{ form.title }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mt-2">
        <form @submit.prevent="submit">
            <!-- Edit Button -->
            <div class="flex items-center justify-end">
                <button @click.prevent="toggleEditMode"
                    class="bg-transparent text-black border-gray-200 px-4 py-2 rounded-md hover:bg-gray-200 transition flex items-center">
                    <i class="me-2 text-lg text-blue-500"
                        :class="isEditing ? 'ri-edit-box-fill' : 'ri-edit-box-line'"></i>
                    {{ isEditing ? "Editing" : "Edit" }}
                </button>
            </div>

            <!-- Title -->
            <div>
                <h1 v-if="!isEditing" class="text-2xl font-bold">{{ form.title }}</h1>
                <input v-else v-model="form.title" placeholder="Untitled Snippet"
                    class="border-b border-b-gray-300 p-2 w-full outline-[#091C2A] text-2xl font-bold mt-2" />
            </div>

            <!-- Description -->
            <p v-if="!isEditing && form.description" class="text-gray-600">{{ form.description }}</p>
            <textarea v-else v-model="form.description"
                class="border border-gray-300 outline-[#091C2A] p-2 w-full rounded mt-2" rows="2"
                placeholder="Describe your snippet..."></textarea>

            <!-- Tags -->
            <div v-if="!isEditing && form.tags.length" class="mt-2 flex gap-2">
                <span v-for="tag in form.tags" :key="tag.id" class="text-sm bg-blue-200 px-2 py-1 rounded-lg">
                    #{{ tag.name }}
                </span>
            </div>
            <input v-else v-model="form.tagsString" placeholder="Tags (comma-separated)"
                class="border border-gray-300 outline-[#091C2A]  p-2 w-full mt-2 rounded text-sm " />

            <hr class="mt-5 mb-5 text-gray-500" />

            <!-- Code Snippet -->
            <pre v-if="!isEditing" class="bg-gray-100 p-4 rounded">{{ form.content }}</pre>
            <textarea v-else v-model="form.content"
                class="border border-gray-300 outline-[#091C2A] p-2 w-full rounded mt-2" rows="10"
                placeholder="Paste your code here"></textarea>

            <div class="flex items-center justify-end">
                <button v-if="isEditing" type="reset"
                    class="cursor-pointer bg-gray-200 border-0 rounded-lg text-[#091C2A] px-4 py-2 hover:bg-gray-300 me-2">
                    Cancel
                </button>

                <button v-if="isEditing" :disabled="form.processing"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</template>
