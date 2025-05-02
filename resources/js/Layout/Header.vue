<script>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export default{
    name: "HeaderComponent",
    components:{
        Link
    }
}
</script>

<template>
    <header class=" rounded-b-md h-28 bg-white flex items-center">
        <div class=" container mx-auto">
            <div class="flex justify-between">
                <div class=" flex">
                    <div class=" text-2xl flex flex-col items-end">
                        <span class=" text-blue-500 text-[32px] font-bold">BRAIN</span>
                        storm
                    </div>
                    <img class=" ml-5" src="/public/img/logo/BRAINstorm.svg" alt="BRAINstorm_logo">
                </div>
                

                <nav class=" w-[270px] flex justify-between items-center">
                    <a href="#">Главная</a>
                    <a href="#">Прогресс</a>
                    <Link :href="route('student.personal')">Аккаунт</Link>
                </nav>
            </div>
        </div>
    </header>
</template>