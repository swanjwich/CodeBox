<script setup>
import { Link, Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineOptions({
    layout: Layout
});

</script>

<template>

    <Head title="Your snippet code manager." />
    <header class="bg-transparent shadow-md">
        <nav class="container mx-auto flex items-center justify-between py-5 px-6 text-[#e9e5e6]">

            <div class="text-xl font-bold flex items-center space-x-2">
                <img src="/storage/app/public/images/logo.png" alt="Logo" class="h-8 w-8" />
                <Link href="/">CodeBox</Link>
            </div>

            <div class="hidden md:flex space-x-6">
                <Link href="/" class="text-sm hover:text-blue-500">Features</Link>
                <Link href="/" class="text-sm hover:text-blue-500">Snippets</Link>
                <Link href="/" class="text-sm hover:text-blue-500">Documentation</Link>
            </div>

            <div>
                <Link href="/login" class="text-md px-4 py-2 rounded-lg hover:text-blue-500">
                Login
                </Link>
                <Link href="/register"
                    class="bg-transparent text-md px-3 py-3 rounded-lg border hover:bg-blue-500 transition-colors duration-300 delay-100">
                Get Started
                </Link>
            </div>
        </nav>
    </header>
    <div
        class="min-h-screen flex flex-col justify-center items-center text-white bg-gradient-to-b from-[#091C2A] to-[#12344D] px-6">
        <div class="mb-4">
            <img src="/storage/app/public/images/logo.png" alt="CodeBox Logo" class="h-16 w-16">
        </div>

        <h1 class="text-5xl font-bold tracking-wide drop-shadow-lg">CodeBox</h1>
        <p class="text-gray-300 mt-3 text-lg text-center max-w-md">
            Save, organize, and manage your code snippets easily.
        </p>

        <div class="mt-6 space-x-4">
            <Link href="/register"
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
            Get started for free
            </Link>
        </div>

        <div class="mt-10 bg-gray-900/50 p-6 rounded-lg shadow-lg backdrop-blur-sm text-left">
            <pre class="text-sm text-gray-300">
<span class="text-blue-400">const</span> greet = <span class="text-yellow-400">()</span> => {
    <span class="text-blue-400">return</span> <span class="text-yellow-300">"Hello, CodeBox!"</span>;
};
console.log(greet());
</pre>
        </div>


    </div>

</template>
