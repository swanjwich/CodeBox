<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineOptions({
    layout: Layout
});

const form = useForm({
    avatar: null,
    name: null,
    email: null,
    password: null,
});

const submit = () => {
    form.post(route("register"), {
        onError: () => form.reset('password')
    });
};
</script>
<template>

    <Head title="Register for Free" />

    <div class="min-h-screen flex items-center justify-center px-6 bg-[#e9e5e6]">
        <div class="w-full max-w-4xl flex flex-col md:flex-row bg-[#e9e5e6] rounded-lg shadow-lg overflow-hidden">

            <div class="w-full md:w-1/2 p-8 text-[#091C2A]">
                <h1 class="text-2xl font-bold text-center">Create Your Account</h1>
                <small class="text-center">Join CodeBox and start managing your snippets efficiently.</small>

                <form @submit.prevent="submit" class="mt-6 space-y-4">
                    <button class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition">
                        <i class="ri-google-fill"></i> Sign up with Google
                    </button>
                    <div class="flex items-center gap-3 w-full">
                        <hr class="flex-grow border-t border-gray-300">
                        <span class="text-gray-400 text-sm">or</span>
                        <hr class="flex-grow border-t border-gray-300">
                    </div>
                    <div>
                        <label class="block">Email</label>
                        <input v-model="form.email" type="email"  autocomplete="email"
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            placeholder="Enter your email address">
                            <small class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</small>
                        </div>
                    <div>
                        <label class="block ">Password</label>
                        <input v-model="form.password" type="password" autocomplete="new-password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            placeholder="Enter your password">
                        <small class="text-red-500" v-if="form.errors.password">{{ form.errors.password }}</small>
                    </div>
                    <button :disabled="form.processing"
                        class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition cursor-pointer">
                        Sign Up
                    </button>
                </form>
                <p class="mt-4 text-sm text-center">
                    Already have an account?
                    <Link href="/login" class="text-blue-600 hover:underline">Login instead.</Link>
                </p>
            </div>

            <div
                class="hidden md:flex md:w-1/2 bg-gradient-to-b from-[#091C2A] to-[#12344D] items-center justify-center">
                <div class="perspective-1000">
                    <Link href="/">
                    <img src="/storage/app/public/images/logo.png" alt="CodeBox Logo" class="w-48 h-48 drop-shadow-[0_0_10px_rgba(0,150,255,0.7)] rounded-lg transition
                        transform hover:rotate-x-10 hover:rotate-y-10">
                    </Link>
                </div>
            </div>

        </div>
    </div>
</template>
