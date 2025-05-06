<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Scroll Handling for Sticky Buttons
const isAtBottom = ref(false);
const checkScroll = () => {
    const scrollPosition = window.innerHeight + window.scrollY;
    const documentHeight = document.body.offsetHeight;
    isAtBottom.value = scrollPosition >= documentHeight;
};

onMounted(() => { window.addEventListener('scroll', checkScroll); });
onUnmounted(() => { window.removeEventListener('scroll', checkScroll); });

// Number of lines
const lines = ref([...Array(1).keys()].map(i => i + 1));
const textarea = ref(null);
const codeContent = ref("");

// Function to update line numbers dynamically
const updateLineNumbers = (event) => {
    const lineCount = event.target.value.split("\n").length;
    lines.value = [...Array(lineCount).keys()].map(i => i + 1);

    codeContent.value = event.target.value;
};

// Sync the scroll position of the line numbers with the textarea
const syncScroll = () => {
    const { scrollTop } = textarea.value;
    textarea.value.previousElementSibling.scrollTop = scrollTop;
};

// toggles for description and tag
const showDescription = ref(false);
const toggleDescription = () => {
    showDescription.value = !showDescription.value;
};

const showTag = ref(false);
const toggleTag = () => {
    showTag.value = !showTag.value;
};

const form = useForm({
    title: null,
    description: null,
    content: null,
    visibility: "private",
    tags: "",
});

const submit = () => {
    form.post(route("store"), {
        onError: () => form.reset()
    });
};
</script>

<template>

    <Head title="New Snippet" />
    <h1 class="text-2xl font-bold">New Snippet</h1>

    <div class="flex items-center justify-end relative">
        <button @click="toggleDescription" class="relative cursor-pointer hover:bg-gray-300 rounded-lg p-1 group">
            <i class="ri-text"></i>
            <span class="absolute top-full mt-2 w-max bg-[#091C2A] text-white text-xs px-2 py-1 rounded opacity-0
            group-hover:opacity-100 transition z-50 whitespace-nowrap
            left-auto right-0">
                Add Description
                <span class="absolute right-3 -top-1 w-2 h-2 bg-[#091C2A] transform rotate-45"></span>
            </span>
        </button>
        <button @click="toggleTag" class="relative cursor-pointer hover:bg-gray-300 rounded-lg p-1 group">
            <i class="ri-code-line"></i>
            <span class="absolute top-full mt-2 w-max bg-[#091C2A] text-white text-xs px-2 py-1 rounded opacity-0
            group-hover:opacity-100 transition z-50 whitespace-nowrap
            left-auto right-0">
                Add Tag
                <span class="absolute right-3 -top-1 w-2 h-2 bg-[#091C2A] transform rotate-45"></span>
            </span>
        </button>
    </div>

    <form @submit.prevent="submit" class="mt-4">
        <!-- Title -->
        <input v-model="form.title" placeholder="Untitled snippet" class="border-0 p-2 w-full ring-0 outline-none text-xl" />

        <!-- Description -->
        <textarea  v-model="form.description" v-if="showDescription" placeholder="Describe your snippet..."
            class="border-0 p-2 w-full mt-5 ring-0 outline-none rounded-lg text-md" rows="1"></textarea>

        <!--Add Tag-->
        <input v-model="form.tags" v-if="showTag" placeholder="Add Tag (separated by comma)"
            class="border-0  p-2 w-full mt-5 ring-0 outline-none  rounded-lg text-xs" />

        <hr class="mt-5 text-gray-500" />

        <div class="relative flex mt-3 ">
            <!-- Line Numbers -->
            <div class="w-10 bg-blue-200 text-[#091C2A] text-right pr-2 py-2 text-sm z-0">
                <pre v-for="n in lines" :key="n" class="leading-6">{{ n }}</pre>
            </div>

            <!-- Content -->
            <textarea v-model="form.content" placeholder="Paste your code here" rows="1"
                class="border-0 p-2 w-full mt-2 ring-0 outline-none resize-none text-md" @input="updateLineNumbers"
                @scroll="syncScroll" ref="textarea"></textarea>
        </div>


        <div v-show="codeContent.length > 0" class="sticky bottom-0 left-0 w-full flex justify-end px-4 rounded-2xl"
            :class="{ 'absolute bottom-0': isAtBottom }">
            <button type="reset"
                class="cursor-pointer bg-gray-200 border-0 rounded-lg text-[#091C2A] px-4 py-2 mt-5 hover:bg-gray-300">
                Clear
            </button>
            <button :disabled="form.processing"
                class="cursor-pointer bg-blue-500 rounded-lg text-[#e9e5e6] px-8 py-2 mt-5 ms-2 hover:bg-blue-600">
                Save
            </button>
        </div>

    </form>


</template>
