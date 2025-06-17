<script>
import axios from 'axios';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export default{
    data(){
        return{
            nameCourse: '',
            fileCourse: '',
            colorCourse: '#ffffff',
        }
    },
    components:{
        Link
    },
    methods:{
        async addCourse(){
            const formData = new FormData();
            formData.append('name', this.nameCourse);
            formData.append('img', this.fileCourse);
            formData.append('color', this.colorCourse);
            const res = await axios.post('/admin/new/course', formData);
            if(res || res.data.status){
                router.visit('/admin');
            }
        },
        imgCourse(event){
            this.fileCourse = event.target.files[0];
        }
    }
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex">
        <div class="py-[30px] px-[77px] bg-white rounded-xl flex flex-col items-center mx-auto my-auto max-[515px]:px-4 max-[326px]:w-[320px]">
            <h1 class=" text-xl font-semibold">Создание нового курса</h1>
            <input v-model="nameCourse" type="text" class="focus:outline-none focus:border-2 
            focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px] mt-5 max-[383px]:w-72" placeholder="Введите название курса">
            <div class=" mt-5">
                <p class=" font-semibold mb-1">Загрузить фото курса</p>
                <input @change="imgCourse" type="file" accept="image/png, image/jpeg">
            </div>
            <div class=" mt-5">
                <p class=" font-semibold mb-1">Введите предпочитаемый цвет для курса</p>
                <input v-model="colorCourse" type="color" value="#2563eb" name="" id="">
            </div>

            <div class=" flex justify-between flex-row mt-5 max-[515px]:flex-col ">
                <Link :href="route('admin.main')" class="py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in ml-5 text-lg bg-gray-100 max-[515px]:ml-0 max-[515px]:my-5">Вернуться назад</Link>
                <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg bg-gray-100 max-[515px]:ml-0" @click.prevent="addCourse">Создать курс</button>
            </div>
        </div>
    </section>
</template>