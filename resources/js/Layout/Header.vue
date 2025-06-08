<script>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export default{
    name: "HeaderComponent",
    components:{
        Link
    },
    data(){
        return{
            burgerWindow: false,
        }
    },
    methods:{
        // burgerWindow
    },
}
</script>

<template>
    <header class=" rounded-b-md h-28 bg-white flex items-center max-[680px]:px-5">
        <div class=" container mx-auto">
            <div class="flex justify-between">
                <div class=" flex">
                    <div class=" text-2xl flex flex-col items-end">
                        <span class=" text-blue-500 text-[32px] font-bold">BRAIN</span>
                        storm
                    </div>
                    <img class=" ml-5 max-[350px]:hidden" src="/public/img/logo/BRAINstorm.svg" alt="BRAINstorm_logo">
                </div>
                

                <nav class=" w-[270px] flex justify-between items-center max-[550px]:hidden">
                <!-- <nav class=" w-[170px] flex justify-between items-center"> -->
                    <Link :href="route('student.index')">Главная</Link>
                    <Link :href="route('student.progress')">Прогресс</Link>
                    <Link :href="route('student.personal')">Аккаунт</Link>
                </nav>

                <button @click.prevent="burgerWindow =! burgerWindow" class=" hidden max-[550px]:block">
                    <svg width="63" height="48" viewBox="0 0 63 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="63" height="13" fill="#0077FF"/>
                        <rect y="17" width="63" height="14" fill="#0077FF"/>
                        <rect y="34" width="63" height="14" fill="#0077FF"/>
                        </svg>
                </button>
            </div>
        </div>
    </header>

    <div v-if="burgerWindow" class=" h-full w-full fixed left-0 top-0 bg-white min-[550px]:hidden">
        <!-- <nav class=" flex flex-col w-[200px] my-auto mx-auto text-center"> -->
        <div @click.prevent="burgerWindow =! burgerWindow" class=" h-28 flex items-center justify-end px-5">
            <svg width="63" height="48" viewBox="0 0 63 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="63" height="13" fill="#0077FF"/>
            <rect y="17" width="63" height="14" fill="#0077FF"/>
            <rect y="34" width="63" height="14" fill="#0077FF"/>
            </svg>
        </div>
        <!-- <nav class="flex flex-col justify-center w-[200px] my-auto mx-auto h-full text-center"> -->
        <nav class="flex flex-col justify-evenly w-[200px] my-auto mx-auto h-[500px] text-center">
            <Link class=" text-xl" :href="route('student.index')">Главная</Link>
            <Link class=" text-xl" :href="route('student.progress')">Прогресс</Link>
            <Link class=" text-xl" :href="route('student.personal')">Аккаунт</Link>
        </nav>
    </div>
</template>