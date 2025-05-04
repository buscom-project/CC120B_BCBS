<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <header class="sticky top-0 bg-white shadow">
        <div class="container flex flex-col sm:flex-row justify-between items-center mx-auto py-4 px-8">
            <div class="flex items-center text-2xl">
                <Link :href="route('home')" class="relative z-20 flex items-center text-lg font-medium">
                    <AppLogo />
                </Link>
            </div>
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm bg-black text-white border border-[#19140035] px-5 py-1.5 text-sm leading-normal hover:border-[#1915014a]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </div>
    </header>

    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8">
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex items-center w-full text-gray-900 flex-col-reverse overflow-hidden rounded-lg">
                <section class="">
                    <div class="container mx-auto px-8 lg:flex">
                        <!-- first -->
                        <div class="text-center pt-24 lg:text-left lg:w-1/2">
                            <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold leading-none">
                                Seamless Bus Booking at Your Fingertips
                            </h1>
                            <p class="text-xl lg:text-2xl mt-6 font-light">
                                Easily reserve your seat, track schedules, and travel with comfort. Your journey starts here.
                            </p>
                            <p class="mt-8 md:mt-12">
                                <button @click="" type="button" >
                                    
                                </button>
                                <Link
                                    :href="route('login')"
                                    class="py-4 px-12 bg-black hover:bg-zinc-800 rounded text-white"
                                >
                                    Book Now
                                </Link>
                            </p>
                        </div>

                        <!-- second -->
                        <div class="lg:w-1/2">
                            <!-- image -->
                            <div class="ml-16">
                                <img src="/images/logo/dark.png" class="h-[500px]" alt="">
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <footer class="container mx-auto py-16 px-8 mt-48 mb-8 text-gray-800">
            <div class="flex -mx-3">
                <div class="flex-1 px-3">
                    <h2 class="text-lg font-semibold">About Us</h2>
                    <p class="mt-5">
                        CC120 – Application Development and Emerging Technologies <br>
                        Bus Booking System
                    </p>
                </div>
            </div>
        </footer>

        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
