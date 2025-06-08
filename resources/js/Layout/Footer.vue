<script>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
export default{
    name: 'FooterComponent',
    components:{
        Link
    }
}
</script>

<template>
    <footer class=" rounded-t-md min-h-[150px] bg-white flex items-center">
        <div class=" container mx-auto">
            <div class="flex justify-between max-[450px]:flex-col max-[450px]:items-center">
                <div class=" text-2xl flex flex-col items-end mt-5">
                    <span class=" text-blue-500 text-[32px] font-bold">BRAIN</span>
                    storm
                </div>

                <div class=" flex w-[250px] justify-between my-5">
                    <div class=" flex flex-col items-center h-[100px] justify-between">
                        <span class=" font-semibold text-xl text-blue-500">Главная</span>
                        <Link :href="route('student.index')">Курсы</Link>
                        <Link :href="route('student.progress')">Прогресс</Link>
                    </div>

                    <div class=" flex flex-col items-center h-[65px] justify-between">
                        <span class=" font-semibold text-xl text-blue-500">Аккаунт</span>
                        <!-- <a href="#">Архив</a> -->
                        <Link :href="route('student.personal')">Редактирование</Link>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>